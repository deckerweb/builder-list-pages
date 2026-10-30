<?php
/** Shared conditions for the main list and lightweight view counts. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

/**
 * Filter native lists and count readable matches using shared conditions.
 */
final class Query {
	private Registry $registry;
	private array $counts = [];
	private \SplObjectStorage $negative_queries;
	private \SplObjectStorage $special_queries;

	/**
	 * Retain the registry and initialize query-scoped exclusions.
	 *
	 * @param Registry $registry Validated builder registry.
	 * @return void
	 */
	public function __construct( Registry $registry ) {
		$this->registry = $registry;
		$this->negative_queries = new \SplObjectStorage();
		$this->special_queries = new \SplObjectStorage();
	}
	/**
	 * Register native list and SQL-filter callbacks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'pre_get_posts', [ $this, 'filter_main' ], 20 );
		add_action( 'clean_post_cache', [ $this, 'reset_counts' ] );
		add_action( 'update_option_blp_settings', [ $this, 'reset_counts' ] );
		foreach ( [ 'added_post_meta', 'updated_post_meta', 'deleted_post_meta' ] as $hook ) { add_action( $hook, [ $this, 'reset_counts' ] ); }
		add_filter( 'posts_where', [ $this, 'exclude_builders' ], 20, 2 );
	}
	/** Invalidate request-local counts after saved content or a synced pattern changes. @return void */
	public function reset_counts(): void { $this->counts = []; }

	/**
	 * Validate the requested builder against available views.
	 *
	 * @param string $type Content-type name.
	 * @return string Resulting text, URL or SQL fragment.
	 */
	public function selected( string $type ): string {
		$value = isset( $_GET['builder'] ) && is_string( $_GET['builder'] ) ? sanitize_key( wp_unslash( $_GET['builder'] ) ) : '';
		if ( ! is_string( $value ) ) { return ''; }
		$id = sanitize_key( wp_unslash( $value ) );
		$builders = $this->registry->for_type( $type );
		return ( ( 'multiple' === $id && count( $builders ) > 1 ) || ( 'traces' === $id && $this->registry->traces( $type ) ) || isset( $builders[ $id ] ) || ( 'none' === $id && $builders && Settings::enabled( 'show_none' ) ) ) ? $id : '';
	}
	/**
	 * Apply the selected builder only to the main admin content list.
	 *
	 * @param \WP_Query $query Native query being filtered.
	 * @return void
	 */
	public function filter_main( \WP_Query $query ): void {
		global $pagenow;
		if ( ! is_admin() || 'edit.php' !== $pagenow || ! $query->is_main_query() || wp_doing_ajax() ) { return; }
		$type = $query->get( 'post_type' );
		$type = empty( $type ) ? 'post' : $type;
		if ( ! is_string( $type ) ) { return; }
		$selected = $this->selected( $type );
		if ( '' !== $selected ) { $this->apply( $query, $type, $selected ); }
	}
	/**
	 * Add a builder constraint while preserving existing metadata groups.
	 *
	 * @param \WP_Query $query Native query being filtered.
	 * @param string $type Content-type name.
	 * @param string $id Validated builder identifier or none.
	 * @return void
	 */
	public function apply( \WP_Query $query, string $type, string $id ): void {
		$builders = $this->registry->for_type( $type );
		if ( 'multiple' === $id && count( $builders ) > 1 ) { $this->special_queries[ $query ] = [ $builders, 2, $type ]; return; }
		if ( 'traces' === $id ) { $traces = $this->registry->traces( $type ); if ( $traces ) { $this->special_queries[ $query ] = [ $traces, 1, $type ]; } return; }
		if ( 'none' === $id && $builders ) {
			$this->negative_queries[ $query ] = [ $builders, $type ];
			return;
		}
		if ( ! isset( $builders[ $id ] ) ) { return; }
		if ( 'blocks' === ( $builders[ $id ]['recognition'] ?? '' ) ) { $this->special_queries[ $query ] = [ [ $builders[ $id ] ], 1, $type ]; return; }
		$existing = $query->get( 'meta_query' );
		$builder = $builders[ $id ]['meta_query'];
		$query->set( 'meta_query', is_array( $existing ) && $existing ? [ 'relation' => 'AND', $existing, $builder ] : $builder );
	}
	/**
	 * Exclude any matching builder using a query-scoped complement.
	 *
	 * @param string $where Existing SQL WHERE fragment.
	 * @param \WP_Query $query Native query being filtered.
	 * @return string Resulting text, URL or SQL fragment.
	 */
	public function exclude_builders( string $where, \WP_Query $query ): string {
		if ( $this->special_queries->contains( $query ) ) {
			global $wpdb;
			[ $builders, $minimum, $type ] = $this->special_queries[ $query ];
			$parts = [];
			foreach ( $builders as $builder ) {
				$condition = $this->condition( $builder, $type );
				$parts[] = "CASE WHEN ({$condition}) THEN 1 ELSE 0 END";
			}
			return $where . ' AND (' . implode( ' + ', $parts ) . ') >= ' . (int) $minimum;
		}
		if ( ! $this->negative_queries->contains( $query ) ) { return $where; }
		$parts = [];
		[ $builders, $type ] = $this->negative_queries[ $query ];
		foreach ( $builders as $builder ) { $parts[] = '(' . $this->condition( $builder, $type ) . ')'; }
		return $where . ' AND NOT (' . implode( ' OR ', $parts ) . ')';
	}
	/**
	 * Build one verified family or metadata condition shared by negative and multiple views.
	 * @param array $builder Validated registry entry.
	 * @param string $type Validated content-type name.
	 * @return string SQL predicate using prepared values and internal integer IDs only.
	 */
	private function condition( array $builder, string $type ): string {
		global $wpdb;
		if ( 'blocks' === ( $builder['recognition'] ?? '' ) ) {
			$ids = BlockAdapters::ids( $type, $builder['builder-id'] );
			return $ids ? $wpdb->posts . '.ID IN (' . implode( ',', array_map( 'absint', $ids ) ) . ')' : '1=0';
		}
		$c = $builder['meta_query'][0];
		$condition = $wpdb->prepare( 'blp_meta.meta_key = %s', $c['key'] );
		if ( '=' === $c['compare'] ) { $condition .= $wpdb->prepare( ' AND blp_meta.meta_value = %s', $c['value'] ); }
		return "EXISTS (SELECT 1 FROM {$wpdb->postmeta} AS blp_meta WHERE blp_meta.post_id = {$wpdb->posts}.ID AND {$condition})";
	}

	/**
	 * Cache readable content counts outside trash for this request.
	 *
	 * @param string $type Content-type name.
	 * @param string $id Validated builder identifier or none.
	 * @return int Number of readable matching items.
	 */
	public function count( string $type, string $id ): int {
		$key = get_current_blog_id() . ':' . get_current_user_id() . ':' . $type . ':' . $id;
		if ( isset( $this->counts[ $key ] ) ) { return $this->counts[ $key ]; }
		$query = new \WP_Query();
		$this->apply( $query, $type, $id );
		$object = get_post_type_object( $type );
		$args = [
			'post_type' => $type,
			'post_status' => array_values( get_post_stati( [ 'show_in_admin_all_list' => true ] ) ),
			'perm' => 'readable', 'fields' => 'ids', 'posts_per_page' => 1,
			'orderby' => 'none', 'ignore_sticky_posts' => true,
			'update_post_meta_cache' => false, 'update_post_term_cache' => false,
			'meta_query' => $query->get( 'meta_query' ),
		];
		if ( $object && ! current_user_can( $object->cap->edit_others_posts ) ) { $args['author'] = get_current_user_id(); }
		$query->query( $args );
		if ( $this->negative_queries->contains( $query ) ) { $this->negative_queries->detach( $query ); }
		if ( $this->special_queries->contains( $query ) ) { $this->special_queries->detach( $query ); }
		return $this->counts[ $key ] = (int) $query->found_posts;
	}
}
