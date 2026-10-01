<?php
/**
 * Plugin Name: Builder List Pages
 * Plugin URI: https://github.com/deckerweb/builder-list-pages
 * Description: Find pages by their page builder. Recognize builders at a glance, filter multiple builders and keep working in familiar WordPress lists.
 * Version: 1.1.0
 * Requires at least: 6.7
 * Requires PHP: 8.0
 * Author: David Decker – DECKERWEB
 * Author URI: https://github.com/deckerweb
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: builder-list-pages
 * Domain Path: /languages/
 * Update URI: https://github.com/deckerweb/builder-list-pages
 * GitHub Plugin URI: https://github.com/deckerweb/builder-list-pages
 * Primary Branch: main
 * Copyright © 2019–2026 David Decker – DECKERWEB.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */
defined( 'ABSPATH' ) || exit;
// Plugin and generated snippet are alternatives, never simultaneous instances.
if ( defined( 'BLP_VERSION' ) || class_exists( 'DDW_Builder_List_Pages', false ) ) {
	return;
}
define( 'BLP_VERSION', '1.1.0' );
define( 'BLP_PLUGIN_FILE', __FILE__ );
define( 'BLP_PLUGIN_DIR', __DIR__ . '/' );
require_once BLP_PLUGIN_DIR . 'includes/class-blp-registry.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-query.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-admin.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-settings.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-github-updates.php';
add_action( 'init', static function (): void {
	load_plugin_textdomain( 'builder-list-pages', false, dirname( plugin_basename( BLP_PLUGIN_FILE ) ) . '/languages/' );
} );
add_action( 'plugins_loaded', static function (): void {
	$registry = new \Deckerweb\BuilderListPages\Registry();
	$query = new \Deckerweb\BuilderListPages\Query( $registry );
	$settings = new \Deckerweb\BuilderListPages\Settings();
	( new \Deckerweb\BuilderListPages\Admin( $registry, $query, $settings ) )->register();
	$settings->register();
	( new \Deckerweb\BuilderListPages\GitHubUpdates() )->register();
} );
require_once BLP_PLUGIN_DIR . 'includes/deckerweb-plugin-library/bootstrap.php';
deckerweb_library_register( __FILE__, [], BLP_PLUGIN_DIR . 'includes/deckerweb-plugin-library' );
