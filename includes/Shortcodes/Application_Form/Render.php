<?php
namespace CEB\Shortcodes\Application_Form;

/**
 * Composant de rendu HTML du formulaire de candidature
 */
class Render {

	/**
	 * Affiche le formulaire en utilisant un buffer de sortie
	 *
	 * @return string Le code HTML généré.
	 */
	public function display(): string {
		ob_start();
		$this->render_html();
		return (string) ob_get_clean();
	}

	/**
	 * Génère le code HTML du formulaire
	 *
	 * @return void
	 */
	private function render_html(): void {
		?>
		<div class="ceb-application-form-container">
			<?php if ( isset( $_GET['success'] ) && '1' === $_GET['success'] ) : ?>
				<div class="notice notice-success is-dismissible" style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 4px;">
					<p><strong>Candidature envoyée avec succès !</strong> Nous avons bien reçu vos informations.</p>
				</div>
			<?php endif; ?>

			<form id="ceb-application-form" method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

				<?php wp_nonce_field( 'ceb_submit_application', 'ceb_application_nonce' ); ?>
				<input type="hidden" name="action" value="ceb_submit_application">

				<!-- 1. Identité de l'élève -->
				<fieldset>
					<legend>1. Identité de l'élève</legend>

					<div class="form-row">
						<label for="ceb_eleve_nom">Nom <span class="required">*</span></label>
						<input type="text" id="ceb_eleve_nom" name="ceb_eleve_nom" required placeholder="NOM DE L'ÉLÈVE">
					</div>

					<div class="form-row">
						<label for="ceb_eleve_prenom">Prénom <span class="required">*</span></label>
						<input type="text" id="ceb_eleve_prenom" name="ceb_eleve_prenom" required placeholder="Prénom">
					</div>

					<div class="form-row">
						<label for="ceb_eleve_ddn">Date de naissance <span class="required">*</span></label>
						<input type="date" id="ceb_eleve_ddn" name="ceb_eleve_ddn" required>
					</div>

					<div class="form-row">
						<label>Sexe <span class="required">*</span></label>
						<div class="radio-group">
							<label><input type="radio" name="ceb_eleve_sexe" value="Masculin" required> Masculin</label>
							<label><input type="radio" name="ceb_eleve_sexe" value="Féminin" required> Féminin</label>
						</div>
					</div>

					<div class="form-row">
						<label for="ceb_eleve_ecole">Établissement scolaire actuel <span class="required">*</span></label>
						<input type="text" id="ceb_eleve_ecole" name="ceb_eleve_ecole" required>
					</div>

					<div class="form-row">
						<label for="ceb_eleve_classe">Classe actuelle <span class="required">*</span></label>
						<select id="ceb_eleve_classe" name="ceb_eleve_classe" required>
							<option value="">Sélectionnez...</option>
							<option value="CM1">CM1</option>
							<option value="CM2">CM2</option>
							<option value="6ème">6ème</option>
							<option value="5ème">5ème</option>
							<option value="4ème">4ème</option>
							<option value="3ème">3ème</option>
						</select>
					</div>

					<div class="form-row">
						<label for="ceb_eleve_lv1">LV1 <span class="required">*</span></label>
						<select id="ceb_eleve_lv1" name="ceb_eleve_lv1" required>
							<option value="">Sélectionnez...</option>
							<option value="Anglais">Anglais</option>
							<option value="Allemand">Allemand</option>
						</select>
					</div>

					<div class="form-row">
						<label for="ceb_eleve_lv2">LV2 (Optionnel)</label>
						<select id="ceb_eleve_lv2" name="ceb_eleve_lv2">
							<option value="Aucune">Aucune</option>
							<option value="Anglais">Anglais</option>
							<option value="Allemand">Allemand</option>
							<option value="Italien">Italien</option>
							<option value="Espagnol">Espagnol</option>
							<option value="Autre">Autre</option>
						</select>
					</div>
				</fieldset>

				<!-- 2. Représentant légal -->
				<fieldset>
					<legend>2. Représentant légal</legend>

					<div class="form-row">
						<label for="ceb_legal_nom">Nom <span class="required">*</span></label>
						<input type="text" id="ceb_legal_nom" name="ceb_legal_nom" required placeholder="NOM DU REPRÉSENTANT">
					</div>

					<div class="form-row">
						<label for="ceb_legal_prenom">Prénom <span class="required">*</span></label>
						<input type="text" id="ceb_legal_prenom" name="ceb_legal_prenom" required placeholder="Prénom">
					</div>

					<div class="form-row">
						<label>Lien de parenté <span class="required">*</span></label>
						<div class="radio-group">
							<label><input type="radio" name="ceb_legal_lien" value="Père" required> Père</label>
							<label><input type="radio" name="ceb_legal_lien" value="Mère" required> Mère</label>
							<label><input type="radio" name="ceb_legal_lien" value="Tuteur" required> Tuteur</label>
						</div>
					</div>

					<div class="form-row">
						<label for="ceb_legal_adresse">Adresse <span class="required">*</span></label>
						<input type="text" id="ceb_legal_adresse" name="ceb_legal_adresse" required>
					</div>

					<div class="form-row">
						<label for="ceb_legal_cplt">Complément d'adresse</label>
						<input type="text" id="ceb_legal_cplt" name="ceb_legal_cplt">
					</div>

					<div class="form-row">
						<label for="ceb_legal_cp">Code Postal <span class="required">*</span></label>
						<input type="text" id="ceb_legal_cp" name="ceb_legal_cp" required pattern="[0-9]{5}">
					</div>

					<div class="form-row">
						<label for="ceb_legal_ville">Ville <span class="required">*</span></label>
						<input type="text" id="ceb_legal_ville" name="ceb_legal_ville" required placeholder="VILLE">
					</div>

					<div class="form-row">
						<label for="ceb_legal_tel">Téléphone <span class="required">*</span></label>
						<input type="tel" id="ceb_legal_tel" name="ceb_legal_tel" required>
					</div>

					<div class="form-row">
						<label for="ceb_legal_email">Courriel <span class="required">*</span></label>
						<input type="email" id="ceb_legal_email" name="ceb_legal_email" required>
					</div>
				</fieldset>

				<!-- 3. Parcours échiquéen -->
				<fieldset>
					<legend>3. Parcours échiquéen</legend>

					<div class="form-row">
						<label for="ceb_echecs_debut">Année de début des Échecs <span class="required">*</span></label>
						<input type="number" id="ceb_echecs_debut" name="ceb_echecs_debut" min="2000" max="<?php echo date('Y'); ?>" required>
					</div>

					<div class="form-row">
						<label for="ceb_echecs_club">Club actuel (Optionnel)</label>
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
						<label for="ceb_echecs_niveau">Niveau <span class="required">*</span></label>
						<select id="ceb_echecs_niveau" name="ceb_echecs_niveau" required>
							<option value="">Sélectionnez...</option>
							<option value="Débutant">Débutant</option>
							<option value="Compétition départementale">Compétition départementale</option>
							<option value="Compétition régionale">Compétition régionale</option>
							<option value="Compétition nationale">Compétition nationale</option>
						</select>
					</div>

					<div class="form-row">
						<label for="ceb_echecs_competitions">Principales compétitions effectuées(Optionnel)</label>
						<?php
						wp_editor( '', 'ceb_echecs_competitions', [
							'media_buttons' => false,
							'textarea_name' => 'ceb_echecs_competitions',
							'textarea_rows' => 4,
							'quicktags'     => false,
							'teeny'         => true,
						] );
						?>
					</div>

					<div class="form-row">
						<label for="ceb_echecs_titres">Titres notables obtenus (Optionnel)</label>
						<?php
						wp_editor( '', 'ceb_echecs_titres', [
							'media_buttons' => false,
							'textarea_name' => 'ceb_echecs_titres',
							'textarea_rows' => 4,
							'quicktags'     => false,
							'teeny'         => true,
						] );
						?>
					</div>

					<!-- Motivation (Fichier ou Texte) -->
					<div class="form-row">
						<label>Lettre de motivation <span class="required">*</span></label>
						<div class="radio-group">
							<label><input type="radio" name="ceb_motivation_type" value="fichier" checked required> Joindre un fichier</label>
							<label><input type="radio" name="ceb_motivation_type" value="texte" required> Rédiger ici</label>
						</div>
					</div>

					<div class="form-row" id="ceb_motivation_fichier_wrap">
						<label for="ceb_motivation_fichier">Fichier de motivation (PDF, DOC, DOCX, ODT, RTF) <span class="required">*</span></label>
						<input type="file" id="ceb_motivation_fichier" name="ceb_motivation_fichier" accept=".pdf,.doc,.docx,.odt,.rtf" required>
					</div>

					<div class="form-row" id="ceb_motivation_texte_wrap" style="display:none;">
						<label for="ceb_motivation_texte">Texte de motivation <span class="required">*</span></label>
						<?php
						wp_editor( '', 'ceb_motivation_texte', [
							'media_buttons' => false,
							'textarea_name' => 'ceb_motivation_texte',
							'textarea_rows' => 8,
							'quicktags'     => false,
						] );
						?>
					</div>
				</fieldset>

				<button type="submit" class="button button-primary">Soumettre ma candidature</button>
			</form>
		</div>

		<!-- Script Vanilla JS pour la Motivation -->
		<script>
		document.addEventListener('DOMContentLoaded', function() {
			const radios = document.querySelectorAll('input[name="ceb_motivation_type"]');
			const fileWrap = document.getElementById('ceb_motivation_fichier_wrap');
			const textWrap = document.getElementById('ceb_motivation_texte_wrap');
			const fileInput = document.getElementById('ceb_motivation_fichier');

			function toggleMotivationFields() {
				const selectedType = document.querySelector('input[name="ceb_motivation_type"]:checked').value;

				if (selectedType === 'fichier') {
					fileWrap.style.display = 'block';
					textWrap.style.display = 'none';
					fileInput.required = true;
					// Note: MCE n'a pas d'attribut "required" natif fiable qui bloque le submit de façon simple.
				} else {
					fileWrap.style.display = 'none';
					textWrap.style.display = 'block';
					fileInput.required = false;
				}
			}

			radios.forEach(radio => {
				radio.addEventListener('change', toggleMotivationFields);
			});

			// Initialisation
			toggleMotivationFields();
		});
		</script>
		<?php
	}
}
