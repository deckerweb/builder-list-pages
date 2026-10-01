<?php
/** Shared conditions for the main list and lightweight view counts. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

final class Query {
	private Registry $registry;
	private array $counts = [];
	private \SplObjectStorage $negative_queries;

	public function __construct( Registry $registry ) {
		$this->registry = $registry;
		$this->negative_queries = new \SplObjectStorage();
	}
	public function register(): void {
		add_action( 'pre_get_posts', [ $this, 'filter_main' ], 20 );
		add_filter( 'posts_where', [ $this, 'exclude_builders' ], 20, 2 );
	}
	/** Unknown or non-string URL parameters never activate a builder filter. */
	public function selected( string $type ): string {
		$value = $_GET['builder'] ?? '';
		if ( ! is_string( $value ) ) { return ''; }
		$id = sanitize_key( wp_unslash( $value ) );
		$builders = $this->registry->for_type( $type );
		return ( isset( $builders[ $id ] ) || ( 'none' === $id && $builders && Settings::enabled( 'show_none' ) ) ) ? $id : '';
	}
	public function filter_main( \WP_Query $query ): void {
		global $pagenow;
		if ( ! is_admin() || 'edit.php' !== $pagenow || ! $query->is_main_query() || wp_doing_ajax() ) { return; }
		$type = $query->get( 'post_type' );
		$type = empty( $type ) ? 'post' : $type;
		if ( ! is_string( $type ) ) { return; }
		$selected = $this->selected( $type );
		if ( '' !== $selected ) { $this->apply( $query, $type, $selected ); }
	}
	/** Preserve the existing group, including any internal OR relationship. */
	public function apply( \WP_Query $query, string $type, string $id ): void {
		$builders = $this->registry->for_type( $type );
		if ( 'none' === $id && $builders ) {
			$this->negative_queries[ $query ] = $builders;
			return;
		}
		if ( ! isset( $builders[ $id ] ) ) { return; }
		$existing = $query->get( 'meta_query' );
		$builder = $builders[ $id ]['meta_query'];
		$query->set( 'meta_query', is_array( $existing ) && $existing ? [ 'relation' => 'AND', $existing, $builder ] : $builder );
	}
	/** Exact complement, including duplicate meta rows. Scoped by object identity. */
	public function exclude_builders( string $where, \WP_Query $query ): string {
		if ( ! $this->negative_queries->contains( $query ) ) { return $where; }
		$clauses = [ 'relation' => 'OR' ];
		foreach ( $this->negative_queries[ $query ] as $builder ) { $clauses[] = $builder['meta_query']; }
		global $wpdb;
		$meta = new \WP_Meta_Query( $clauses );
		$sql = $meta->get_sql( 'post', 'blp_posts', 'ID', $query );
		return $where . " AND {$wpdb->posts}.ID NOT IN (SELECT blp_posts.ID FROM {$wpdb->posts} AS blp_posts {$sql['join']} WHERE 1=1 {$sql['where']})";
	}
	/** Count readable items outside trash; search does not change the badges. */
	public function count( string $type, string $id ): int {
		$key = $type . ':' . $id;
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
		return $this->counts[ $key ] = (int) $query->found_posts;
	}
}
