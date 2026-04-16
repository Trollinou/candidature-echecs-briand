<?php
namespace CEB\Tests\Metaboxes\Candidature;

use PHPUnit\Framework\TestCase;
use CEB\Metaboxes\Candidature\Identity;
use WP_Post;

/**
 * Test class for CEB\Metaboxes\Candidature\Identity
 */
class IdentityTest extends TestCase {

	/**
	 * Setup method
	 */
	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['wp_actions']   = [];
		$GLOBALS['wp_meta_boxes'] = [];
		$GLOBALS['wp_post_meta']  = [];
	}

	/**
	 * Test init method
	 */
	public function test_init(): void {
		$identity = new Identity();
		$identity->init();

		$this->assertArrayHasKey( 'add_meta_boxes', $GLOBALS['wp_actions'] );
		$this->assertEquals( [ $identity, 'add_box' ], $GLOBALS['wp_actions']['add_meta_boxes'][0]['callback'] );
	}

	/**
	 * Test add_box method
	 */
	public function test_add_box(): void {
		$identity = new Identity();
		$identity->add_box();

		$this->assertArrayHasKey( 'ceb_candidature', $GLOBALS['wp_meta_boxes'] );
		$this->assertArrayHasKey( 'normal', $GLOBALS['wp_meta_boxes']['ceb_candidature'] );
		$this->assertArrayHasKey( 'high', $GLOBALS['wp_meta_boxes']['ceb_candidature']['normal'] );
		$this->assertArrayHasKey( 'ceb_candidature_identity', $GLOBALS['wp_meta_boxes']['ceb_candidature']['normal']['high'] );

		$meta_box = $GLOBALS['wp_meta_boxes']['ceb_candidature']['normal']['high']['ceb_candidature_identity'];
		$this->assertEquals( '1. Identité de l\'élève', $meta_box['title'] );
		$this->assertEquals( [ $identity, 'render' ], $meta_box['callback'] );
	}

	/**
	 * Test render method
	 */
	public function test_render(): void {
		$post_id = 123;
		$post    = new WP_Post( $post_id );

		$GLOBALS['wp_post_meta'][$post_id] = [
			'_ceb_eleve_nom'          => 'DOE',
			'_ceb_eleve_prenom'       => 'John',
			'_ceb_eleve_ddn'          => '2015-05-20',
			'_ceb_eleve_sexe'         => 'M',
			'_ceb_eleve_ecole'        => 'School Name',
			'_ceb_eleve_classe'       => 'CM2',
			'_ceb_eleve_lv1'          => 'Anglais',
			'_ceb_eleve_lv2'          => 'Espagnol',
			'_ceb_eleve_classe_cible' => '6ème',
		];

		$identity = new Identity();

		ob_start();
		$identity->render( $post );
		$output = ob_get_clean();

		$this->assertStringContainsString( '<strong>Nom :</strong> DOE', $output );
		$this->assertStringContainsString( '<strong>Prénom :</strong> John', $output );
		$this->assertStringContainsString( '<strong>Date de naissance :</strong> 20/05/2015', $output );
		$this->assertStringContainsString( '<strong>Sexe :</strong> M', $output );
		$this->assertStringContainsString( '<strong>Établissement actuel :</strong> School Name', $output );
		$this->assertStringContainsString( '<strong>Classe :</strong> CM2', $output );
		$this->assertStringContainsString( '<strong>LV1 :</strong> Anglais', $output );
		$this->assertStringContainsString( '<strong>LV2 :</strong> Espagnol', $output );
		$this->assertStringContainsString( '<strong>Classe ciblée à la rentrée :</strong> 6ème', $output );
	}
}
