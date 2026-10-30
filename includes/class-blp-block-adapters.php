<?php
/** Recognize stored block-library usage without rendering or modifying content. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

/** Share content parsing and request-local candidate scans across block integrations. */
final class BlockAdapters {
	private static array $indexes = [];
	private static array $rows = [];
	/** Return audited public block families; shared AI blocks are deliberately not attributed. @return array Family IDs, prefixes, labels and activation state. */
	public static function families(): array {
		return array_merge( [
			'kadence-blocks' => [ 'prefixes' => [ 'kadence/' ], 'label' => 'Kadence Blocks', 'active' => defined( 'KADENCE_BLOCKS_VERSION' ) ],
			'spectra-legacy' => [ 'prefixes' => [ 'uagb/' ], 'label' => 'Spectra Legacy', 'active' => defined( 'UAGB_VER' ) ],
			'spectra-blocks' => [ 'prefixes' => [ 'spectra/', 'spectra-pro/' ], 'label' => 'Spectra Blocks', 'active' => defined( 'SPECTRA_BLOCKS_VER' ) ],
			'greenshift' => [ 'prefixes' => [ 'greenshift-blocks/' ], 'label' => 'Greenshift / GreenLight', 'active' => defined( 'GREENSHIFT_DIR_PATH' ) ],
			'etch' => [ 'prefixes' => [ 'etch/' ], 'label' => 'Etch', 'active' => defined( 'ETCH_PLUGIN_DIR' ) && \WP_Block_Type_Registry::get_instance()->is_registered( 'etch/element' ) ],
			'genesis-blocks' => [ 'prefixes' => [ 'genesis-blocks/' ], 'label' => 'Genesis Blocks', 'active' => function_exists( 'genesis_blocks_load' ) ],
			'generateblocks' => [ 'prefixes' => [ 'generateblocks/', 'generateblocks-pro/' ], 'label' => 'GenerateBlocks', 'active' => defined( 'GENERATEBLOCKS_VERSION' ) ],
			'stackable' => [ 'prefixes' => [ 'stackable/', 'ugb/' ], 'label' => 'Stackable', 'active' => defined( 'STACKABLE_VERSION' ) ],
			'otter' => [ 'prefixes' => [ 'themeisle-blocks/', 'atomic-wind/' ], 'label' => 'Otter Blocks', 'active' => defined( 'OTTER_BLOCKS_VERSION' ) ],
			'essential-blocks' => [ 'prefixes' => [ 'essential-blocks/' ], 'label' => 'Essential Blocks', 'active' => defined( 'ESSENTIAL_BLOCKS_VERSION' ) ],
			'gutenkit' => [ 'prefixes' => [ 'gutenkit/' ], 'label' => 'GutenKit', 'active' => defined( 'GUTENKIT_PLUGIN_VERSION' ) ],
			'kubio' => [ 'prefixes' => [ 'kubio/' ], 'label' => 'Kubio', 'active' => defined( 'KUBIO_VERSION' ) ],
		], LayoutAdapters::families() );
	}
	/** Discard request-local results after WordPress changes a post or referenced pattern. @return void */
	public static function reset(): void { self::$indexes = []; self::$rows = []; LayoutAdapters::reset(); }
	/** Resolve block-family types, or structural candidates for a shared scan. @param string $family Family ID; empty for internal scan eligibility. @return array Accessible content-type names. */
	public static function types( string $family = '' ): array {
		$result = [];
		foreach ( get_post_types( [ 'show_ui' => true ], 'objects' ) as $type ) {
			if ( ( '' === $family ? ContentTypes::accessible( $type->name ) : ContentTypes::allows( $type->name, $family, true ) ) ) { $result[] = $type->name; }
		}
		return $result;
	}
	/**
	 * Parse content and readable published synced patterns; never run block callbacks or shortcodes.
	 * @param string $content Stored block content.
	 * @param array $path Referenced pattern IDs on the current recursion path.
	 * @param int|null $remaining Shared remaining pattern traversals; defaults to 200 per content item.
	 * @return array Family IDs mapped to unique matched block names or extension signatures.
	 */
	public static function inspect( string $content, array $path = [], ?int &$remaining = null ): array {
		if ( null === $remaining ) { $remaining = 200; }
		$hits = []; $stack = parse_blocks( $content );
		foreach ( self::families() as $id => $family ) { $hits[ $id ] = []; }
		while ( $stack ) {
			$block = array_pop( $stack ); $name = $block['blockName'] ?? null;
			if ( is_string( $name ) ) {
				foreach ( self::families() as $id => $family ) {
					foreach ( $family['prefixes'] ?? [] as $prefix ) { if ( str_starts_with( $name, $prefix ) && 'kadence/page-wizard' !== $name ) { $hits[ $id ][ $name ] = true; } }
				}
				$attrs = $block['attrs'] ?? [];
				if ( 'siteorigin-panels/layout-block' === $name && LayoutAdapters::siteorigin_layout( $attrs['panelsData'] ?? null ) ) { $hits['siteorigin'][ $name ] = true; }
				if ( ! empty( $attrs['otterConditions'] ) && is_array( $attrs['otterConditions'] ) ) { $hits['otter'][ $name . ' [otterConditions]' ] = true; }
				if ( is_array( $attrs['kubio'] ?? null ) && is_string( $attrs['kubio']['id'] ?? null ) && '' !== trim( $attrs['kubio']['id'] ) && is_string( $attrs['kubio']['styleRef'] ?? null ) && '' !== trim( $attrs['kubio']['styleRef'] ) ) { $hits['kubio'][ $name . ' [kubio styles]' ] = true; }
				if ( str_starts_with( $name, 'core/' ) && is_string( $attrs['spectraId'] ?? null ) && '' !== trim( $attrs['spectraId'] ) && ! empty( $attrs['responsiveControls'] ) && is_array( $attrs['responsiveControls'] ) ) { $hits['spectra-blocks'][ $name . ' [spectraId + responsiveControls]' ] = true; }
				if ( 'core/image' === $name && is_array( $attrs['spectraMask'] ?? null ) && is_string( $attrs['spectraMask']['shape'] ?? null ) && '' !== trim( $attrs['spectraMask']['shape'] ) && 'none' !== $attrs['spectraMask']['shape'] ) { $hits['spectra-blocks'][ $name . ' [spectraMask]' ] = true; }
				if ( 'core/block' === $name && is_int( $attrs['ref'] ?? null ) && $attrs['ref'] > 0 && ! in_array( $attrs['ref'], $path, true ) && count( $path ) < 20 && $remaining > 0 ) {
					--$remaining;
					$pattern = get_post( $attrs['ref'] );
					if ( $pattern && 'wp_block' === $pattern->post_type && 'publish' === $pattern->post_status && current_user_can( 'read_post', $pattern->ID ) ) {
						foreach ( self::inspect( $pattern->post_content, array_merge( $path, [ $pattern->ID ] ), $remaining ) as $id => $names ) { foreach ( $names as $match ) { $hits[ $id ][ $match ] = true; } }
					}
				}
			}
			foreach ( $block['innerBlocks'] ?? [] as $inner ) { $stack[] = $inner; }
		}
		return array_filter( array_map( 'array_keys', $hits ) );
	}
	/**
	 * Reuse parsed row signatures across badges, traces and details without saving data.
	 * @param int $post_id Native list content ID.
	 * @return array Parsed family signatures for the current user and site.
	 */
	public static function for_post( int $post_id ): array {
		$key = get_current_blog_id() . ':' . get_current_user_id() . ':' . $post_id;
		if ( ! isset( self::$rows[ $key ] ) ) { $post = get_post( $post_id ); self::$rows[ $key ] = $post ? array_merge( self::inspect( $post->post_content ), LayoutAdapters::for_post( $post_id ) ) : []; }
		return self::$rows[ $key ];
	}

	/**
	 * Find candidate content in ID batches and verify each candidate with the native block parser.
	 * SQL LIKE only narrows candidates, never determines a match; final native queries retain status/author/search permissions.
	 * @param string $type Validated editable content-type name.
	 * @return array Post IDs mapped to parsed family signatures; cached only for this request/user/site.
	 */
	public static function index( string $type ): array {
		$key = get_current_blog_id() . ':' . get_current_user_id() . ':' . $type;
		if ( isset( self::$indexes[ $key ] ) ) { return self::$indexes[ $key ]; }
		if ( ! in_array( $type, self::types(), true ) ) { return self::$indexes[ $key ] = LayoutAdapters::index( $type ); }
		global $wpdb;
		$result = []; $after = 0; $needles = [];
		foreach ( self::families() as $family ) { foreach ( $family['prefixes'] ?? [] as $prefix ) { $needles[] = '%' . $wpdb->esc_like( 'wp:' . $prefix ) . '%'; } }
		$needles[] = '%' . $wpdb->esc_like( 'wp:block' ) . '%';
		$needles[] = '%' . $wpdb->esc_like( 'wp:siteorigin-panels/layout-block' ) . '%';
		$needles[] = '%' . $wpdb->esc_like( 'spectraId' ) . '%';
		$needles[] = '%' . $wpdb->esc_like( 'spectraMask' ) . '%';
		$needles[] = '%' . $wpdb->esc_like( 'otterConditions' ) . '%';
		$needles[] = '%' . $wpdb->esc_like( '"kubio"' ) . '%';
		$or = implode( ' OR ', array_fill( 0, count( $needles ), 'post_content LIKE %s' ) );
		do {
			// Only IDs and block content are required; never prime legacy builder metadata payloads.
			$sql = "SELECT ID, post_content FROM {$wpdb->posts} WHERE post_type = %s AND ID > %d AND ({$or}) ORDER BY ID ASC LIMIT 250";
			$rows = $wpdb->get_results( $wpdb->prepare( $sql, array_merge( [ $type, $after ], $needles ) ) );
			foreach ( $rows as $row ) { $after = (int) $row->ID; $hits = self::inspect( $row->post_content ); if ( $hits ) { $result[ $after ] = $hits; } }
		} while ( count( $rows ) === 250 );
		foreach ( LayoutAdapters::index( $type ) as $id => $hits ) { $result[ $id ] = array_merge( $result[ $id ] ?? [], $hits ); }
		return self::$indexes[ $key ] = $result;
	}
	/**
	 * Resolve verified matching IDs for a single family.
	 * @param string $type Validated content-type name.
	 * @param string $family Registered family ID.
	 * @return array Integer post IDs, independent of current list filters.
	 */
	public static function ids( string $type, string $family ): array {
		$ids = []; foreach ( self::index( $type ) as $id => $hits ) { if ( isset( $hits[ $family ] ) ) { $ids[] = (int) $id; } } return $ids;
	}
}
