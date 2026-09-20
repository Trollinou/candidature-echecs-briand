<?php
/**
 * Gestionnaire du shortcode du formulaire de candidature.
 *
 * @package Candidature_Echecs_Briand
 */

namespace CEB\Shortcodes\Application_Form;

/**
 * Gestionnaire principal du shortcode du formulaire de candidature.
 */
class Manager {

	/**
	 * Initialisation du shortcode et des actions associées.
	 *
	 * @return void
	 */
	public function init(): void {
		add_shortcode( 'ceb_formulaire_candidature', [ $this, 'render_shortcode' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

		// Initialisation du traitement de formulaire.
		$handler = new Handler();
		$handler->init();
	}

	/**
	 * Enregistrement des fichiers CSS et JS nécessaires au formulaire.
	 *
	 * @return void
	 */
	public function enqueue_assets(): void {
		$style_url  = plugins_url( 'assets/css/public-application-form.css', dirname( __DIR__, 2 ) );
		$script_url = plugins_url( 'assets/js/public-application-form.js', dirname( __DIR__, 2 ) );

		if ( ! empty( $style_url ) ) {
			wp_register_style(
				'ceb-public-application-form',
				$style_url,
				[],
				CEB_VERSION
			);
		}

		if ( ! empty( $script_url ) ) {
			wp_register_script(
				'ceb-public-application-form',
				$script_url,
				[],
				CEB_VERSION,
				true
			);
		}
	}

	/**
	 * Rendu du shortcode [ceb_formulaire_candidature].
	 *
	 * @param array<string, mixed>|string $atts Les attributs du shortcode.
	 * @return string Le code HTML du formulaire.
	 */
	public function render_shortcode( $atts = [] ): string {
		// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
		unset( $atts );

		wp_enqueue_style( 'ceb-public-application-form' );
		wp_enqueue_script( 'ceb-public-application-form' );

		$renderer = new Render();
		return $renderer->render();
	}
}
