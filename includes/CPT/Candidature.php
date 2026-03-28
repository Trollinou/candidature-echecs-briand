<?php
namespace CEB\CPT;

class Candidature {

	public function init() {
		add_action( 'init', [ $this, 'register_cpt' ] );
		add_filter( 'manage_ceb_candidature_posts_columns', [ $this, 'set_custom_columns' ] );
		add_action( 'manage_ceb_candidature_posts_custom_column', [ $this, 'render_custom_columns' ], 10, 2 );
	}

	public function register_cpt() {
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

	public function set_custom_columns( $columns ) {
		$new_columns = [];
		$new_columns['cb'] = $columns['cb'];
		$new_columns['title'] = 'Candidat';
		$new_columns['rentree'] = 'Rentrée';
		$new_columns['classe'] = 'Classe demandée';
		$new_columns['niveau'] = 'Niveau Échecs';
		$new_columns['date'] = $columns['date'];
		return $new_columns;
	}

	public function render_custom_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'rentree':
				echo esc_html( get_post_meta( $post_id, '_ceb_target_year', true ) );
				break;
			case 'classe':
				echo esc_html( get_post_meta( $post_id, '_ceb_eleve_classe', true ) );
				break;
			case 'niveau':
				echo esc_html( get_post_meta( $post_id, '_ceb_echecs_niveau', true ) );
				break;
		}
	}
}