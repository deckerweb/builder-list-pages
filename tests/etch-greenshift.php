<?php
/** Verify Etch block signatures and GreenShift addon inventory without loading addon vendor code. */
define( 'WP_ADMIN', true );
require $argv[1];
wp_set_current_user( 1 );
use Deckerweb\BuilderListPages\BlockAdapters;
use Deckerweb\BuilderListPages\Overview;
use Deckerweb\BuilderListPages\Settings;
/** Assert an observable integration outcome. @param bool $ok Expected condition. @param string $label Outcome. @return void */
function addon_check( bool $ok, string $label ): void {
 global $checks;
 if ( ! $ok ) { throw new Exception( $label ); }
 ++$checks; echo 'PASS ' . $label . PHP_EOL;
}
$checks = 0;
require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/template.php';
$cached = wp_cache_get( 'plugins', 'plugins' );
$plugins = get_plugins();
$active = get_option( 'active_plugins', [] );
$fixture = static function ( $value ) use ( $active ) { return array_merge( $active, [ 'greenshiftquery/greenshiftquery.php', 'greenshiftgsap/greenshiftgsap.php', 'greenshiftwoo/greenshiftwoo.php' ] ); };
try {
 foreach ( [ 'etch/element', 'etch/text', 'etch/component', 'etch/loop', 'etch/condition', 'etch/svg', 'etch/dynamic-image', 'etch/raw-html', 'etch/slot-content' ] as $name ) {
  addon_check( isset( BlockAdapters::inspect( '<!-- wp:' . $name . ' /-->' )['etch'] ), 'Stored Etch signature: ' . $name );
 }
 addon_check( isset( BlockAdapters::inspect( '<!-- wp:group --><div><!-- wp:etch/text {"content":"Nested"} /--></div><!-- /wp:group -->' )['etch'] ), 'Nested Etch content recognized' );
 addon_check( ! isset( BlockAdapters::inspect( '<!-- wp:template-part {"slug":"footer"} /-->' )['etch'] ), 'Global footer does not attribute page' );
 addon_check( ! isset( BlockAdapters::inspect( '<!-- wp:paragraph --><p>etch/element</p><!-- /wp:paragraph -->' )['etch'] ), 'Plain namespace text excluded' );
 foreach ( [ 'greenshiftquery', 'greenshiftgsap', 'greenshiftseo', 'greenshiftchart', 'greenshiftwoo' ] as $directory ) {
  $plugins[ $directory . '/' . $directory . '.php' ] = [ 'Name' => $directory, 'Version' => '1.0' ];
 }
 wp_cache_set( 'plugins', [ '' => $plugins ], 'plugins' );
 add_filter( 'option_active_plugins', $fixture );
 $rows = array_column( Overview::rows(), null, 'id' ); $row = $rows['greenshift'];
 addon_check( count( $row['addons'] ) === 5, 'All five installed GreenShift addons shown together' );
 addon_check( $row['addons']['Query and Meta'] && $row['addons']['Advanced Animation'] && $row['addons']['WooCommerce'], 'Active addon states preserved' );
 addon_check( ! $row['addons']['Chart'] && ! $row['addons']['Marketing and SEO'], 'Inactive addons distinguished' );
 addon_check( ! isset( $rows['greenshiftquery'], $rows['greenshiftwoo'] ), 'No duplicate addon families or counters' );
 addon_check( $row['active'], 'Active addon is not presented as entirely inactive integration' );
 if ( ! defined( 'GREENSHIFT_DIR_PATH' ) ) { addon_check( ! $row['ready'] && ! $row['links'], 'Addon without base cannot enable filtered shortcuts' ); }
 foreach ( [ 'querygrid', 'animation-container', 'comparison-table', 'chart', 'productgrid' ] as $block ) {
  addon_check( isset( BlockAdapters::inspect( '<!-- wp:greenshift-blocks/' . $block . ' /-->' )['greenshift'] ), 'Shared GreenShift namespace: ' . $block );
 }
 ob_start(); ( new Settings() )->render(); $html = ob_get_clean();
 addon_check( str_contains( $html, 'Query and Meta: ' . __( 'Active', 'builder-list-pages' ) ), 'Active addon label rendered' );
 addon_check( str_contains( $html, 'Chart: ' . __( 'inactive', 'builder-list-pages' ) ), 'Inactive addon label rendered' );
 $before_user = get_current_user_id(); wp_set_current_user( 0 );
 addon_check( Overview::rows() === [], 'Inventory remains restricted to administrators' );
 wp_set_current_user( $before_user );
 echo 'RESULT ' . wp_json_encode( [ 'passed' => true, 'checks' => $checks, 'php' => PHP_VERSION ] ) . PHP_EOL;
} finally {
 remove_filter( 'option_active_plugins', $fixture ); wp_cache_set( 'plugins', $cached, 'plugins' );
}
