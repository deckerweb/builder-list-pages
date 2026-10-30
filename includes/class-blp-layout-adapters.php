<?php
/** Read stored classic layouts without rendering or changing vendor content. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

/** Share stored classic layouts and explicitly owned template assignments. */
final class LayoutAdapters {
	private static array $indexes = [];
	/** Return families with their actual runtime prerequisites. @return array Family definitions. */
	public static function families(): array {
		return array_merge( [
			'siteorigin' => [ 'prefixes' => [], 'label' => 'SiteOrigin', 'active' => class_exists( 'SiteOrigin_Panels' ) ],
			'mosaic' => [ 'prefixes' => [], 'label' => 'Mosaic', 'active' => self::mosaic_ready() ],
		], DirectAdapters::families() );
	}
	/** Guard vendor APIs before autoloading them on older hosts or switched sites. @return bool Whether the current Mosaic theme is available. */
	private static function mosaic_ready(): bool {
		global $wp_version;
		return PHP_VERSION_ID >= 80200 && version_compare( (string) $wp_version, '6.9', '>=' )
			&& ! ( is_multisite() && ms_is_switched() )
			&& class_exists( '\Mosaic\MosaicThemeEntry', false )
			&& is_callable( [ '\Mosaic\MosaicThemeEntry', 'isCurrentThemeMosaic' ] )
			&& \Mosaic\MosaicThemeEntry::isCurrentThemeMosaic();
	}
	/** Exclude vendor libraries and internal template records from ordinary content integrations. @param string $type Content-type name. @return bool Whether it is a known internal library. */
	public static function internal_type( string $type ): bool {
		return ContentTypes::internal( $type );
	}
	/** List layout-family types, or structural candidates for a shared scan. @param string $family Family ID; empty for internal scan eligibility. @return array Accessible content-type names. */
	public static function types( string $family = '' ): array {
		$result = [];
		foreach ( get_post_types( [ 'show_ui' => true ], 'objects' ) as $type ) {
			if ( ( '' === $family ? ContentTypes::accessible( $type->name ) : ContentTypes::allows( $type->name, $family ) && DirectAdapters::allows_type( $type->name, $family ) ) ) { $result[] = $type->name; }
		}
		return $result;
	}
	/** Forget only request-local layout indexes. @return void */
	public static function reset(): void { self::$indexes = []; }
	/** Validate meaningful SiteOrigin rows or widgets, rather than an empty metadata key. @param mixed $data Stored layout data. @return bool Whether a structured nonempty layout exists. */
	public static function siteorigin_layout( $data ): bool {
		return is_array( $data ) && ( ( ! empty( $data['grids'] ) && is_array( $data['grids'] ) ) || ( ! empty( $data['widgets'] ) && is_array( $data['widgets'] ) ) );
	}
	/** Resolve stored signatures for one content item using the request-local index. @param int $post_id Native content ID. @return array Family IDs and non-sensitive recognition descriptions. */
	public static function for_post( int $post_id ): array {
		$type = get_post_type( $post_id );
		return is_string( $type ) ? ( self::index( $type )[ $post_id ] ?? [] ) : [];
	}
	/**
	 * Read candidate layouts once per site, user and type; native outer queries enforce visibility.
	 * Mosaic queries use its active schema and theme, never automatic assignment rules or rendering.
	 * @param string $type Validated editable content-type name.
	 * @return array Post IDs mapped to stored family signatures.
	 */
	public static function index( string $type ): array {
		$key = get_current_blog_id() . ':' . get_current_user_id() . ':' . $type;
		if ( isset( self::$indexes[ $key ] ) ) { return self::$indexes[ $key ]; }
		if ( ! in_array( $type, self::types(), true ) ) { return []; }
		global $wpdb;
		$result = [];
		$after = 0;
		do {
			$rows = $wpdb->get_results( $wpdb->prepare( "SELECT p.ID, m.meta_id, m.meta_value FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} m ON m.post_id = p.ID WHERE p.post_type = %s AND m.meta_key = %s AND m.meta_id > %d ORDER BY m.meta_id ASC LIMIT 250", $type, 'panels_data', $after ) );
			foreach ( $rows as $row ) {
				$after = (int) $row->meta_id;
				// Disable object creation when reading serialized vendor metadata.
				$value = is_serialized( $row->meta_value ) ? unserialize( trim( $row->meta_value ), [ 'allowed_classes' => false ] ) : null;
				if ( self::siteorigin_layout( $value ) ) { $result[ (int) $row->ID ]['siteorigin'] = [ 'panels_data' ]; }
			}
		} while ( count( $rows ) === 250 );
		if ( self::mosaic_ready() && is_callable( [ '\Mosaic\Database\Schema', 'schema' ] ) && is_callable( [ '\Mosaic\Admin\WordPress\PostEdit', 'getManualAssignedPostTypes' ] ) ) {
			try {
				$types = \Mosaic\Admin\WordPress\PostEdit::getManualAssignedPostTypes();
				if ( in_array( $type, $types, true ) ) {
					$theme = \Mosaic\MosaicThemeEntry::getTheme()->getEditorInstance()->getThemeMResource();
					$schema = \Mosaic\Database\Schema::schema();
					$assigns = $schema->getTable( 'mosaic_template_assigns' );
					$templates = $schema->getTable( 'mosaic_templates' );
					$mosaic_ids = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT p.ID FROM {$wpdb->posts} p INNER JOIN %i a ON a.typeIdentifier = CAST(p.ID AS CHAR) INNER JOIN %i t ON t.ID = a.parentID AND t.themeID = a.themeID WHERE p.post_type = %s AND a.themeID = %s AND a.type = %s AND a.parentType = %s AND a.parentID <> '' AND t.status = %s", $assigns, $templates, $type, $theme->getID(), 'post', 'template', 'publish' ) );
					foreach ( $mosaic_ids as $id ) { $result[ (int) $id ]['mosaic'] = [ 'direct layout assignment' ]; }
				}
			} catch ( \Throwable $exception ) {
				// A missing or incompatible vendor runtime must not break native WordPress lists.
			}
		}
		foreach ( DirectAdapters::index( $type ) as $id => $hits ) { $result[ $id ] = array_merge( $result[ $id ] ?? [], $hits ); }
		return self::$indexes[ $key ] = $result;
	}
}
