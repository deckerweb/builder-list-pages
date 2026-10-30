<?php
/** Verify selected content types, native tools and presentation in disposable WordPress. */
define('WP_ADMIN',true);define('WP_DISABLE_FATAL_ERROR_HANDLER',true);require $argv[1];
require_once ABSPATH.'wp-admin/includes/plugin.php';require_once ABSPATH.'wp-admin/includes/template.php';
wp_set_current_user(1);add_filter('pre_wp_mail','__return_true');switch_to_locale('en_US');
use Deckerweb\BuilderListPages\ContentTypes as C;
use Deckerweb\BuilderListPages\Registry as R;
use Deckerweb\BuilderListPages\Query as Q;
use Deckerweb\BuilderListPages\Settings as S;
use Deckerweb\BuilderListPages\Overview as O;
use Deckerweb\BuilderListPages\AdminTools as T;
use Deckerweb\BuilderListPages\BlockAdapters as B;
/** Verify one observable behavior. @param bool $ok Expected condition. @param string $label Behavior. @return void */
function type_check(bool$ok,string$label):void{global$checks;if(!$ok)throw new Exception($label);++$checks;echo 'PASS '.$label.PHP_EOL;}
$checks=0;$ids=[];$old=get_option('blp_settings');$old_bricks=get_option('bricks_global_settings');$user=0;
foreach(['blp_portfolio','blp_empty','redirect_rule','rcb-cookie','kadence_form','branda_email_log']as$type)register_post_type($type,['public'=>true,'show_ui'=>true,'show_in_rest'=>true,'supports'=>['editor','title']]);
register_post_type('blp_private',['public'=>false,'show_ui'=>true,'show_in_rest'=>false,'supports'=>['title']]);
register_post_type('blp_forbidden',['public'=>true,'show_ui'=>true,'capabilities'=>['edit_posts'=>'blp_permission_never_granted']]);
if(!defined('CT_VERSION'))define('CT_VERSION','fixture');
if(!defined('BRICKS_VERSION'))define('BRICKS_VERSION','fixture');
update_option('bricks_global_settings',['postTypes'=>['page']]);
try{
 update_option('blp_settings',S::defaults());
 type_check(C::allows('page','oxygen-classic')&&C::allows('post','oxygen-classic'),'Core editorial types selected automatically');
 type_check(C::allows('blp_portfolio','oxygen-classic'),'Public custom editorial type retained');
 foreach(['redirect_rule','rcb-cookie','kadence_form','branda_email_log']as$type)type_check(!C::allows($type,'oxygen-classic'),'Technical type excluded: '.$type);
 type_check(!C::allows('blp_private','oxygen-classic'),'Private editorless custom type needs explicit choice');
 $s=new S();$raw=['show_column'=>'1','show_submenus'=>'1','post_type_rules_present'=>'1','post_type_rules'=>['blp_private'=>'include','blp_portfolio'=>'exclude','redirect_rule'=>'include','nav_menu_item'=>'include','wp_navigation'=>'include','blp_forbidden'=>'include','unregistered_type'=>'include','page'=>['include']]];
 $saved=$s->sanitize($raw);type_check($s->sanitize($saved)===$saved,'Repeated Settings API sanitization preserves explicit choices');update_option('blp_settings',$saved);
 type_check(C::rules()===['blp_portfolio'=>'exclude','redirect_rule'=>'include','blp_private'=>'include']||C::rules()===['blp_portfolio'=>'exclude','blp_private'=>'include','redirect_rule'=>'include']||count(C::rules())===3,'Only registered, accessible non-system overrides accepted');
 type_check(C::allows('blp_private','oxygen-classic'),'Include permits private editorless content');
 type_check(C::allows('redirect_rule','oxygen-classic'),'Explicit selection can correct technical classification');
 type_check(!C::allows('blp_portfolio','oxygen-classic'),'Hide overrides automatic selection');
 type_check(!C::allows('nav_menu_item','oxygen-classic')&&!C::allows('wp_navigation','oxygen-classic')&&!C::allows('attachment','oxygen-classic'),'Core system records cannot be forced in');
 type_check(!C::allows('blp_forbidden','oxygen-classic'),'Include cannot bypass editing permissions');
 $r=new R();type_check(isset($r->for_type('blp_private')['oxygen-classic']),'Classic registry includes selected private content');
 type_check(!isset($r->for_type('blp_portfolio')['oxygen-classic']),'Classic registry hides excluded content');
 type_check(!isset($r->for_type('blp_private')['bricks']),'Include does not bypass native builder type restrictions');
 type_check(isset($r->for_type('blp_private')['kadence-blocks']),'Block registry shares explicit type selection');
 type_check(!$r->traces('blp_portfolio'),'Inactive traces honor hidden types');
 $workflow=new \Deckerweb\BuilderListPages\Workflows($r,new Q($r));type_check($workflow->parameters(['post_type'=>'blp_portfolio','builder'=>'kadence-blocks'],false)===[],'Saved favorites cannot reopen hidden content types');type_check($workflow->rows(['post_type'=>'blp_portfolio','builder'=>'kadence-blocks'])===[],'CSV cannot broaden a hidden-type selection');
 $id=wp_insert_post(['post_type'=>'blp_private','post_title'=>'Private layout','post_status'=>'publish','post_content'=>'<!-- wp:kadence/rowlayout /-->']);$ids[]=$id;update_post_meta($id,'_ct_builder_json','{}');
 type_check(isset((new R())->matches($id,'blp_private')['kadence-blocks']),'Stored blocks recognized without standard editor after Include');
 $q=new Q(new R());$q->register();type_check($q->count('blp_private','kadence-blocks')===1,'Native count honors selected private type');
 $commands=(new T())->commands();$names=array_column($commands,'name');
 type_check(in_array('builder-list-pages/kadence-blocks/blp_private',$names,true),'Palette includes permitted overridden type');
 type_check(!in_array('builder-list-pages/kadence-blocks/blp_portfolio',$names,true),'Palette excludes hidden type');
 type_check(in_array('builder-list-pages/settings',$names,true),'Administrator receives settings shortcut');
 $info=(new T())->information([]);$fields=$info['builder-list-pages']['fields'];
 type_check($fields['included']['value']==='redirect_rule, blp_private'||$fields['included']['value']==='blp_private, redirect_rule','Site Health reports only configured include slugs');
 type_check($fields['excluded']['value']==='blp_portfolio','Site Health reports explicit exclusions');
 type_check(!str_contains(json_encode($info),'Private layout'),'Site Health never includes page contents or titles');
 $before=get_num_queries();(new T())->commands();type_check(get_num_queries()-$before<5,'Palette performs no content scans');
 wp_dequeue_script('wp-commands');wp_dequeue_script('blp-commands');(new T())->commands_assets();type_check(!wp_script_is('blp-commands','enqueued'),'No palette forced onto unavailable screens');
 wp_enqueue_script('wp-commands');(new T())->commands_assets();type_check(wp_script_is('blp-commands','enqueued'),'Shortcuts enqueued when native palette is available');
 $extra=wp_scripts()->get_data('blp-commands','before');(new T())->commands_assets();type_check(wp_scripts()->get_data('blp-commands','before')===$extra,'Palette payload not duplicated');
 $old_form=$s->sanitize(['show_column'=>'1']);type_check($old_form['post_type_rules']===C::rules(),'Old-form submission preserves content-type overrides');
 $absent=$saved;$absent['post_type_rules']['temporarily_absent']='exclude';update_option('blp_settings',$absent);
 $automatic=$s->sanitize(['post_type_rules_present'=>'1','post_type_rules'=>['blp_private'=>'auto']]);type_check(!isset($automatic['post_type_rules']['blp_private'])&&$automatic['post_type_rules']['temporarily_absent']==='exclude','Automatic clears one choice while absent-plugin values survive');
 update_option('blp_settings',['post_type_rules'=>['page'=>'exclude']]);$cleared=$s->sanitize(['post_type_rules_present'=>'1','post_type_rules'=>['page'=>'auto']]);type_check($s->sanitize($cleared)['post_type_rules']===[],'Repeated sanitization does not resurrect cleared choices');
 update_option('blp_settings',S::defaults());
 $filter=static function($allowed,$object,$builder){return 'blp_private'===$object->name&&'oxygen-classic'===$builder?true:$allowed;};add_filter('deckerweb_builder_list_pages_content_type_allowed',$filter,10,3);
 type_check(C::allows('blp_private','oxygen-classic')&&!C::allows('blp_private','bricks'),'Developer filter is scoped to its builder');
 update_option('blp_settings',['post_type_rules'=>['blp_private'=>'exclude']]);type_check(!C::allows('blp_private','oxygen-classic'),'Explicit site exclusion wins over developer automatic filter');remove_filter('deckerweb_builder_list_pages_content_type_allowed',$filter,10);
 $links=[['type'=>'post','count'=>0,'label'=>'Posts'],['type'=>'page','count'=>2,'label'=>'Pages'],['type'=>'portfolio','count'=>12,'label'=>'Portfolio'],['type'=>'books','count'=>4,'label'=>'Books']];usort($links,[O::class,'compare_links']);type_check(array_column($links,'type')===['page','books','portfolio','post'],'Matching types first, Pages first, other types alphabetically');
 $links=[['type'=>'post','count'=>2,'label'=>'Posts'],['type'=>'page','count'=>0,'label'=>'Pages'],['type'=>'portfolio','count'=>12,'label'=>'Portfolio']];usort($links,[O::class,'compare_links']);type_check(array_column($links,'type')===['post','portfolio','page'],'Nonzero types precede empty Pages');
 update_option('blp_settings',S::defaults());ob_start();$s->render();$html=ob_get_clean();
 type_check(str_contains($html,'blp-type-settings')&&str_contains($html,'blp_settings[post_type_rules]'),'Type choices rendered inside settings form');
 type_check(str_contains($html,__('What does “No recognized builder” mean?','builder-list-pages'))&&str_contains($html,__('What does this plugin mean by a “builder”?','builder-list-pages')),'Existing help retained and builder explanation appended');
 type_check(str_contains($html,'blp-content-page')&&!preg_match('/blp-content-match[^\"]*\"[^>]*>[^<]*\(0\)/',$html),'Matching Pages highlighted while zero-count links stay neutral');
 foreach(['de_DE','de_DE_formal']as$locale){$mo=new MO();$mo->import_from_file(BLP_PLUGIN_DIR.'languages/builder-list-pages-'.$locale.'.mo');$GLOBALS['l10n']['builder-list-pages']=$mo;type_check(str_contains(__('This plugin uses “builder” to mean tools for designing content and layouts in WordPress. This includes classic page builders such as Bricks, Elementor and Oxygen, as well as extensions for the WordPress block editor, such as Kadence Blocks and Spectra.','builder-list-pages'),$locale==='de_DE'?'mit denen du':'mit denen Sie'),$locale.' approved explanation uses correct address');}
 $user=wp_insert_user(['user_login'=>'types_subscriber_'.time(),'user_pass'=>wp_generate_password(),'role'=>'subscriber']);wp_set_current_user($user);type_check((new T())->information([])===[]&&(new T())->commands()===[],'Subscriber gets neither diagnostic section nor shortcuts');
 echo 'SUCCESS '.$checks.' content-type and native-tools checks; WP'.$GLOBALS['wp_version'].' PHP'.PHP_VERSION.PHP_EOL;
}finally{wp_set_current_user(1);update_option('blp_settings',$old);update_option('bricks_global_settings',$old_bricks);foreach($ids as$id)wp_delete_post($id,true);if($user){require_once ABSPATH.'wp-admin/includes/user.php';wp_delete_user($user);}restore_previous_locale();}
