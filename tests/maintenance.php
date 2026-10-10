<?php
/** Integration checks for translations, history and settings preservation on disposable WordPress. */
define( 'WP_ADMIN', true );
require $argv[1];
require_once ABSPATH . 'wp-admin/includes/plugin.php';
wp_set_current_user( 1 );
/** @param bool $value Condition to verify. @param string $label Human-readable result. @return void */
function blp_check( bool $value, string $label ): void { if ( ! $value ) { throw new Exception( $label ); } echo 'PASS: ' . $label . PHP_EOL; }
$plugin = WP_PLUGIN_DIR . '/builder-list-pages/';
$preferences = [ 'show_column' => false, 'show_submenus' => false, 'show_none' => true ];
update_option( 'blp_settings', $preferences );
deactivate_plugins( 'builder-list-pages/builder-list-pages.php' );
blp_check( get_option( 'blp_settings' ) === $preferences, 'Deactivation preserves the existing three choices' );
$result = activate_plugin( 'builder-list-pages/builder-list-pages.php' );
blp_check( ! is_wp_error( $result ) && get_option( 'blp_settings' ) === $preferences, 'Reactivation preserves settings without a migration reset' );
blp_check( get_class( $GLOBALS['deckerweb_library_runtime_v1'] ) === 'Deckerweb\\PluginLibrary\\V0_9_0\\Library', 'Latest compatible Library elected once' );
foreach ( [ 'de_DE', 'de_DE_formal' ] as $locale ) {
 $mo = new MO(); $mo->import_from_file( $plugin . 'languages/builder-list-pages-' . $locale . '.mo' );
 blp_check( count( $mo->entries ) === 241, $locale . ': all 241 host and component messages included' );
 $GLOBALS['l10n']['builder-list-pages'] = $mo;
 blp_check( __( 'Close', 'builder-list-pages' ) === 'Schließen', $locale . ': host domain is translated' );
 $translate = require $plugin . 'includes/updater-translations.php';
 blp_check( $translate( 'See the release on GitHub.' ) !== 'See the release on GitHub.', $locale . ': shared updater uses the host domain' );
 blp_check( __( 'Plugin series', 'builder-list-pages' ) === 'Plugin-Serien', $locale . ': Library messages are in the same host domain' );
 $slogan = __( 'Your pages. Your builders. One clear overview.', 'builder-list-pages' );
 blp_check( str_contains( $slogan, $locale === 'de_DE' ? 'Deine' : 'Ihre' ), $locale . ': appropriate informal/formal wording' );
}
require_once $plugin . 'includes/deckerweb-changelog-v1.php';
foreach ( [ 'changelog.txt' => 'Fixed', 'changelog-de.txt' => 'Behoben' ] as $file => $prefix ) {
 $html = Deckerweb_Changelog_Renderer_V1::render( file_get_contents( $plugin . 'docs/' . $file ) );
 blp_check( substr_count( $html, 'ddw-changelog-release' ) === 2 && str_contains( $html, 'ddw-changelog-fixed' ) && str_contains( $html, $prefix ), $file . ': versions and localized category badges render' );
}
$html = Deckerweb_Changelog_Renderer_V1::render( "== Changelog ==\n\n= 1.1.0 · 2026-10-01 =\n* Fixed: <script>alert(1)</script> `<img src=x onerror=alert(1)>`" );
blp_check( ! str_contains( $html, '<script>' ) && ! str_contains( $html, '<img' ) && str_contains( $html, '&lt;script&gt;' ), 'History markup cannot inject executable HTML' );
update_option( 'blp_settings', Deckerweb\BuilderListPages\Settings::defaults() );
echo 'SUCCESS: maintenance checks on WordPress ' . $GLOBALS['wp_version'] . PHP_EOL;
