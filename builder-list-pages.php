<?php
/**
 * Plugin Name: Builder List Pages
 * Plugin URI: https://deckerwebplugins.com/en/plugins/builder-list-pages/
 * Description: Find pages by their page builder. Recognize builders at a glance, filter multiple builders and keep working in familiar WordPress lists.
 * Version: 1.3.0
 * Requires at least: 6.7
 * Requires PHP: 8.1
 * Author: David Decker – DECKERWEB
 * Author URI: https://deckerwebplugins.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: builder-list-pages
 * Domain Path: /languages/
 * Update URI: https://github.com/deckerweb/builder-list-pages
 * GitHub Plugin URI: https://github.com/deckerweb/builder-list-pages
 *
 * Copyright © 2019–2026 David Decker – DECKERWEB.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */
defined( 'ABSPATH' ) || exit;
// Plugin and generated snippet are alternatives, never simultaneous instances.
if ( defined( 'BLP_VERSION' ) || class_exists( 'DDW_Builder_List_Pages', false ) ) {
	return;
}
define( 'BLP_VERSION', '1.3.0' );
define( 'BLP_PLUGIN_FILE', __FILE__ );
define( 'BLP_PLUGIN_DIR', __DIR__ . '/' );
require_once BLP_PLUGIN_DIR . 'includes/class-blp-content-types.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-direct-adapters.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-layout-adapters.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-block-adapters.php';
add_action( 'clean_post_cache', [ \Deckerweb\BuilderListPages\BlockAdapters::class, 'reset' ] );
add_action( 'add_option_cb_settings', [ \Deckerweb\BuilderListPages\BlockAdapters::class, 'reset' ] );
add_action( 'delete_option_cb_settings', [ \Deckerweb\BuilderListPages\BlockAdapters::class, 'reset' ] );
add_action( 'add_option_builderius_page_template_post_types', [ \Deckerweb\BuilderListPages\BlockAdapters::class, 'reset' ] );
add_action( 'delete_option_builderius_page_template_post_types', [ \Deckerweb\BuilderListPages\BlockAdapters::class, 'reset' ] );
add_action( 'update_option_cb_settings', [ \Deckerweb\BuilderListPages\BlockAdapters::class, 'reset' ] );
add_action( 'update_option_builderius_page_template_post_types', [ \Deckerweb\BuilderListPages\BlockAdapters::class, 'reset' ] );
add_action( 'update_option_blp_settings', [ \Deckerweb\BuilderListPages\BlockAdapters::class, 'reset' ] );
add_action( 'added_post_meta', [ \Deckerweb\BuilderListPages\BlockAdapters::class, 'reset' ] );
add_action( 'updated_post_meta', [ \Deckerweb\BuilderListPages\BlockAdapters::class, 'reset' ] );
add_action( 'deleted_post_meta', [ \Deckerweb\BuilderListPages\BlockAdapters::class, 'reset' ] );
require_once BLP_PLUGIN_DIR . 'includes/class-blp-registry.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-query.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-admin.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-workflows.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-overview.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-settings.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-admin-tools.php';
require_once BLP_PLUGIN_DIR . 'includes/class-blp-github-updates.php';
add_action( 'init',
/**
 * Load the host translation domain during initialization.
 *
 * @return void
 */
static function (): void {
	load_plugin_textdomain( 'builder-list-pages', false, dirname( plugin_basename( BLP_PLUGIN_FILE ) ) . '/languages/' );
} );
add_action( 'plugins_loaded',
/**
 * Register plugin integrations after all plugin bootstraps are available.
 *
 * @return void
 */
static function (): void {
	$registry = new \Deckerweb\BuilderListPages\Registry();
	$query = new \Deckerweb\BuilderListPages\Query( $registry );
	$settings = new \Deckerweb\BuilderListPages\Settings();
	( new \Deckerweb\BuilderListPages\Admin( $registry, $query, $settings ) )->register();
	$settings->register();
	( new \Deckerweb\BuilderListPages\AdminTools() )->register();
	( new \Deckerweb\BuilderListPages\Workflows( $registry, $query ) )->register();
	( new \Deckerweb\BuilderListPages\GitHubUpdates() )->register();
} );
require_once BLP_PLUGIN_DIR . 'includes/deckerweb-plugin-library/bootstrap.php';
deckerweb_library_register_v2( __FILE__, [], BLP_PLUGIN_DIR . 'includes/deckerweb-plugin-library' );
