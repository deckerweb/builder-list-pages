<?php
/**
 * Remove temporary owned component data while retaining site preferences and content.
 *
 * @package BuilderListPages
 */
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;
require_once __DIR__ . '/includes/deckerweb-plugin-library/lifecycle.php';
deckerweb_library_uninstall_v3( __DIR__ . '/builder-list-pages.php' );
// Site settings, site-scoped user favorites/display choices and builder metadata remain.
delete_site_transient( 'ddw_ghru_' . substr( md5( 'https://github.com/deckerweb/builder-list-pages' ), 0, 24 ) );
