<?php
/** Small native WordPress integrations without extra content scans. */
namespace Deckerweb\BuilderListPages;
defined( 'ABSPATH' ) || exit;

/** Add support information and permission-aware navigation to existing WordPress tools. */
final class AdminTools {
	/** Register information and optional command-palette callbacks. @return void */
	public function register(): void {
		add_filter( 'debug_information', [ $this, 'information' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'commands_assets' ], 100 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'commands_assets' ], 100 );
	}
	/**
	 * Add plain, local support information; inactive builders never become health warnings.
	 * @param array $information Existing WordPress Site Health information.
	 * @return array Information with a namespaced section for permitted administrators only.
	 */
	public function information( array $information ): array {
		if ( ! current_user_can( 'manage_options' ) || is_network_admin() ) { return $information; }
		$registry = new Registry();
		$labels = array_map( 'wp_strip_all_tags', array_column( $registry->all(), 'label' ) );
		$rules = ContentTypes::rules();
		$include = array_keys( $rules, 'include', true );
		$exclude = array_keys( $rules, 'exclude', true );
		$fields = [
			'version' => [ 'label' => __( 'Version', 'builder-list-pages' ), 'value' => BLP_VERSION ],
			'integrations' => [ 'label' => __( 'Available integrations', 'builder-list-pages' ), 'value' => $labels ? implode( ', ', $labels ) : __( 'None', 'builder-list-pages' ) ],
			'content-types' => [ 'label' => __( 'Selected content types', 'builder-list-pages' ), 'value' => implode( ', ', $registry->post_types() ) ?: __( 'None', 'builder-list-pages' ) ],
			'included' => [ 'label' => __( 'Explicitly included content types', 'builder-list-pages' ), 'value' => implode( ', ', $include ) ?: __( 'None', 'builder-list-pages' ) ],
			'excluded' => [ 'label' => __( 'Explicitly hidden content types', 'builder-list-pages' ), 'value' => implode( ', ', $exclude ) ?: __( 'None', 'builder-list-pages' ) ],
		];
		$information['builder-list-pages'] = [ 'label' => 'Builder List Pages', 'description' => __( 'Local integration and content-type settings for troubleshooting. No page contents, credentials or license details are included.', 'builder-list-pages' ), 'fields' => $fields ];
		return $information;
	}
	/** Return safe navigation targets, without inventory queries or matching-content counts. @return array Commands limited to the current user's accessible site lists. */
	public function commands(): array {
		if ( ! is_admin() || is_network_admin() ) { return []; }
		$commands = [];
		if ( current_user_can( 'manage_options' ) ) {
			$commands[] = [ 'name' => 'builder-list-pages/settings', 'label' => __( 'Builder List Pages: integrations and settings', 'builder-list-pages' ), 'url' => admin_url( 'options-general.php?page=builder-list-pages' ) ];
		}
		foreach ( ( new Registry() )->all() as $id => $builder ) {
			foreach ( $builder['builder-types'] as $type ) {
				$object = get_post_type_object( $type );
				if ( ! $object ) { continue; }
				/* translators: 1: builder name, 2: content-type label. */
				$label = sprintf( __( 'Builder List Pages: %1$s — %2$s', 'builder-list-pages' ), wp_strip_all_tags( $builder['label'] ), wp_strip_all_tags( $object->labels->name ) );
				$commands[] = [ 'name' => 'builder-list-pages/' . $id . '/' . $type, 'label' => $label, 'url' => Admin::list_url( $type, $id ) ];
			}
		}
		return $commands;
	}
	/** Enqueue shortcuts only where WordPress already provides its native palette. @return void */
	public function commands_assets(): void {
		if ( ! wp_script_is( 'wp-commands', 'enqueued' ) || wp_script_is( 'blp-commands', 'enqueued' ) ) { return; }
		$commands = $this->commands();
		if ( ! $commands ) { return; }
		wp_enqueue_script( 'blp-commands', plugins_url( 'assets/commands.js', BLP_PLUGIN_FILE ), [ 'wp-commands', 'wp-data' ], BLP_VERSION, true );
		wp_add_inline_script( 'blp-commands', 'window.blpCommands = ' . wp_json_encode( $commands, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . ';', 'before' );
	}
}
