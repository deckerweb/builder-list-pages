<?php
/** Translate the shared updater through the Builder List Pages textdomain. */
defined( 'ABSPATH' ) || exit;
/**
 * Translate a source message at display time; unknown messages retain English.
 *
 * @param string $message Shared updater English source message.
 * @return string Localized message in the current administrator locale.
 */
return static function ( string $message ): string {
    // Literal calls let the host's normal translation extractor collect every source string.
    switch ( $message ) {
        case 'Private mode must be boolean.':
            return __( 'Private mode must be boolean.', 'builder-list-pages' );
        case 'Invalid authentication provider.':
            return __( 'Invalid authentication provider.', 'builder-list-pages' );
        case 'The plugin must be installed in a stable slug directory.':
            return __( 'The plugin must be installed in a stable slug directory.', 'builder-list-pages' );
        case 'Invalid GitHub repository URL.':
            return __( 'Invalid GitHub repository URL.', 'builder-list-pages' );
        case 'The private update could not be authorized. Check the repository credentials and refresh updates.':
            return __( 'The private update could not be authorized. Check the repository credentials and refresh updates.', 'builder-list-pages' );
        case 'Could not create the update download file.':
            return __( 'Could not create the update download file.', 'builder-list-pages' );
        case 'The private update download failed. Check credentials and try again.':
            return __( 'The private update download failed. Check credentials and try again.', 'builder-list-pages' );
        case 'Could not access the update filesystem.':
            return __( 'Could not access the update filesystem.', 'builder-list-pages' );
        case 'GitHub release does not contain the plugin main file.':
            return __( 'GitHub release does not contain the plugin main file.', 'builder-list-pages' );
        case 'Could not prepare the GitHub release package.':
            return __( 'Could not prepare the GitHub release package.', 'builder-list-pages' );
        case 'See the release on GitHub.':
            return __( 'See the release on GitHub.', 'builder-list-pages' );
        default:
            return $message;
    }
};
