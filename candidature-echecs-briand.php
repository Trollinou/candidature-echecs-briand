<?php
/**
 * Plugin Name:       Candidature Section Échecs Collège Briand
 * Description:       Gestion des candidatures pour la section sportive échecs (Ouverture Septembre 2026).
 * Version:           1.0.0
 * Author:            Etienne Gagnon
 * Text Domain:       candidature-echecs-briand
 * Domain Path:       /languages
 * Requires PHP:      8.4
 * Requires at least: 6.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Constantes du Plugin
 */
define( 'CEB_VERSION', '1.0.0' );
define( 'CEB_PATH', plugin_dir_path( __FILE__ ) );
define( 'CEB_URL', plugin_dir_url( __FILE__ ) );

/**
 * AUTOLOADER SPL NAtif (Conforme AGENTS.md Section 10)
 * Charge les classes du namespace CEB\ depuis le dossier includes/
 */
spl_autoload_register( function ( $class ) {
	$prefix   = 'CEB\\';
	$base_dir = CEB_PATH . 'includes/';

	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	$relative_class = substr( $class, $len );
	$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}
} );

/**
 * Initialisation du Plugin
 */
function ceb_run_plugin() {
	// Exemple d'instanciation via le namespace
	// $plugin = new CEB\Core\Loader();
	// $plugin->init();
}
add_action( 'plugins_loaded', 'ceb_run_plugin' );
