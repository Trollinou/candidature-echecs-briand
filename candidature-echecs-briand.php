<?php
/**
 * Plugin Name:       Candidature Section Échecs Collège Briand
 * Description:       Gestion des candidatures pour la section sportive échecs (Ouverture Septembre 2026).
 * Version:           1.0.2
 * Author:            Etienne Gagnon
 * Text Domain:       candidature-echecs-briand
 * Domain Path:       /languages
 * Requires PHP:      8.4
 * Requires at least: 7.1
 *
 * @package Candidature_Echecs_Briand
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Constantes du Plugin.
 */
define( 'CEB_VERSION', '1.0.2' );
define( 'CEB_PATH', plugin_dir_path( __FILE__ ) );
define( 'CEB_URL', plugin_dir_url( __FILE__ ) );

/**
 * Autoloader SPL natif pour le namespace CEB\
 *
 * @param string $class_name Nom complet de la classe à charger.
 * @return void
 */
spl_autoload_register(
	function ( string $class_name ): void {
		$prefix   = 'CEB\\';
		$base_dir = CEB_PATH . 'includes/';

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class_name, $len ) !== 0 ) {
			return;
		}

		$relative_class = substr( $class_name, $len );
		$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

/**
 * Initialisation principale des modules du plugin.
 *
 * @return void
 */
function ceb_run_plugin(): void {
	// CPT & Métadonnées typées (WP 7.1+).
	$cpt_candidature = new \CEB\CPT\Candidature();
	$cpt_candidature->init();

	$cpt_meta = new \CEB\CPT\Meta();
	$cpt_meta->init();

	// REST API Controller (WP 7.1+).
	$rest_controller = new \CEB\API\Candidature_Controller();
	$rest_controller->init();

	// Gutenberg Block (WP 7.1+).
	$block_form = new \CEB\Blocks\Application_Form();
	$block_form->init();

	// Formulaire Front & Shortcode.
	$shortcode_application_form = new \CEB\Shortcodes\Application_Form\Manager();
	$shortcode_application_form->init();

	// Back-office Metaboxes.
	$metabox_identity = new \CEB\Metaboxes\Candidature\Identity();
	$metabox_identity->init();

	$metabox_legal_rep = new \CEB\Metaboxes\Candidature\Legal_Rep();
	$metabox_legal_rep->init();

	$metabox_chess_journey = new \CEB\Metaboxes\Candidature\Chess_Journey();
	$metabox_chess_journey->init();

	// Service d'export CSV.
	$export_manager = new \CEB\Services\Export\Manager();
	$export_manager->init();
}
add_action( 'plugins_loaded', 'ceb_run_plugin' );
