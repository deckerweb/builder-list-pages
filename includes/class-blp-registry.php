<?php
/** Builder definitions and defensive, lazy settings resolution. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

final class Registry {
	private ?array $resolved = null;

	/** Read a list option or one nested setting without assuming its format. */
	private function option_types( string $option, ?string $key = null, array $default = [] ): array {
		$value = get_option( $option, $default );
		if ( null !== $key ) {
			$value = is_array( $value ) ? ( $value[ $key ] ?? $default ) : $default;
		}
		return is_array( $value ) ? $value : $default;
	}

	private function breakdance_types(): array {
		if ( ! function_exists( '\Breakdance\Settings\get_allowed_post_types' ) ) {
			return [];
		}
		$value = \Breakdance\Settings\get_allowed_post_types( false );
		return is_array( $value ) ? $value : [];
	}

	private function oxygen_classic_types(): array {
		global $ct_ignore_post_types;
		return array_values( array_diff( get_post_types(), is_array( $ct_ignore_post_types ) ? $ct_ignore_post_types : [], [ 'ct_template', 'oxy_user_library' ] ) );
	}

	private function zion_types(): array {
		$value = get_option( '_zionbuilder_options', [] );
		if ( is_string( $value ) ) {
			$decoded = json_decode( $value, true );
			$value = is_array( $decoded ) ? $decoded : json_decode( wp_unslash( $value ), true );
		}
		return is_array( $value ) && is_array( $value['allowed_post_types'] ?? null ) ? $value['allowed_post_types'] : [];
	}

	/** IDs and legacy filter fields remain compatible with version 1.0.0. */
	private function definitions(): array {
		$definitions = [
			'elementor' => [ 'elementor', defined( 'ELEMENTOR_VERSION' ), fn() => $this->option_types( 'elementor_cpt_support' ), '_elementor_edit_mode', 'builder', 'Elementor', 'With Elementor' ],
			'bricks' => [ 'bricks', defined( 'BRICKS_VERSION' ), fn() => $this->option_types( 'bricks_global_settings', 'postTypes' ), '_bricks_editor_mode', 'bricks', 'Bricks', 'With Bricks' ],
			'breakdance' => [ 'breakdance', defined( '__BREAKDANCE_VERSION' ) && ! defined( 'BREAKDANCE_MODE' ), fn() => $this->breakdance_types(), '_breakdance_data', null, 'Breakdance', 'With Breakdance' ],
			'oxygen-classic' => [ 'oxygen-classic', defined( 'CT_VERSION' ), fn() => $this->oxygen_classic_types(), '_ct_builder_json', null, 'Oxygen Classic', 'With Oxygen Classic' ],
			'oxygen-builder' => [ 'oxygen', defined( 'BREAKDANCE_MODE' ) && 'oxygen' === BREAKDANCE_MODE, fn() => $this->breakdance_types(), '_oxygen_data', null, 'Oxygen', 'With Oxygen' ],
			'brizy' => [ 'brizy', defined( 'BRIZY_VERSION' ), fn() => $this->option_types( 'brizy', 'post-types' ), 'brizy_enabled', '1', 'Brizy', 'With Brizy' ],
			'beaver-builder' => [ 'beaver', class_exists( 'FLBuilderLoader' ), fn() => $this->option_types( '_fl_builder_post_types' ), '_fl_builder_enabled', '1', 'Beaver Builder', 'With Beaver' ],
			'pagelayer' => [ 'pagelayer', defined( 'PAGELAYER_VERSION' ), fn() => $this->option_types( 'pl_support_ept', null, [ 'post', 'page' ] ), 'pagelayer-data', null, 'Pagelayer', 'With Pagelayer' ],
			'zionbuilder' => [ 'zionbuilder', function_exists( '\ZionBuilder\zionbuilder_load_textdomain' ), fn() => $this->zion_types(), '_zionbuilder_page_status', 'enabled', 'ZionBuilder', 'With ZionBuilder' ],
			'visual-composer' => [ 'visualcomposer', defined( 'VCV_VERSION' ), fn() => [ 'page', 'post' ], 'vcv-be-editor', 'fe', 'Visual Composer', 'With Visual Composer' ],
			'thrive-architect' => [ 'thrive-architect', defined( 'TVE_EDITOR_URL' ), fn() => get_post_types( [ 'public' => true ] ), 'tcb_editor_enabled', '1', 'Thrive Architect', 'With Thrive Architect' ],
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
		return $data;
	}

	/** Resolve only in admin hooks, after themes and post types have initialized. */
	public function all(): array {
		if ( null !== $this->resolved ) {
			return $this->resolved;
		}
		$raw = apply_filters( 'blp/filter/builder-data', $this->definitions() );
		$this->resolved = [];
		foreach ( is_array( $raw ) ? $raw : [] as $builder ) {
			if ( ! is_array( $builder ) || empty( $builder['is-active'] ) || ! is_string( $builder['builder-id'] ?? null ) || ! is_string( $builder['meta-key'] ?? null ) || ! is_array( $builder['builder-types'] ?? null ) ) {
				continue;
			}
			$id = sanitize_key( $builder['builder-id'] );
			if ( '' === $id || 'none' === $id || '' === $builder['meta-key'] || isset( $this->resolved[ $id ] ) ) {
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

	public function for_type( string $type ): array {
		return array_filter( $this->all(), static fn( array $builder ): bool => in_array( $type, $builder['builder-types'], true ) );
	}

	public function post_types(): array {
		$types = [];
		foreach ( $this->all() as $builder ) { $types = array_merge( $types, $builder['builder-types'] ); }
		return array_values( array_unique( $types ) );
	}

	/** Labels describe detected metadata, not rendering or template assignment. */
	public function matches( int $post_id, string $type ): array {
		$result = [];
		foreach ( $this->for_type( $type ) as $id => $builder ) {
			$clause = $builder['meta_query'][0];
			$values = get_post_meta( $post_id, $clause['key'], false );
			$values = array_map( static fn( $value ): string => is_scalar( $value ) ? (string) $value : '', $values );
			$matches = 'EXISTS' === $clause['compare'] ? metadata_exists( 'post', $post_id, $clause['key'] ) : in_array( (string) $clause['value'], $values, true );
			if ( $matches ) { $result[ $id ] = $builder; }
		}
		return $result;
	}
}
