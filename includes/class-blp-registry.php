<?php
/** Builder definitions and defensive, lazy settings resolution. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

/**
 * Resolve supported builders and their metadata without changing builder data.
 */
final class Registry {
	private ?array $resolved = null;
	private ?array $definitions_cache = null;
	private array $trace_cache = [];

	/**
	 * Read a list option or nested setting defensively.
	 *
	 * @param string $option WordPress option name.
	 * @param string|null $key Nested setting key or display-choice key.
	 * @param array $default Fallback list for absent or malformed data.
	 * @return array Validated values or generated entries.
	 */
	private function option_types( string $option, ?string $key = null, array $default = [] ): array {
		$value = get_option( $option, $default );
		if ( null !== $key ) {
			$value = is_array( $value ) ? ( $value[ $key ] ?? $default ) : $default;
		}
		return is_array( $value ) ? $value : $default;
	}

	/**
	 * Read content types from the active Breakdance-compatible builder.
	 *
	 * @return array Validated values or generated entries.
	 */
	private function breakdance_types(): array {
		if ( ! function_exists( '\Breakdance\Settings\get_allowed_post_types' ) ) {
			return [];
		}
		$value = \Breakdance\Settings\get_allowed_post_types( false );
		return is_array( $value ) ? $value : [];
	}

	/**
	 * Exclude Oxygen Classic templates and disabled types.
	 *
	 * @return array Validated values or generated entries.
	 */
	private function oxygen_classic_types(): array {
		global $ct_ignore_post_types;
		return array_values( array_diff( get_post_types(), is_array( $ct_ignore_post_types ) ? $ct_ignore_post_types : [], [ 'ct_template', 'oxy_user_library' ] ) );
	}

	/**
	 * Read Zion content types from array or encoded legacy settings.
	 *
	 * @return array Validated values or generated entries.
	 */
	private function zion_types(): array {
		$value = get_option( '_zionbuilder_options', [] );
		if ( is_string( $value ) ) {
			$decoded = json_decode( $value, true );
			$value = is_array( $decoded ) ? $decoded : json_decode( wp_unslash( $value ), true );
		}
		return is_array( $value ) && is_array( $value['allowed_post_types'] ?? null ) ? $value['allowed_post_types'] : [];
	}

	/**
	 * Resolve the enabled content types for this builder after initialization.
	 *
	 * @return array Content-type names; inactive builders never invoke this resolver.
	 */
	private function types_elementor(): array { return $this->option_types( 'elementor_cpt_support' ); }

	/**
	 * Resolve the enabled content types for this builder after initialization.
	 *
	 * @return array Content-type names; inactive builders never invoke this resolver.
	 */
	private function types_bricks(): array { return $this->option_types( 'bricks_global_settings', 'postTypes' ); }

	/**
	 * Resolve the enabled content types for this builder after initialization.
	 *
	 * @return array Content-type names; inactive builders never invoke this resolver.
	 */
	private function types_brizy(): array { return $this->option_types( 'brizy', 'post-types' ); }

	/**
	 * Resolve the enabled content types for this builder after initialization.
	 *
	 * @return array Content-type names; inactive builders never invoke this resolver.
	 */
	private function types_beaver(): array { return $this->option_types( '_fl_builder_post_types' ); }

	/**
	 * Resolve the enabled content types for this builder after initialization.
	 *
	 * @return array Content-type names; inactive builders never invoke this resolver.
	 */
	private function types_pagelayer(): array { return $this->option_types( 'pl_support_ept', null, [ 'post', 'page' ] ); }

	/**
	 * Resolve the enabled content types for this builder after initialization.
	 *
	 * @return array Content-type names; inactive builders never invoke this resolver.
	 */
	private function types_visual(): array { return [ 'page', 'post' ]; }

	/**
	 * Resolve the enabled content types for this builder after initialization.
	 *
	 * @return array Content-type names; inactive builders never invoke this resolver.
	 */
	private function types_thrive(): array { return get_post_types( [ 'public' => true ] ); }

	/**
	 * Build legacy-compatible definitions without resolving inactive builders.
	 *
	 * @return array Validated values or generated entries.
	 */
	private function definitions(): array {
		if ( null !== $this->definitions_cache ) { return $this->definitions_cache; }
		$definitions = [
			'elementor' => [ 'elementor', defined( 'ELEMENTOR_VERSION' ), [ $this, 'types_elementor' ], '_elementor_edit_mode', 'builder', 'Elementor', 'With Elementor' ],
			'bricks' => [ 'bricks', defined( 'BRICKS_VERSION' ), [ $this, 'types_bricks' ], '_bricks_editor_mode', 'bricks', 'Bricks', 'With Bricks' ],
			'breakdance' => [ 'breakdance', defined( '__BREAKDANCE_VERSION' ) && ! defined( 'BREAKDANCE_MODE' ), [ $this, 'breakdance_types' ], '_breakdance_data', null, 'Breakdance', 'With Breakdance' ],
			'oxygen-classic' => [ 'oxygen-classic', defined( 'CT_VERSION' ), [ $this, 'oxygen_classic_types' ], '_ct_builder_json', null, 'Oxygen Classic', 'With Oxygen Classic' ],
			'oxygen-builder' => [ 'oxygen', defined( 'BREAKDANCE_MODE' ) && 'oxygen' === BREAKDANCE_MODE, [ $this, 'breakdance_types' ], '_oxygen_data', null, 'Oxygen', 'With Oxygen' ],
			'brizy' => [ 'brizy', defined( 'BRIZY_VERSION' ), [ $this, 'types_brizy' ], 'brizy_enabled', '1', 'Brizy', 'With Brizy' ],
			'beaver-builder' => [ 'beaver', class_exists( 'FLBuilderLoader' ), [ $this, 'types_beaver' ], '_fl_builder_enabled', '1', 'Beaver Builder', 'With Beaver' ],
			'pagelayer' => [ 'pagelayer', defined( 'PAGELAYER_VERSION' ), [ $this, 'types_pagelayer' ], 'pagelayer-data', null, 'Pagelayer', 'With Pagelayer' ],
			'zionbuilder' => [ 'zionbuilder', function_exists( '\ZionBuilder\zionbuilder_load_textdomain' ), [ $this, 'zion_types' ], '_zionbuilder_page_status', 'enabled', 'ZionBuilder', 'With ZionBuilder' ],
			'visual-composer' => [ 'visualcomposer', defined( 'VCV_VERSION' ), [ $this, 'types_visual' ], 'vcv-be-editor', 'fe', 'Visual Composer', 'With Visual Composer' ],
			'thrive-architect' => [ 'thrive-architect', defined( 'TVE_EDITOR_URL' ), [ $this, 'types_thrive' ], 'tcb_editor_enabled', '1', 'Thrive Architect', 'With Thrive Architect' ],
		];
		$data = [];
		foreach ( $definitions as $key => $definition ) {
			[ $id, $active, $resolver, $meta_key, $value, $label, $with ] = $definition;
			$data[ $key ] = [
				'builder-id' => $id, 'is-active' => $active,
				'builder-types' => $active ? $resolver() : [],
				'meta-key' => $meta_key, 'meta-value' => $value ?? 0,
				'meta-compare' => null === $value ? 'EXISTS' : '=',
				'label' => $label, 'with-label' => $with,
			];
		}
		return $this->definitions_cache = $data;
	}

	/**
	 * Validate and cache builder definitions for the current user.
	 *
	 * @return array Validated values or generated entries.
	 */
	public function all(): array {
		if ( null !== $this->resolved ) {
			return $this->resolved;
		}
		/**
		 * Modify builder definitions before validation and permission checks.
		 *
		 * Return the definition array with the desired builder entries. Invalid entries are ignored.
		 * @since 1.0.0
		 * @param array $builders Builder definitions with IDs, activation, types and metadata conditions.
		 */
		$raw = apply_filters( 'blp/filter/builder-data', $this->definitions() );
		$this->resolved = [];
		foreach ( is_array( $raw ) ? $raw : [] as $builder ) {
			if ( ! is_array( $builder ) || empty( $builder['is-active'] ) || ! is_string( $builder['builder-id'] ?? null ) || ! is_string( $builder['meta-key'] ?? null ) || ! is_array( $builder['builder-types'] ?? null ) ) {
				continue;
			}
			$id = sanitize_key( $builder['builder-id'] );
			if ( '' === $id || in_array( $id, [ 'none', 'multiple', 'traces' ], true ) || '' === $builder['meta-key'] || isset( $this->resolved[ $id ] ) ) {
				continue;
			}
			$types = [];
			foreach ( $builder['builder-types'] as $type ) {
				if ( ! is_string( $type ) ) { continue; }
				$object = get_post_type_object( $type );
				if ( $object && $object->show_ui && 'attachment' !== $type && current_user_can( $object->cap->edit_posts ) ) {
					$types[] = $type;
				}
			}
			$builder['builder-id'] = $id;
			$builder['builder-types'] = array_values( array_unique( $types ) );
			$builder['label'] = is_string( $builder['label'] ?? null ) ? $builder['label'] : $id;
			$builder['with-label'] = is_string( $builder['with-label'] ?? null ) ? $builder['with-label'] : $builder['label'];
			// Legacy extensions may omit the comparator: preserve known EXISTS builders.
			$compare = $builder['meta-compare'] ?? ( in_array( $id, [ 'breakdance', 'oxygen', 'oxygen-classic', 'pagelayer' ], true ) ? 'EXISTS' : '=' );
			$compare = in_array( $compare, [ '=', 'EXISTS' ], true ) ? $compare : '=';
			$value = $builder['meta-value'] ?? '';
			if ( '=' === $compare && ! is_scalar( $value ) ) { continue; }
			$builder['meta_query'] = [ [ 'key' => $builder['meta-key'], 'compare' => $compare ] ];
			if ( '=' === $compare ) { $builder['meta_query'][0]['value'] = (string) $value; }
			$this->resolved[ $id ] = $builder;
		}
		return $this->resolved;
	}

	/**
	 * Select active builders the current user may access for a content type.
	 *
	 * @param string $type Content-type name.
	 * @return array Validated values or generated entries.
	 */
	public function for_type( string $type ): array {
		$result = [];
		foreach ( $this->all() as $id => $builder ) {
			if ( in_array( $type, $builder['builder-types'], true ) ) { $result[ $id ] = $builder; }
		}
		return $result;
	}

	/**
	 * Return unique accessible supported content types.
	 *
	 * @return array Validated values or generated entries.
	 */
	public function post_types(): array {
		$types = [];
		foreach ( $this->all() as $builder ) { $types = array_merge( $types, $builder['builder-types'] ); }
		return array_values( array_unique( $types ) );
	}

	/**
	 * Return known inactive metadata signatures without invoking inactive APIs.
	 *
	 * @param string $type Content-type name.
	 * @return array Read-only signatures, restricted to editable admin content types.
	 */
	public function traces( string $type ): array {
		$object = get_post_type_object( $type );
		if ( ! $object || ! $object->show_ui || 'attachment' === $type || ! current_user_can( $object->cap->edit_posts ) ) { return []; }
		if ( isset( $this->trace_cache[ $type ] ) ) { return $this->trace_cache[ $type ]; }
		$result = [];
		foreach ( $this->definitions() as $builder ) {
			if ( $builder['is-active'] ) { continue; }
			$builder['meta_query'] = [ [ 'key' => $builder['meta-key'], 'compare' => $builder['meta-compare'] ] ];
			if ( '=' === $builder['meta-compare'] ) { $builder['meta_query'][0]['value'] = (string) $builder['meta-value']; }
			$result[ $builder['builder-id'] ] = $builder;
		}
		return $this->trace_cache[ $type ] = $result;
	}

	/**
	 * Identify inactive signatures in already primed metadata.
	 *
	 * @param int $post_id Content item ID.
	 * @param string $type Content-type name.
	 * @return array Matching inactive signatures; no builder data is changed.
	 */
	public function trace_matches( int $post_id, string $type ): array {
		$result = [];
		foreach ( $this->traces( $type ) as $id => $builder ) {
			$c = $builder['meta_query'][0];
			$values = get_post_meta( $post_id, $c['key'], false );
			if ( 'EXISTS' === $c['compare'] ? metadata_exists( 'post', $post_id, $c['key'] ) : in_array( $c['value'], array_map( 'strval', array_filter( $values, 'is_scalar' ) ), true ) ) { $result[ $id ] = $builder; }
		}
		return $result;
	}

	/**
	 * Resolve CSV signatures in bounded ID batches without loading builder payloads.
	 *
	 * @param array $ids Native query results restricted to readable and editable content.
	 * @param string $type Content-type name.
	 * @return array Builder label lists keyed by post ID, including marked inactive traces.
	 */
	public function export_labels( array $ids, string $type ): array {
		$ids = array_values( array_filter( array_map( 'absint', $ids ) ) );
		$result = [];
		if ( ! $ids ) { return $result; }
		global $wpdb;
		$builders = $this->for_type( $type );
		foreach ( $this->traces( $type ) as $id => $b ) { $b['label'] .= ' (' . __( 'inactive trace', 'builder-list-pages' ) . ')'; $builders[ $id ] = $b; }
		foreach ( array_chunk( $ids, 500 ) as $batch ) {
			$placeholders = implode( ',', array_fill( 0, count( $batch ), '%d' ) );
			foreach ( $builders as $builder ) {
				$c = $builder['meta_query'][0];
				$sql = "SELECT DISTINCT post_id FROM {$wpdb->postmeta} WHERE post_id IN ({$placeholders}) AND meta_key = %s";
				$values = array_merge( $batch, [ $c['key'] ] );
				if ( '=' === $c['compare'] ) { $sql .= ' AND meta_value = %s'; $values[] = $c['value']; }
				foreach ( $wpdb->get_col( $wpdb->prepare( $sql, $values ) ) as $post_id ) { $result[ (int) $post_id ][] = $builder['label']; }
			}
		}
		return $result;
	}

	/**
	 * Detect metadata matches; this does not predict frontend rendering.
	 *
	 * @param int $post_id Content item ID.
	 * @param string $type Content-type name.
	 * @return array Validated values or generated entries.
	 */
	public function matches( int $post_id, string $type ): array {
		$result = [];
		foreach ( $this->for_type( $type ) as $id => $builder ) {
			$clause = $builder['meta_query'][0];
			$values = get_post_meta( $post_id, $clause['key'], false );
			foreach ( $values as &$value ) { $value = is_scalar( $value ) ? (string) $value : ''; }
			unset( $value );
			$matches = 'EXISTS' === $clause['compare'] ? metadata_exists( 'post', $post_id, $clause['key'] ) : in_array( (string) $clause['value'], $values, true );
			if ( $matches ) { $result[ $id ] = $builder; }
		}
		return $result;
	}
}
