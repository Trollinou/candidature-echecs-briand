<?php
/**
 * Composant de rendu HTML du formulaire de candidature.
 *
 * @package Candidature_Echecs_Briand
 */

namespace CEB\Shortcodes\Application_Form;

/**
 * Composant de rendu HTML du formulaire de candidature.
 */
class Render {

	/**
	 * Affiche le formulaire en utilisant un buffer de sortie.
	 *
	 * @return string Le code HTML généré.
	 */
	public function render(): string {
		ob_start();
		$this->render_html();
		return (string) ob_get_clean();
	}

	/**
	 * Alias historique de render().
	 *
	 * @return string Le code HTML généré.
	 */
	public function display(): string {
		return $this->render();
	}

	/**
	 * Génère le code HTML du formulaire.
	 *
	 * @return void
	 */
	private function render_html(): void {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Lecture de paramètres GET informatifs pour les notices.
		$is_success = isset( $_GET['success'] ) && '1' === $_GET['success'];
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$has_error = isset( $_GET['ceb_error'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['ceb_error'] ) ) : '';
		?>
		<div class="ceb-application-form-container">
			<?php if ( $is_success ) : ?>
				<div class="notice notice-success is-dismissible" style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 4px;">
					<p><strong><?php esc_html_e( 'Candidature envoyée avec succès !', 'candidature-echecs-briand' ); ?></strong> <?php esc_html_e( 'Nous avons bien reçu vos informations.', 'candidature-echecs-briand' ); ?></p>
					<p><strong><?php esc_html_e( 'Attention :', 'candidature-echecs-briand' ); ?></strong> <?php esc_html_e( 'Si le collège Briand de Lons-le-Saunier n\'est pas votre collège de secteur, nous vous rappelons que vous devez effectuer une \'Demande de dérogation\' auprès de la \'Direction des services départementaux de l\'Éducation nationale (DSDEN) du Jura — Division élèves, familles et second degré\' en appelant le 03 84 87 27 39.', 'candidature-echecs-briand' ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $has_error ) ) : ?>
				<div class="notice notice-error is-dismissible" style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 4px;">
					<?php if ( 'age' === $has_error ) : ?>
						<p><strong><?php esc_html_e( 'Erreur :', 'candidature-echecs-briand' ); ?></strong> <?php esc_html_e( 'L\'âge du candidat doit être compris entre 7 et 17 ans.', 'candidature-echecs-briand' ); ?></p>
					<?php elseif ( 'tel' === $has_error ) : ?>
						<p><strong><?php esc_html_e( 'Erreur :', 'candidature-echecs-briand' ); ?></strong> <?php esc_html_e( 'Le numéro de téléphone du représentant légal est invalide. Il doit comporter 10 chiffres.', 'candidature-echecs-briand' ); ?></p>
					<?php else : ?>
						<p><strong><?php esc_html_e( 'Erreur :', 'candidature-echecs-briand' ); ?></strong> <?php esc_html_e( 'Une erreur est survenue lors de la soumission de votre candidature. Veuillez vérifier vos informations.', 'candidature-echecs-briand' ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<form id="ceb-application-form" method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

				<?php wp_nonce_field( 'ceb_submit_application', 'ceb_application_nonce' ); ?>
				<input type="hidden" name="action" value="ceb_submit_application">

				<!-- 1. Identité de l'élève -->
				<fieldset>
					<legend><?php esc_html_e( '1. Identité de l\'élève', 'candidature-echecs-briand' ); ?></legend>

					<div class="form-row">
						<label for="ceb_eleve_nom"><?php esc_html_e( 'Nom', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<input type="text" id="ceb_eleve_nom" name="ceb_eleve_nom" required placeholder="<?php esc_attr_e( 'NOM DE L\'ÉLÈVE', 'candidature-echecs-briand' ); ?>">
					</div>

					<div class="form-row">
						<label for="ceb_eleve_prenom"><?php esc_html_e( 'Prénom', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<input type="text" id="ceb_eleve_prenom" name="ceb_eleve_prenom" required placeholder="<?php esc_attr_e( 'Prénom', 'candidature-echecs-briand' ); ?>">
					</div>

					<div class="form-row">
						<label for="ceb_eleve_ddn"><?php esc_html_e( 'Date de naissance', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<?php
						$max_date = wp_date( 'Y-m-d', (int) strtotime( '-7 years' ) );
						$min_date = wp_date( 'Y-m-d', (int) strtotime( '-17 years' ) );
						?>
						<input type="date" id="ceb_eleve_ddn" name="ceb_eleve_ddn" min="<?php echo esc_attr( (string) $min_date ); ?>" max="<?php echo esc_attr( (string) $max_date ); ?>" required>
					</div>

					<div class="form-row">
						<label><?php esc_html_e( 'Sexe', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<div class="radio-group">
							<label><input type="radio" name="ceb_eleve_sexe" value="Masculin" required> <?php esc_html_e( 'Masculin', 'candidature-echecs-briand' ); ?></label>
							<label><input type="radio" name="ceb_eleve_sexe" value="Féminin" required> <?php esc_html_e( 'Féminin', 'candidature-echecs-briand' ); ?></label>
						</div>
					</div>

					<div class="form-row">
						<label for="ceb_eleve_ecole"><?php esc_html_e( 'Établissement scolaire actuel', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<input type="text" id="ceb_eleve_ecole" name="ceb_eleve_ecole" required>
					</div>

					<div class="form-row">
						<label for="ceb_eleve_classe"><?php esc_html_e( 'Classe actuelle', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<select id="ceb_eleve_classe" name="ceb_eleve_classe" required>
							<option value=""><?php esc_html_e( 'Sélectionnez...', 'candidature-echecs-briand' ); ?></option>
							<option value="CM1">CM1</option>
							<option value="CM2">CM2</option>
							<option value="6ème">6ème</option>
							<option value="5ème">5ème</option>
							<option value="4ème">4ème</option>
							<option value="3ème">3ème</option>
						</select>
					</div>

					<div class="form-row">
						<label for="ceb_eleve_lv1"><?php esc_html_e( 'LV1', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<select id="ceb_eleve_lv1" name="ceb_eleve_lv1" required>
							<option value=""><?php esc_html_e( 'Sélectionnez...', 'candidature-echecs-briand' ); ?></option>
							<option value="Anglais"><?php esc_html_e( 'Anglais', 'candidature-echecs-briand' ); ?></option>
							<option value="Allemand"><?php esc_html_e( 'Allemand', 'candidature-echecs-briand' ); ?></option>
						</select>
					</div>

					<div class="form-row">
						<label for="ceb_eleve_lv2"><?php esc_html_e( 'LV2 (Optionnel)', 'candidature-echecs-briand' ); ?></label>
						<select id="ceb_eleve_lv2" name="ceb_eleve_lv2">
							<option value="Aucune"><?php esc_html_e( 'Aucune', 'candidature-echecs-briand' ); ?></option>
							<option value="Anglais"><?php esc_html_e( 'Anglais', 'candidature-echecs-briand' ); ?></option>
							<option value="Allemand"><?php esc_html_e( 'Allemand', 'candidature-echecs-briand' ); ?></option>
							<option value="Italien"><?php esc_html_e( 'Italien', 'candidature-echecs-briand' ); ?></option>
							<option value="Espagnol"><?php esc_html_e( 'Espagnol', 'candidature-echecs-briand' ); ?></option>
							<option value="Autre"><?php esc_html_e( 'Autre', 'candidature-echecs-briand' ); ?></option>
						</select>
					</div>

					<div class="form-row">
						<label for="ceb_eleve_classe_cible"><?php esc_html_e( 'Classe ciblée à la rentrée', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<select id="ceb_eleve_classe_cible" name="ceb_eleve_classe_cible" required>
							<option value=""><?php esc_html_e( 'Sélectionnez...', 'candidature-echecs-briand' ); ?></option>
							<option value="6ème">6ème</option>
							<option value="5ème">5ème</option>
							<option value="4ème">4ème</option>
							<option value="3ème">3ème</option>
						</select>
					</div>
				</fieldset>

				<!-- 2. Représentant légal -->
				<fieldset>
					<legend><?php esc_html_e( '2. Représentant légal', 'candidature-echecs-briand' ); ?></legend>

					<div class="form-row">
						<label for="ceb_legal_nom"><?php esc_html_e( 'Nom', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<input type="text" id="ceb_legal_nom" name="ceb_legal_nom" required placeholder="<?php esc_attr_e( 'NOM DU REPRÉSENTANT', 'candidature-echecs-briand' ); ?>">
					</div>

					<div class="form-row">
						<label for="ceb_legal_prenom"><?php esc_html_e( 'Prénom', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<input type="text" id="ceb_legal_prenom" name="ceb_legal_prenom" required placeholder="<?php esc_attr_e( 'Prénom', 'candidature-echecs-briand' ); ?>">
					</div>

					<div class="form-row">
						<label><?php esc_html_e( 'Lien de parenté', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<div class="radio-group">
							<label><input type="radio" name="ceb_legal_lien" value="Père" required> <?php esc_html_e( 'Père', 'candidature-echecs-briand' ); ?></label>
							<label><input type="radio" name="ceb_legal_lien" value="Mère" required> <?php esc_html_e( 'Mère', 'candidature-echecs-briand' ); ?></label>
							<label><input type="radio" name="ceb_legal_lien" value="Tuteur" required> <?php esc_html_e( 'Tuteur', 'candidature-echecs-briand' ); ?></label>
						</div>
					</div>

					<div class="form-row">
						<label for="ceb_legal_adresse"><?php esc_html_e( 'Adresse', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<input type="text" id="ceb_legal_adresse" name="ceb_legal_adresse" required>
					</div>

					<div class="form-row">
						<label for="ceb_legal_cplt"><?php esc_html_e( 'Complément d\'adresse', 'candidature-echecs-briand' ); ?></label>
						<input type="text" id="ceb_legal_cplt" name="ceb_legal_cplt">
					</div>

					<div class="form-row">
						<label for="ceb_legal_cp"><?php esc_html_e( 'Code Postal', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<input type="text" id="ceb_legal_cp" name="ceb_legal_cp" required pattern="[0-9]{5}">
					</div>

					<div class="form-row">
						<label for="ceb_legal_ville"><?php esc_html_e( 'Ville', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<input type="text" id="ceb_legal_ville" name="ceb_legal_ville" required placeholder="<?php esc_attr_e( 'VILLE', 'candidature-echecs-briand' ); ?>">
					</div>

					<div class="form-row">
						<label for="ceb_legal_tel"><?php esc_html_e( 'Téléphone', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<input type="tel" id="ceb_legal_tel" name="ceb_legal_tel" required>
					</div>

					<div class="form-row">
						<label for="ceb_legal_email"><?php esc_html_e( 'Courriel', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<input type="email" id="ceb_legal_email" name="ceb_legal_email" required>
					</div>
				</fieldset>

				<!-- 2b. Second Représentant légal (Optionnel) -->
				<fieldset>
					<legend><?php esc_html_e( 'Second Représentant Légal (Optionnel)', 'candidature-echecs-briand' ); ?></legend>

					<div class="form-row">
						<label for="ceb_legal2_nom"><?php esc_html_e( 'Nom', 'candidature-echecs-briand' ); ?></label>
						<input type="text" id="ceb_legal2_nom" name="ceb_legal2_nom" placeholder="<?php esc_attr_e( 'NOM DU REPRÉSENTANT', 'candidature-echecs-briand' ); ?>">
					</div>

					<div class="form-row">
						<label for="ceb_legal2_prenom"><?php esc_html_e( 'Prénom', 'candidature-echecs-briand' ); ?></label>
						<input type="text" id="ceb_legal2_prenom" name="ceb_legal2_prenom" placeholder="<?php esc_attr_e( 'Prénom', 'candidature-echecs-briand' ); ?>">
					</div>

					<div class="form-row">
						<label><?php esc_html_e( 'Lien de parenté', 'candidature-echecs-briand' ); ?></label>
						<div class="radio-group">
							<label><input type="radio" name="ceb_legal2_lien" value="Père"> <?php esc_html_e( 'Père', 'candidature-echecs-briand' ); ?></label>
							<label><input type="radio" name="ceb_legal2_lien" value="Mère"> <?php esc_html_e( 'Mère', 'candidature-echecs-briand' ); ?></label>
							<label><input type="radio" name="ceb_legal2_lien" value="Tuteur"> <?php esc_html_e( 'Tuteur', 'candidature-echecs-briand' ); ?></label>
						</div>
					</div>

					<div class="form-row">
						<label for="ceb_legal2_adresse"><?php esc_html_e( 'Adresse', 'candidature-echecs-briand' ); ?></label>
						<input type="text" id="ceb_legal2_adresse" name="ceb_legal2_adresse">
					</div>

					<div class="form-row">
						<label for="ceb_legal2_cplt"><?php esc_html_e( 'Complément d\'adresse', 'candidature-echecs-briand' ); ?></label>
						<input type="text" id="ceb_legal2_cplt" name="ceb_legal2_cplt">
					</div>

					<div class="form-row">
						<label for="ceb_legal2_cp"><?php esc_html_e( 'Code Postal', 'candidature-echecs-briand' ); ?></label>
						<input type="text" id="ceb_legal2_cp" name="ceb_legal2_cp" pattern="[0-9]{5}">
					</div>

					<div class="form-row">
						<label for="ceb_legal2_ville"><?php esc_html_e( 'Ville', 'candidature-echecs-briand' ); ?></label>
						<input type="text" id="ceb_legal2_ville" name="ceb_legal2_ville" placeholder="<?php esc_attr_e( 'VILLE', 'candidature-echecs-briand' ); ?>">
					</div>

					<div class="form-row">
						<label for="ceb_legal2_tel"><?php esc_html_e( 'Téléphone', 'candidature-echecs-briand' ); ?></label>
						<input type="tel" id="ceb_legal2_tel" name="ceb_legal2_tel">
					</div>

					<div class="form-row">
						<label for="ceb_legal2_email"><?php esc_html_e( 'Courriel', 'candidature-echecs-briand' ); ?></label>
						<input type="email" id="ceb_legal2_email" name="ceb_legal2_email">
					</div>
				</fieldset>

				<!-- 3. Parcours échiquéen -->
				<fieldset>
					<legend><?php esc_html_e( '3. Parcours échiquéen', 'candidature-echecs-briand' ); ?></legend>

					<div class="form-row">
						<label for="ceb_echecs_debut"><?php esc_html_e( 'Année de début des Échecs', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<input type="number" id="ceb_echecs_debut" name="ceb_echecs_debut" min="2000" max="<?php echo esc_attr( (string) wp_date( 'Y' ) ); ?>" required>
					</div>

					<div class="form-row">
						<label for="ceb_echecs_club"><?php esc_html_e( 'Club actuel (Optionnel)', 'candidature-echecs-briand' ); ?></label>
						<input type="text" id="ceb_echecs_club" name="ceb_echecs_club" list="clubs-region">
						<datalist id="clubs-region">
							<option value="Echiquier Ledonien">
							<option value="Ecole d'echecs ledonienne">
							<option value="Le Cavalier Bayard">
							<option value="Tour Prends Garde! Besancon">
							<option value="Echiquier Bisontin">
							<option value="Roi Blanc Montbeliard">
							<option value="Le Pion Tissalien Echecs">
							<option value="BelfortEchecs - AEAU">
							<option value="Cercle d'Echecs de Valdoie">
							<option value="Louhans Chateaurenaud Echecs">
						</datalist>
					</div>

					<div class="form-row">
						<label for="ceb_echecs_niveau"><?php esc_html_e( 'Niveau', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<select id="ceb_echecs_niveau" name="ceb_echecs_niveau" required>
							<option value=""><?php esc_html_e( 'Sélectionnez...', 'candidature-echecs-briand' ); ?></option>
							<option value="Débutant"><?php esc_html_e( 'Débutant', 'candidature-echecs-briand' ); ?></option>
							<option value="Compétition départementale"><?php esc_html_e( 'Compétition départementale', 'candidature-echecs-briand' ); ?></option>
							<option value="Compétition régionale"><?php esc_html_e( 'Compétition régionale', 'candidature-echecs-briand' ); ?></option>
							<option value="Compétition nationale"><?php esc_html_e( 'Compétition nationale', 'candidature-echecs-briand' ); ?></option>
						</select>
					</div>

					<div class="form-row">
						<label for="ceb_echecs_competitions"><?php esc_html_e( 'Principales compétitions effectuées (Optionnel)', 'candidature-echecs-briand' ); ?></label>
						<?php
						wp_editor(
							'',
							'ceb_echecs_competitions',
							[
								'media_buttons' => false,
								'textarea_name' => 'ceb_echecs_competitions',
								'textarea_rows' => 4,
								'quicktags'     => false,
								'teeny'         => true,
							]
						);
						?>
					</div>

					<div class="form-row">
						<label for="ceb_echecs_titres"><?php esc_html_e( 'Titres notables obtenus (Optionnel)', 'candidature-echecs-briand' ); ?></label>
						<?php
						wp_editor(
							'',
							'ceb_echecs_titres',
							[
								'media_buttons' => false,
								'textarea_name' => 'ceb_echecs_titres',
								'textarea_rows' => 4,
								'quicktags'     => false,
								'teeny'         => true,
							]
						);
						?>
					</div>

					<!-- Motivation (Fichier ou Texte) -->
					<div class="form-row">
						<label><?php esc_html_e( 'Lettre de motivation', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<div class="radio-group">
							<label><input type="radio" name="ceb_motivation_type" value="fichier" checked required> <?php esc_html_e( 'Joindre un fichier', 'candidature-echecs-briand' ); ?></label>
							<label><input type="radio" name="ceb_motivation_type" value="texte" required> <?php esc_html_e( 'Rédiger ici', 'candidature-echecs-briand' ); ?></label>
						</div>
					</div>

					<div class="form-row" id="ceb_motivation_fichier_wrap">
						<label for="ceb_motivation_fichier"><?php esc_html_e( 'Fichier de motivation (PDF, DOC, DOCX, ODT, RTF)', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<input type="file" id="ceb_motivation_fichier" name="ceb_motivation_fichier" accept=".pdf,.doc,.docx,.odt,.rtf" required>
					</div>

					<div class="form-row" id="ceb_motivation_texte_wrap" style="display:none;">
						<label for="ceb_motivation_texte"><?php esc_html_e( 'Texte de motivation', 'candidature-echecs-briand' ); ?> <span class="required">*</span></label>
						<?php
						wp_editor(
							'',
							'ceb_motivation_texte',
							[
								'media_buttons' => false,
								'textarea_name' => 'ceb_motivation_texte',
								'textarea_rows' => 8,
								'quicktags'     => false,
							]
						);
						?>
					</div>
				</fieldset>

				<button type="submit" class="button button-primary"><?php esc_html_e( 'Soumettre ma candidature', 'candidature-echecs-briand' ); ?></button>
			</form>
		</div>

		<?php
	}
}
