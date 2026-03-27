<?php
namespace CEB\Metaboxes\Candidature;

class Legal_Rep {

	public function init() {
		add_action( 'add_meta_boxes', [ $this, 'add_box' ] );
	}

	public function add_box() {
		add_meta_box(
			'ceb_candidature_legal_rep',
			'2. Représentant légal',
			[ $this, 'render' ],
			'ceb_candidature',
			'normal',
			'default'
		);
	}

	public function render( $post ) {
		$nom     = get_post_meta( $post->ID, '_ceb_legal_nom', true );
		$prenom  = get_post_meta( $post->ID, '_ceb_legal_prenom', true );
		$lien    = get_post_meta( $post->ID, '_ceb_legal_lien', true );
		$adresse = get_post_meta( $post->ID, '_ceb_legal_adresse', true );
		$cplt    = get_post_meta( $post->ID, '_ceb_legal_cplt', true );
		$cp      = get_post_meta( $post->ID, '_ceb_legal_cp', true );
		$ville   = get_post_meta( $post->ID, '_ceb_legal_ville', true );
		$tel     = get_post_meta( $post->ID, '_ceb_legal_tel', true );
		$email   = get_post_meta( $post->ID, '_ceb_legal_email', true );
		?>
		<table class="form-table">
			<tr>
				<th><strong>Identité :</strong></th>
				<td><?php echo esc_html( $prenom . ' ' . $nom ); ?></td>
			</tr>
			<tr>
				<th><strong>Lien de parenté :</strong></th>
				<td><?php echo esc_html( $lien ); ?></td>
			</tr>
			<tr>
				<th><strong>Adresse :</strong></th>
				<td>
					<p><?php echo esc_html( $adresse ); ?></p>
					<?php if ( ! empty( $cplt ) ) : ?>
						<p><?php echo esc_html( $cplt ); ?></p>
					<?php endif; ?>
					<p><?php echo esc_html( $cp . ' ' . $ville ); ?></p>
				</td>
			</tr>
			<tr>
				<th><strong>Téléphone :</strong></th>
				<td><?php echo esc_html( $tel ); ?></td>
			</tr>
			<tr>
				<th><strong>Courriel :</strong></th>
				<td><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></td>
			</tr>
		</table>
		<?php
	}
}