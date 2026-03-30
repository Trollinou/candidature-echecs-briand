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
	public function init(): void {
		add_action( 'add_meta_boxes', [ $this, 'add_box' ] );
	}

	/**
	 * Ajout de la metabox dans le CPT
	 *
	 * @return void
	 */
	public function add_box():void {
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
	public function render( \WP_Post $post ): void {
		$nom     = (string) get_post_meta( $post->ID, '_ceb_legal_nom', true );
		$prenom  = (string) get_post_meta( $post->ID, '_ceb_legal_prenom', true );
		$lien    = (string) get_post_meta( $post->ID, '_ceb_legal_lien', true );
		$adresse = (string) get_post_meta( $post->ID, '_ceb_legal_adresse', true );
		$cplt    = (string) get_post_meta( $post->ID, '_ceb_legal_cplt', true );
		$cp      = (string) get_post_meta( $post->ID, '_ceb_legal_cp', true );
		$ville   = (string) get_post_meta( $post->ID, '_ceb_legal_ville', true );
		$tel     = (string) get_post_meta( $post->ID, '_ceb_legal_tel', true );
		$email   = (string) get_post_meta( $post->ID, '_ceb_legal_email', true );

		$nom2     = (string) get_post_meta( $post->ID, '_ceb_legal2_nom', true );
		$prenom2  = (string) get_post_meta( $post->ID, '_ceb_legal2_prenom', true );
		$lien2    = (string) get_post_meta( $post->ID, '_ceb_legal2_lien', true );
		$adresse2 = (string) get_post_meta( $post->ID, '_ceb_legal2_adresse', true );
		$cplt2    = (string) get_post_meta( $post->ID, '_ceb_legal2_cplt', true );
		$cp2      = (string) get_post_meta( $post->ID, '_ceb_legal2_cp', true );
		$ville2   = (string) get_post_meta( $post->ID, '_ceb_legal2_ville', true );
		$tel2     = (string) get_post_meta( $post->ID, '_ceb_legal2_tel', true );
		$email2   = (string) get_post_meta( $post->ID, '_ceb_legal2_email', true );
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

			<?php if ( ! empty( $nom2 ) ) : ?>
				<hr>
				<h4 style="margin: 8px 0 4px 0;">Second Représentant Légal</h4>
				<p style="margin: 4px 0;"><strong>Identité :</strong> <?php echo esc_html( $prenom2 . ' ' . $nom2 ); ?></p>
				<p style="margin: 4px 0;"><strong>Lien de parenté :</strong> <?php echo esc_html( $lien2 ); ?></p>

				<p style="margin: 4px 0;"><strong>Adresse :</strong><br>
				<?php echo esc_html( $adresse2 ); ?><br>
				<?php if ( ! empty( $cplt2 ) ) : ?>
					<?php echo esc_html( $cplt2 ); ?><br>
				<?php endif; ?>
				<?php echo esc_html( $cp2 . ' ' . $ville2 ); ?></p>

				<p style="margin: 4px 0;"><strong>Téléphone :</strong> <?php echo esc_html( $tel2 ); ?></p>
				<p style="margin: 4px 0;"><strong>Courriel :</strong> <a href="mailto:<?php echo esc_attr( $email2 ); ?>"><?php echo esc_html( $email2 ); ?></a></p>
			<?php endif; ?>
		</div>
		<?php
	}
}