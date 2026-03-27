<?php
namespace CEB\Shortcodes\Application_Form;

class Manager {

	public function init() {
		add_shortcode( 'ceb_formulaire_candidature', [ $this, 'render_shortcode' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

		// Initialisation du traitement de formulaire
		$handler = new Handler();
		$handler->init();
	}

	public function enqueue_assets() {
		// On enregistre les assets, mais on ne les charge que si le shortcode est présent (facultatif, mais bonne pratique)
		// Le nom respecte la convention {slug}-public-{contexte}
		wp_register_style(
			'ceb-public-application-form',
			CEB_URL . 'assets/css/public-application-form.css',
			[],
			CEB_VERSION
		);
		wp_register_script(
			'ceb-public-application-form',
			CEB_URL . 'assets/js/public-application-form.js',
			[],
			CEB_VERSION,
			true
		);
	}

	public function render_shortcode( $atts ) {
		// S'assurer que les assets sont chargés uniquement quand le shortcode est appelé
		wp_enqueue_style( 'ceb-public-application-form' );
		wp_enqueue_script( 'ceb-public-application-form' );

		// Le Render Component est responsable de l'affichage
		$renderer = new Render();
		return $renderer->display();
	}
}