<?php
namespace CEB\Shortcodes\Application_Form;

/**
 * Composant de traitement de la soumission du formulaire de candidature
 */
class Handler {

	/**
	 * Initialisation des hooks de traitement
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'admin_post_nopriv_ceb_submit_application', [ $this, 'process_form' ] );
		add_action( 'admin_post_ceb_submit_application', [ $this, 'process_form' ] );
	}

	/**
	 * Traitement des données soumises par le formulaire
	 *
	 * @return void
	 */
	public function process_form(): void {
		if ( ! isset( $_POST['ceb_application_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ceb_application_nonce'] ) ), 'ceb_submit_application' ) ) {
			wp_die( 'Erreur de sécurité. Veuillez réessayer.' );
		}

		$nom       = isset( $_POST['ceb_eleve_nom'] ) ? mb_strtoupper( sanitize_text_field( wp_unslash( $_POST['ceb_eleve_nom'] ) ) ) : '';
		$prenom    = isset( $_POST['ceb_eleve_prenom'] ) ? mb_convert_case( sanitize_text_field( wp_unslash( $_POST['ceb_eleve_prenom'] ) ), MB_CASE_TITLE, 'UTF-8' ) : '';
		$classe    = isset( $_POST['ceb_eleve_classe'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_eleve_classe'] ) ) : '';

		// Logique de rentrée
		$current_month = (int) date( 'n' );
		$current_year  = (int) date( 'Y' );
		if ( $current_month >= 3 && $current_month <= 5 ) {
			$target_year = $current_year;
		} else {
			$target_year = $current_year + 1;
		}

		// Titre du Post
		$post_title = sprintf( '[%s] %s %s - %s', $target_year, $nom, $prenom, $classe );

		// Création du CPT
		$post_id = wp_insert_post( [
			'post_title'  => $post_title,
			'post_type'   => 'ceb_candidature',
			'post_status' => 'publish',
		] );

		if ( $post_id === 0 ) {
			wp_die( 'Erreur lors de la création de la candidature.' );
		}

		// Sauvegarde de toutes les metas
		$meta_mapping = [
			'_ceb_eleve_nom'           => $nom,
			'_ceb_eleve_prenom'        => $prenom,
			'_ceb_eleve_ddn'           => isset( $_POST['ceb_eleve_ddn'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_eleve_ddn'] ) ) : '',
			'_ceb_eleve_sexe'          => isset( $_POST['ceb_eleve_sexe'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_eleve_sexe'] ) ) : '',
			'_ceb_eleve_ecole'         => isset( $_POST['ceb_eleve_ecole'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_eleve_ecole'] ) ) : '',
			'_ceb_eleve_classe'        => $classe,
			'_ceb_eleve_lv1'           => isset( $_POST['ceb_eleve_lv1'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_eleve_lv1'] ) ) : '',
			'_ceb_eleve_lv2'           => isset( $_POST['ceb_eleve_lv2'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_eleve_lv2'] ) ) : '',

			'_ceb_legal_nom'           => isset( $_POST['ceb_legal_nom'] ) ? mb_strtoupper( sanitize_text_field( wp_unslash( $_POST['ceb_legal_nom'] ) ) ) : '',
			'_ceb_legal_prenom'        => isset( $_POST['ceb_legal_prenom'] ) ? mb_convert_case( sanitize_text_field( wp_unslash( $_POST['ceb_legal_prenom'] ) ), MB_CASE_TITLE, 'UTF-8' ) : '',
			'_ceb_legal_lien'          => isset( $_POST['ceb_legal_lien'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_legal_lien'] ) ) : '',
			'_ceb_legal_adresse'       => isset( $_POST['ceb_legal_adresse'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_legal_adresse'] ) ) : '',
			'_ceb_legal_cplt'          => isset( $_POST['ceb_legal_cplt'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_legal_cplt'] ) ) : '',
			'_ceb_legal_cp'            => isset( $_POST['ceb_legal_cp'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_legal_cp'] ) ) : '',
			'_ceb_legal_ville'         => isset( $_POST['ceb_legal_ville'] ) ? mb_strtoupper( sanitize_text_field( wp_unslash( $_POST['ceb_legal_ville'] ) ) ) : '',
			'_ceb_legal_tel'           => isset( $_POST['ceb_legal_tel'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_legal_tel'] ) ) : '',
			'_ceb_legal_email'         => isset( $_POST['ceb_legal_email'] ) ? sanitize_email( wp_unslash( $_POST['ceb_legal_email'] ) ) : '',

			'_ceb_echecs_debut'        => isset( $_POST['ceb_echecs_debut'] ) ? absint( $_POST['ceb_echecs_debut'] ) : '',
			'_ceb_echecs_club'         => isset( $_POST['ceb_echecs_club'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_echecs_club'] ) ) : '',
			'_ceb_echecs_niveau'       => isset( $_POST['ceb_echecs_niveau'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_echecs_niveau'] ) ) : '',
			'_ceb_echecs_competitions' => isset( $_POST['ceb_echecs_competitions'] ) ? wp_kses_post( wp_unslash( $_POST['ceb_echecs_competitions'] ) ) : '',
			'_ceb_echecs_titres'       => isset( $_POST['ceb_echecs_titres'] ) ? wp_kses_post( wp_unslash( $_POST['ceb_echecs_titres'] ) ) : '',

			'_ceb_target_year'         => (string) $target_year,
		];

		// Gestion de la motivation
		$motivation_type = isset( $_POST['ceb_motivation_type'] ) ? sanitize_text_field( wp_unslash( $_POST['ceb_motivation_type'] ) ) : 'fichier';
		$meta_mapping['_ceb_motivation_type'] = $motivation_type;

		$motivation_file_error = false;
		if ( 'fichier' === $motivation_type ) {
			if ( ! empty( $_FILES['ceb_motivation_fichier']['name'] ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';

				// Validation du type de fichier
				$file_name          = sanitize_text_field( wp_unslash( $_FILES['ceb_motivation_fichier']['name'] ) );
				$file_type          = wp_check_filetype( $file_name );
				$allowed_extensions = [ 'pdf', 'doc', 'docx' ];

				if ( ! in_array( strtolower( (string) $file_type['ext'] ), $allowed_extensions, true ) ) {
					wp_delete_post( $post_id, true );
					wp_die( 'Type de fichier non autorisé. Veuillez télécharger un fichier PDF, DOC ou DOCX.' );
				}

				$attachment_id = media_handle_upload( 'ceb_motivation_fichier', $post_id );
				if ( is_wp_error( $attachment_id ) ) {
					wp_delete_post( $post_id, true );
					wp_die( 'Erreur lors du téléchargement du fichier de motivation : ' . esc_html( $attachment_id->get_error_message() ) );
				}
				$meta_mapping['_ceb_motivation_fichier_id'] = $attachment_id;
			} else {
				$motivation_file_error = true;
			}
		} elseif ( 'texte' === $motivation_type ) {
			$meta_mapping['_ceb_motivation_texte'] = isset( $_POST['ceb_motivation_texte'] ) ? wp_kses_post( wp_unslash( $_POST['ceb_motivation_texte'] ) ) : '';
		}

		if ( $motivation_file_error ) {
			wp_delete_post( $post_id, true );
			wp_die( 'Le fichier de motivation est requis.' );
		}

		// Bulk Insert Metas
		global $wpdb;
		$values = [];
		$placeholders = [];

		foreach ( $meta_mapping as $meta_key => $meta_value ) {
			$placeholders[] = '(%d, %s, %s)';
			$values[] = $post_id;
			$values[] = $meta_key;
			$values[] = $meta_value;
		}

		if ( ! empty( $values ) ) {
			$query = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES " . implode( ', ', $placeholders );
			$wpdb->query( $wpdb->prepare( $query, $values ) );
		}

		// Invalidation du cache post meta
		if ( function_exists( 'clean_post_cache' ) ) {
			clean_post_cache( $post_id );
		}

		// Redirection
		$redirect_url = wp_get_referer() ?: home_url();
		$redirect_url = add_query_arg( 'success', '1', $redirect_url );

		// Ensure it points to the current page by stripping query string and rebuilding if necessary, or just rely on referer
		wp_safe_redirect( $redirect_url );
		exit;
	}
}