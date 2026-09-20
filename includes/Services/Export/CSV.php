<?php
/**
 * Service d'export CSV pour les candidatures.
 *
 * @package Candidature_Echecs_Briand
 */

namespace CEB\Services\Export;

use WP_Post;
use WP_Query;

/**
 * Service de génération de fichier CSV pour l'export des candidatures.
 */
class CSV {

	/**
	 * Génère et envoie le fichier CSV en téléchargement direct.
	 *
	 * @return void
	 */
	public function generate(): void {
		// Récupérer toutes les candidatures (sans limite de nombre pour l'export).
		$query = new WP_Query(
			[
				'post_type'      => 'ceb_candidature',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			]
		);

		/**
		 * Liste des posts.
		 *
		 * @var array<int, WP_Post> $posts
		 */
		$posts = $query->get_posts();

		// En-têtes HTTP pour déclencher le téléchargement.
		$filename = 'candidatures-echecs-' . wp_date( 'Y-m-d-His' ) . '.csv';

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		// Ouvrir le flux de sortie php://output.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$output = fopen( 'php://output', 'w' );
		if ( ! $output ) {
			return;
		}

		// UTF-8 BOM pour qu'Excel ouvre correctement le fichier avec les accents en français.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
		fwrite( $output, "\xEF\xBB\xBF" );

		// Définition des colonnes (à l'exception des champs de motivation).
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

		// Écrire les en-têtes avec le séparateur point-virgule (standard Excel français).
		fputcsv( $output, $headers, ';' );

		foreach ( $posts as $post ) {
			$post_id = $post->ID;

			// Extraction propre des métadonnées.
			$row = [
				(string) get_the_date( 'd/m/Y H:i:s', $post_id ),
				(string) get_post_meta( $post_id, '_ceb_target_year', true ),
				(string) get_post_meta( $post_id, '_ceb_eleve_nom', true ),
				(string) get_post_meta( $post_id, '_ceb_eleve_prenom', true ),
				(string) get_post_meta( $post_id, '_ceb_eleve_ddn', true ),
				(string) get_post_meta( $post_id, '_ceb_eleve_sexe', true ),
				(string) get_post_meta( $post_id, '_ceb_eleve_ecole', true ),
				(string) get_post_meta( $post_id, '_ceb_eleve_classe', true ),
				(string) get_post_meta( $post_id, '_ceb_eleve_classe_cible', true ),
				(string) get_post_meta( $post_id, '_ceb_eleve_lv1', true ),
				(string) get_post_meta( $post_id, '_ceb_eleve_lv2', true ),
				(string) get_post_meta( $post_id, '_ceb_legal_nom', true ),
				(string) get_post_meta( $post_id, '_ceb_legal_prenom', true ),
				(string) get_post_meta( $post_id, '_ceb_legal_lien', true ),
				(string) get_post_meta( $post_id, '_ceb_legal_adresse', true ),
				(string) get_post_meta( $post_id, '_ceb_legal_cplt', true ),
				(string) get_post_meta( $post_id, '_ceb_legal_cp', true ),
				(string) get_post_meta( $post_id, '_ceb_legal_ville', true ),
				(string) get_post_meta( $post_id, '_ceb_legal_tel', true ),
				(string) get_post_meta( $post_id, '_ceb_legal_email', true ),
				(string) get_post_meta( $post_id, '_ceb_legal2_nom', true ),
				(string) get_post_meta( $post_id, '_ceb_legal2_prenom', true ),
				(string) get_post_meta( $post_id, '_ceb_legal2_lien', true ),
				(string) get_post_meta( $post_id, '_ceb_legal2_adresse', true ),
				(string) get_post_meta( $post_id, '_ceb_legal2_cplt', true ),
				(string) get_post_meta( $post_id, '_ceb_legal2_cp', true ),
				(string) get_post_meta( $post_id, '_ceb_legal2_ville', true ),
				(string) get_post_meta( $post_id, '_ceb_legal2_tel', true ),
				(string) get_post_meta( $post_id, '_ceb_legal2_email', true ),
				(string) get_post_meta( $post_id, '_ceb_echecs_debut', true ),
				(string) get_post_meta( $post_id, '_ceb_echecs_club', true ),
				(string) get_post_meta( $post_id, '_ceb_echecs_niveau', true ),
				wp_strip_all_tags( (string) get_post_meta( $post_id, '_ceb_echecs_competitions', true ) ),
				wp_strip_all_tags( (string) get_post_meta( $post_id, '_ceb_echecs_titres', true ) ),
			];

			// Nettoyer d'éventuels retours à la ligne ou conversions.
			$row = array_map(
				function ( string $value ): string {
					return str_replace( [ "\r", "\n" ], ' ', $value );
				},
				$row
			);

			fputcsv( $output, $row, ';' );
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		fclose( $output );
		exit;
	}
}
