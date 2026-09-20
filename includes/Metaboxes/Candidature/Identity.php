<?php
/**
 * Metabox Identité de l'élève.
 *
 * @package Candidature_Echecs_Briand
 */

namespace CEB\Metaboxes\Candidature;

/**
 * Metabox : Identité de l'élève (Lecture seule).
 */
class Identity {

	/**
	 * Initialisation de la metabox.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'add_meta_boxes', [ $this, 'add_box' ] );
	}

	/**
	 * Ajout de la metabox dans le CPT.
	 *
	 * @return void
	 */
	public function add_box(): void {
		add_meta_box(
			'ceb_candidature_identity',
			__( '1. Identité de l\'élève', 'candidature-echecs-briand' ),
			[ $this, 'render' ],
			'ceb_candidature',
			'normal',
			'high'
		);
	}

	/**
	 * Rendu HTML de la metabox (Lecture seule).
	 *
	 * @param \WP_Post $post L'objet Post courant.
	 * @return void
	 */
	public function render( \WP_Post $post ): void {
		$nom          = (string) get_post_meta( $post->ID, '_ceb_eleve_nom', true );
		$prenom       = (string) get_post_meta( $post->ID, '_ceb_eleve_prenom', true );
		$ddn          = (string) get_post_meta( $post->ID, '_ceb_eleve_ddn', true );
		$sexe         = (string) get_post_meta( $post->ID, '_ceb_eleve_sexe', true );
		$ecole        = (string) get_post_meta( $post->ID, '_ceb_eleve_ecole', true );
		$classe       = (string) get_post_meta( $post->ID, '_ceb_eleve_classe', true );
		$lv1          = (string) get_post_meta( $post->ID, '_ceb_eleve_lv1', true );
		$lv2          = (string) get_post_meta( $post->ID, '_ceb_eleve_lv2', true );
		$classe_cible = (string) get_post_meta( $post->ID, '_ceb_eleve_classe_cible', true );

		$ddn_formattee = $ddn ? wp_date( 'd/m/Y', (int) strtotime( $ddn ) ) : '';
		?>
		<div class="ceb-metabox-content">
			<p style="margin: 4px 0;"><strong><?php esc_html_e( 'Nom :', 'candidature-echecs-briand' ); ?></strong> <?php echo esc_html( $nom ); ?></p>
			<p style="margin: 4px 0;"><strong><?php esc_html_e( 'Prénom :', 'candidature-echecs-briand' ); ?></strong> <?php echo esc_html( $prenom ); ?></p>
			<p style="margin: 4px 0;"><strong><?php esc_html_e( 'Date de naissance :', 'candidature-echecs-briand' ); ?></strong> <?php echo esc_html( (string) $ddn_formattee ); ?></p>
			<p style="margin: 4px 0;"><strong><?php esc_html_e( 'Sexe :', 'candidature-echecs-briand' ); ?></strong> <?php echo esc_html( $sexe ); ?></p>
			<p style="margin: 4px 0;"><strong><?php esc_html_e( 'Établissement actuel :', 'candidature-echecs-briand' ); ?></strong> <?php echo esc_html( $ecole ); ?></p>
			<p style="margin: 4px 0;"><strong><?php esc_html_e( 'Classe :', 'candidature-echecs-briand' ); ?></strong> <?php echo esc_html( $classe ); ?></p>
			<p style="margin: 4px 0;"><strong><?php esc_html_e( 'LV1 :', 'candidature-echecs-briand' ); ?></strong> <?php echo esc_html( $lv1 ); ?></p>
			<p style="margin: 4px 0;"><strong><?php esc_html_e( 'LV2 :', 'candidature-echecs-briand' ); ?></strong> <?php echo esc_html( $lv2 ); ?></p>
			<p style="margin: 4px 0;"><strong><?php esc_html_e( 'Classe ciblée à la rentrée :', 'candidature-echecs-briand' ); ?></strong> <?php echo esc_html( $classe_cible ); ?></p>
		</div>
		<?php
	}
}