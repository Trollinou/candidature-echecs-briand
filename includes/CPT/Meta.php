<?php
/**
 * Enregistrement typé des métadonnées du CPT Candidature pour WordPress 7.1+
 *
 * @package Candidature_Echecs_Briand
 */

namespace CEB\CPT;

/**
 * Classe gérant l'enregistrement formel des Post Meta de Candidature.
 */
class Meta {

	/**
	 * Initialise les hooks d'enregistrement des métadonnées.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'init', [ $this, 'register_meta' ] );
	}

	/**
	 * Enregistre formellement toutes les métadonnées de ceb_candidature.
	 *
	 * @return void
	 */
	public function register_meta(): void {
		$post_type = 'ceb_candidature';

		$string_meta_keys = [
			'_ceb_eleve_nom'           => 'Nom de l\'élève',
			'_ceb_eleve_prenom'        => 'Prénom de l\'élève',
			'_ceb_eleve_ddn'           => 'Date de naissance de l\'élève',
			'_ceb_eleve_sexe'          => 'Sexe de l\'élève',
			'_ceb_eleve_ecole'         => 'Établissement scolaire actuel',
			'_ceb_eleve_classe'        => 'Classe actuelle',
			'_ceb_eleve_classe_cible'  => 'Classe ciblée',
			'_ceb_eleve_lv1'           => 'Langue vivante 1',
			'_ceb_eleve_lv2'           => 'Langue vivante 2',
			'_ceb_legal_nom'           => 'Nom du responsable légal',
			'_ceb_legal_prenom'        => 'Prénom du responsable légal',
			'_ceb_legal_lien'          => 'Lien de parenté',
			'_ceb_legal_adresse'       => 'Adresse du responsable',
			'_ceb_legal_cplt'          => 'Complément d\'adresse',
			'_ceb_legal_cp'            => 'Code postal',
			'_ceb_legal_ville'         => 'Ville',
			'_ceb_legal_tel'           => 'Téléphone du responsable légal',
			'_ceb_legal2_nom'          => 'Nom du second responsable',
			'_ceb_legal2_prenom'       => 'Prénom du second responsable',
			'_ceb_legal2_lien'         => 'Lien de parenté du second responsable',
			'_ceb_legal2_adresse'      => 'Adresse du second responsable',
			'_ceb_legal2_cplt'         => 'Complément d\'adresse du second responsable',
			'_ceb_legal2_cp'           => 'Code postal du second responsable',
			'_ceb_legal2_ville'        => 'Ville du second responsable',
			'_ceb_legal2_tel'          => 'Téléphone du second responsable',
			'_ceb_echecs_club'         => 'Nom du club d\'échecs',
			'_ceb_echecs_niveau'       => 'Niveau d\'échecs',
			'_ceb_echecs_competitions' => 'Compétitions effectuées',
			'_ceb_echecs_titres'       => 'Titres échiquéens',
			'_ceb_motivation_type'     => 'Type de lettre de motivation',
			'_ceb_motivation_texte'    => 'Texte de motivation',
			'_ceb_target_year'         => 'Année scolaire cible',
		];

		foreach ( $string_meta_keys as $key => $description ) {
			register_post_meta(
				$post_type,
				$key,
				[
					'show_in_rest'      => [
						'schema' => [
							'type'        => 'string',
							'description' => $description,
						],
					],
					'single'            => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => [ $this, 'auth_edit_candidature' ],
				]
			);
		}

		// Email légal 1.
		register_post_meta(
			$post_type,
			'_ceb_legal_email',
			[
				'show_in_rest'      => [
					'schema' => [
						'type'        => 'string',
						'format'      => 'email',
						'description' => 'Email du responsable légal',
					],
				],
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_email',
				'auth_callback'     => [ $this, 'auth_edit_candidature' ],
			]
		);

		// Email légal 2.
		register_post_meta(
			$post_type,
			'_ceb_legal2_email',
			[
				'show_in_rest'      => [
					'schema' => [
						'type'        => 'string',
						'format'      => 'email',
						'description' => 'Email du second responsable',
					],
				],
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_email',
				'auth_callback'     => [ $this, 'auth_edit_candidature' ],
			]
		);

		// Année de début d'échecs (Entier).
		register_post_meta(
			$post_type,
			'_ceb_echecs_debut',
			[
				'show_in_rest'      => [
					'schema' => [
						'type'        => 'integer',
						'description' => 'Année de début des échecs',
					],
				],
				'single'            => true,
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
				'auth_callback'     => [ $this, 'auth_edit_candidature' ],
			]
		);

		// ID du fichier de motivation (Entier).
		register_post_meta(
			$post_type,
			'_ceb_motivation_fichier_id',
			[
				'show_in_rest'      => [
					'schema' => [
						'type'        => 'integer',
						'description' => 'ID du fichier média joint',
					],
				],
				'single'            => true,
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
				'auth_callback'     => [ $this, 'auth_edit_candidature' ],
			]
		);
	}

	/**
	 * Callback d'autorisation pour modifier les métadonnées.
	 *
	 * @param bool              $allowed  Autorisation par défaut.
	 * @param string            $meta_key Clé meta.
	 * @param int               $post_id  ID du post.
	 * @param int               $user_id  ID de l'utilisateur.
	 * @param string            $cap      Capacité requise.
	 * @param array<int, mixed> $caps     Capacités de l'utilisateur.
	 * @return bool
	 */
	public function auth_edit_candidature( bool $allowed, string $meta_key, int $post_id, int $user_id, string $cap, array $caps ): bool {
		// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		unset( $allowed, $meta_key, $user_id, $cap, $caps );
		return current_user_can( 'edit_post', $post_id );
	}
}
