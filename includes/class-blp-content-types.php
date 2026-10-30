<?php
/** Shared, site-specific content-type selection for every builder entry point. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

/** Separate editorial content from system records, with explicit administrator overrides. */
final class ContentTypes {
	/**
	 * Identify system and internal builder records that are never ordinary content lists.
	 * @param string $type Registered content-type slug.
	 * @return bool Whether including this type is unsupported even with an override.
	 */
	public static function internal( string $type ): bool {
		return in_array( $type, [ 'attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block', 'wp_template', 'wp_template_part', 'wp_global_styles', 'wp_navigation', 'wp_font_family', 'wp_font_face', 'ct_template', 'oxy_user_library', 'fl-builder-template', 'fl-theme-layout', 'et_pb_layout', 'layout', 'zion_template', 'gspbstylebook', 'kadence_element', 'kadence_adv_page', 'kadence_query', 'kadence_query_card', 'kubio_recommend_page', 'builderius_template', 'builderius_branch', 'builderius_commit', 'builderius_release', 'builderius_dsm', 'builderius_saved_cm', 'builderius_component', 'builderius_sett_set', 'builderius_bh_commit', 'builderius_saved_fr', 'builderius_form_subm' ], true );
	}
	/**
	 * Enforce the current site's ordinary list and editing boundary.
	 * @param string $type Registered content-type slug.
	 * @return bool Whether the current user can access an eligible admin list.
	 */
	public static function accessible( string $type ): bool {
		$object = get_post_type_object( $type );
		return $object && $object->show_ui && ! self::internal( $type ) && current_user_can( $object->cap->edit_posts );
	}
	/** Read validated overrides without dropping values belonging to temporarily absent plugins. @return array Slugs mapped to include or exclude. */
	public static function rules(): array {
		$settings = get_option( 'blp_settings', [] );
		$raw = is_array( $settings ) ? ( $settings['post_type_rules'] ?? [] ) : [];
		$result = [];
		foreach ( is_array( $raw ) ? $raw : [] as $type => $mode ) {
			if ( is_string( $type ) && $type === sanitize_key( $type ) && ! self::internal( $type ) && in_array( $mode, [ 'include', 'exclude' ], true ) ) { $result[ $type ] = $mode; }
		}
		return $result;
	}
	/**
	 * Resolve automatic classification, the developer filter and explicit site overrides.
	 * Native builder type restrictions are enforced by the registry before this method.
	 * @param string $type Registered content-type slug.
	 * @param string $builder Builder family identifier.
	 * @param bool $blocks Whether automatic selection also requires block-editor support.
	 * @return bool Whether stored signatures may be recognized for this type.
	 */
	public static function allows( string $type, string $builder, bool $blocks = false ): bool {
		if ( ! self::accessible( $type ) ) { return false; }
		$object = get_post_type_object( $type );
		$technical = [ 'rcb-cookie', 'rcb-blocker', 'rcb-banner-link', 'redirect_rule', 'kadence_form', 'kadence_navigation', 'kadence_header', 'kadence_lottie', 'kadence_vector', 'kadence_element', 'kadence_adv_page', 'kadence_query', 'kadence_query_card', 'llar_digest_day', 'branda_email_log', 'kubio_recommend_page' ];
		$allowed = ! in_array( $type, $technical, true ) && ( $object->public || $object->publicly_queryable );
		if ( $blocks ) { $allowed = $allowed && post_type_supports( $type, 'editor' ) && use_block_editor_for_post_type( $type ); }
		/**
		 * Adjust automatic content-type selection before explicit site overrides.
		 * Return a Boolean; system exclusions, native builder restrictions and permissions still apply.
		 * @since 1.3.0
		 * @param bool $allowed Automatic classification result.
		 * @param \WP_Post_Type $object Registered content-type object.
		 * @param string $builder Builder family identifier.
		 */
		$allowed = true === apply_filters( 'deckerweb_builder_list_pages_content_type_allowed', (bool) $allowed, $object, $builder );
		$mode = self::rules()[ $type ] ?? 'auto';
		return 'include' === $mode || ( 'exclude' !== $mode && $allowed );
	}
	/**
	 * Sanitize submitted choices, retaining undisplayed settings from absent or inaccessible types.
	 * @param mixed $submitted Untrusted slug-to-mode selections from the settings form.
	 * @param bool $complete Whether a second Settings API pass contains the complete normalized map.
	 * @return array Validated site overrides; automatic entries are not stored.
	 */
	public static function sanitize( $submitted, bool $complete = false ): array {
		$result = $complete ? [] : self::rules();
		if ( $complete ) {
			foreach ( self::rules() as $type => $mode ) { if ( ! self::accessible( $type ) && is_array( $submitted ) && ( $submitted[ $type ] ?? null ) === $mode ) { $result[ $type ] = $mode; } }
		}
		foreach ( get_post_types( [ 'show_ui' => true ] ) as $type ) {
			if ( ! self::accessible( $type ) ) { continue; }
			$mode = is_array( $submitted ) ? ( $submitted[ $type ] ?? null ) : null;
			if ( 'auto' === $mode ) { unset( $result[ $type ] ); }
			elseif ( in_array( $mode, [ 'include', 'exclude' ], true ) ) { $result[ $type ] = $mode; }
		}
		return $result;
	}
}
