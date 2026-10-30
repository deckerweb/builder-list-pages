<?php
/** Verify direct assignment contracts in disposable WordPress with native CrocoBuilder and Builderius Free/Pro. */
define('WP_ADMIN',true);require $argv[1];wp_set_current_user(1);
use Deckerweb\BuilderListPages\DirectAdapters as D;
use Deckerweb\BuilderListPages\BlockAdapters as B;
use Deckerweb\BuilderListPages\Registry as R;
use Deckerweb\BuilderListPages\Overview as O;
use Deckerweb\BuilderListPages\Query as Q;
$n=0;$ids=[];$old=get_option('cb_settings');$old_types=get_option('builderius_page_template_post_types');
/** Assert an integration contract. @param bool $ok Expected condition. @param string $label Description. @return void */
function direct_check(bool $ok,string $label):void{global$n;if(!$ok)throw new Exception($label);++$n;echo 'PASS '.$label.PHP_EOL;}
/** Create neutral content for this test. @param array $args Native post fields. @return int Created ID. */
function direct_post(array $args):int{global$ids;$id=wp_insert_post(array_merge(['post_type'=>'page','post_status'=>'publish','post_title'=>'Direct fixture'],$args),true);if(is_wp_error($id))throw new Exception($id->get_error_message());$ids[]=$id;return$id;}
/** Return one explicit native direct-assignment rule. @param int $id Owner ID. @param string $type Owner type. @return array Rule configuration. */
function direct_rules(int $id,string$type='page'):array{$rules=[['name'=>'post_id','operator'=>'equals','value'=>$id]];if(!in_array($type,['page','post'],true))$rules[]=['name'=>'post_type','operator'=>'equals','value'=>$type];return['location'=>$type==='page'?'pages':($type==='post'?'blog_posts':'custom_posts'),'conditions'=>['condition'=>'or','rules'=>[['condition'=>'and','rules'=>$rules]]]];}
register_post_type('blp_direct',['public'=>true,'show_ui'=>true,'supports'=>['title','editor']]);
try{
 direct_check(D::families()['crocobuilder']['active'],'Native CrocoBuilder runtime ready');direct_check(D::families()['builderius']['active'],'Native Builderius runtime ready');
 direct_check(function_exists('builderius_pro_get_version'),'Native Builderius Pro loaded');
 $settings=is_array($old)?$old:[];$settings['editable_cpts']=['page','post','blp_direct'];update_option('cb_settings',$settings);update_option('builderius_page_template_post_types',['page','post','blp_direct']);
 direct_check(D::allows_type('blp_direct','crocobuilder')&&D::allows_type('blp_direct','builderius'),'Native custom-type choices respected');
 $plain=direct_post([]);$enabled=[];
 $native=direct_post([]);direct_check(\Crocoblocks\Plugin::instance()->ed->enable_builder_for_post($native),'Native Croco enable method succeeded');$enabled[]=$native;direct_check(isset((new R)->matches($native,'page')['crocobuilder']),'Native Croco activation recognized');
 foreach(['1','true','TRUE','yes','on','TrUe']as$value){$id=direct_post([]);update_post_meta($id,'_cb_use_builder',$value);$enabled[]=$id;direct_check(isset((new R)->matches($id,'page')['crocobuilder']),'Croco enabled flag '.$value);}
 $disabled=direct_post([]);update_post_meta($disabled,'_cb_use_builder','0');update_post_meta($disabled,'_crocoblocks_data','[{"tag":"section"}]');direct_check(!isset((new R)->matches($disabled,'page')['crocobuilder']),'Disabled Croco with retained layout does not match');
 direct_check(!isset((new R)->matches($plain,'page')['crocobuilder']),'Plain page excluded');
 $owner=direct_post([]);$template=direct_post(['post_type'=>'builderius_template','post_parent'=>$owner]);update_post_meta($template,'apply_rules_config',wp_json_encode(direct_rules($owner)));
 direct_check(isset((new R)->matches($owner,'page')['builderius']),'Direct Builderius page template recognized');
 direct_check(!isset((new R)->matches($template,'builderius_template')['builderius']),'Template itself never becomes ordinary content');
 update_post_meta($owner,'_cb_use_builder','1');$both=(new R)->matches($owner,'page');direct_check(isset($both['crocobuilder'],$both['builderius']),'Mixed Croco and Builderius assignments retained together');delete_post_meta($owner,'_cb_use_builder');
 $global=direct_post(['post_type'=>'builderius_template','post_parent'=>0]);update_post_meta($global,'apply_rules_config',wp_json_encode(direct_rules($plain)));direct_check(!isset((new R)->matches($plain,'page')['builderius']),'Global template rule cannot bypass explicit ownership');
 $bad=direct_post([]);$t=direct_post(['post_type'=>'builderius_template','post_parent'=>$bad]);update_post_meta($t,'apply_rules_config',wp_json_encode(direct_rules($owner)));direct_check(!isset((new R)->matches($bad,'page')['builderius']),'Mismatched direct ID excluded');
 $cpt=direct_post(['post_type'=>'blp_direct']);$t2=direct_post(['post_type'=>'builderius_template','post_parent'=>$cpt]);update_post_meta($t2,'apply_rules_config',wp_json_encode(direct_rules($cpt,'blp_direct')));direct_check(isset((new R)->matches($cpt,'blp_direct')['builderius']),'Pro direct custom-type template recognized');
 foreach(['not_equals','contains','in']as$operator){$rule=direct_rules($owner);$rule['conditions']['rules'][0]['rules'][0]['operator']=$operator;direct_check(!D::direct_rule($rule,$owner,'page'),'Non-direct operator rejected: '.$operator);}
 $rule=direct_rules($owner);$rule['conditions']['rules'][]=['condition'=>'and','rules'=>[['name'=>'user_role','operator'=>'equals','value'=>'administrator']]];direct_check(!D::direct_rule($rule,$owner,'page'),'Broad OR alternative rejected');
 $rule=direct_rules($owner);$rule['conditions']['rules'][0]['rules'][]=['name'=>'user_role','operator'=>'equals','value'=>'administrator'];direct_check(!D::direct_rule($rule,$owner,'page'),'Context-dependent assignment excluded');
 foreach([null,[],['location'=>'pages','conditions'=>'bad']]as$rule)direct_check(!D::direct_rule($rule,$owner,'page'),'Malformed rule rejected');
 wp_update_post(['ID'=>$template,'post_status'=>'draft']);direct_check(!isset((new R)->matches($owner,'page')['builderius']),'Disabled draft template removed after native cache invalidation');wp_update_post(['ID'=>$template,'post_status'=>'publish']);
 $reg=new R();$q=new Q($reg);$q->register();direct_check($q->count('page','crocobuilder')===count($enabled),'Croco native filtered count');direct_check($q->count('page','builderius')===1,'Builderius native filtered count');
 $rows=array_column(O::rows(),null,'id');direct_check($rows['builderius']['editions']===['Free'=>true,'Pro'=>true],'Free/Pro native inventory states');direct_check($rows['crocobuilder']['ready'],'Croco overview ready');
 $settings['editable_cpts']=['post'];update_option('cb_settings',$settings);direct_check(!in_array('page',(new R())->all()['crocobuilder']['builder-types'],true),'Croco native disabled type cannot be forced into registry');
 update_option('builderius_page_template_post_types',['page','post']);direct_check(!in_array('blp_direct',(new R())->all()['builderius']['builder-types'],true),'Builderius Pro disabled type excluded');
 echo 'RESULT '.wp_json_encode(['passed'=>true,'checks'=>$n,'php'=>PHP_VERSION,'wordpress'=>get_bloginfo('version')]).PHP_EOL;
}finally{foreach($ids as$id)wp_delete_post($id,true);if($old===false)delete_option('cb_settings');else update_option('cb_settings',$old);if($old_types===false)delete_option('builderius_page_template_post_types');else update_option('builderius_page_template_post_types',$old_types);}
