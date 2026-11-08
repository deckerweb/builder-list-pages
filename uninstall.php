<?php
/**
 * Remove temporary owned component data while retaining site preferences and content.
 *
 * @package BuilderListPages
 */
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;
require_once __DIR__ . '/includes/deckerweb-plugin-library/lifecycle.php';
deckerweb_library_uninstall_v4( __DIR__ . '/builder-list-pages.php' );
// Site settings, site-scoped user favorites/display choices and builder metadata remain.
delete_site_transient( 'ddw_ghru_' . substr( md5( 'https://github.com/deckerweb/builder-list-pages' ), 0, 24 ) );

// Remove this implementation's metadata without introducing global variables.
/** Clear only this repository's version-isolated updater cache. @return void */
( static function (): void {
    $updater_class = require __DIR__ . '/includes/deckerweb-github-release-updater-v2.php';
    if ( is_string( $updater_class ) && method_exists( $updater_class, 'clear_repository_cache' ) ) {
        $updater_class::clear_repository_cache( 'https://github.com/deckerweb/builder-list-pages' );
    }
} )();
