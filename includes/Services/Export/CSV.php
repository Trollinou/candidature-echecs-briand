<?php
namespace CEB\Services\Export;

/**
 * Service de génération de fichier CSV pour l'export des candidatures
 */
class CSV {

	/**
	 * Génère et envoie le fichier CSV en téléchargement direct
	 *
	 * @return void
	 */
	public function generate(): void {
		// Récupérer toutes les candidatures (sans limite de nombre pour l'export)
		$query = new \WP_Query( [
			'post_type'      => 'ceb_candidature',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		] );

		$posts = $query->get_posts();

		// En-têtes HTTP pour déclencher le téléchargement
		$filename = 'candidatures-echecs-' . wp_date( 'Y-m-d-His' ) . '.csv';

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		// Ouvrir la sortie php://output
		$output = fopen( 'php://output', 'w' );
		if ( ! $output ) {
			return;
		}

		// UTF-8 BOM pour qu'Excel ouvre correctement le fichier avec les accents en français
		fwrite( $output, "\xEF\xBB\xBF" );

		// Définition des colonnes (à l'exception des champs de motivation)
		$headers = [
			'Date de soumission',
			'Année de rentrée',
			'Nom élève',
			'Prénom élève',
			'Date de naissance',
			'Sexe',
			'Établissement actuel',
			'Classe actuelle',
			'Classe ciblée',
			'LV1',
			'LV2',
			'Représentant 1 Nom',
			'Représentant 1 Prénom',
			'Représentant 1 Lien',
			'Représentant 1 Adresse',
			'Représentant 1 Complément',
			'Représentant 1 Code Postal',
			'Représentant 1 Ville',
			'Représentant 1 Téléphone',
			'Représentant 1 Email',
			'Représentant 2 Nom',
			'Représentant 2 Prénom',
			'Représentant 2 Lien',
			'Représentant 2 Adresse',
			'Représentant 2 Complément',
			'Représentant 2 Code Postal',
			'Représentant 2 Ville',
			'Représentant 2 Téléphone',
			'Représentant 2 Email',
			'Échecs Début',
			'Échecs Club actuel',
			'Échecs Niveau',
			'Échecs Compétitions',
			'Échecs Titres notables',
		];

		// Écrire les en-têtes avec le séparateur point-virgule (standard Excel français)
		fputcsv( $output, $headers, ';' );

		foreach ( $posts as $post ) {
			$post_id = $post->ID;

			// Extraction propre des métadonnées
			$row = [
				get_the_date( 'd/m/Y H:i:s', $post_id ),
				get_post_meta( $post_id, '_ceb_target_year', true ),
				get_post_meta( $post_id, '_ceb_eleve_nom', true ),
				get_post_meta( $post_id, '_ceb_eleve_prenom', true ),
				get_post_meta( $post_id, '_ceb_eleve_ddn', true ),
				get_post_meta( $post_id, '_ceb_eleve_sexe', true ),
				get_post_meta( $post_id, '_ceb_eleve_ecole', true ),
				get_post_meta( $post_id, '_ceb_eleve_classe', true ),
				get_post_meta( $post_id, '_ceb_eleve_classe_cible', true ),
				get_post_meta( $post_id, '_ceb_eleve_lv1', true ),
				get_post_meta( $post_id, '_ceb_eleve_lv2', true ),
				get_post_meta( $post_id, '_ceb_legal_nom', true ),
				get_post_meta( $post_id, '_ceb_legal_prenom', true ),
				get_post_meta( $post_id, '_ceb_legal_lien', true ),
				get_post_meta( $post_id, '_ceb_legal_adresse', true ),
				get_post_meta( $post_id, '_ceb_legal_cplt', true ),
				get_post_meta( $post_id, '_ceb_legal_cp', true ),
				get_post_meta( $post_id, '_ceb_legal_ville', true ),
				get_post_meta( $post_id, '_ceb_legal_tel', true ),
				get_post_meta( $post_id, '_ceb_legal_email', true ),
				get_post_meta( $post_id, '_ceb_legal2_nom', true ),
				get_post_meta( $post_id, '_ceb_legal2_prenom', true ),
				get_post_meta( $post_id, '_ceb_legal2_lien', true ),
				get_post_meta( $post_id, '_ceb_legal2_adresse', true ),
				get_post_meta( $post_id, '_ceb_legal2_cplt', true ),
				get_post_meta( $post_id, '_ceb_legal2_cp', true ),
				get_post_meta( $post_id, '_ceb_legal2_ville', true ),
				get_post_meta( $post_id, '_ceb_legal2_tel', true ),
				get_post_meta( $post_id, '_ceb_legal2_email', true ),
				get_post_meta( $post_id, '_ceb_echecs_debut', true ),
				get_post_meta( $post_id, '_ceb_echecs_club', true ),
				get_post_meta( $post_id, '_ceb_echecs_niveau', true ),
				wp_strip_all_tags( (string) get_post_meta( $post_id, '_ceb_echecs_competitions', true ) ),
				wp_strip_all_tags( (string) get_post_meta( $post_id, '_ceb_echecs_titres', true ) ),
			];

			// Nettoyer d'éventuels retours à la ligne ou conversions
			$row = array_map( function ( $value ) {
				return str_replace( [ "\r", "\n" ], ' ', (string) $value );
			}, $row );

			fputcsv( $output, $row, ';' );
		}

		fclose( $output );
		exit;
	}
}
