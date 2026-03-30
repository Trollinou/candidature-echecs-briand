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
			case 'niveau':
				echo esc_html( (string) get_post_meta( $post_id, '_ceb_echecs_niveau', true ) );
				break;
		}
	}
}