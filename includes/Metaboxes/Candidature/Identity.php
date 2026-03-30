<?php
namespace CEB\Metaboxes\Candidature;

/**
 * Metabox : Identité de l'élève (Lecture seule)
 */
class Identity {

	/**
	 * Initialisation de la metabox
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'add_meta_boxes', [ $this, 'add_box' ] );
	}

	/**
	 * Ajout de la metabox dans le CPT
	 *
	 * @return void
	 */
	public function add_box(): void {
		add_meta_box(
			'ceb_candidature_identity',
			'1. Identité de l\'élève',
			[ $this, 'render' ],
			'ceb_candidature',
			'normal',
			'high'
		);
	}

	/**
	 * Rendu HTML de la metabox (Lecture seule)
	 *
	 * @param \WP_Post $post L'objet Post courant.
	 * @return void
	 */
	public function render( \WP_Post $post ): void {
		$nom    = (string) get_post_meta( $post->ID, '_ceb_eleve_nom', true );
		$prenom = (string) get_post_meta( $post->ID, '_ceb_eleve_prenom', true );
		$ddn    = (string) get_post_meta( $post->ID, '_ceb_eleve_ddn', true );
		$sexe   = (string) get_post_meta( $post->ID, '_ceb_eleve_sexe', true );
		$ecole  = (string) get_post_meta( $post->ID, '_ceb_eleve_ecole', true );
		$classe = (string) get_post_meta( $post->ID, '_ceb_eleve_classe', true );
		$lv1    = (string) get_post_meta( $post->ID, '_ceb_eleve_lv1', true );
		$lv2          = (string) get_post_meta( $post->ID, '_ceb_eleve_lv2', true );
		$classe_cible = (string) get_post_meta( $post->ID, '_ceb_eleve_classe_cible', true );

		$ddn_formattee = $ddn ? wp_date( 'd/m/Y', (int) strtotime( $ddn ) ) : '';
		?>
		<div class="ceb-metabox-content">
			<p style="margin: 4px 0;"><strong>Nom :</strong> <?php echo esc_html( $nom ); ?></p>
			<p style="margin: 4px 0;"><strong>Prénom :</strong> <?php echo esc_html( $prenom ); ?></p>
			<p style="margin: 4px 0;"><strong>Date de naissance :</strong> <?php echo esc_html( $ddn_formattee ); ?></p>
			<p style="margin: 4px 0;"><strong>Sexe :</strong> <?php echo esc_html( $sexe ); ?></p>
			<p style="margin: 4px 0;"><strong>Établissement actuel :</strong> <?php echo esc_html( $ecole ); ?></p>
			<p style="margin: 4px 0;"><strong>Classe :</strong> <?php echo esc_html( $classe ); ?></p>
			<p style="margin: 4px 0;"><strong>LV1 :</strong> <?php echo esc_html( $lv1 ); ?></p>
			<p style="margin: 4px 0;"><strong>LV2 :</strong> <?php echo esc_html( $lv2 ); ?></p>
			<p style="margin: 4px 0;"><strong>Classe ciblée à la rentrée :</strong> <?php echo esc_html( $classe_cible ); ?></p>
		</div>
		<?php
	}
}