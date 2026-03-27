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
		?>
		<table class="form-table">
			<tr>
				<th><strong>Nom :</strong></th>
				<td><?php echo esc_html( $nom ); ?></td>
			</tr>
			<tr>
				<th><strong>Prénom :</strong></th>
				<td><?php echo esc_html( $prenom ); ?></td>
			</tr>
			<tr>
				<th><strong>Date de naissance :</strong></th>
				<td><?php echo esc_html( $ddn ); ?></td>
			</tr>
			<tr>
				<th><strong>Sexe :</strong></th>
				<td><?php echo esc_html( $sexe ); ?></td>
			</tr>
			<tr>
				<th><strong>Établissement actuel :</strong></th>
				<td><?php echo esc_html( $ecole ); ?></td>
			</tr>
			<tr>
				<th><strong>Classe :</strong></th>
				<td><?php echo esc_html( $classe ); ?></td>
			</tr>
			<tr>
				<th><strong>LV1 :</strong></th>
				<td><?php echo esc_html( $lv1 ); ?></td>
			</tr>
			<tr>
				<th><strong>LV2 :</strong></th>
				<td><?php echo esc_html( $lv2 ); ?></td>
			</tr>
		</table>
		<?php
	}
}