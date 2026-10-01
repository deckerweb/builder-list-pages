<?php
/**
 * Integrate the shared deckerweb updater with plugin-scoped safeguards.
 *
 * @package BuilderListPages
 */

namespace Deckerweb\BuilderListPages;

defined( 'ABSPATH' ) || exit;

/** Configure shared update artwork and isolate plugin-specific safeguards. */
final class GitHubUpdates {
	/** Public release repository; never taken from user input. */
	private const REPOSITORY = 'https://github.com/deckerweb/builder-list-pages';

	/** Register after WordPress initializes translations. @return void */
	public function register(): void {
		add_action( 'init', array( $this, 'boot' ) );
	}

	/** Load the shared versioned class once, including beside other deckerweb plugins. @return void */
	public function boot(): void {
		if ( ! class_exists( '\Deckerweb\GitHubReleaseUpdater\V2\Updater' ) ) {
			require_once BLP_PLUGIN_DIR . 'includes/deckerweb-github-release-updater-v2.php';
		}
		try {
			$updater = new \Deckerweb\GitHubReleaseUpdater\V2\Updater(
				BLP_PLUGIN_FILE,
				self::REPOSITORY,
				'Builder List Pages',
				__( 'Find pages by their page builder. Recognize builders at a glance, filter multiple builders and keep working in familiar WordPress lists.', 'builder-list-pages' ),
				$this->artwork()
			);
		} catch ( \InvalidArgumentException $error ) {
			// An unsupported directory must not break the admin or login design.
			return;
		}
		$updater->register();
		add_filter( 'http_request_args', array( $this, 'request_limits' ), 20, 2 );
		add_filter( 'upgrader_source_selection', array( $this, 'validate_source' ), 30, 4 );
	}

	/**
	 * Provide bundled artwork matching the current administrator's language.
	 *
	 * @return array<string,array<string,string>> WordPress icon and banner maps.
	 */
	public function artwork(): array {
		$language = 1 === preg_match( '/^de(?:_|$)/i', determine_locale() ) ? 'de-' : '';
		return array(
			'icons'   => array(
				'svg' => plugins_url( 'assets/icon.svg', BLP_PLUGIN_FILE ),
				'1x'  => plugins_url( 'assets/icon-128x128.png', BLP_PLUGIN_FILE ),
				'2x'  => plugins_url( 'assets/icon-256x256.png', BLP_PLUGIN_FILE ),
			),
			'banners' => array(
				'low'  => plugins_url( 'assets/banner-' . $language . '772x250.png', BLP_PLUGIN_FILE ),
				'high' => plugins_url( 'assets/banner-' . $language . '1544x500.png', BLP_PLUGIN_FILE ),
			),
		);
	}

	/**
	 * Bound only this repository's metadata requests; do not change package downloads.
	 *
	 * @param array  $args WordPress HTTP arguments.
	 * @param string $url Requested URL.
	 * @return array
	 */
	public function request_limits( array $args, string $url ): array {
		if ( 'https://api.github.com/repos/deckerweb/builder-list-pages/releases/latest' === $url ) {
			$args['limit_response_size'] = 512 * 1024;
			$args['timeout']             = 6;
			$args['redirection']         = 0;
			$args['sslverify']           = true;
			$args['reject_unsafe_urls']  = true;
		}
		return $args;
	}

	/**
	 * Validate the actual candidate before WordPress removes the installed plugin.
	 * Requirements in installed headers cannot describe a future release reliably.
	 *
	 * @param mixed $source Normalized directory or WP_Error from the shared updater.
	 * @param mixed $remote_source Unused extraction root.
	 * @param mixed $upgrader Unused WordPress upgrader instance.
	 * @param array $hook_extra Upgrade context.
	 * @return mixed Original source or localized WP_Error.
	 */
	public function validate_source( $source, $remote_source, $upgrader, array $hook_extra ) {
		if ( ( $hook_extra['plugin'] ?? '' ) !== plugin_basename( BLP_PLUGIN_FILE ) || ( $hook_extra['type'] ?? '' ) !== 'plugin' || ( $hook_extra['action'] ?? '' ) !== 'update' ) {
			return $source;
		}
		if ( is_wp_error( $source ) ) {
			$messages = array(
				'ddw_ghru_filesystem' => __( 'The update filesystem is unavailable. Please try again.', 'builder-list-pages' ),
				'ddw_ghru_archive'    => __( 'The GitHub package does not contain the Builder List Pages plugin file.', 'builder-list-pages' ),
				'ddw_ghru_rename'     => __( 'The GitHub package could not be prepared. The installed version has been kept.', 'builder-list-pages' ),
			);
			$code     = $source->get_error_code();
			return isset( $messages[ $code ] ) ? new \WP_Error( $code, $messages[ $code ], $source->get_error_data( $code ) ) : $source;
		}
		global $wp_filesystem;
		if ( ! is_string( $source ) || ! $wp_filesystem ) {
			return new \WP_Error( 'blp_update_source', __( 'The update package could not be checked.', 'builder-list-pages' ) );
		}
		$main = trailingslashit( $source ) . basename( BLP_PLUGIN_FILE );
		if ( ! $wp_filesystem->is_file( $main ) || $wp_filesystem->size( $main ) > 1024 * 1024 ) {
			return new \WP_Error( 'blp_update_source', __( 'The update package could not be checked.', 'builder-list-pages' ) );
		}
		$text = $wp_filesystem->get_contents( $main );
		if ( ! is_string( $text ) ) {
			return new \WP_Error( 'blp_update_source', __( 'The update package could not be checked.', 'builder-list-pages' ) );
		}
		$headers = array();
		foreach ( array( 'Plugin Name', 'Version', 'Update URI', 'Requires PHP', 'Requires at least' ) as $header ) {
			$headers[ $header ] = preg_match( '/^[ \t\/*#@]*' . preg_quote( $header, '/' ) . ':(.*)$/mi', str_replace( "\r", "\n", substr( $text, 0, 8192 ) ), $match ) ? trim( $match[1] ) : '';
		}
		if ( 'Builder List Pages' !== $headers['Plugin Name'] || self::REPOSITORY !== $headers['Update URI'] || ! preg_match( '/^\d+\.\d+\.\d+$/D', $headers['Version'] ) || version_compare( $headers['Version'], \BLP_VERSION, '<=' ) ) {
			return new \WP_Error( 'blp_update_identity', __( 'The package identity or version does not match a newer Builder List Pages release.', 'builder-list-pages' ) );
		}
		$current  = get_site_transient( 'update_plugins' );
		$expected = is_object( $current ) ? ( $current->response[ plugin_basename( BLP_PLUGIN_FILE ) ]->new_version ?? '' ) : '';
		if ( ! is_string( $expected ) || '' === $expected || $headers['Version'] !== $expected ) {
			return new \WP_Error( 'blp_update_version', __( 'The package version differs from the offered update. Please check for updates again.', 'builder-list-pages' ) );
		}
		foreach ( array( 'Requires PHP', 'Requires at least' ) as $header ) {
			if ( ! preg_match( '/^\d+\.\d+(?:\.\d+)?$/D', $headers[ $header ] ) ) {
				return new \WP_Error( 'blp_update_requirements', __( 'The update package has missing or invalid WordPress/PHP requirements.', 'builder-list-pages' ) );
			}
		}
		if ( ! is_php_version_compatible( $headers['Requires PHP'] ) || ! is_wp_version_compatible( $headers['Requires at least'] ) ) {
			return new \WP_Error( 'blp_update_compatibility', __( 'This release requires a newer WordPress or PHP version. The installed version has been kept.', 'builder-list-pages' ) );
		}
		return $source;
	}
}
