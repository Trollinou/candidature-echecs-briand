<?php
namespace CEB\Services\Export;

/**
 * Gestionnaire de l'action d'exportation vers Excel/CSV
 */
class Manager {

	/**
	 * Initialisation des hooks d'exportation
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'restrict_manage_posts', [ $this, 'add_export_button' ] );
		add_action( 'admin_init', [ $this, 'process_export' ] );
	}

	/**
	 * Affiche le bouton d'exportation dans la liste des publications
	 *
	 * @param string $post_type Le type de publication actuel.
	 * @return void
	 */
	public function add_export_button( string $post_type ): void {
		if ( 'ceb_candidature' !== $post_type ) {
			return;
		}

		$url = add_query_arg( [
			'ceb_action' => 'export_csv',
			'_wpnonce'   => wp_create_nonce( 'ceb_export_csv' ),
		], admin_url( 'edit.php?post_type=ceb_candidature' ) );

		printf(
			'<a href="%s" class="button button-secondary" style="margin-left: 5px;">%s</a>',
			esc_url( $url ),
			esc_html__( 'Exporter vers Excel', 'candidature-echecs-briand' )
		);
	}

	/**
	 * Traite la requête d'exportation si les paramètres requis sont présents
	 *
	 * @return void
	 */
	public function process_export(): void {
		if ( ! isset( $_GET['ceb_action'] ) || 'export_csv' !== $_GET['ceb_action'] ) {
			return;
		}

		// Sécurité : Vérification des droits d'administration
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.', 'candidature-echecs-briand' ) );
		}

		// Sécurité : Vérification du Nonce
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'ceb_export_csv' ) ) {
			wp_die( esc_html__( 'Erreur de sécurité. Veuillez réessayer.', 'candidature-echecs-briand' ) );
		}

		// Lancement de l'exportation
		$csv_exporter = new CSV();
		$csv_exporter->generate();
	}
}
