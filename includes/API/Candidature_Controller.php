<?php
/**
 * Contrôleur REST API pour la gestion des candidatures.
 *
 * @package Candidature_Echecs_Briand
 */

namespace CEB\API;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Contrôleur REST pour la soumission des candidatures en WordPress 7.1+.
 */
class Candidature_Controller extends WP_REST_Controller {

	/**
	 * Namespace de l'API.
	 *
	 * @var string
	 */
	protected $namespace = 'ceb/v1';

	/**
	 * Ressource de base.
	 *
	 * @var string
	 */
	protected $rest_base = 'candidature';

	/**
	 * Initialisation et enregistrement des routes REST.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Enregistre les routes REST de l'extension.
	 *
	 * @return void
	 */
	public function register_routes(): void {
		$route_namespace = ! empty( $this->namespace ) ? $this->namespace : 'ceb/v1';

		register_rest_route(
			$route_namespace,
			'/' . $this->rest_base,
			[
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_item' ],
					'permission_callback' => [ $this, 'create_item_permissions_check' ],
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);
	}

	/**
	 * Vérifie les autorisations de soumission.
	 *
	 * @param WP_REST_Request $request La requête REST.
	 * @return bool|WP_Error
	 */
	public function create_item_permissions_check( $request ) {
		// Le formulaire public est accessible à tous mais protégé par un nonce anti-CSRF / anti-spam.
		$nonce = $request->get_param( 'ceb_application_nonce' );
		if ( empty( $nonce ) ) {
			$nonce = $request->get_header( 'x_wp_nonce' );
		}

		if ( ! empty( $nonce ) && ! wp_verify_nonce( (string) $nonce, 'ceb_submit_application' ) && ! wp_verify_nonce( (string) $nonce, 'wp_rest' ) ) {
			return new WP_Error(
				'rest_forbidden_nonce',
				__( 'Le jeton de sécurité est invalide ou a expiré.', 'candidature-echecs-briand' ),
				[ 'status' => 403 ]
			);
		}

		return true;
	}

	/**
	 * Traite la création d'une candidature via l'API REST.
	 *
	 * @param WP_REST_Request $request La requête REST.
	 * @return WP_REST_Response|WP_Error
	 */
	public function create_item( $request ) {
		$params = $request->get_params();

		$eleve_nom          = sanitize_text_field( (string) ( $params['ceb_eleve_nom'] ?? '' ) );
		$eleve_prenom       = sanitize_text_field( (string) ( $params['ceb_eleve_prenom'] ?? '' ) );
		$eleve_dob          = sanitize_text_field( (string) ( $params['ceb_eleve_dob'] ?? '' ) );
		$eleve_classe       = sanitize_text_field( (string) ( $params['ceb_eleve_classe'] ?? '' ) );
		$eleve_classe_cible = sanitize_text_field( (string) ( $params['ceb_eleve_classe_cible'] ?? '' ) );

		$legal_nom       = sanitize_text_field( (string) ( $params['ceb_legal_nom'] ?? '' ) );
		$legal_prenom    = sanitize_text_field( (string) ( $params['ceb_legal_prenom'] ?? '' ) );
		$legal_email     = sanitize_email( (string) ( $params['ceb_legal_email'] ?? '' ) );
		$legal_tel       = sanitize_text_field( (string) ( $params['ceb_legal_telephone'] ?? '' ) );
		$echecs_niveau   = sanitize_text_field( (string) ( $params['ceb_echecs_niveau'] ?? '' ) );
		$echecs_club     = sanitize_text_field( (string) ( $params['ceb_echecs_club'] ?? '' ) );
		$echecs_code_ffe = sanitize_text_field( (string) ( $params['ceb_echecs_code_ffe'] ?? '' ) );
		$echecs_elo      = isset( $params['ceb_echecs_elo'] ) ? absint( $params['ceb_echecs_elo'] ) : 0;
		$target_year     = sanitize_text_field( (string) ( $params['ceb_target_year'] ?? gmdate( 'Y' ) ) );

		// Validations obligatoires.
		if ( empty( $eleve_nom ) || empty( $eleve_prenom ) || empty( $eleve_dob ) || empty( $eleve_classe ) || empty( $eleve_classe_cible ) ) {
			return new WP_Error(
				'rest_missing_student_fields',
				__( 'Veuillez renseigner tous les champs obligatoires relatifs à l\'élève.', 'candidature-echecs-briand' ),
				[ 'status' => 400 ]
			);
		}

		if ( empty( $legal_nom ) || empty( $legal_prenom ) || empty( $legal_email ) || ! is_email( $legal_email ) || empty( $legal_tel ) ) {
			return new WP_Error(
				'rest_missing_legal_fields',
				__( 'Veuillez renseigner une adresse email et des coordonnées de responsable valides.', 'candidature-echecs-briand' ),
				[ 'status' => 400 ]
			);
		}

		if ( empty( $echecs_niveau ) ) {
			return new WP_Error(
				'rest_missing_chess_level',
				__( 'Veuillez sélectionner le niveau d\'échecs du candidat.', 'candidature-echecs-briand' ),
				[ 'status' => 400 ]
			);
		}

		// Création du CPT.
		$post_title = sprintf( '%s %s', $eleve_nom, $eleve_prenom );
		$post_id    = wp_insert_post(
			[
				'post_title'  => $post_title,
				'post_type'   => 'ceb_candidature',
				'post_status' => 'publish',
			]
		);

		if ( is_wp_error( $post_id ) || 0 === $post_id ) {
			return new WP_Error(
				'rest_post_creation_failed',
				__( 'Erreur lors de l\'enregistrement de la candidature.', 'candidature-echecs-briand' ),
				[ 'status' => 500 ]
			);
		}

		// Sauvegarde des métadonnées.
		update_post_meta( $post_id, '_ceb_eleve_nom', $eleve_nom );
		update_post_meta( $post_id, '_ceb_eleve_prenom', $eleve_prenom );
		update_post_meta( $post_id, '_ceb_eleve_dob', $eleve_dob );
		update_post_meta( $post_id, '_ceb_eleve_classe', $eleve_classe );
		update_post_meta( $post_id, '_ceb_eleve_classe_cible', $eleve_classe_cible );

		update_post_meta( $post_id, '_ceb_legal_nom', $legal_nom );
		update_post_meta( $post_id, '_ceb_legal_prenom', $legal_prenom );
		update_post_meta( $post_id, '_ceb_legal_email', $legal_email );
		update_post_meta( $post_id, '_ceb_legal_telephone', $legal_tel );

		update_post_meta( $post_id, '_ceb_echecs_niveau', $echecs_niveau );
		update_post_meta( $post_id, '_ceb_echecs_club', $echecs_club );
		update_post_meta( $post_id, '_ceb_echecs_code_ffe', $echecs_code_ffe );
		update_post_meta( $post_id, '_ceb_echecs_elo', $echecs_elo );
		update_post_meta( $post_id, '_ceb_target_year', $target_year );

		// Traitement du fichier joint si présent dans $_FILES.
		$files = $request->get_file_params();
		if ( ! empty( $files['ceb_motivation_fichier'] ) && is_array( $files['ceb_motivation_fichier'] ) && ! empty( $files['ceb_motivation_fichier']['name'] ) ) {
			/**
			 * Structure du fichier joint.
			 *
			 * @var array{name: string, type: string, tmp_name: string, error: int, size: int} $file
			 */
			$file = $files['ceb_motivation_fichier'];

			$allowed_mime = [
				'pdf'  => 'application/pdf',
				'png'  => 'image/png',
				'jpg'  => 'image/jpeg',
				'jpeg' => 'image/jpeg',
			];

			$file_name  = (string) $file['name'];
			$check_file = wp_check_filetype( $file_name, $allowed_mime );
			if ( ! $check_file['ext'] ) {
				wp_delete_post( $post_id, true );
				return new WP_Error(
					'rest_invalid_file_type',
					__( 'Le format du fichier joint n\'est pas autorisé (PDF, PNG ou JPG requis).', 'candidature-echecs-briand' ),
					[ 'status' => 400 ]
				);
			}

			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/image.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';
			}

			$upload = wp_handle_upload( $file, [ 'test_form' => false ] );

			if ( ! isset( $upload['error'] ) && isset( $upload['url'] ) ) {
				$attachment = [
					'post_mime_type' => (string) $upload['type'],
					'post_title'     => sanitize_file_name( $file_name ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				];

				$attach_id = wp_insert_attachment( $attachment, (string) $upload['file'], $post_id );
				if ( ! is_wp_error( $attach_id ) ) {
					update_post_meta( $post_id, '_ceb_motivation_fichier_id', $attach_id );
				}
			}
		}

		// Envoi des notifications email.
		$this->send_notifications( $post_id, $eleve_nom, $eleve_prenom, $legal_email );

		$response_data = [
			'success' => true,
			'id'      => $post_id,
			'message' => __( 'Votre dossier de candidature a été transmis avec succès. Un accusé de réception a été envoyé à votre adresse email.', 'candidature-echecs-briand' ),
		];

		$response = new WP_REST_Response( $response_data, 201 );
		return $response;
	}

	/**
	 * Envoie les emails de notification.
	 *
	 * @param int    $post_id      ID du dossier.
	 * @param string $eleve_nom    Nom élève.
	 * @param string $eleve_prenom Prénom élève.
	 * @param string $legal_email  Email parent.
	 * @return void
	 */
	protected function send_notifications( int $post_id, string $eleve_nom, string $eleve_prenom, string $legal_email ): void {
		$admin_email = get_option( 'admin_email' );
		$subject     = sprintf( 'Nouvelle candidature Section Échecs : %s %s', $eleve_nom, $eleve_prenom );
		$admin_url   = admin_url( 'post.php?post=' . $post_id . '&action=edit' );

		$body  = "Bonjour,\n\n";
		$body .= "Une nouvelle candidature vient d'être déposée pour la section sportive échecs.\n\n";
		$body .= sprintf( "Candidat : %s %s\n", $eleve_nom, $eleve_prenom );
		$body .= sprintf( "Consulter le dossier : %s\n\n", $admin_url );
		$body .= "Cordialement,\nSection Échecs Collège Briand";

		wp_mail( (string) $admin_email, $subject, $body );

		// Accusé de réception aux parents.
		$ack_subject = 'Accusé de réception - Candidature Section Sportive Échecs';
		$ack_body    = sprintf( "Bonjour,\n\nNous vous confirmons la bonne réception du dossier de candidature pour %s %s à la section sportive échecs du collège Briand.\n\nNotre commission examinera le dossier et vous recontactera prochainement.\n\nCordialement,\nL'équipe pédagogique du Collège Briand", $eleve_prenom, $eleve_nom );

		wp_mail( $legal_email, $ack_subject, $ack_body );
	}

	/**
	 * Schéma public de la ressource.
	 *
	 * @return array<string, mixed>
	 */
	public function get_public_item_schema(): array {
		return [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'candidature',
			'type'       => 'object',
			'properties' => [
				'id'      => [
					'description' => 'Identifiant unique de la candidature.',
					'type'        => 'integer',
					'context'     => [ 'view', 'edit' ],
					'readonly'    => true,
				],
				'success' => [
					'type'     => 'boolean',
					'readonly' => true,
				],
				'message' => [
					'type'     => 'string',
					'readonly' => true,
				],
			],
		];
	}
}
