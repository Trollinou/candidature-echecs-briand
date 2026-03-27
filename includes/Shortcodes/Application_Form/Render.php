<?php
namespace CEB\Shortcodes\Application_Form;

class Render {

	public function display() {
		ob_start();
		$this->render_html();
		return ob_get_clean();
	}

	private function render_html() {
		?>
		<div class="ceb-application-form-container">
			<form id="ceb-application-form" method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

				<?php wp_nonce_field( 'ceb_submit_application', 'ceb_application_nonce' ); ?>
				<input type="hidden" name="action" value="ceb_process_application">

				<!-- 1. Identité de l'élève -->
				<fieldset>
					<legend>1. Identité de l'élève</legend>

					<div class="form-row">
						<label for="_ceb_eleve_nom">Nom <span class="required">*</span></label>
						<input type="text" id="_ceb_eleve_nom" name="_ceb_eleve_nom" required placeholder="NOM DE L'ÉLÈVE">
					</div>

					<div class="form-row">
						<label for="_ceb_eleve_prenom">Prénom <span class="required">*</span></label>
						<input type="text" id="_ceb_eleve_prenom" name="_ceb_eleve_prenom" required placeholder="Prénom">
					</div>

					<div class="form-row">
						<label for="_ceb_eleve_ddn">Date de naissance <span class="required">*</span></label>
						<input type="date" id="_ceb_eleve_ddn" name="_ceb_eleve_ddn" required>
					</div>

					<div class="form-row">
						<label>Sexe <span class="required">*</span></label>
						<label><input type="radio" name="_ceb_eleve_sexe" value="Masculin" required> Masculin</label>
						<label><input type="radio" name="_ceb_eleve_sexe" value="Féminin" required> Féminin</label>
					</div>

					<div class="form-row">
						<label for="_ceb_eleve_ecole">Établissement scolaire actuel <span class="required">*</span></label>
						<input type="text" id="_ceb_eleve_ecole" name="_ceb_eleve_ecole" required>
					</div>

					<div class="form-row">
						<label for="_ceb_eleve_classe">Classe actuelle <span class="required">*</span></label>
						<select id="_ceb_eleve_classe" name="_ceb_eleve_classe" required>
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
						<label for="_ceb_eleve_lv1">LV1 <span class="required">*</span></label>
						<select id="_ceb_eleve_lv1" name="_ceb_eleve_lv1" required>
							<option value="">Sélectionnez...</option>
							<option value="Anglais">Anglais</option>
							<option value="Allemand">Allemand</option>
						</select>
					</div>

					<div class="form-row">
						<label for="_ceb_eleve_lv2">LV2 (Optionnel)</label>
						<select id="_ceb_eleve_lv2" name="_ceb_eleve_lv2">
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
						<label for="_ceb_legal_nom">Nom <span class="required">*</span></label>
						<input type="text" id="_ceb_legal_nom" name="_ceb_legal_nom" required placeholder="NOM DU REPRÉSENTANT">
					</div>

					<div class="form-row">
						<label for="_ceb_legal_prenom">Prénom <span class="required">*</span></label>
						<input type="text" id="_ceb_legal_prenom" name="_ceb_legal_prenom" required placeholder="Prénom">
					</div>

					<div class="form-row">
						<label for="_ceb_legal_lien">Lien de parenté <span class="required">*</span></label>
						<select id="_ceb_legal_lien" name="_ceb_legal_lien" required>
							<option value="">Sélectionnez...</option>
							<option value="Père">Père</option>
							<option value="Mère">Mère</option>
							<option value="Tuteur">Tuteur</option>
						</select>
					</div>

					<div class="form-row">
						<label for="_ceb_legal_adresse">Adresse <span class="required">*</span></label>
						<input type="text" id="_ceb_legal_adresse" name="_ceb_legal_adresse" required>
					</div>

					<div class="form-row">
						<label for="_ceb_legal_cplt">Complément d'adresse</label>
						<input type="text" id="_ceb_legal_cplt" name="_ceb_legal_cplt">
					</div>

					<div class="form-row">
						<label for="_ceb_legal_cp">Code Postal <span class="required">*</span></label>
						<input type="text" id="_ceb_legal_cp" name="_ceb_legal_cp" required pattern="[0-9]{5}">
					</div>

					<div class="form-row">
						<label for="_ceb_legal_ville">Ville <span class="required">*</span></label>
						<input type="text" id="_ceb_legal_ville" name="_ceb_legal_ville" required placeholder="VILLE">
					</div>

					<div class="form-row">
						<label for="_ceb_legal_tel">Téléphone <span class="required">*</span></label>
						<input type="tel" id="_ceb_legal_tel" name="_ceb_legal_tel" required>
					</div>

					<div class="form-row">
						<label for="_ceb_legal_email">Courriel <span class="required">*</span></label>
						<input type="email" id="_ceb_legal_email" name="_ceb_legal_email" required>
					</div>
				</fieldset>

				<!-- 3. Parcours échiquéen -->
				<fieldset>
					<legend>3. Parcours échiquéen</legend>

					<div class="form-row">
						<label for="_ceb_echecs_debut">Année de début <span class="required">*</span></label>
						<input type="number" id="_ceb_echecs_debut" name="_ceb_echecs_debut" min="2000" max="<?php echo date('Y'); ?>" required>
					</div>

					<div class="form-row">
						<label for="_ceb_echecs_club">Club actuel (Optionnel)</label>
						<input type="text" id="_ceb_echecs_club" name="_ceb_echecs_club" list="clubs-region">
						<datalist id="clubs-region">
							<option value="Club Échecs Briand">
							<option value="Échiquier de la Ville">
							<option value="Tour et Cavaliers">
						</datalist>
					</div>

					<div class="form-row">
						<label for="_ceb_echecs_niveau">Niveau <span class="required">*</span></label>
						<select id="_ceb_echecs_niveau" name="_ceb_echecs_niveau" required>
							<option value="">Sélectionnez...</option>
							<option value="Débutant">Débutant</option>
							<option value="Compétition départementale">Compétition départementale</option>
							<option value="Compétition régionale">Compétition régionale</option>
							<option value="Compétition nationale">Compétition nationale</option>
						</select>
					</div>

					<div class="form-row">
						<label for="_ceb_echecs_competitions">Compétitions (Optionnel)</label>
						<?php
						wp_editor( '', '_ceb_echecs_competitions', [
							'media_buttons' => false,
							'textarea_name' => '_ceb_echecs_competitions',
							'textarea_rows' => 4,
							'quicktags'     => false,
							'teeny'         => true,
						] );
						?>
					</div>

					<div class="form-row">
						<label for="_ceb_echecs_titres">Titres notables (Optionnel)</label>
						<?php
						wp_editor( '', '_ceb_echecs_titres', [
							'media_buttons' => false,
							'textarea_name' => '_ceb_echecs_titres',
							'textarea_rows' => 4,
							'quicktags'     => false,
							'teeny'         => true,
						] );
						?>
					</div>

					<!-- Motivation (Fichier ou Texte) -->
					<div class="form-row">
						<label>Format de la motivation <span class="required">*</span></label>
						<label><input type="radio" name="_ceb_motivation_type" value="fichier" checked required> Joindre un fichier</label>
						<label><input type="radio" name="_ceb_motivation_type" value="texte" required> Rédiger ici</label>
					</div>

					<div class="form-row" id="ceb_motivation_fichier_wrap">
						<label for="_ceb_motivation_fichier">Fichier de motivation (PDF, DOC, DOCX) <span class="required">*</span></label>
						<input type="file" id="_ceb_motivation_fichier" name="_ceb_motivation_fichier" accept=".pdf,.doc,.docx" required>
					</div>

					<div class="form-row" id="ceb_motivation_texte_wrap" style="display:none;">
						<label for="_ceb_motivation_texte">Texte de motivation <span class="required">*</span></label>
						<?php
						wp_editor( '', '_ceb_motivation_texte', [
							'media_buttons' => false,
							'textarea_name' => '_ceb_motivation_texte',
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
			const radios = document.querySelectorAll('input[name="_ceb_motivation_type"]');
			const fileWrap = document.getElementById('ceb_motivation_fichier_wrap');
			const textWrap = document.getElementById('ceb_motivation_texte_wrap');
			const fileInput = document.getElementById('_ceb_motivation_fichier');

			function toggleMotivationFields() {
				const selectedType = document.querySelector('input[name="_ceb_motivation_type"]:checked').value;

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