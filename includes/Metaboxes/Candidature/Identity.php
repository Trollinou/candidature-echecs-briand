<?php
namespace CEB\Metaboxes\Candidature;

class Identity {

	public function init() {
		add_action( 'add_meta_boxes', [ $this, 'add_box' ] );
	}

	public function add_box() {
		add_meta_box(
			'ceb_candidature_identity',
			'1. Identité de l\'élève',
			[ $this, 'render' ],
			'ceb_candidature',
			'normal',
			'high'
		);
	}

	public function render( $post ) {
		$nom    = get_post_meta( $post->ID, '_ceb_eleve_nom', true );
		$prenom = get_post_meta( $post->ID, '_ceb_eleve_prenom', true );
		$ddn    = get_post_meta( $post->ID, '_ceb_eleve_ddn', true );
		$sexe   = get_post_meta( $post->ID, '_ceb_eleve_sexe', true );
		$ecole  = get_post_meta( $post->ID, '_ceb_eleve_ecole', true );
		$classe = get_post_meta( $post->ID, '_ceb_eleve_classe', true );
		$lv1    = get_post_meta( $post->ID, '_ceb_eleve_lv1', true );
		$lv2    = get_post_meta( $post->ID, '_ceb_eleve_lv2', true );

		$ddn_formattee = $ddn ? wp_date( 'd/m/Y', strtotime( $ddn ) ) : '';
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
		</div>
		<?php
	}
}