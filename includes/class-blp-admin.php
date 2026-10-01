<?php
/** Integrate with native post lists, columns and existing post-type menus. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

final class Admin {
	private Registry $registry;
	private Query $query;
	private Settings $settings;
	public function __construct( Registry $registry, Query $query, Settings $settings ) {
		$this->registry = $registry; $this->query = $query; $this->settings = $settings;
	}
	public function register(): void {
		$this->query->register();
		add_action( 'current_screen', [ $this, 'screen' ] );
		add_action( 'admin_menu', [ $this, 'menus' ], 30 );
		add_filter( 'parent_file', [ $this, 'highlight_menu' ], 20 );
		if ( defined( 'BLP_SNIPPET' ) ) { return; }
		add_filter( 'plugin_action_links_' . plugin_basename( BLP_PLUGIN_FILE ), [ $this, 'links' ] );
		add_filter( 'plugin_row_meta', [ $this, 'meta_links' ], 10, 2 );
	}
	public static function list_url( string $type, string $id ): string {
		return add_query_arg( [ 'post_type' => $type, 'builder' => $id ], admin_url( 'edit.php' ) );
	}
	/** Register only the currently visible supported list. */
	public function screen( \WP_Screen $screen ): void {
		if ( 'edit' !== $screen->base || ! $this->registry->for_type( $screen->post_type ) ) { return; }
		add_filter( 'views_edit-' . $screen->post_type, [ $this, 'views' ] );
		add_action( 'restrict_manage_posts', [ $this, 'preserve_selection' ], 10, 2 );
		if ( Settings::enabled( 'show_column' ) ) {
			add_filter( 'manage_' . $screen->post_type . '_posts_columns', [ $this, 'columns' ] );
			$hook = $screen->post_type === 'page' ? 'manage_pages_custom_column' : 'manage_' . $screen->post_type . '_posts_custom_column';
			add_action( $hook, [ $this, 'column' ], 10, 2 );
			add_action( 'admin_enqueue_scripts', [ $this, 'list_assets' ] );
		}
	}
	public function list_assets(): void {
		if ( defined( 'BLP_SNIPPET' ) ) { return; }
		wp_enqueue_style( 'blp-admin', plugins_url( 'assets/admin.css', BLP_PLUGIN_FILE ), [], BLP_VERSION );
	}
	public function views( array $views ): array {
		$screen = get_current_screen();
		if ( ! $screen || ! $screen->post_type ) { return $views; }
		$type = $screen->post_type;
		$builders = $this->registry->for_type( $type );
		$selected = $this->query->selected( $type );
		if ( '' !== $selected ) {
			foreach ( $views as &$view ) {
				// Core creates its current status view before our independent builder view.
				$view = str_replace( [ ' class="current"', ' aria-current="page"' ], '', $view );
			}
			unset( $view );
		}
		foreach ( $builders as $id => $builder ) {
			$views[ 'blp_' . $id ] = $this->view_link( $type, $id, $builder['label'], $selected );
		}
		if ( $builders && Settings::enabled( 'show_none' ) ) {
			$views['blp_none'] = $this->view_link( $type, 'none', __( 'No recognized builder', 'builder-list-pages' ), $selected );
		}
		return $views;
	}
	private function view_link( string $type, string $id, string $label, string $selected ): string {
		return sprintf( '<a href="%1$s"%2$s>%3$s <span class="count">(%4$s)</span></a>', esc_url( self::list_url( $type, $id ) ), $id === $selected ? ' class="current" aria-current="page"' : '', esc_html( $label ), esc_html( number_format_i18n( $this->query->count( $type, $id ) ) ) );
	}
	/** WordPress rebuilds list searches from form fields; explicitly carry the ID. */
	public function preserve_selection( string $type, string $which ): void {
		$id = $this->query->selected( $type );
		if ( 'top' === $which && '' !== $id ) {
			echo '<input type="hidden" name="builder" value="' . esc_attr( $id ) . '">';
		}
	}
	public function columns( array $columns ): array {
		$out = [];
		foreach ( $columns as $key => $label ) {
			$out[ $key ] = $label;
			if ( 'title' === $key ) { $out['blp_builder'] = __( 'Builder', 'builder-list-pages' ); }
		}
		if ( ! isset( $out['blp_builder'] ) ) { $out['blp_builder'] = __( 'Builder', 'builder-list-pages' ); }
		return $out;
	}
	public function column( string $column, int $post_id ): void {
		if ( 'blp_builder' !== $column ) { return; }
		$type = get_post_type( $post_id );
		if ( ! is_string( $type ) ) { return; }
		$builders = $this->registry->matches( $post_id, $type );
		if ( ! $builders ) {
			$label = __( 'No recognized builder', 'builder-list-pages' );
			echo Settings::enabled( 'show_none' ) ? '<a class="blp-builder blp-builder-none" href="' . esc_url( self::list_url( $type, 'none' ) ) . '">' . esc_html( $label ) . '</a>' : '<span class="blp-builder-none">' . esc_html( $label ) . '</span>';
			return;
		}
		foreach ( $builders as $id => $builder ) {
			echo '<a class="blp-builder" href="' . esc_url( self::list_url( $type, $id ) ) . '">' . esc_html( $builder['label'] ) . '</a> ';
		}
	}
	private function parent( string $type ): string {
		$object = get_post_type_object( $type );
		$parent = 'post' === $type ? 'edit.php' : 'edit.php?post_type=' . $type;
		if ( $object && is_string( $object->show_in_menu ) ) { $parent = $object->show_in_menu; }
		// Preserve integrations from 1.0.0 when their parent menu exists.
		global $menu;
		$special = [ 'astra-advanced-hook' => 'astra', 'oceanwp_library' => 'oceanwp' ];
		foreach ( is_array( $menu ) ? $menu : [] as $item ) {
			if ( isset( $special[ $type ] ) && ( $item[2] ?? '' ) === $special[ $type ] ) { $parent = $special[ $type ]; }
		}
		return $parent;
	}
	public function menus(): void {
		if ( ! Settings::enabled( 'show_submenus' ) ) { return; }
		foreach ( $this->registry->post_types() as $type ) {
			$object = get_post_type_object( $type );
			if ( ! $object || ! $object->show_in_menu ) { continue; }
			foreach ( $this->registry->for_type( $type ) as $id => $builder ) {
				/* translators: %s: builder name. */
				$label = sprintf( __( 'With %s', 'builder-list-pages' ), $builder['label'] );
				add_submenu_page( $this->parent( $type ), $label, $label, $object->cap->edit_posts, 'edit.php?post_type=' . $type . '&builder=' . $id );
			}
		}
	}
	public function highlight_menu( string $parent ): string {
		$screen = get_current_screen();
		if ( ! $screen || 'edit' !== $screen->base || ! Settings::enabled( 'show_submenus' ) ) { return $parent; }
		$id = $this->query->selected( $screen->post_type );
		if ( '' !== $id && 'none' !== $id ) {
			global $submenu_file;
			$submenu_file = 'edit.php?post_type=' . $screen->post_type . '&builder=' . $id;
			return $this->parent( $screen->post_type );
		}
		return $parent;
	}
	public function links( array $links ): array {
		if ( current_user_can( 'manage_options' ) ) {
			$links = [ 'blp_settings' => '<a href="' . esc_url( admin_url( 'options-general.php?page=builder-list-pages' ) ) . '">' . esc_html__( 'Settings', 'builder-list-pages' ) . '</a>' ] + $links;
		}
		return $links;
	}
	public function meta_links( array $links, string $file ): array {
		if ( plugin_basename( BLP_PLUGIN_FILE ) === $file ) {
			$links[] = '<a href="https://github.com/deckerweb/builder-list-pages/tree/main/docs/wiki">' . esc_html__( 'Documentation', 'builder-list-pages' ) . '</a>';
			$links[] = '<a href="https://ko-fi.com/deckerweb" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support the project', 'builder-list-pages' ) . '</a>';
		}
		return $links;
	}
}
