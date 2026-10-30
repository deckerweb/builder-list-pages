<?php
/** Installed integrations and permission-aware shortcuts on the settings screen. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

/** Read local plugin/theme inventory without activating dependencies or contacting vendors. */
final class Overview {
	/**
	 * Map known distribution directories to shared recognition families and editions.
	 * Directory names identify products, never a license or entitlement.
	 * @return array Family IDs with plugin directories and theme directories.
	 */
	private static function catalog(): array {
		return [
			'elementor' => [ 'plugins' => [ 'elementor' => 'Free', 'elementor-pro' => 'Pro' ] ],
			'bricks' => [ 'themes' => [ 'bricks' ] ],
			'breakdance' => [ 'plugins' => [ 'breakdance' => '' ] ],
			'oxygen-classic' => [ 'plugins' => [ 'oxygen' => '' ] ],
			'oxygen' => [ 'plugins' => [ 'oxygen-builder' => '' ] ],
			'brizy' => [ 'plugins' => [ 'brizy' => 'Free', 'brizy-pro' => 'Pro' ] ],
			'beaver' => [ 'plugins' => [ 'beaver-builder-lite-version' => 'Free', 'bb-plugin' => 'Pro' ] ],
			'divi' => [ 'plugins' => [ 'divi-builder' => '' ], 'themes' => [ 'Divi', 'Extra' ] ],
			'pagelayer' => [ 'plugins' => [ 'pagelayer' => 'Free', 'pagelayer-pro' => 'Pro' ] ],
			'zionbuilder' => [ 'plugins' => [ 'zionbuilder' => 'Free', 'zionbuilder-pro' => 'Pro' ] ],
			'visualcomposer' => [ 'plugins' => [ 'visualcomposer' => '' ] ],
			'thrive-architect' => [ 'plugins' => [ 'thrive-visual-editor' => '' ], 'themes' => [ 'thrive-theme' ] ],
			'siteorigin' => [ 'plugins' => [ 'siteorigin-panels' => 'Free', 'siteorigin-premium' => 'Premium' ] ],
			'crocobuilder' => [ 'plugins' => [ 'crocobuilder' => '' ] ],
			'builderius' => [ 'plugins' => [ 'builderius' => 'Free', 'builderius-pro' => 'Pro' ] ],
			'mosaic' => [ 'plugins' => [ 'mosaic' => 'Pro' ] ],
			'kadence-blocks' => [ 'plugins' => [ 'kadence-blocks' => 'Free', 'kadence-blocks-pro' => 'Pro' ] ],
			'spectra-legacy' => [ 'plugins' => [ 'ultimate-addons-for-gutenberg' => 'Free', 'spectra-pro' => 'Pro' ] ],
			'spectra-blocks' => [ 'plugins' => [ 'spectra' => 'Free', 'spectra-blocks' => 'Free', 'spectra-blocks-pro' => 'Pro' ] ],
			'greenshift' => [ 'plugins' => [ 'greenshift-animation-and-page-builder-blocks' => '' ], 'addons' => [ 'greenshiftquery' => 'Query and Meta', 'greenshiftgsap' => 'Advanced Animation', 'greenshiftseo' => 'Marketing and SEO', 'greenshiftchart' => 'Chart', 'greenshiftwoo' => 'WooCommerce' ] ],
			'etch' => [ 'plugins' => [ 'etch' => '' ], 'themes' => [ 'etch-theme' ] ],
			'genesis-blocks' => [ 'plugins' => [ 'genesis-blocks' => 'Free' ] ],
			'generateblocks' => [ 'plugins' => [ 'generateblocks' => 'Free', 'generateblocks-pro' => 'Pro' ] ],
			'stackable' => [ 'plugins' => [ 'stackable-ultimate-gutenberg-blocks' => 'Free', 'stackable-ultimate-gutenberg-blocks-premium' => 'Premium' ] ],
			'otter' => [ 'plugins' => [ 'otter-blocks' => 'Free', 'otter-pro' => 'Pro' ] ],
			'essential-blocks' => [ 'plugins' => [ 'essential-blocks' => 'Free', 'essential-blocks-pro' => 'Pro' ] ],
			'gutenkit' => [ 'plugins' => [ 'gutenkit-blocks-addon' => 'Free', 'gutenkit-blocks-addon-pro' => 'Pro' ] ],
			'kubio' => [ 'plugins' => [ 'kubio' => 'Free', 'kubio-pro' => 'Pro' ] ],
		];
	}

	/**
	 * Resolve installed editions, runtime readiness and filtered content links for this user/site.
	 * Only this explicitly opened screen reads the inventory or calculates matching counts.
	 * @return array Alphabetically ordered integrations; inactive entries have no content links.
	 */
	public static function rows(): array {
		if ( ! current_user_can( 'manage_options' ) ) { return []; }
		if ( ! function_exists( 'get_plugins' ) ) { require_once ABSPATH . 'wp-admin/includes/plugin.php'; }
		$plugins = get_plugins();
		$themes = wp_get_themes();
		$registry = new Registry();
		$active = $registry->all();
		$labels = [];
		foreach ( $registry->known() as $builder ) { $labels[ $builder['builder-id'] ] = $builder['label']; }
		$rows = [];
		foreach ( self::catalog() as $id => $source ) {
			$editions = []; $addons = []; $installed_active = false;
			foreach ( $plugins as $file => $headers ) {
				$directory = dirname( $file );
				if ( isset( $source['addons'][ $directory ] ) ) {
					$label = $source['addons'][ $directory ];
					$addons[ $label ] = ( $addons[ $label ] ?? false ) || is_plugin_active( $file );
					$installed_active = $installed_active || $addons[ $label ];
					continue;
				}
				if ( ! array_key_exists( $directory, $source['plugins'] ?? [] ) ) { continue; }
				$enabled = is_plugin_active( $file );
				$edition = $source['plugins'][ $directory ];
				// Stackable also distributes a replacement premium build using the free directory.
				if ( 'stackable' === $id && false !== stripos( $headers['Name'], 'Premium' ) ) { $edition = 'Premium'; }
				$editions[ $edition ] = ( $editions[ $edition ] ?? false ) || $enabled;
				$installed_active = $installed_active || $enabled;
			}
			foreach ( $source['themes'] ?? [] as $directory ) {
				if ( ! isset( $themes[ $directory ] ) ) { continue; }
				$enabled = get_template() === $directory;
				$editions[''] = ( $editions[''] ?? false ) || $enabled;
				$installed_active = $installed_active || $enabled;
			}
			$ready = isset( $active[ $id ] );
			if ( ! $editions && ! $addons && ! $ready ) { continue; }
			$row = [ 'id' => $id, 'label' => $active[ $id ]['label'] ?? $labels[ $id ] ?? $id, 'ready' => $ready, 'active' => $installed_active || $ready, 'editions' => $editions, 'addons' => $addons, 'links' => [] ];
			foreach ( $active[ $id ]['builder-types'] ?? [] as $type ) {
				$object = get_post_type_object( $type );
				if ( $object && ! LayoutAdapters::internal_type( $type ) ) { $row['links'][] = [ 'type' => $type, 'label' => $object->labels->name, 'url' => Admin::list_url( $type, $id ) ]; }
			}
			$rows[] = $row;
		}
		// Count with the same scoped SQL conditions as the native list, then remove our temporary hook.
		$query = new Query( $registry );
		add_filter( 'posts_where', [ $query, 'exclude_builders' ], 20, 2 );
		try {
			foreach ( $rows as &$row ) {
				foreach ( $row['links'] as &$link ) { $link['count'] = $query->count( $link['type'], $row['id'] ); } unset( $link );
				usort( $row['links'], [ self::class, 'compare_links' ] );
			} unset( $row );
		} finally { remove_filter( 'posts_where', [ $query, 'exclude_builders' ], 20 ); }
		usort( $rows, static function ( $a, $b ) { return strnatcasecmp( $a['label'], $b['label'] ); } );
		return $rows;
	}
	/**
	 * Sort matching types first, with Pages and Posts leading each group.
	 * @param array $a First content-type link with its count.
	 * @param array $b Second content-type link with its count.
	 * @return int Natural alphabetical order after match group and Core-type preference.
	 */
	public static function compare_links( array $a, array $b ): int {
		$matches = (int) ( $b['count'] > 0 ) <=> (int) ( $a['count'] > 0 );
		$rank = static function ( $type ) { return 'page' === $type ? 0 : ( 'post' === $type ? 1 : 2 ); };
		return $matches ?: ( $rank( $a['type'] ) <=> $rank( $b['type'] ) ) ?: strnatcasecmp( $a['label'], $b['label'] );
	}

}
