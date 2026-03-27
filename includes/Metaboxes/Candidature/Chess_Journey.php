<?php
namespace CEB\Metaboxes\Candidature;

class Chess_Journey {

	public function init() {
		add_action( 'add_meta_boxes', [ $this, 'add_box' ] );
	}

	public function add_box() {
		add_meta_box(
			'ceb_candidature_chess',
			'3. Parcours échiquéen & Motivation',
			[ $this, 'render' ],
			'ceb_candidature',
			'normal',
			'default'
		);
	}

	public function render( $post ) {
		$debut        = get_post_meta( $post->ID, '_ceb_echecs_debut', true );
		$club         = get_post_meta( $post->ID, '_ceb_echecs_club', true );
		$niveau       = get_post_meta( $post->ID, '_ceb_echecs_niveau', true );
		$competitions = get_post_meta( $post->ID, '_ceb_echecs_competitions', true );
		$titres       = get_post_meta( $post->ID, '_ceb_echecs_titres', true );

		$motivation_type = get_post_meta( $post->ID, '_ceb_motivation_type', true );
		?>
		<div class="ceb-metabox-content">
			<p style="margin: 4px 0;"><strong>Année de début :</strong> <?php echo esc_html( $debut ); ?></p>
			<p style="margin: 4px 0;"><strong>Club actuel :</strong> <?php echo esc_html( $club ?: 'Aucun' ); ?></p>
			<p style="margin: 4px 0;"><strong>Niveau :</strong> <?php echo esc_html( $niveau ); ?></p>

			<?php if ( $competitions ) : ?>
				<p style="margin: 4px 0;"><strong>Compétitions :</strong></p>
				<div style="margin-left: 10px; margin-bottom: 8px;"><?php echo wp_kses_post( wpautop( $competitions ) ); ?></div>
			<?php endif; ?>

			<?php if ( $titres ) : ?>
				<p style="margin: 4px 0;"><strong>Titres notables :</strong></p>
				<div style="margin-left: 10px; margin-bottom: 8px;"><?php echo wp_kses_post( wpautop( $titres ) ); ?></div>
			<?php endif; ?>

			<hr style="margin: 15px 0;">

			<p style="margin: 4px 0;"><strong>Motivation :</strong></p>
			<div style="margin-left: 10px; margin-bottom: 8px;">
				<?php if ( 'fichier' === $motivation_type ) : ?>
					<?php
					$fichier_id = get_post_meta( $post->ID, '_ceb_motivation_fichier_id', true );
					if ( $fichier_id ) {
						$url = wp_get_attachment_url( $fichier_id );
						if ( $url ) {
							printf(
								'<a href="%s" target="_blank" class="button button-primary" style="margin-top: 5px;">Télécharger la lettre de motivation</a>',
								esc_url( $url )
							);
						} else {
							echo '<em>Fichier introuvable.</em>';
						}
					} else {
						echo '<em>Aucun fichier joint.</em>';
					}
					?>
				<?php elseif ( 'texte' === $motivation_type ) : ?>
					<?php
					$texte = get_post_meta( $post->ID, '_ceb_motivation_texte', true );
					if ( $texte ) {
						echo wp_kses_post( wpautop( $texte ) );
					} else {
						echo '<em>Aucun texte fourni.</em>';
					}
					?>
				<?php else : ?>
					<em>Non renseignée.</em>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}