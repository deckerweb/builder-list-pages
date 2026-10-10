<?php
/** Small settings screen and localized, bundled release history. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

/**
 * Manage three site-level display choices and accessible bundled documentation.
 */
final class Settings {
	/**
	 * Return defaults for the three site-specific display choices.
	 *
	 * @return array Validated values or generated entries.
	 */
	public static function defaults(): array {
		return [ 'show_column' => true, 'show_submenus' => true, 'show_none' => true ];
	}
	/**
	 * Read a display choice with backward-compatible defaults.
	 *
	 * @param string $key Display-choice key.
	 * @return bool Whether the condition or display choice is enabled.
	 */
	public static function enabled( string $key ): bool {
		$value = get_option( 'blp_settings', [] );
		return (bool) ( ( is_array( $value ) ? $value : [] )[ $key ] ?? self::defaults()[ $key ] ?? false );
	}
	/**
	 * Normalize submitted settings to three Boolean values.
	 *
	 * @param mixed $raw Untrusted submitted settings.
	 * @return array Validated values or generated entries.
	 */
	public function sanitize( $raw ): array {
		$result = [];
		foreach ( self::defaults() as $key => $default ) { $result[ $key ] = is_array( $raw ) && isset( $raw[ $key ] ) && '1' === (string) ( is_scalar( $raw[ $key ] ) ? $raw[ $key ] : '' ); }
		return $result;
	}
	/**
	 * Register the settings, menu and scoped asset callbacks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_init', [ $this, 'register_options' ] );
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
	}
	/**
	 * Register the site option with the WordPress Settings API.
	 *
	 * @return void
	 */
	public function register_options(): void {
		register_setting( 'blp_settings', 'blp_settings', [ 'type' => 'array', 'sanitize_callback' => [ $this, 'sanitize' ], 'default' => self::defaults() ] );
	}
	/**
	 * Register the existing administrator settings screen.
	 *
	 * @return void
	 */
	public function menu(): void {
		add_options_page( 'Builder List Pages', 'Builder List Pages', 'manage_options', 'builder-list-pages', [ $this, 'render' ] );
	}
	/**
	 * Enqueue local styles and dialog behavior only on this settings screen.
	 *
	 * @param string $hook Current admin screen hook suffix.
	 * @return void
	 */
	public function assets( string $hook ): void {
		if ( 'settings_page_builder-list-pages' !== $hook ) { return; }
		wp_enqueue_style( 'blp-admin', plugins_url( 'assets/admin.css', BLP_PLUGIN_FILE ), [], BLP_VERSION );
		wp_enqueue_script( 'blp-documentation', plugins_url( 'assets/documentation.js', BLP_PLUGIN_FILE ), [], BLP_VERSION, true );
	}
	/**
	 * Determine whether the current administrator locale is German.
	 *
	 * @return bool Whether the condition or display choice is enabled.
	 */
	private function german(): bool { return 1 === preg_match( '/^de(?:_|$)/i', determine_locale() ); }
	/**
	 * Return the matching bundled changelog URL.
	 *
	 * @return string Resulting text, URL or SQL fragment.
	 */
	private function document_url(): string { return plugins_url( $this->german() ? 'docs/changelog-de.txt' : 'docs/changelog.txt', BLP_PLUGIN_FILE ); }
	/**
	 * Print the settings form and documentation only for permitted users.
	 *
	 * @return void
	 */
	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) { return; }
		$wiki = plugins_url( $this->german() ? 'docs/wiki/Deutsch.html' : 'docs/wiki/English.html', BLP_PLUGIN_FILE );
		echo '<div class="wrap blp-root"><header class="blp-header"><img src="' . esc_url( plugins_url( 'assets/icon.svg', BLP_PLUGIN_FILE ) ) . '" width="56" height="56" alt=""><div><h1>Builder List Pages</h1><p>' . esc_html__( 'Your pages. Your builders. One clear overview.', 'builder-list-pages' ) . '</p></div></header><hr class="wp-header-end">';
		echo '<section class="blp-card"><h2>' . esc_html__( 'Find your pages faster', 'builder-list-pages' ) . '</h2><p>' . esc_html__( 'Open Pages or another supported content list. Click a builder above the table to filter, or use its name in the Builder column.', 'builder-list-pages' ) . '</p><form method="post" action="options.php">';
		if ( ! ( new Registry() )->post_types() ) {
			echo '<p class="description">' . esc_html__( 'No supported builder is available for an editable content type on this site. Activate a supported builder and enable it for Pages or another content type.', 'builder-list-pages' ) . '</p>';
		}
		settings_fields( 'blp_settings' );
		$labels = [ 'show_column' => __( 'Show the Builder column in the Post Table (Overview)', 'builder-list-pages' ), 'show_submenus' => __( 'Show builder links in post-type admin menus', 'builder-list-pages' ), 'show_none' => __( 'Show the “No recognized builder” filter', 'builder-list-pages' ) ];
		foreach ( $labels as $key => $label ) {
			echo '<p><label><input type="checkbox" name="blp_settings[' . esc_attr( $key ) . ']" value="1" ' . checked( self::enabled( $key ), true, false ) . '> ' . esc_html( $label ) . '</label></p>';
		}
		echo '<p class="description">' . esc_html__( 'These choices apply to this site. Each user can also hide the Builder column using Screen Options on the content list.', 'builder-list-pages' ) . '</p>';
		submit_button();
		echo '</form></section><section class="blp-card"><h2>' . esc_html__( 'What does “No recognized builder” mean?', 'builder-list-pages' ) . '</h2><p>' . esc_html__( 'No matching data was found for the active, supported builders enabled for this content type. The page may still use the block editor, an unsupported or inactive builder, or a builder template.', 'builder-list-pages' ) . '</p><p>' . esc_html__( 'Counts include readable published and unpublished content outside the trash. Search and other list filters can reduce the visible results. Builder groups may overlap.', 'builder-list-pages' ) . '</p><p><a href="' . esc_url( $wiki ) . '">' . esc_html__( 'Read the documentation and FAQ', 'builder-list-pages' ) . '</a></p></section>';
		echo '<footer class="blp-footer" aria-label="' . esc_attr__( 'Plugin information', 'builder-list-pages' ) . '"><div class="blp-footer-identity"><div><strong>Builder List Pages</strong> <span>' . esc_html__( 'Version', 'builder-list-pages' ) . ' ' . esc_html( BLP_VERSION ) . '</span> · <a href="' . esc_url( $this->document_url() ) . '" data-blp-document="changelog">' . esc_html__( 'Changelog', 'builder-list-pages' ) . '</a> · <a href="' . esc_url( $wiki ) . '">' . esc_html__( 'Documentation', 'builder-list-pages' ) . '</a><p>' . esc_html__( 'Your pages. Your builders. One clear overview.', 'builder-list-pages' ) . '</p></div></div><div><span>© 2019–2026 <a href="https://github.com/deckerweb" target="_blank" rel="noopener noreferrer">David Decker – DECKERWEB</a></span><a href="https://github.com/deckerweb/builder-list-pages" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Plugin website', 'builder-list-pages' ) . '</a></div></footer>';
		$this->dialog();
		echo '</div>';
	}
	/**
	 * Render a bounded local history as escaped structured dialog content.
	 *
	 * @return void
	 */
	private function dialog(): void {
		$file = BLP_PLUGIN_DIR . ( $this->german() ? 'docs/changelog-de.txt' : 'docs/changelog.txt' );
		if ( ! is_readable( $file ) || filesize( $file ) > 262144 ) { return; }
		$text = file_get_contents( $file );
		if ( ! is_string( $text ) ) { return; }
		require_once BLP_PLUGIN_DIR . 'includes/deckerweb-changelog-v1.php';
		$content = \Deckerweb_Changelog_Renderer_V1::render( $text );
		echo '<dialog class="blp-document-dialog" id="blp-document-changelog" aria-labelledby="blp-changelog-title"><header><h2 id="blp-changelog-title">Builder List Pages · ' . esc_html__( 'Changelog', 'builder-list-pages' ) . '</h2><button class="button" type="button" data-blp-close autofocus>' . esc_html__( 'Close', 'builder-list-pages' ) . '</button></header><div class="blp-document-content" tabindex="0">';
		echo $content; // Every text node is escaped above; markup is fixed.
		echo '</div></dialog>';
	}
}
