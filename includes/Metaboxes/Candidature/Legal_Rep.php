<?php
namespace CEB\Metaboxes\Candidature;

/**
 * Metabox : Représentant Légal (Lecture seule)
 */
class Legal_Rep {

	/**
	 * Initialisation de la metabox
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'add_meta_boxes', [ $this, 'add_box' ] );
	}

	/**
	 * Ajout de la metabox dans le CPT
	 *
	 * @return void
	 */
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

	/**
	 * Rendu HTML de la metabox (Lecture seule)
	 *
	 * @param \WP_Post $post L'objet Post courant.
	 * @return void
	 */
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
		<div class="ceb-metabox-content">
			<p style="margin: 4px 0;"><strong>Identité :</strong> <?php echo esc_html( $prenom . ' ' . $nom ); ?></p>
			<p style="margin: 4px 0;"><strong>Lien de parenté :</strong> <?php echo esc_html( $lien ); ?></p>

			<p style="margin: 4px 0;"><strong>Adresse :</strong><br>
			<?php echo esc_html( $adresse ); ?><br>
			<?php if ( ! empty( $cplt ) ) : ?>
				<?php echo esc_html( $cplt ); ?><br>
			<?php endif; ?>
			<?php echo esc_html( $cp . ' ' . $ville ); ?></p>

			<p style="margin: 4px 0;"><strong>Téléphone :</strong> <?php echo esc_html( $tel ); ?></p>
			<p style="margin: 4px 0;"><strong>Courriel :</strong> <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
		</div>
		<?php
	}
}