<?php
namespace CEB\Shortcodes\Application_Form;

class Manager {

	public function init() {
		add_shortcode( 'ceb_formulaire_candidature', [ $this, 'render_shortcode' ] );

		// Initialisation du traitement de formulaire
		$handler = new Handler();
		$handler->init();
	}

	public function render_shortcode( $atts ) {
		// Le Render Component est responsable de l'affichage
		$renderer = new Render();
		return $renderer->display();
	}
}