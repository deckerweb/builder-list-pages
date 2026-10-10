<?php
/** Check host-scoped update validation against actual filesystem candidates in disposable WordPress. */
define( 'WP_ADMIN', true );
define( 'WP_DISABLE_FATAL_ERROR_HANDLER', true );
require $argv[1];
require_once ABSPATH . 'wp-admin/includes/file.php';
WP_Filesystem();
$adapter = new Deckerweb\BuilderListPages\GitHubUpdates();
$context = [ 'plugin' => 'builder-list-pages/builder-list-pages.php', 'type' => 'plugin', 'action' => 'update' ];
$dir = get_temp_dir() . 'blp-update-safety-' . wp_generate_password( 8, false );
mkdir( $dir );
$initial = get_site_transient( 'update_plugins' );
set_site_transient( 'update_plugins', (object) [ 'response' => [ $context['plugin'] => (object) [ 'new_version' => '1.1.1' ] ] ] );
/**
 * Verify the expected candidate outcome without executing candidate PHP.
 * @param Deckerweb\BuilderListPages\GitHubUpdates $adapter Host update validator.
 * @param string $dir Disposable candidate directory.
 * @param array $context Exact WordPress upgrade context.
 * @param array $headers Header overrides for the candidate.
 * @param string|null $error Expected error code, or null for acceptance.
 * @return void Throws on a wrong result and prints each passing case.
 */
function blp_update_case( $adapter, string $dir, array $context, array $headers, ?string $error ): void {
 $headers += [ 'Plugin Name' => 'Builder List Pages', 'Version' => '1.1.1', 'Update URI' => 'https://github.com/deckerweb/builder-list-pages', 'Requires PHP' => '8.0', 'Requires at least' => '6.7' ];
 $text = "<?php\n/**\n";
 foreach ( $headers as $key => $value ) { $text .= ' * ' . $key . ': ' . $value . "\n"; }
 file_put_contents( $dir . '/builder-list-pages.php', $text . " */\n" );
 $result = $adapter->validate_source( $dir, '', null, $context );
 if ( null === $error ? $result !== $dir : ( ! is_wp_error( $result ) || $result->get_error_code() !== $error ) ) { throw new Exception( 'Wrong validation outcome: ' . json_encode( $headers ) ); }
 echo 'PASS candidate: ' . ( $error ?? 'compatible newer release accepted' ) . PHP_EOL;
}
try {
 blp_update_case( $adapter, $dir, $context, [], null );
 blp_update_case( $adapter, $dir, $context, [ 'Plugin Name' => 'Different plugin' ], 'blp_update_identity' );
 blp_update_case( $adapter, $dir, $context, [ 'Update URI' => 'https://evil.example/plugin' ], 'blp_update_identity' );
 blp_update_case( $adapter, $dir, $context, [ 'Version' => '1.0.0' ], 'blp_update_identity' );
 blp_update_case( $adapter, $dir, $context, [ 'Version' => '1.1.1-rc1' ], 'blp_update_identity' );
 blp_update_case( $adapter, $dir, $context, [ 'Version' => '1.1.2' ], 'blp_update_version' );
 blp_update_case( $adapter, $dir, $context, [ 'Requires PHP' => '' ], 'blp_update_requirements' );
 blp_update_case( $adapter, $dir, $context, [ 'Requires PHP' => '99.0' ], 'blp_update_compatibility' );
 blp_update_case( $adapter, $dir, $context, [ 'Requires at least' => '99.0' ], 'blp_update_compatibility' );
 unlink( $dir . '/builder-list-pages.php' );
 $result = $adapter->validate_source( $dir, '', null, $context );
 if ( ! is_wp_error( $result ) || $result->get_error_code() !== 'blp_update_source' ) { throw new Exception( 'Missing main file accepted' ); }
 echo 'PASS candidate: missing main file rejected' . PHP_EOL;
 $unrelated = $context; $unrelated['plugin'] = 'other/other.php';
 if ( $adapter->validate_source( $dir, '', null, $unrelated ) !== $dir ) { throw new Exception( 'Other plugin modified' ); }
 $http = [ 'timeout' => 60, 'redirection' => 5 ];
 if ( $adapter->request_limits( $http, 'https://example.org/update.zip' ) !== $http || $adapter->request_limits( $http, 'https://api.github.com/repos/deckerweb/builder-list-pages/releases/latest' )['limit_response_size'] !== 524288 ) { throw new Exception( 'Request scoping changed' ); }
 echo 'PASS unrelated plugins/downloads unchanged; repository metadata bounded' . PHP_EOL;
} finally {
 if ( is_file( $dir . '/builder-list-pages.php' ) ) { unlink( $dir . '/builder-list-pages.php' ); }
 rmdir( $dir ); set_site_transient( 'update_plugins', $initial );
}
echo 'SUCCESS: 12 update-safety checks on WordPress ' . $GLOBALS['wp_version'] . PHP_EOL;
