<?php
/** Exercise inventory states, permission boundaries and native filter counts in disposable WordPress. */
define('WP_ADMIN',true);require $argv[1];wp_set_current_user(1);
use Deckerweb\BuilderListPages\Overview;
use Deckerweb\BuilderListPages\Registry;
use Deckerweb\BuilderListPages\Settings;
use Deckerweb\BuilderListPages\Admin;
/** Verify one observable outcome. @param bool $ok Condition. @param string $label Description. @return void */
function overview_check(bool $ok,string $label):void{if(!$ok)throw new Exception($label);echo 'PASS '.$label.PHP_EOL;}
require_once ABSPATH.'wp-admin/includes/plugin.php';require_once ABSPATH.'wp-admin/includes/template.php';
add_filter('pre_wp_mail','__return_true');
switch_to_locale('en_US');
$theme_fixture=false;$theme_path=get_theme_root().'/bricks';$before=wp_cache_get('plugins','plugins');$ids=[];$user=0;$settings=get_option('blp_settings');
$inventory=get_plugins();
foreach(['kadence-blocks-pro/kadence-blocks-pro.php'=>'Kadence Blocks Pro','brizy/brizy.php'=>'Brizy','brizy-pro/brizy-pro.php'=>'Brizy Pro','unrelated/unrelated.php'=>'Unrelated']as$file=>$name){$inventory[$file]=['Name'=>$name,'Version'=>'1.0'];}
wp_cache_set('plugins',[''=>$inventory],'plugins');
$raw_active=get_option('active_plugins');
$active=static function($value)use($raw_active){return array_values(array_unique(array_merge($raw_active,['brizy/brizy.php'])));};
add_filter('option_active_plugins',$active);
if(!defined('BRIZY_VERSION'))define('BRIZY_VERSION','fixture');
$brizy=static function(){return ['post-types'=>['page','blp_portfolio']];};add_filter('pre_option_brizy',$brizy);
register_post_type('blp_portfolio',['public'=>true,'show_ui'=>true,'show_in_rest'=>true,'supports'=>['editor','title'],'labels'=>['name'=>'Portfolio']]);
register_post_type('blp_locked',['public'=>true,'show_ui'=>true,'show_in_rest'=>true,'supports'=>['editor'],'capabilities'=>['edit_posts'=>'blp_never_granted']]);
try{
 foreach(['page','blp_portfolio']as$type){$ids[]=wp_insert_post(['post_title'=>'Overview test','post_type'=>$type,'post_status'=>'publish','post_content'=>'<!-- wp:kadence/rowlayout /-->']);}
 $ids[]=wp_insert_post(['post_title'=>'Trash must not count','post_type'=>'blp_portfolio','post_status'=>'trash','post_content'=>'<!-- wp:kadence/rowlayout /-->']);
 $private=wp_insert_post(['post_title'=>'Private match','post_type'=>'blp_portfolio','post_status'=>'private','post_content'=>'<!-- wp:kadence/rowlayout /-->']);$ids[]=$private;
 $brizy_id=wp_insert_post(['post_title'=>'Brizy test','post_type'=>'blp_portfolio','post_status'=>'publish']);$ids[]=$brizy_id;update_post_meta($brizy_id,'brizy_enabled','1');
 $hooks=array_map('array_keys',$GLOBALS['wp_filter']['posts_where']->callbacks??[]);$start=microtime(true);$rows=array_column(Overview::rows(),null,'id');$elapsed=(microtime(true)-$start)*1000;
 overview_check(isset($rows['kadence-blocks']),'Active Kadence is listed');
 overview_check($rows['kadence-blocks']['ready']&&$rows['kadence-blocks']['active'],'Active state follows available recognition');
 overview_check($rows['kadence-blocks']['editions']['Free']===true&&$rows['kadence-blocks']['editions']['Pro']===false,'Inactive Pro never masquerades as active Pro');
 $links=array_column($rows['kadence-blocks']['links'],null,'type');
 overview_check(isset($links['page'],$links['post'],$links['blp_portfolio']),'Separate permitted content-type links');
 overview_check(!isset($links['blp_locked']),'Forbidden content type excluded');
 overview_check($links['blp_portfolio']['count']===2,'Count includes readable private content and excludes trash');
 overview_check($links['blp_portfolio']['url']===Admin::list_url('blp_portfolio','kadence-blocks'),'Canonical menu-compatible URL reused');
 overview_check(str_contains($links['blp_portfolio']['url'],'blp_view=1'),'Admin Cleaner compatibility marker retained');
 overview_check($rows['brizy']['editions']===['Free'=>true,'Pro'=>false],'Brizy Free/Pro installation states');
 overview_check(array_column($rows['brizy']['links'],null,'type')['blp_portfolio']['count']===1,'Classic Brizy count uses stored marker');
 overview_check(!isset($rows['unrelated']),'Unrelated plugins excluded');
 overview_check(array_map('array_keys',$GLOBALS['wp_filter']['posts_where']->callbacks??[])===$hooks,'No unrelated query filter added');
 update_option('blp_settings',['show_column'=>false,'show_submenus'=>false,'show_none'=>false]);
 overview_check(isset(array_column(Overview::rows(),null,'id')['kadence-blocks']['links'][0]),'Overview independent of display switches');
 remove_filter('option_active_plugins',$active);$active=static function($value)use($raw_active){return array_merge($raw_active,['kadence-blocks-pro/kadence-blocks-pro.php']);};add_filter('option_active_plugins',$active);
 overview_check(array_column(Overview::rows(),null,'id')['kadence-blocks']['editions']['Pro']===true,'Active Pro addon is distinguished from installed inactive Pro');
 $original_active=$active;remove_filter('option_active_plugins',$active);
 $active=static function($value)use($raw_active){return array_values(array_diff($raw_active,['genesis-blocks/genesis-blocks.php']));};add_filter('option_active_plugins',$active);
 // Inactive inventory is tested through a known plugin whose runtime is absent.
 $rows2=array_column(Overview::rows(),null,'id');
 overview_check($rows2['brizy']['ready'],'Runtime recognition is not inferred from a Pro file');
 $inventory['elementor-pro/elementor-pro.php']=['Name'=>'Elementor Pro','Version'=>'1.0'];wp_cache_set('plugins',[''=>$inventory],'plugins');
 $rows2=array_column(Overview::rows(),null,'id');
 if(!defined('ELEMENTOR_VERSION')){overview_check(!$rows2['elementor']['ready']&&!$rows2['elementor']['active']&&!$rows2['elementor']['links'],'Installed inactive integration has no content links');}
 $network=static function(){return ['elementor-pro/elementor-pro.php'=>time()];};
 // Network activation uses WordPress core; emulate the current-site plugin option on single-site fixtures.
 remove_filter('option_active_plugins',$active);$active=static function($value)use($raw_active){return array_merge($raw_active,['elementor-pro/elementor-pro.php']);};add_filter('option_active_plugins',$active);
 $rows2=array_column(Overview::rows(),null,'id');if(!defined('ELEMENTOR_VERSION'))overview_check($rows2['elementor']['active']&&!$rows2['elementor']['ready']&&!$rows2['elementor']['links'],'Active addon without base is unavailable, not functional');
 ob_start();(new Settings())->render();$html=ob_get_clean();
 overview_check(str_contains($html,__('Your builders and integrations','builder-list-pages')),'Overview rendered in settings');
 overview_check(str_contains($html,'Pro ('.__('inactive','builder-list-pages').')'),'Inactive edition explicitly labelled');
 overview_check(str_contains($html,'aria-label="Kadence Blocks: Portfolio (2)"'),'Repeated button labels have integration-specific accessible names');
 overview_check((bool)preg_match('/Builders-(English|Deutsch)\.html/',$html),'All-integrations link opens local matrix');
 overview_check(strpos($html,'wp-header-end')<strpos($html,'blp-integrations-title'),'Overview follows coherent header and notice boundary');
 $user=wp_insert_user(['user_login'=>'blp_overview_'.time(),'user_pass'=>wp_generate_password(),'role'=>'editor']);wp_set_current_user($user);
 overview_check(Overview::rows()===[],'Non-administrator cannot read inventory');ob_start();(new Settings())->render();overview_check(ob_get_clean()==='','Settings permission remains enforced');
 wp_set_current_user(1);
 foreach(['de_DE','de_DE_formal']as$locale){$mo=new MO();$mo->import_from_file(BLP_PLUGIN_DIR.'languages/builder-list-pages-'.$locale.'.mo');$GLOBALS['l10n']['builder-list-pages']=$mo;overview_check(__('Your builders and integrations','builder-list-pages')===($locale==='de_DE'?'Deine Builder und Integrationen':'Ihre Builder und Integrationen'),$locale.' overview heading translated');}
 if(!is_dir($theme_path)&&!defined('BRICKS_VERSION')){
  mkdir($theme_path);$theme_fixture=true;file_put_contents($theme_path.'/style.css',"/*\nTheme Name: Bricks inventory fixture\nVersion: 0.0.0\n*/");file_put_contents($theme_path.'/index.php',"<?php // Disposable inventory fixture.");wp_clean_themes_cache();
  $theme_rows=array_column(Overview::rows(),null,'id');overview_check(isset($theme_rows['bricks'])&&!$theme_rows['bricks']['active']&&!$theme_rows['bricks']['links'],'Installed inactive theme is listed without links');
  define('BRICKS_VERSION','inventory-fixture');$template=static function(){return 'bricks';};$bricks_types=static function(){return ['postTypes'=>['page']];};add_filter('pre_option_template',$template);add_filter('pre_option_bricks_global_settings',$bricks_types);
  $theme_rows=array_column(Overview::rows(),null,'id');overview_check($theme_rows['bricks']['ready']&&$theme_rows['bricks']['active'],'Parent theme activation is recognized independently of child-theme stylesheet');overview_check($theme_rows['bricks']['links'][0]['type']==='page','Theme integration receives its enabled content-type link');
  remove_filter('pre_option_template',$template);remove_filter('pre_option_bricks_global_settings',$bricks_types);
 }
 if($theme_fixture){unlink($theme_path.'/style.css');unlink($theme_path.'/index.php');rmdir($theme_path);wp_clean_themes_cache();$theme_fixture=false;}
 $empty_registry=static function(){return [];};add_filter('blp/filter/builder-data',$empty_registry);wp_cache_set('plugins',[''=>[]],'plugins');overview_check(Overview::rows()===[],'Empty installation overview has no invented entries');remove_filter('blp/filter/builder-data',$empty_registry);
 echo 'First inventory/count pass: '.round($elapsed,2).' ms; '.count($rows).' integrations'.PHP_EOL;
}finally{
 if($theme_fixture){unlink($theme_path.'/style.css');unlink($theme_path.'/index.php');rmdir($theme_path);wp_clean_themes_cache();}wp_set_current_user(1);remove_filter('pre_wp_mail','__return_true');restore_previous_locale();remove_filter('option_active_plugins',$active);remove_filter('pre_option_brizy',$brizy);wp_cache_set('plugins',$before,'plugins');update_option('blp_settings',$settings);foreach($ids as$id)wp_delete_post($id,true);if($user){require_once ABSPATH.'wp-admin/includes/user.php';wp_delete_user($user);}
}
echo 'SUCCESS overview checks'.PHP_EOL;
