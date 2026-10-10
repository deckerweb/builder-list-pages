<?php
/** Exercise optional workflows on a disposable WordPress installation. */
define('WP_ADMIN',true); define('WP_DISABLE_FATAL_ERROR_HANDLER',true); require $argv[1];
require_once ABSPATH.'wp-admin/includes/plugin.php'; require_once ABSPATH.'wp-admin/includes/template.php'; require_once ABSPATH.'wp-admin/includes/screen.php'; require_once ABSPATH.'wp-admin/includes/class-wp-screen.php';
use Deckerweb\BuilderListPages\Registry; use Deckerweb\BuilderListPages\Query; use Deckerweb\BuilderListPages\Workflows; use Deckerweb\BuilderListPages\Settings;
/** Assert a meaningful workflow outcome. @param bool $condition Expected outcome. @param string $message Check label. @return void */
function verify_workflow(bool $condition,string $message):void { if(!$condition) throw new Exception($message); echo 'PASS '.$message.PHP_EOL; }
wp_set_current_user(1);
foreach(get_posts(['post_type'=>['page','post'],'post_status'=>['publish','draft','pending','private','trash'],'numberposts'=>-1,'s'=>'BLP workflow'])as $fixture){if(str_starts_with($fixture->post_title,'BLP workflow '))wp_delete_post($fixture->ID,true);}
update_option('blp_settings',Settings::defaults());update_option('elementor_cpt_support',['page']); update_option('bricks_global_settings',['postTypes'=>['page']]);
foreach(['ELEMENTOR_VERSION','BRICKS_VERSION'] as $c)if(!defined($c))define($c,'fixture');
$r=new Registry();$q=new Query($r);$q->register();$w=new Workflows($r,$q);
$ids=[];
foreach(['single','multiple','duplicate','inactive','empty'] as $label){$ids[$label]=wp_insert_post(['post_type'=>'page','post_title'=>'BLP workflow '.$label,'post_status'=>'publish']);}
update_post_meta($ids['single'],'_elementor_edit_mode','builder');update_post_meta($ids['multiple'],'_elementor_edit_mode','builder');update_post_meta($ids['multiple'],'_bricks_editor_mode','bricks');add_post_meta($ids['duplicate'],'_elementor_edit_mode','builder');add_post_meta($ids['duplicate'],'_elementor_edit_mode','builder');update_post_meta($ids['inactive'],'brizy_enabled','1');update_post_meta($ids['empty'],'_breakdance_data','');
$multi=new WP_Query();$q->apply($multi,'page','multiple');$multi->query(['post_type'=>'page','post__in'=>array_values($ids),'fields'=>'ids','posts_per_page'=>20]);verify_workflow($multi->posts===[$ids['multiple']],'Multiple-builder view excludes duplicate metadata signatures');
$trace=new WP_Query();$q->apply($trace,'page','traces');$trace->query(['post_type'=>'page','post__in'=>array_values($ids),'fields'=>'ids','posts_per_page'=>20]);verify_workflow(in_array($ids['inactive'],$trace->posts,true)&&in_array($ids['empty'],$trace->posts,true)&&!in_array($ids['single'],$trace->posts,true),'Inactive trace filter preserves exact equality and empty EXISTS semantics');
verify_workflow(isset($r->trace_matches($ids['inactive'],'page')['brizy']),'Inactive trace badge identifies its builder');
verify_workflow($q->count('page','multiple')>0,'Multiple group count includes recognized overlaps');
$bulk=$r->export_labels(array_values($ids),'page');verify_workflow(count($bulk[$ids['multiple']])===2&&str_contains(implode(',',$bulk[$ids['inactive']]),'inactive'),'Bulk CSV signature matching agrees with active and inactive row recognition');
$params=$w->parameters(['post_type'=>'page','builder'=>'multiple','post_status'=>'publish','s'=>'BLP workflow']);verify_workflow($params['builder']==='multiple'&&$params['s']==='BLP workflow','Saved view parameters validated');
verify_workflow($w->parameters(['post_type'=>'attachment'])===[],'Attachments excluded from personal and CSV actions');
$bad=$w->parameters(['post_type'=>'page','builder'=>['bricks'],'s'=>['hostile']]);verify_workflow($bad['builder']===''&&$bad['s']==='','Array inputs rejected');
$literal=['post_type'=>'page','builder'=>'bricks','post_status'=>'','s'=>'a\\b'];verify_workflow($w->parameters($literal,false)['s']===$literal['s'],'Stored searches preserve literal backslashes');
$diagnostic=$w->diagnostics();verify_workflow(!str_contains($diagnostic,home_url())&&!str_contains($diagnostic,wp_get_current_user()->user_login),'Diagnostics omit site URL and user identity');
foreach(['=HYPERLINK("evil")','  +1','\t@SUM(A1)','-1'] as $value){$value=str_replace('\\t',"\t",$value);verify_workflow(str_starts_with(Workflows::csv_cell($value),"'"),'CSV neutralizes formula prefix');}
$rows=$w->rows($params);verify_workflow(count(array_filter($rows,
/** Match the single overlap fixture. @param array $row CSV cells. @return bool Whether this row matches. */
fn($row)=>$row[0]==='BLP workflow multiple'))===1,'CSV selection applies overlap builder constraint');
verify_workflow(!str_contains(json_encode($rows),'_elementor_edit_mode'),'CSV never includes builder metadata');
$key=Workflows::key();$old=get_user_meta(1,$key,true);update_user_meta(1,$key,['compact'=>true,'views'=>[['name'=>'Mine','params'=>$params]]]);verify_workflow(Workflows::state()['compact']&&count(Workflows::state()['views'])===1,'Personal compact preference and favorites stay user scoped');
$blog=get_current_blog_id();verify_workflow($key==='blp_views_'.$blog,'Personal favorites stay site scoped');
set_current_screen('edit-page');$GLOBALS['wp_query']=new WP_Query(['post_type'=>'page','s'=>'blp-impossible-result']);$_GET=['post_type'=>'page','builder'=>'multiple','s'=>'blp-impossible-result','author'=>'1'];ob_start();$w->panel();$html=ob_get_clean();verify_workflow(str_contains($html,'Reset search')&&str_contains($html,'author=1'),'Empty recovery retains author while offering independent reset actions');verify_workflow(str_contains($html,'name="_wpnonce"')&&!str_contains($html,'<script>'),'Actions have nonces and escaped panel output');
$author=wp_insert_user(['user_login'=>'blp_workflow_'.wp_generate_password(8,false),'user_pass'=>wp_generate_password(20,true),'role'=>'author']);
$owned=wp_insert_post(['post_type'=>'post','post_status'=>'publish','post_title'=>'BLP CSV role owned','post_author'=>$author]);$foreign=wp_insert_post(['post_type'=>'post','post_status'=>'publish','post_title'=>'BLP CSV role foreign','post_author'=>1]);
wp_set_current_user($author);$author_rows=$w->rows(['post_type'=>'post','builder'=>'','post_status'=>'publish','s'=>'BLP CSV role']);verify_workflow(count($author_rows)===1&&$author_rows[0][0]==='BLP CSV role owned','Author CSV excludes other authors even when their posts are readable');verify_workflow($w->parameters(['post_type'=>'page'])===[],'Author cannot create favorites for an inaccessible page type');wp_set_current_user(1);wp_delete_post($owned,true);wp_delete_post($foreign,true);require_once ABSPATH.'wp-admin/includes/user.php';wp_delete_user($author);
wp_set_current_user(0);verify_workflow($w->parameters(['post_type'=>'page'])===[],'Anonymous users cannot request personal actions or CSV');verify_workflow($w->rows($params)===[],'CSV refuses lost content permissions');wp_set_current_user(1);
if($old==='')delete_user_meta(1,$key);else update_user_meta(1,$key,$old);foreach($ids as $id)wp_delete_post($id,true);echo 'SUCCESS workflows on WordPress '.$GLOBALS['wp_version'].PHP_EOL;
