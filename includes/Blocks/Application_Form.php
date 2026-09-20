<?php
/**
 * Enregistrement du bloc Gutenberg pour le Formulaire de Candidature.
 *
 * @package Candidature_Echecs_Briand
 */

namespace CEB\Blocks;

use CEB\Shortcodes\Application_Form\Render;

/**
 * Gestion de l'enregistrement et du rendu serveur du bloc Gutenberg.
 */
class Application_Form {

	/**
	 * Initialisation du bloc.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'init', [ $this, 'register_block' ] );
	}

	/**
	 * Enregistre le type de bloc Gutenberg.
	 *
	 * @return void
	 */
	public function register_block(): void {
		register_block_type(
			'candidature-echecs-briand/application-form',
			[
				'api_version'     => '3',
				'title'           => __( 'Formulaire Candidature Section Échecs', 'candidature-echecs-briand' ),
				'category'        => 'widgets',
				'icon'            => 'welcome-learn-more',
				'description'     => __( 'Formulaire dynamique d\'inscription pour la section sportive échecs.', 'candidature-echecs-briand' ),
				'render_callback' => [ $this, 'render_block' ],
				'supports'        => [
					'html'  => false,
					'align' => [ 'wide', 'full' ],
				],
			]
		);
	}

	/**
	 * Rendu côté serveur du bloc.
	 *
	 * @param array<string, mixed> $attributes Attributs du bloc.
	 * @param string               $content    Contenu interne éventuel.
	 * @return string HTML du formulaire.
	 */
	public function render_block( array $attributes = [], string $content = '' ): string {
		// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		unset( $attributes, $content );

		$renderer = new Render();
		return $renderer->render();
	}
}
