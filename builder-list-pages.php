<?php # -*- coding: utf-8 -*-
/*
Plugin Name:       Builder List Pages
Plugin URI:        https://github.com/deckerweb/builder-list-pages
Description:       List those pages and post types which were edited with your favorite Page Builder. Adds additional views to the post type list tables, plus, submenus.
Project:           Code Snippet: DDW Builder List Pages
Version:           1.0.0
Author:            David Decker - DECKERWEB
Author URI:        https://deckerweb.de/
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:       builder-list-pages
Domain Path:       /languages/
Requires WP:       6.7
Requires PHP:      7.4
Requires CP:       2.0.0
Update URI:        https://github.com/deckerweb/builder-list-pages/
GitHub Plugin URI: https://github.com/deckerweb/builder-list-pages
Primary Branch:    main
Copyright:         © 2019-2025, David Decker - DECKERWEB

TESTED WITH:
Product			Versions
--------------------------------------------------------------------------------------------------------------
PHP 			8.0, 8.3
WordPress		6.7.2 ... 6.8 Beta
ClassicPress	2.4.x
--------------------------------------------------------------------------------------------------------------

VERSION HISTORY:
Date        Version     Description
--------------------------------------------------------------------------------------------------------------
2025-04-11	1.0.0	    Initial public release
2019-08-12	0.5.0       Development start / alpha
--------------------------------------------------------------------------------------------------------------
*/

/**
 * Exit if called directly.
 */
if ( ! defined( 'ABSPATH' ) ) exit( 'Sorry, you are not allowed to access this file directly.' );


if ( ! class_exists( 'DDW_Builder_List_Pages' ) ) :

class DDW_Builder_List_Pages {

	/** Class constants & variables */
	private const VERSION = '1.0.0';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init',        array( $this, 'load_translations' ), 1 );
		add_filter( 'parse_query', array( $this, 'post_types_builder_parse_query_filter' ) );
		add_action( 'admin_init',  array( $this, 'prepare_views_hook_builder' ), 10 );
		add_action( 'admin_menu',  array( $this, 'add_submenu_post_types_builder' ), 20 );
	}
	
	/**
	 * Load the text domain for translation of the plugin.
	 *
	 * @uses get_user_locale()
	 * @uses load_textdomain() To load translations first from WP_LANG_DIR sub folder.
	 * @uses load_plugin_textdomain() To additionally load default translations from plugin folder (default).
	 */
	public function load_translations() {
	
		/** Set unique textdomain string */
		$blp_textdomain = 'builder-list-pages';
	
		/** The 'plugin_locale' filter is also used by default in load_plugin_textdomain() */
		$locale = esc_attr(
			apply_filters(
				'plugin_locale',
				get_user_locale(),
				$blp_textdomain
			)
		);
	
		/**
		 * WordPress languages directory
		 *   Will default to: wp-content/languages/builder-list-pages/builder-list-pages-{locale}.mo
		 */
		$blp_wp_lang_dir = trailingslashit( WP_LANG_DIR ) . trailingslashit( $blp_textdomain ) . $blp_textdomain . '-' . $locale . '.mo';
	
		/** Translations: First, look in WordPress' "languages" folder = custom & update-safe! */
		load_textdomain( $blp_textdomain, $blp_wp_lang_dir );
	
		/** Translations: Secondly, look in 'wp-content/languages/plugins/' for the proper .mo/.l10n.php file (= default) */
		load_plugin_textdomain( $blp_textdomain, FALSE, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'languages' );
	}
	
	/**
	 * For Oxygen Classic Builder (v4.x) get all post types that can be edited with
	 *   the builder (but not its own template types).
	 *
	 * @return array $post_types Array of Oxygen-editable post types.
	 */
	private function oxygen_classic_post_type_helper() {
		
		if ( ! defined( 'CT_VERSION' ) ) return [];
		
		/** Get Oxygen's list of ignored post types */
		global $ct_ignore_post_types;
		
		/** Get all registered post types */
		$post_types = get_post_types();
		
		$oxy_template_types = array( 'ct_template', 'oxy_user_library' );
		
		/** Check post type list against list of ignored types */
		if ( is_array( $ct_ignore_post_types ) && is_array( $post_types ) ) {
			$post_types = array_diff( $post_types, $ct_ignore_post_types );
		}
		
		$post_types = array_diff( $post_types, $oxy_template_types );
		
		return $post_types;
	}
	
	/**
	 * For Breakdance Builder get all post types that can be edited with the builder
	 *   (but not its own template types).
	 *
	 * @return array $bd_types Array of Breakdance-editable post types.
	 */
	private function breakdance_post_type_helper() {
		
		if ( ! function_exists( '\Breakdance\Settings\get_allowed_post_types' ) ) return [];
		
		$bd_types = \Breakdance\Settings\get_allowed_post_types( $include_builder_own_types = FALSE );
		
		return $bd_types;
	}
	
	/**
	 * For Oxygen Builder (v6.x) get all post types that can be edited with the builder
	 *   (but not its own template types).
	 * NOTE: Since Oxygen 6+ is somehow an edition of "Breakdance" it also uses
	 *       Breakdance namespaces, classes and functions under the hood.
	 *
	 * @return array $bd_types Array of Oxygen 6+-editable post types.
	 */
	private function oxygen_builder_post_type_helper() {
		
		return $this->breakdance_post_type_helper();
	}
	
	/**
	 * For ZionBuilder get all post types that can be edited with the builder.
	 *
	 * @link https://stackoverflow.com/questions/44305643/json-decode-in-wordpress
	 *
	 * @return array $zion_types Array of ZionBuilder-editable post types.
	 */
	private function zionbuilder_post_type_helper() {
		
		if ( ! function_exists( '\ZionBuilder\zionbuilder_load_textdomain' ) ) return [];
		
		$zion_json  = get_option( '_zionbuilder_options', [] );
		$zion_array = json_decode( stripslashes( $zion_json ), TRUE );
		
		$zion_types = $zion_array[ 'allowed_post_types' ];
		
		return $zion_types;
	}
	
	/**
	 * For Thrive Architect get all public post types that can be edited with
	 *   the builder.
	 *
	 * @link https://www.tannerrecord.com/checking-post-type-on-save/
	 *
	 * @return array $ta_types Array of Thrive Architect-editable post types.
	 */
	function thrive_architect_post_type_helper() {
	
		if ( ! defined( 'TVE_EDITOR_URL' ) ) return [];
		
		$args = [ 'public' => TRUE, ];
		
		$ta_types = get_post_types( $args );
		
		return $ta_types;
	}
	
	/**
	 * Get all the specific data of all supported Builders in an array.
	 *
	 * @return array $post_types Filterable array of supported Builders.
	 */
	private function get_builder_data() {
		
		$post_types = [
			/** Elementor (free & Premium) */
			'elementor' => [
				'builder-id'    => 'elementor',
				'is-active'     => defined( 'ELEMENTOR_VERSION' ),
				'builder-types' => get_option( 'elementor_cpt_support', [] ),
				'meta-key'      => '_elementor_edit_mode',
				'meta-value'    => 'builder',
				'label'         => __( 'Elementor', 'builder-list-pages' ),
				'with-label'    => __( 'With Elementor', 'builder-list-pages' ),
			],
			
			/** Bricks Builder (Premium) */
			'bricks' => [
				'builder-id'    => 'bricks',
				'is-active'     => defined( 'BRICKS_VERSION' ),
				'builder-types' => get_option( 'bricks_global_settings' ) ? get_option( 'bricks_global_settings', [] )[ 'postTypes' ] : [],
				'meta-key'      => '_bricks_editor_mode',
				'meta-value'    => 'bricks',
				'label'         => __( 'Bricks', 'builder-list-pages' ),
				'with-label'    => __( 'With Bricks', 'builder-list-pages' ),
			],
			
			/** Breakdance Builder (Premium) */
			'breakdance' => [
				'builder-id'    => 'breakdance',
				'is-active'     => ( defined( '__BREAKDANCE_VERSION' ) && ! defined( 'BREAKDANCE_MODE' ) ),
				'builder-types' => $this->breakdance_post_type_helper(),
				'meta-key'      => '_breakdance_data',
				'meta-value'    => 0,
				'label'         => __( 'Breakdance', 'builder-list-pages' ),
				'with-label'    => __( 'With Breakdance', 'builder-list-pages' ),
			],
			
			/** Oxygen Classic (4.x) (Premium) */
			'oxygen-classic'    => [
				'builder-id'    => 'oxygen-classic',
				'is-active'     => defined( 'CT_VERSION' ),
				'builder-types' => $this->oxygen_classic_post_type_helper(),
				'meta-key'      => '_ct_builder_json',
				'meta-value'    => 0,
				'label'         => __( 'Oxygen', 'builder-list-pages' ),
				'with-label'    => __( 'With Oxygen', 'builder-list-pages' ),
			],
			
			/** Oxygen 6+ (6.x) (Premium) */
			'oxygen-builder' => [
				'builder-id'    => 'oxygen',
				'is-active'     => ( defined( 'BREAKDANCE_MODE' ) && 'oxygen' === BREAKDANCE_MODE ),
				'builder-types' => $this->oxygen_builder_post_type_helper(),
				'meta-key'      => '_oxygen_data',
				'meta-value'    => 0,
				'label'         => __( 'Oxygen', 'builder-list-pages' ),
				'with-label'    => __( 'With Oxygen', 'builder-list-pages' ),
			],
			
			/** Brizy (free & Premium) */
			'brizy' => [
				'builder-id'    => 'brizy',
				'is-active'     => defined( 'BRIZY_VERSION' ),
				'builder-types' => get_option( 'brizy' ) ? get_option( 'brizy', [] )[ 'post-types' ] : [],
				'meta-key'      => 'brizy_enabled',
				'meta-value'    => TRUE,
				'label'         => __( 'Brizy', 'builder-list-pages' ),
				'with-label'    => __( 'With Brizy', 'builder-list-pages' ),
			],
			
			/** Beaver Builder (free & Premium) */
			'beaver-builder' => [
				'builder-id'    => 'beaver',
				'is-active'     => class_exists( 'FLBuilderLoader' ),
				'builder-types' => get_option( '_fl_builder_post_types', [] ),
				'meta-key'      => '_fl_builder_enabled',
				'meta-value'    => TRUE,
				'label'         => __( 'Beaver Builder', 'builder-list-pages' ),
				'with-label'    => __( 'With Beaver', 'builder-list-pages' ),
			],
			
			/** Pagelayer Builder (free & Premium) */
			'pagelayer' => [
				'builder-id'    => 'pagelayer',
				'is-active'     => defined( 'PAGELAYER_VERSION' ),
				'builder-types' => get_option( 'pl_support_ept' ) ? get_option( 'pl_support_ept', [] ) : [ 'post', 'page' ],
				'meta-key'      => 'pagelayer-data',
				'meta-value'    => 0,
				'label'         => __( 'Pagelayer', 'builder-list-pages' ),
				'with-label'    => __( 'With Pagelayer', 'builder-list-pages' ),
			],
			
			/** ZionBuilder (free & Premium) */
			'zionbuilder' => [
				'builder-id'    => 'zionbuilder',
				'is-active'     => function_exists( '\ZionBuilder\zionbuilder_load_textdomain' ),
				'builder-types' => $this->zionbuilder_post_type_helper(),
				'meta-key'      => '_zionbuilder_page_status',
				'meta-value'    => 'enabled',
				'label'         => __( 'ZionBuilder', 'builder-list-pages' ),
				'with-label'    => __( 'With ZionBuilder', 'builder-list-pages' ),
			],
			
			/** Visual Composer (free & Pro) – currently only free version supported! */
			'visual-composer' => [
				'builder-id'    => 'visualcomposer',
				'is-active'     => defined( 'VCV_VERSION' ),
				'builder-types' => [ 'page', 'post', ],		// this is only for free version
				'meta-key'      => 'vcv-be-editor',
				'meta-value'    => 'fe',
				'label'         => __( 'Visual Composer', 'builder-list-pages' ),
				'with-label'    => __( 'With Visual Composer', 'builder-list-pages' ),
			],
			
			/** Thrive Architect (Premium) */
			'thrive-architect' => [
				'builder-id'    => 'thrive-architect',
				'is-active'     => defined( 'TVE_EDITOR_URL' ),
				'builder-types' => $this->thrive_architect_post_type_helper(),
				'meta-key'      => 'tcb_editor_enabled',
				'meta-value'    => TRUE,
				'label'         => __( 'Thrive Architect', 'builder-list-pages' ),
				'with-label'    => __( 'With Thrive Architect', 'builder-list-pages' ),
			],
		];
		
		/** Return the array, filterable */
		return apply_filters(
			'blp/filter/builder-data',
			$post_types
		);
	}
	
	/**
	 * Check if any (supported) Builder is active.
	 *
	 * NOTE: This is an in-between step to determine the first active Builder from
	 *       our array of supported Builders. We assume that a site has only one
	 *       "big" Builder active at a time (otherwise this plugin here makes no
	 *       real sense, honestly).
	 *
	 * @return string|array $is_builder Array of Builder data if a Builder is active,
	 *                                  a string otherwise.
	 */
	private function is_builder_active() {
		
		$builders = $this->get_builder_data();
		
		$is_builder = '_no_builder_active';
		
		foreach ( $builders as $builder ) {
			if ( $builder[ 'is-active' ] ) {
				$is_builder = (array) $builder;
				break;
			}
		}
		
		return $is_builder;
	}
	
	/**
	 * Query the post type items which were edited with the Builder.
	 *
	 * @param object $query
	 */
	public function post_types_builder_parse_query_filter( $query ) {
	
		/** Bail early if no supported builder active */
		if ( '_no_builder_active' === $this->is_builder_active() ) {
			return;
		}
		
		/** Get the one active builder & its data */
		$builder_infos = $this->is_builder_active();
		
		$meta_query = ( in_array( $builder_infos[ 'builder-id' ], [ 'breakdance', 'oxygen', 'oxygen-classic', 'pagelayer' ] ) ) ? [ 'key' => $builder_infos[ 'meta-key' ] ] : [ 'key' => $builder_infos[ 'meta-key' ], 'value' => $builder_infos[ 'meta-value' ] ];
		
		if ( is_admin() && in_array( $query->query[ 'post_type' ], (array) $builder_infos[ 'builder-types' ] ) ) {
			
			if ( isset( $_GET[ 'builder' ] ) && sanitize_key( wp_unslash( $_GET[ 'builder' ] ) ) ) {
	
				$query_var = &$query->query_vars;
	
				$query_var[ 'meta_query' ] = [ $meta_query ];
	
			}  // end if
	
		}  // end if
	}
	
	/**
	 * Prepare the `views_edit-{post_type}` folter with the supported post types.
	 */
	public function prepare_views_hook_builder() {
	
		/** Bail early if no supported builder active */
		if ( '_no_builder_active' === $this->is_builder_active() ) {
			return;
		}
		
		/** Get the one active builder & its data */
		$builder_infos = $this->is_builder_active();
				
		foreach ( (array) $builder_infos[ 'builder-types' ] as $builder_type ) {
			add_filter( 'views_edit-' . $builder_type, array( $this, 'post_types_builder_views_filter' ), 1 );
		}
	}
	
	/**
	 * Setup the views filter above the post type list table.
	 *
	 * @param  array $views Array which holds all views.
	 * @return array $views Modified array of views.
	 */
	public function post_types_builder_views_filter( $views ) {
	
		/** Bail early if no supported builder active */
		if ( '_no_builder_active' === $this->is_builder_active() ) {
			return $views;
		}

		global $wp_query;
	
		/** Get the one active builder & its data */
		$builder = $this->is_builder_active();
	
		$current_post_type = $wp_query->query[ 'post_type' ];
		
		$meta_query = ( in_array( $builder[ 'builder-id' ], [ 'breakdance', 'oxygen', 'oxygen-classic', 'pagelayer' ] ) ) ? [ 'key' => $builder[ 'meta-key' ] ] : [ 'key' => $builder[ 'meta-key' ], 'value' => $builder[ 'meta-value' ] ];
		
		$query = array(
			'post_type'  => $current_post_type,
			'meta_query' => [ $meta_query ]
		);
	
		$result = new WP_Query( $query );
		$class  = ( isset( $_GET[ 'builder' ] ) && $builder[ 'builder-id' ] == sanitize_key( wp_unslash( $_GET[ 'builder' ] ) ) ) ? ' class="current"' : '';
	  
		$admin_url = add_query_arg(
			'builder',
			$builder[ 'builder-id' ],
			admin_url( 'edit.php?post_type=' . $current_post_type )
		);
		
		$post_type  = get_post_type_object( $current_post_type );
		$label_name = $post_type->labels->name;
		
		$title_attr = sprintf(
			/* translators: 1 - plural name of post type (i.e. Pages) / 2 - name of the Builder (i.e. Bricks) */
			_x( '%1$s edited with %2$s', 'Title attribute of Views link (above post type list table)', 'builder-list-pages' ),
			$label_name,
			esc_html( $builder[ 'label' ] )
		);
		
		$views[ 'builder' ] = sprintf(
			'<a href="%1$s"%2$s title="%3$s">%4$s <span class="count">(%5$d)</span></a>',
			esc_url( $admin_url ),
			$class,
			esc_html( $title_attr ),
			esc_html( $builder[ 'label' ] ),
			$result->found_posts
		);
	
		return $views;
	}
	
	/**
	 * Add a "With {Builder}" submenu for each determined post type.
	 */
	public function add_submenu_post_types_builder() {
	
		/** Bail early if no supported builder active */	
		if ( '_no_builder_active' === $this->is_builder_active() ) {
			return;
		}
		
		/** Get the one active builder & its data */
		$builder_infos = $this->is_builder_active();
		
		$label_edited = 'Edited with a Builder';
		$label_with   = 'With Builder';
		$post_type    = '';
		$parent_id    = '';
		$submenu_id   = '';
		
		global $parent_file;
		global $submenu_file;
		
		foreach ( (array) $builder_infos[ 'builder-types' ] as $builder_type ) {
			
			$parent_id = 'edit.php?post_type=' . $builder_type;
	
			if ( 'post' === $builder_type ) {
				$parent_id = 'edit.php';
			} elseif ( 'astra-advanced-hook' === $builder_type ) {
				$parent_id = 'astra';
			} elseif ( 'oceanwp_library' === $builder_type ) {
				$parent_id = 'oceanwp';
			}
			
			$submenu_id = 'edit.php?post_type=' . $builder_type . '&builder=' . $builder_infos[ 'builder-id' ];
		
			$post_type  = get_post_type_object( $builder_type );
			$label_name = $post_type->labels->name;
			
			$label_edited = sprintf(
				/* translators: 1 - plural name of post type (i.e. Pages) / 2 - name of the Builder (i.e. Bricks) */
				_x( '%1$s edited with %2$s', 'Page title', 'builder-list-pages' ),
				$label_name,
				esc_html( $builder_infos[ 'label' ] )
			);
			
			add_submenu_page(
				$parent_id,
				esc_html( $label_edited ),
				esc_html( $builder_infos[ 'with-label' ] ),
				'edit_theme_options',	// that fits with most of the builders
				esc_url( admin_url( 'edit.php?post_type=' . $builder_type . '&builder=' . $builder_infos[ 'builder-id' ] ) )
			);
			
		}  // end foreach
	}
	
}  // end of class

/** Start instance of Class */
new DDW_Builder_List_Pages();
	
endif;


if ( ! function_exists( 'ddw_blp_pluginrow_meta' ) ) :
	
add_filter( 'plugin_row_meta', 'ddw_blp_pluginrow_meta', 10, 2 );
/**
 * Add plugin related links to plugin page.
 *
 * @param array  $ddwp_meta (Default) Array of plugin meta links.
 * @param string $ddwp_file File location of plugin.
 * @return array $ddwp_meta (Modified) Array of plugin links/ meta.
 */
function ddw_blp_pluginrow_meta( $ddwp_meta, $ddwp_file ) {
 
	if ( ! current_user_can( 'install_plugins' ) ) return $ddwp_meta;
	
	/** Get current user */
	$user = wp_get_current_user();
	
	/** Build Newsletter URL */
	$url_nl = sprintf(
		'https://deckerweb.us2.list-manage.com/subscribe?u=e09bef034abf80704e5ff9809&amp;id=380976af88&amp;MERGE0=%1$s&amp;MERGE1=%2$s',
		esc_attr( $user->user_email ),
		esc_attr( $user->user_firstname )
	);
	
	/** List additional links only for this plugin */
	if ( $ddwp_file === trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . basename( __FILE__ ) ) {
		$ddwp_meta[] = sprintf(
			'<a class="button button-inline" href="https://ko-fi.com/deckerweb" target="_blank" rel="nofollow noopener noreferrer" title="%1$s">❤ <b>%1$s</b></a>',
			esc_html_x( 'Donate', 'Plugins page listing', 'builder-list-pages' )
		);
		
		$ddwp_meta[] = sprintf(
			'<a class="button-primary" href="%1$s" target="_blank" rel="nofollow noopener noreferrer" title="%2$s">⚡ <b>%2$s</b></a>',
			$url_nl,
			esc_html_x( 'Join our Newsletter', 'Plugins page listing', 'builder-list-pages' )
		);
	}  // end if
	
	return apply_filters( 'pat/plugins-page/meta-links', $ddwp_meta );

}  // end function

endif;