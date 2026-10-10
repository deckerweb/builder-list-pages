<?php
/** Personal views and read-only handover tools for native content lists. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

/** Keep optional workflows scoped to the current site and authenticated user. */
final class Workflows {
	private Registry $registry;
	private Query $query;
	/** Retain shared recognition and query rules. @param Registry $registry Builder registry. @param Query $query List constraints. @return void */
	public function __construct( Registry $registry, Query $query ) { $this->registry = $registry; $this->query = $query; }
	/** Register site-local UI and authenticated actions. @return void */
	public function register(): void {
		add_action( 'admin_notices', [ $this, 'panel' ] );
		add_action( 'admin_post_blp_view', [ $this, 'action' ] );
		add_action( 'admin_post_blp_csv', [ $this, 'export' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
	}
	/** Return the current site's personal metadata key. @return string Site-scoped user metadata key. */
	public static function key(): string { return 'blp_views_' . get_current_blog_id(); }
	/** Read bounded personal state without changing it. @return array Saved views and compact display choice. */
	public static function state(): array {
		$value = get_user_meta( get_current_user_id(), self::key(), true );
		return is_array( $value ) ? [ 'compact' => ! empty( $value['compact'] ), 'views' => array_slice( is_array( $value['views'] ?? null ) ? $value['views'] : [], 0, 20 ) ] : [ 'compact' => false, 'views' => [] ];
	}
	/** Validate a content type's editing access. @param string $type Post-type slug. @return bool Whether native editing access exists. */
	public static function allowed( string $type ): bool {
		$o = get_post_type_object( $type );
		return $o && $o->show_ui && 'attachment' !== $type && current_user_can( $o->cap->edit_posts );
	}
	/** Read one bounded scalar request input. @param array $source Request fields. @param string $key Field name. @param bool $slashed Whether WordPress request slashes must be removed. @return string Unslashed input or empty string. */
	private static function input( array $source, string $key, bool $slashed = true ): string { return is_string( $source[ $key ] ?? null ) ? substr( $slashed ? wp_unslash( $source[ $key ] ) : $source[ $key ], 0, 500 ) : ''; }
	/** Normalize the explicitly supported favorite fields. @param array $source Request or stored view. @param bool $slashed Whether inputs carry WordPress request slashes. @return array Validated native list parameters. */
	public function parameters( array $source, bool $slashed = true ): array {
		$type = sanitize_key( self::input( $source, 'post_type', $slashed ) );
		if ( ! self::allowed( $type ) ) { return []; }
		$builder = sanitize_key( self::input( $source, 'builder', $slashed ) );
		$choices = $this->choices( $type );
		$status = sanitize_key( self::input( $source, 'post_status', $slashed ) );
		return [ 'post_type' => $type, 'builder' => isset( $choices[ $builder ] ) ? $builder : '', 'post_status' => in_array( $status, get_post_stati( [ 'show_in_admin_status_list' => true ] ), true ) ? $status : '', 's' => sanitize_text_field( self::input( $source, 's', $slashed ) ) ];
	}
	/** Return available builder groups including explicit read-only audits. @param string $type Post-type slug. @return array Group IDs and visible labels. */
	public function choices( string $type ): array {
		$result = [];
		foreach ( $this->registry->for_type( $type ) as $id => $b ) { $result[ $id ] = $b['label']; }
		if ( count( $result ) > 1 ) { $result['multiple'] = __( 'Multiple builders recognized', 'builder-list-pages' ); }
		if ( $result && Settings::enabled( 'show_none' ) ) { $result['none'] = __( 'No recognized builder', 'builder-list-pages' ); }
		if ( $this->registry->traces( $type ) ) { $result['traces'] = __( 'Check inactive builder traces', 'builder-list-pages' ); }
		return $result;
	}
	/** Build a list URL with the compatibility marker last. @param array $parameters Validated native list fields. @return string Same-site content-list URL. */
	public static function url( array $parameters ): string { return add_query_arg( 'blp_view', '1', add_query_arg( array_filter( $parameters, 'strlen' ), admin_url( 'edit.php' ) ) ); }
	/** Enqueue local behavior on supported native content screens only. @param string $hook Admin hook suffix. @return void */
	public function assets( string $hook ): void {
		$s = get_current_screen();
		if ( 'edit.php' !== $hook || ! $s || ! self::allowed( $s->post_type ) ) { return; }
		wp_enqueue_style( 'blp-admin', plugins_url( 'assets/admin.css', BLP_PLUGIN_FILE ), [], BLP_VERSION );
		wp_enqueue_script( 'blp-workflows', plugins_url( 'assets/workflows.js', BLP_PLUGIN_FILE ), [], BLP_VERSION, true );
	}
	/** Print a validated view's hidden native fields. @param array $params Native list fields. @return void */
	private function fields( array $params ): void { foreach ( $params as $k => $v ) { echo '<input type="hidden" name="' . esc_attr( $k ) . '" value="' . esc_attr( $v ) . '">'; } }
	/** Render optional tools and helpful empty-state recovery outside the list form. @return void */
	public function panel(): void {
		$s = get_current_screen();
		if ( ! $s || 'edit' !== $s->base || ! self::allowed( $s->post_type ) || ! $this->choices( $s->post_type ) ) { return; }
		$params = $this->parameters( [ 'post_type' => $s->post_type ] + $_GET );
		$choices = $this->choices( $s->post_type ); $state = self::state();
		global $wp_query;
		if ( $params['builder'] && $wp_query instanceof \WP_Query && 0 === (int) $wp_query->found_posts ) {
			echo '<div class="notice notice-info blp-empty"><p><strong>' . esc_html__( 'No matching items with these filters.', 'builder-list-pages' ) . '</strong> ' . esc_html( implode( ' · ', array_filter( [ $choices[ $params['builder'] ], $params['s'], $params['post_status'] ] ) ) ) . '</p><p>';
			if ( $params['s'] ) { echo '<a class="button" href="' . esc_url( $this->reset_url( 's', $s->post_type ) ) . '">' . esc_html__( 'Reset search', 'builder-list-pages' ) . '</a> '; }
			echo '<a class="button" href="' . esc_url( $this->reset_url( 'builder', $s->post_type ) ) . '">' . esc_html__( 'Remove builder filter', 'builder-list-pages' ) . '</a></p></div>';
		}
		if ( 'traces' === $params['builder'] ) { echo '<div class="notice notice-info"><p>' . esc_html__( 'Read-only audit: these items contain known signatures from inactive builders. A trace does not prove which builder renders the page. No content is changed.', 'builder-list-pages' ) . '</p></div>'; }
		echo '<details class="blp-tools"><summary>' . esc_html__( 'Builder tools and personal views', 'builder-list-pages' ) . '</summary><div class="blp-tools-content"><p>' . esc_html__( 'Builder groups can overlap. An existing metadata field can count even when empty. Recognition describes stored signatures, not frontend rendering or global templates.', 'builder-list-pages' ) . '</p>';
		echo '<form action="' . esc_url( admin_url( 'edit.php' ) ) . '" method="get"><input type="hidden" name="post_type" value="' . esc_attr( $s->post_type ) . '"><input type="hidden" name="s" value="' . esc_attr( $params['s'] ) . '"><input type="hidden" name="post_status" value="' . esc_attr( $params['post_status'] ) . '"><label for="blp-builder-select">' . esc_html__( 'Builder view', 'builder-list-pages' ) . '</label> <select id="blp-builder-select" name="builder"><option value="">' . esc_html__( 'All builders', 'builder-list-pages' ) . '</option>';
		foreach ( $choices as $id => $label ) { echo '<option value="' . esc_attr( $id ) . '" ' . selected( $params['builder'], $id, false ) . '>' . esc_html( $label ) . '</option>'; }
		echo '</select><input type="hidden" name="blp_view" value="1"> <button class="button">' . esc_html__( 'Apply view', 'builder-list-pages' ) . '</button></form>';
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="blp_view"><input type="hidden" name="operation" value="save">'; wp_nonce_field( 'blp_view' ); $this->fields( $params );
		echo '<label for="blp-view-name">' . esc_html__( 'View name', 'builder-list-pages' ) . '</label> <input id="blp-view-name" name="name" maxlength="80" required> <button class="button">' . esc_html__( 'Save personal view', 'builder-list-pages' ) . '</button></form>';
		foreach ( $state['views'] as $i => $view ) {
			if ( ! is_array( $view ) || ! is_array( $view['params'] ?? null ) ) { continue; }
			$valid = $this->parameters( $view['params'], false ); if ( ! $valid || $valid['builder'] !== ( $view['params']['builder'] ?? '' ) ) { continue; }
			echo '<form class="blp-saved-view" method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><a href="' . esc_url( self::url( $valid ) ) . '">' . esc_html( is_string( $view['name'] ?? null ) ? $view['name'] : '' ) . '</a> <input type="hidden" name="action" value="blp_view"><input type="hidden" name="operation" value="delete"><input type="hidden" name="index" value="' . (int) $i . '">'; wp_nonce_field( 'blp_view' ); $this->fields( $params ); echo '<button class="button-link-delete">' . esc_html__( 'Delete view', 'builder-list-pages' ) . '</button></form>';
		}
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="blp_view"><input type="hidden" name="operation" value="compact">'; wp_nonce_field( 'blp_view' ); $this->fields( $params ); echo '<label><input type="checkbox" name="compact" value="1" ' . checked( $state['compact'], true, false ) . '> ' . esc_html__( 'Use compact builder selection for my account', 'builder-list-pages' ) . '</label> <button class="button">' . esc_html__( 'Save display choice', 'builder-list-pages' ) . '</button></form>';
		echo '<p><a href="' . esc_url( self::url( [ 'post_type' => $s->post_type, 'builder' => 'traces' ] ) ) . '">' . esc_html__( 'Check inactive builder traces', 'builder-list-pages' ) . '</a></p><form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="blp_csv">'; wp_nonce_field( 'blp_csv' ); $this->fields( $params ); echo '<p>' . esc_html__( 'CSV exports this builder, status and search selection, up to 5,000 readable items. Other list filters are not included.', 'builder-list-pages' ) . '</p><button class="button">' . esc_html__( 'Export selection as CSV', 'builder-list-pages' ) . '</button></form>';
		echo '<p><label for="blp-diagnostics">' . esc_html__( 'Support diagnostics: select and copy', 'builder-list-pages' ) . '</label></p><textarea id="blp-diagnostics" readonly rows="5">' . esc_textarea( $this->diagnostics() ) . '</textarea></div></details>';
	}
	/** Retain existing list filters while clearing only the requested constraint. @param string $key Search or builder field. @param string $type Content-type slug. @return string Safe native list URL. */
	private function reset_url( string $key, string $type ): string {
		$fields = $_GET; unset( $fields[ $key ], $fields['paged'], $fields['blp_view'], $fields['action'], $fields['action2'], $fields['_wpnonce'] ); $fields['post_type'] = $type;
		return add_query_arg( 'blp_view', '1', add_query_arg( wp_unslash( $fields ), admin_url( 'edit.php' ) ) );
	}
	/** Return opt-in copyable diagnostics without URLs, users or contents. @return string Local version and supported-builder details. */
	public function diagnostics(): string {
		global $wp_version;
		$labels = []; foreach ( $this->registry->all() as $b ) { $labels[] = $b['label']; }
		return 'Builder List Pages: ' . BLP_VERSION . "\nWordPress: " . $wp_version . "\nPHP: " . PHP_VERSION . "\nBuilders: " . implode( ', ', $labels ) . "\nPost types: " . implode( ', ', $this->registry->post_types() );
	}
	/** Process nonce-protected user-only changes and redirect to a permitted list. @return void */
	public function action(): void {
		check_admin_referer( 'blp_view' ); $params = $this->parameters( $_POST ); if ( ! $params ) { wp_die( esc_html__( 'You cannot access this content type.', 'builder-list-pages' ), '', [ 'response' => 403 ] ); }
		$state = self::state(); $op = self::input( $_POST, 'operation' );
		if ( 'compact' === $op ) { $state['compact'] = '1' === self::input( $_POST, 'compact' ); }
		elseif ( 'delete' === $op ) { $index = self::input( $_POST, 'index' ); if ( ctype_digit( $index ) ) { unset( $state['views'][ (int) $index ] ); $state['views'] = array_values( $state['views'] ); } }
		elseif ( 'save' === $op ) { $name = sanitize_text_field( self::input( $_POST, 'name' ) ); $name = function_exists( 'mb_substr' ) ? mb_substr( $name, 0, 80 ) : substr( $name, 0, 80 ); if ( '' !== $name ) { foreach ( $state['views'] as $index => $view ) { if ( ( $view['name'] ?? '' ) === $name ) { unset( $state['views'][ $index ] ); } } $state['views'] = array_values( $state['views'] ); if ( count( $state['views'] ) >= 20 ) { wp_die( esc_html__( 'You can save up to 20 personal views. Delete a view before adding another.', 'builder-list-pages' ) ); } $state['views'][] = [ 'name' => $name, 'params' => $params ]; } }
		update_user_meta( get_current_user_id(), self::key(), wp_slash( $state ) ); wp_safe_redirect( self::url( $params ) ); exit;
	}
	/** Neutralize spreadsheet formula prefixes, including leading whitespace. @param string $value Cell value. @return string Formula-safe text cell. */
	public static function csv_cell( string $value ): string { return preg_match( '/^[\s\x00-\x20]*[=+@-]/', $value ) ? "'" . $value : $value; }
	/** Fetch a bounded, capability-checked handover selection. @param array $params Validated native list fields. @return array CSV rows; builder content is never exported. */
	public function rows( array $params ): array {
		$params = $this->parameters( $params, false );
		if ( ! $params || ! self::allowed( $params['post_type'] ) ) { return []; }
		$args = [ 'post_type' => $params['post_type'], 'post_status' => $params['post_status'] ?: array_values( get_post_stati( [ 'show_in_admin_all_list' => true ] ) ), 's' => $params['s'], 'posts_per_page' => 5000, 'perm' => 'readable', 'no_found_rows' => true, 'update_post_term_cache' => false, 'update_post_meta_cache' => false ];
		$o = get_post_type_object( $params['post_type'] ); if ( ! current_user_can( $o->cap->edit_others_posts ) ) { $args['author'] = get_current_user_id(); }
		$q = new \WP_Query(); if ( $params['builder'] ) { $this->query->apply( $q, $params['post_type'], $params['builder'] ); } $args['meta_query'] = $q->get( 'meta_query' ); $q->query( $args ); $rows = []; $permitted = [];
		foreach ( $q->posts as $p ) {
			if ( current_user_can( 'read_post', $p->ID ) && current_user_can( 'edit_post', $p->ID ) ) { $permitted[ $p->ID ] = $p; }
		}
		$labels = $this->registry->export_labels( array_keys( $permitted ), $params['post_type'] );
		foreach ( $permitted as $p ) {
			$rows[] = array_map( [ self::class, 'csv_cell' ], [ $p->post_title, $p->post_status, implode( ', ', $labels[ $p->ID ] ?? [] ), (string) get_edit_post_link( $p->ID, 'raw' ) ] );
		}
		return $rows;
	}
	/** Stream an explicitly requested bounded CSV after nonce and capability checks. @return void */
	public function export(): void {
		check_admin_referer( 'blp_csv' ); $params = $this->parameters( $_POST ); if ( ! $params ) { wp_die( esc_html__( 'You cannot access this content type.', 'builder-list-pages' ), '', [ 'response' => 403 ] ); }
		$rows = $this->rows( $params ); nocache_headers(); header( 'Content-Type: text/csv; charset=UTF-8' ); header( 'Content-Disposition: attachment; filename="builder-list-pages.csv"' ); $stream = fopen( 'php://output', 'w' ); if ( false === $stream ) { return; } fwrite( $stream, "\xEF\xBB\xBF" ); fputcsv( $stream, [ __( 'Title', 'builder-list-pages' ), __( 'Status', 'builder-list-pages' ), __( 'Builder', 'builder-list-pages' ), __( 'Edit link', 'builder-list-pages' ) ], ',', '"', '' ); foreach ( $rows as $row ) { fputcsv( $stream, $row, ',', '"', '' ); } fclose( $stream ); exit;
	}
}
