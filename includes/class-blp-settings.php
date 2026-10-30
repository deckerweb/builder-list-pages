<?php
/** Small settings screen and localized, bundled release history. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

/**
 * Manage site-level display choices, content-type overrides and bundled documentation.
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
	 * Normalize display choices and explicit content-type selections without resetting absent fields.
	 *
	 * @param mixed $raw Untrusted submitted settings.
	 * @return array Validated values or generated entries.
	 */
	public function sanitize( $raw ): array {
		$result = [];
		foreach ( self::defaults() as $key => $default ) { $result[ $key ] = is_array( $raw ) && isset( $raw[ $key ] ) && '1' === (string) ( is_scalar( $raw[ $key ] ) ? $raw[ $key ] : '' ); }
		$submitted = is_array( $raw ) && ( isset( $raw['post_type_rules_present'] ) || array_key_exists( 'post_type_rules', $raw ) );
		$rules = $submitted ? ContentTypes::sanitize( $raw['post_type_rules'] ?? [], ! isset( $raw['post_type_rules_present'] ) ) : ContentTypes::rules();
		// WordPress may sanitize again during add_option(); preserve the normalized map, including an explicit empty map.
		if ( $submitted || $rules ) { $result['post_type_rules'] = $rules; }
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
	private function german(): bool { return 0 === strpos( $this->document_language(), 'Deutsch' ); }
	/** Resolve documents from the active admin language, including switched and formal locales. @return string Whitelisted local document suffix. */
	private function document_language(): string {
		$locale = is_locale_switched() ? get_locale() : determine_locale();
		return 'de_DE_formal' === $locale ? 'Deutsch-formal' : ( 1 === preg_match( '/^de(?:_|$)/i', $locale ) ? 'Deutsch' : 'English' );
	}
	/**
	 * Return the matching bundled changelog URL.
	 *
	 * @return string Resulting text, URL or SQL fragment.
	 */
	private function document_url(): string { return plugins_url( 'docs/wiki/Changelog-' . $this->document_language() . '.html', BLP_PLUGIN_FILE ); }
	/**
	 * Print the settings form and documentation only for permitted users.
	 *
	 * @return void
	 */
	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) { return; }
		$wiki = plugins_url( 'docs/wiki/' . $this->document_language() . '.html', BLP_PLUGIN_FILE );
		echo '<div class="wrap blp-root"><header class="blp-header"><img src="' . esc_url( plugins_url( 'assets/icon.svg', BLP_PLUGIN_FILE ) ) . '" width="56" height="56" alt=""><div><h1>Builder List Pages</h1><p>' . esc_html__( 'Your pages. Your builders. One clear overview.', 'builder-list-pages' ) . '</p></div></header><hr class="wp-header-end">';
		$this->overview();
		echo '<section class="blp-card"><h2>' . esc_html__( 'Find your pages faster', 'builder-list-pages' ) . '</h2><p>' . esc_html__( 'Open Pages or another supported content list. Click a builder above the table to filter, or use its name in the Builder column.', 'builder-list-pages' ) . '</p><form method="post" action="options.php">';
		if ( ! ( new Registry() )->post_types() ) {
			echo '<p class="description">' . esc_html__( 'No builder content lists are currently selected. Check the content-type choices below and the supported builder’s settings.', 'builder-list-pages' ) . '</p>';
		}
		settings_fields( 'blp_settings' );
		$labels = [ 'show_column' => __( 'Show the Builder column in the Post Table (Overview)', 'builder-list-pages' ), 'show_submenus' => __( 'Show builder links in post-type admin menus', 'builder-list-pages' ), 'show_none' => __( 'Show the “No recognized builder” filter', 'builder-list-pages' ) ];
		foreach ( $labels as $key => $label ) {
			echo '<p><label><input type="checkbox" name="blp_settings[' . esc_attr( $key ) . ']" value="1" ' . checked( self::enabled( $key ), true, false ) . '> ' . esc_html( $label ) . '</label></p>';
		}
		echo '<p class="description">' . esc_html__( 'These choices apply to this site. Each user can also hide the Builder column using Screen Options on the content list.', 'builder-list-pages' ) . '</p>';
		echo '<p class="description">' . esc_html__( 'Block libraries are recognized from saved blocks and readable published synced patterns, up to 20 reference levels and 200 pattern references per content item. A match means that the content uses those blocks; it does not mean that the entire page was built with that library. Global headers, templates and theme settings alone do not count.', 'builder-list-pages' ) . '</p>';
		$this->content_types();
		submit_button();
		echo '</form></section><section class="blp-card"><h2>' . esc_html__( 'What does “No recognized builder” mean?', 'builder-list-pages' ) . '</h2><p>' . esc_html__( 'No matching data was found for the active, supported builders enabled for this content type. The page may still use the block editor, an unsupported or inactive builder, or a builder template.', 'builder-list-pages' ) . '</p><p>' . esc_html__( 'Counts include readable published and unpublished content outside the trash. Search and other list filters can reduce the visible results. Builder groups may overlap.', 'builder-list-pages' ) . '</p><p><a href="' . esc_url( $wiki ) . '">' . esc_html__( 'Read the documentation and FAQ', 'builder-list-pages' ) . '</a></p></section>';
		$this->builder_explanation();
		echo '<footer class="blp-footer" aria-label="' . esc_attr__( 'Plugin information', 'builder-list-pages' ) . '"><div class="blp-footer-identity"><div><strong>Builder List Pages</strong> <span>' . esc_html__( 'Version', 'builder-list-pages' ) . ' ' . esc_html( BLP_VERSION ) . '</span> · <a href="' . esc_url( $this->document_url() ) . '" data-blp-document="changelog">' . esc_html__( 'Changelog', 'builder-list-pages' ) . '</a> · <a href="' . esc_url( $wiki ) . '">' . esc_html__( 'Documentation', 'builder-list-pages' ) . '</a><p>' . esc_html__( 'Your pages. Your builders. One clear overview.', 'builder-list-pages' ) . '</p></div></div><div><span>© 2019–2026 <a href="' . esc_url( $this->german() ? 'https://deckerwebplugins.com/de/' : 'https://deckerwebplugins.com/en/' ) . '" target="_blank" rel="noopener noreferrer">David Decker – DECKERWEB</a></span><a href="' . esc_url( $this->german() ? 'https://deckerwebplugins.com/de/plugins/builder-list-pages/' : 'https://deckerwebplugins.com/en/plugins/builder-list-pages/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Plugin website', 'builder-list-pages' ) . '</a></div></footer>';
		$this->dialog();
		echo '</div>';
	}
	/** Render optional site overrides with one labelled select per accessible registered type. @return void */
	private function content_types(): void {
		$rules = ContentTypes::rules();
		$types = [];
		foreach ( get_post_types( [ 'show_ui' => true ], 'objects' ) as $object ) {
			if ( ContentTypes::accessible( $object->name ) ) { $types[] = [ 'type' => $object->name, 'label' => $object->labels->name, 'count' => 0 ]; }
		}
		usort( $types, [ Overview::class, 'compare_links' ] );
		echo '<details class="blp-type-settings"><summary>' . esc_html__( 'Adjust content types', 'builder-list-pages' ) . '</summary><p>' . esc_html__( 'Custom content types cannot always be classified reliably. If a content type is missing or does not belong here, adjust the automatic selection.', 'builder-list-pages' ) . '</p><p class="description">' . esc_html__( 'These choices apply to the overview, Builder column, filters and submenus on this site. Include checks stored builder data; it does not mark all items as builder content or override permissions and native builder restrictions. Builder settings and content remain unchanged.', 'builder-list-pages' ) . '</p><input type="hidden" name="blp_settings[post_type_rules_present]" value="1"><div class="blp-type-choices">';
		foreach ( $types as $type ) {
			$id = 'blp-type-' . $type['type'];
			echo '<div class="blp-type-choice"><label for="' . esc_attr( $id ) . '">' . esc_html( $type['label'] ) . ' <code>' . esc_html( $type['type'] ) . '</code></label><select id="' . esc_attr( $id ) . '" name="blp_settings[post_type_rules][' . esc_attr( $type['type'] ) . ']">';
			foreach ( [ [ 'auto', __( 'Automatic', 'builder-list-pages' ) ], [ 'exclude', __( 'Hide', 'builder-list-pages' ) ], [ 'include', __( 'Include', 'builder-list-pages' ) ] ] as [ $mode, $label ] ) { echo '<option value="' . esc_attr( $mode ) . '" ' . selected( $rules[ $type['type'] ] ?? 'auto', $mode, false ) . '>' . esc_html( $label ) . '</option>'; }
			echo '</select></div>';
		}
		echo '</div></details>';
	}
	/** Explain classic builders and block libraries in plain language below the existing help. @return void */
	private function builder_explanation(): void {
		echo '<section class="blp-card"><h2>' . esc_html__( 'What does this plugin mean by a “builder”?', 'builder-list-pages' ) . '</h2><p>' . esc_html__( 'This plugin uses “builder” to mean tools for designing content and layouts in WordPress. This includes classic page builders such as Bricks, Elementor and Oxygen, as well as extensions for the WordPress block editor, such as Kadence Blocks and Spectra.', 'builder-list-pages' ) . '</p><p>' . esc_html__( 'For block extensions, using individual blocks or supported design features is enough for a match. The entire page does not have to be built with that tool. A content item can therefore show more than one builder.', 'builder-list-pages' ) . '</p><p>' . esc_html__( 'An active theme or a global template alone does not mean that the individual content item was edited with that builder.', 'builder-list-pages' ) . '</p></section>';
	}

	/** Print installed integrations with textual statuses and ordinary accessible filter links. @return void */
	private function overview(): void {
		$rows = Overview::rows();
		echo '<section class="blp-card" aria-labelledby="blp-integrations-title"><h2 id="blp-integrations-title">' . esc_html__( 'Your builders and integrations', 'builder-list-pages' ) . '</h2><p>' . esc_html__( 'See which supported tools are installed on this site and open their recognized content. Active describes the tool, not every page on the site.', 'builder-list-pages' ) . '</p>';
		if ( ! $rows ) { echo '<p>' . esc_html__( 'No supported integration is installed or available on this site.', 'builder-list-pages' ) . '</p>'; }
		if ( $rows ) { echo '<ul class="blp-integrations">'; }
		foreach ( $rows as $row ) {
			$status = $row['ready'] ? __( 'Active', 'builder-list-pages' ) : ( $row['active'] ? __( 'Active — recognition unavailable', 'builder-list-pages' ) : __( 'Installed, inactive', 'builder-list-pages' ) );
			echo '<li class="blp-integration"><div class="blp-integration-name"><h3>' . esc_html( $row['label'] ) . '</h3><span class="blp-integration-status' . ( $row['ready'] ? ' blp-integration-active' : '' ) . '">' . esc_html( $status ) . '</span>';
			$editions = [];
			foreach ( $row['editions'] as $edition => $active ) { if ( '' !== $edition ) { $editions[] = $edition . ( $active ? '' : ' (' . __( 'inactive', 'builder-list-pages' ) . ')' ); } }
			if ( $editions ) { echo '<span class="blp-integration-editions">' . esc_html( implode( ' + ', $editions ) ) . '</span>'; }
			if ( $row['addons'] ) {
				echo '<ul class="blp-integration-addons" aria-label="' . esc_attr__( 'Installed addons', 'builder-list-pages' ) . '">';
				foreach ( $row['addons'] as $addon => $active ) { echo '<li>' . esc_html( $addon . ': ' . ( $active ? __( 'Active', 'builder-list-pages' ) : __( 'inactive', 'builder-list-pages' ) ) ) . '</li>'; }
				echo '</ul>';
			}
			echo '</div><div class="blp-integration-content">';
			foreach ( $row['links'] as $link ) {
				/* translators: 1: content-type label, 2: number of matching readable items. */
				$label = sprintf( __( '%1$s (%2$s)', 'builder-list-pages' ), $link['label'], number_format_i18n( $link['count'] ) );
				$class = $link['count'] > 0 ? ' blp-content-match blp-content-' . ( in_array( $link['type'], [ 'page', 'post' ], true ) ? $link['type'] : 'custom' ) : '';
				echo '<a class="button' . esc_attr( $class ) . '" href="' . esc_url( $link['url'] ) . '" aria-label="' . esc_attr( $row['label'] . ': ' . $label ) . '">' . esc_html( $label ) . '</a>';
			}
			if ( $row['links'] && 0 === array_sum( array_column( $row['links'], 'count' ) ) ) { echo '<p class="description">' . esc_html__( 'No matching content yet.', 'builder-list-pages' ) . '</p>'; }
			if ( ! $row['links'] ) { echo '<p class="description">' . esc_html( $row['ready'] ? __( 'No selected, editable content type is available. Check the content-type choices below and the builder’s settings.', 'builder-list-pages' ) : ( $row['active'] ? __( 'Recognition is paused. Check the integration’s dependencies and requirements.', 'builder-list-pages' ) : __( 'Content links become available when this integration is active.', 'builder-list-pages' ) ) ) . '</p>'; }
			echo '</div></li>';
		}
		if ( $rows ) { echo '</ul>'; }
		echo '<p class="description">' . esc_html__( 'Counts include readable published and unpublished content outside the trash. Search and other list filters can reduce the visible results. Builder groups may overlap.', 'builder-list-pages' ) . '</p><p><a href="' . esc_url( plugins_url( 'docs/wiki/Builders-' . ( $this->german() ? 'Deutsch' : 'English' ) . '.html', BLP_PLUGIN_FILE ) ) . '">' . esc_html__( 'All supported integrations', 'builder-list-pages' ) . '</a></p></section>';
	}

	/**
	 * Render a bounded local history as escaped structured dialog content.
	 *
	 * @return void
	 */
	private function dialog(): void {
		$file = BLP_PLUGIN_DIR . ( 'Deutsch-formal' === $this->document_language() ? 'docs/changelog-de-formal.txt' : ( $this->german() ? 'docs/changelog-de.txt' : 'docs/changelog.txt' ) );
		if ( ! is_readable( $file ) || filesize( $file ) > 262144 ) { return; }
		$text = file_get_contents( $file );
		if ( ! is_string( $text ) ) { return; }
		require_once BLP_PLUGIN_DIR . 'includes/deckerweb-changelog-v1.php';
		$content = \Deckerweb_Changelog_Renderer_V1::render( $text );
		echo '<dialog hidden class="blp-document-dialog" id="blp-document-changelog" aria-labelledby="blp-changelog-title"><header><h2 id="blp-changelog-title">Builder List Pages · ' . esc_html__( 'Changelog', 'builder-list-pages' ) . '</h2><form method="dialog"><button class="button" data-blp-close autofocus>' . esc_html__( 'Close', 'builder-list-pages' ) . '</button></form></header><div class="blp-document-content" tabindex="0">';
		echo wp_kses( $content, [ 'section' => [ 'class' => true ], 'header' => [ 'class' => true ], 'h3' => [], 'span' => [ 'class' => true ], 'ul' => [ 'class' => true ], 'li' => [], 'code' => [] ] );
		echo '</div></dialog>';
	}
}
