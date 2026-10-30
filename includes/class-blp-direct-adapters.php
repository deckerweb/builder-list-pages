<?php
/** Recognize direct CrocoBuilder activation and explicit Builderius page-template ownership. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

/** Read native stored assignments without rendering templates or evaluating frontend conditions. */
final class DirectAdapters {
	/** Return guarded runtime families without autoloading modern vendor code on older PHP. @return array Family definitions. */
	public static function families(): array {
		global $wp_version;
		return [
			'crocobuilder' => [ 'prefixes' => [], 'label' => 'CrocoBuilder', 'active' => PHP_VERSION_ID >= 80200 && ! ( is_multisite() && ms_is_switched() ) && version_compare( (string) $wp_version, '7.0', '>=' ) && defined( 'CROCOBLOCKS_VERSION' ) && class_exists( '\\Crocoblocks\\Plugin', false ) ],
			'builderius' => [ 'prefixes' => [], 'label' => 'Builderius', 'active' => PHP_VERSION_ID >= 80200 && ! ( is_multisite() && ms_is_switched() ) && function_exists( 'builderius_get_version' ) && post_type_exists( 'builderius_template' ) ],
		];
	}
	/**
	 * Respect native content-type settings only when their corresponding runtime is ready.
	 * @param string $type Content-type slug.
	 * @param string $family Integration family; other families pass through.
	 * @return bool Whether native settings permit direct editing of this type.
	 */
	public static function allows_type( string $type, string $family ): bool {
		if ( empty( self::families()[ $family ]['active'] ) ) { return true; }
		if ( 'crocobuilder' === $family ) {
			try {
				if ( class_exists( '\\Crocoblocks\\Settings\\Controller' ) ) {
					$settings = new \Crocoblocks\Settings\Controller();
					return in_array( $type, $settings->get_editable_post_types(), true );
				}
			} catch ( \Throwable $exception ) { return false; }
			return false;
		}
		if ( 'builderius' === $family ) {
			$types = [ 'page', 'post' ];
			if ( function_exists( 'builderius_pro_get_version' ) ) {
				$stored = get_option( 'builderius_page_template_post_types', $types );
				$types = is_array( $stored ) ? $stored : [];
			}
			return in_array( $type, $types, true );
		}
		return true;
	}
	/**
	 * Accept only the native direct-ID rule shape; no general conditional template evaluation.
	 * @param mixed $config Decoded native apply-rules configuration.
	 * @param int $post_id Explicit owner content ID.
	 * @param string $type Owner content-type slug.
	 * @return bool Whether every alternative is a direct assignment to this exact item.
	 */
	public static function direct_rule( $config, int $post_id, string $type ): bool {
		$location = 'page' === $type ? 'pages' : ( 'post' === $type ? 'blog_posts' : 'custom_posts' );
		if ( ! is_array( $config ) || ( $config['location'] ?? null ) !== $location || ( $config['conditions']['condition'] ?? null ) !== 'or' ) { return false; }
		$groups = $config['conditions']['rules'] ?? [];
		if ( ! is_array( $groups ) || ! $groups || count( $groups ) > 20 ) { return false; }
		foreach ( $groups as $group ) {
			if ( ! is_array( $group ) || ( $group['condition'] ?? null ) !== 'and' || ! is_array( $group['rules'] ?? null ) || ! $group['rules'] || count( $group['rules'] ) > 2 ) { return false; }
			$has_id = false; $has_type = false;
			foreach ( $group['rules'] as $rule ) {
				if ( ! is_array( $rule ) || ( $rule['operator'] ?? null ) !== 'equals' ) { return false; }
				if ( 'post_id' === ( $rule['name'] ?? null ) && ! $has_id && ( is_int( $rule['value'] ?? null ) || ( is_string( $rule['value'] ?? null ) && ctype_digit( $rule['value'] ) ) ) && (int) $rule['value'] === $post_id ) { $has_id = true; }
				elseif ( 'post_type' === ( $rule['name'] ?? null ) && ! $has_type && ( $rule['value'] ?? null ) === $type ) { $has_type = true; }
				else { return false; }
			}
			if ( ! $has_id || ( 'custom_posts' === $location && ! $has_type ) ) { return false; }
		}
		return true;
	}
	/**
	 * Read candidates in native API batches. Results join the shared request-local layout cache.
	 * Stored assignments describe editor ownership, not deployment or live frontend output.
	 * @param string $type Validated accessible content-type slug.
	 * @return array Post IDs mapped to non-sensitive recognition signatures.
	 */
	public static function index( string $type ): array {
		$result = [];
		foreach ( [ 'crocobuilder', 'builderius' ] as $family ) {
			if ( ! self::allows_type( $type, $family ) ) { continue; }
			$page = 1;
			do {
				$args = [ 'post_type' => 'crocobuilder' === $family ? $type : 'builderius_template', 'post_status' => 'crocobuilder' === $family ? array_values( get_post_stati() ) : 'publish', 'posts_per_page' => 250, 'paged' => $page++, 'orderby' => 'ID', 'order' => 'ASC', 'no_found_rows' => true, 'ignore_sticky_posts' => true, 'update_post_term_cache' => false ];
				if ( 'crocobuilder' === $family ) { $args['fields'] = 'ids'; $args['meta_key'] = '_cb_use_builder'; $args['meta_compare'] = 'IN'; $args['meta_value'] = [ '1', 'true', 'yes', 'on' ]; }
				$query = new \WP_Query( $args );
				foreach ( $query->posts as $candidate ) {
					if ( 'crocobuilder' === $family ) {
						if ( is_numeric( $candidate ) && (int) $candidate > 0 ) { $result[ (int) $candidate ][ $family ] = [ '_cb_use_builder' ]; }
					} else {
						if ( ! $candidate instanceof \WP_Post ) { continue; }
						$owner = (int) $candidate->post_parent;
						$stored = get_post_meta( $candidate->ID, 'apply_rules_config', true );
						if ( $owner > 0 && get_post_type( $owner ) === $type && self::direct_rule( is_string( $stored ) ? json_decode( $stored, true ) : null, $owner, $type ) ) { $result[ $owner ][ $family ] = [ 'builderius_template: post_parent + apply_rules_config.post_id' ]; }
					}
				}
			} while ( count( $query->posts ) === 250 );
		}
		return $result;
	}
}
