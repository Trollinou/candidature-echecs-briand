<?php
namespace CEB\Shortcodes\Application_Form;

/**
 * Gestionnaire principal du shortcode du formulaire de candidature
 */
class Manager {

	/**
	 * Initialisation du shortcode et des actions associées
	 *
	 * @return void
	 */
	public function init(): void {
		add_shortcode( 'ceb_formulaire_candidature', [ $this, 'render_shortcode' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

		// Initialisation du traitement de formulaire
		$handler = new Handler();
		$handler->init();
	}

	/**
	 * Enregistrement des fichiers CSS et JS nécessaires au formulaire
	 *
	 * @return void
	 */
	public function enqueue_assets(): void {
		// On enregistre les assets, mais on ne les charge que si le shortcode est présent (facultatif, mais bonne pratique)
		// Le nom respecte la convention {slug}-public-{contexte}
		wp_register_style(
			'ceb-public-application-form',
			plugins_url( 'assets/css/public-application-form.css', dirname( __FILE__, 3 ) ),
			[],
			CEB_VERSION
		);
		wp_register_script(
			'ceb-public-application-form',
			plugins_url( 'assets/js/public-application-form.js', dirname( __FILE__, 3 ) ),
			[],
			CEB_VERSION,
			true
		);
	}

	/**
	 * Rendu du shortcode [ceb_formulaire_candidature]
	 *
	 * @param array<string, mixed>|string $atts Les attributs du shortcode.
	 * @return string Le code HTML du formulaire.
	 */
	public function render_shortcode( $atts ): string {
		// S'assurer que les assets sont chargés uniquement quand le shortcode est appelé
		wp_enqueue_style( 'ceb-public-application-form' );
		wp_enqueue_script( 'ceb-public-application-form' );

		// Le Render Component est responsable de l'affichage
		$renderer = new Render();
		return $renderer->display();
	}
}