<?php
namespace CEB\CPT;

/**
 * Gestion du Custom Post Type "Candidature"
 */
class Candidature {

	/**
	 * Initialisation du CPT et de ses colonnes
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'init', [ $this, 'register_cpt' ] );
		add_filter( 'manage_ceb_candidature_posts_columns', [ $this, 'set_custom_columns' ] );
		add_action( 'manage_ceb_candidature_posts_custom_column', [ $this, 'render_custom_columns' ], 10, 2 );
		add_action( 'restrict_manage_posts', [ $this, 'restrict_manage_posts' ] );
		add_action( 'pre_get_posts', [ $this, 'filter_by_year' ] );
		add_action( 'before_delete_post', [ $this, 'delete_associated_file' ] );
	}

	/**
	 * Enregistrement du type de publication personnalisé "ceb_candidature"
	 *
	 * @return void
	 */
	public function register_cpt(): void {
		$labels = [
			'name'                  => 'Candidatures',
			'singular_name'         => 'Candidature',
			'menu_name'             => 'Candidatures Échecs',
			'all_items'             => 'Toutes les candidatures',
			'not_found'             => 'Aucune candidature trouvée',
		];

		$args = [
			'label'                 => 'Candidature',
			'labels'                => $labels,
			'supports'              => [ 'title' ],
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 25,
			'menu_icon'             => 'dashicons-welcome-learn-more',
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'capabilities'          => [
				'create_posts' => 'do_not_allow',
			],
			'map_meta_cap'          => true,
		];

		register_post_type( 'ceb_candidature', $args );
	}

	/**
	 * Définition des colonnes personnalisées pour la liste des candidatures
	 *
	 * @param array<string, string> $columns Les colonnes par défaut.
	 * @return array<string, string> Les colonnes modifiées.
	 */
	public function set_custom_columns( array $columns ): array {
		$new_columns = [];
		$new_columns['cb'] = $columns['cb'];
		$new_columns['title'] = 'Candidat';
		$new_columns['rentree'] = 'Rentrée';
		$new_columns['classe'] = 'Classe demandée';
		$new_columns['classe_cible'] = 'Classe ciblée';
		$new_columns['niveau'] = 'Niveau Échecs';
		$new_columns['date'] = $columns['date'];
		return $new_columns;
	}

	/**
	 * Affichage du contenu des colonnes personnalisées
	 *
	 * @param string $column  L'identifiant de la colonne.
	 * @param int    $post_id L'ID de la publication.
	 * @return void
	 */
	public function render_custom_columns( string $column, int $post_id ): void {
		switch ( $column ) {
			case 'rentree':
				echo esc_html( (string) get_post_meta( $post_id, '_ceb_target_year', true ) );
				break;
			case 'classe':
				echo esc_html( (string) get_post_meta( $post_id, '_ceb_eleve_classe', true ) );
				break;
			case 'classe_cible':
				echo esc_html( (string) get_post_meta( $post_id, '_ceb_eleve_classe_cible', true ) );
				break;
			case 'niveau':
				echo esc_html( (string) get_post_meta( $post_id, '_ceb_echecs_niveau', true ) );
				break;
		}
	}

	/**
	 * Ajoute le menu déroulant de filtre par année cible.
	 *
	 * @param string $post_type Le type de publication actuel.
	 * @return void
	 */
	public function restrict_manage_posts( string $post_type ): void {
		if ( 'ceb_candidature' !== $post_type ) {
			return;
		}

		$current_year = date( 'Y' );
		$selected     = isset( $_GET['ceb_filter_year'] ) ? sanitize_text_field( $_GET['ceb_filter_year'] ) : $current_year;

		// Utiliser $wpdb pour récupérer les années existantes.
		global $wpdb;
		$years = $wpdb->get_col( $wpdb->prepare(
			"SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s ORDER BY meta_value DESC",
			'_ceb_target_year'
		) );

		if ( empty( $years ) ) {
			$years = [ $current_year, (string) ( (int) $current_year + 1 ) ];
		} else {
			// S'assurer que l'année courante est toujours dans la liste
			if ( ! in_array( $current_year, $years, true ) ) {
				$years[] = $current_year;
			}
			rsort( $years );
		}

		echo '<select name="ceb_filter_year" id="ceb_filter_year">';
		echo '<option value="">' . esc_html__( 'Toutes les années', 'candidature-echecs-briand' ) . '</option>';
		foreach ( $years as $year ) {
			printf(
				'<option value="%1$s" %2$s>%1$s</option>',
				esc_attr( $year ),
				selected( $selected, $year, false )
			);
		}
		echo '</select>';
	}

	/**
	 * Filtre la requête principale en fonction de l'année sélectionnée.
	 *
	 * @param \WP_Query $query L'objet requête de WordPress.
	 * @return void
	 */
	public function filter_by_year( \WP_Query $query ): void {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( 'ceb_candidature' !== $query->get( 'post_type' ) ) {
			return;
		}

		$current_year = date( 'Y' );
		$filter_year  = isset( $_GET['ceb_filter_year'] ) ? sanitize_text_field( $_GET['ceb_filter_year'] ) : $current_year;

		if ( ! empty( $filter_year ) ) {
			$meta_query = $query->get( 'meta_query' );
			if ( ! is_array( $meta_query ) ) {
				$meta_query = [];
			}

			$meta_query[] = [
				'key'     => '_ceb_target_year',
				'value'   => $filter_year,
				'compare' => '=',
			];

			$query->set( 'meta_query', $meta_query );
		}
	}

	/**
	 * Supprime physiquement le fichier de motivation attaché lors de la suppression de la candidature.
	 *
	 * @param int $post_id L'ID de la publication en cours de suppression.
	 * @return void
	 */
	public function delete_associated_file( int $post_id ): void {
		if ( 'ceb_candidature' !== get_post_type( $post_id ) ) {
			return;
		}

		$attachment_id = get_post_meta( $post_id, '_ceb_motivation_fichier_id', true );

		if ( ! empty( $attachment_id ) ) {
			wp_delete_attachment( (int) $attachment_id, true );
		}
	}
}