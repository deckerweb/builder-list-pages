<?php
/** Verify native bulk package contexts against the host validator in isolated WordPress. */
define('WP_ADMIN',true);require $argv[1];require_once ABSPATH.'wp-admin/includes/file.php';require_once ABSPATH.'wp-admin/includes/class-wp-upgrader.php';WP_Filesystem();
/** Assert scoped rejection or passthrough. @param bool $ok Expected outcome. @param string $label Test purpose. @return void */
function blp_bulk_check(bool $ok,string $label):void {if(!$ok)throw new Exception($label);echo 'PASS '.$label.PHP_EOL;}
$adapter=new Deckerweb\BuilderListPages\GitHubUpdates();$dir=get_temp_dir().'blp-bulk-'.wp_generate_password(8,false);mkdir($dir);file_put_contents($dir.'/builder-list-pages.php',"<?php\n/** Plugin Name: Wrong plugin\n * Version: 9.0.0\n * Update URI: https://github.com/deckerweb/builder-list-pages\n * Requires PHP: 8.1\n * Requires at least: 6.7\n */");
$bulk=(new ReflectionClass(Plugin_Upgrader::class))->newInstanceWithoutConstructor();$bulk->bulk=true;$context=['plugin'=>'builder-list-pages/builder-list-pages.php'];
blp_bulk_check(is_wp_error($adapter->validate_source($dir,'',$bulk,$context)),'Native bulk item with omitted type/action still rejects wrong identity');
foreach([['type'=>'theme'],['action'=>'install'],['plugin'=>'other/other.php']]as $override){$other=array_replace($context,$override);blp_bulk_check($adapter->validate_source($dir,'',$bulk,$other)===$dir,'Explicit conflicting bulk context remains untouched');}
$bulk->bulk=false;blp_bulk_check($adapter->validate_source($dir,'',$bulk,$context)===$dir,'Incomplete non-bulk context remains untouched');$bulk->bulk=true;blp_bulk_check($adapter->validate_source($dir,'',null,$context)===$dir,'Incomplete context without native Plugin_Upgrader remains untouched');
$full=$context+['type'=>'plugin','action'=>'update'];blp_bulk_check(is_wp_error($adapter->validate_source($dir,'',null,$full)),'Complete single-update context retains identity validation');unlink($dir.'/builder-list-pages.php');rmdir($dir);echo 'SUCCESS: 7 bulk-context checks'.PHP_EOL;
