document.addEventListener('DOMContentLoaded', () => {
	/**
	 * Convertit la valeur en UPPERCASE
	 */
	const forceUpperCase = (event) => {
		if (event.target && event.target.value) {
			event.target.value = event.target.value.toUpperCase();
		}
	};

	/**
	 * Convertit la valeur en Title Case (MixedCase)
	 */
	const forceTitleCase = (event) => {
		if (event.target && event.target.value) {
			event.target.value = event.target.value.toLowerCase().replace(/(?:^|\s|-)\S/g, (a) => a.toUpperCase());
		}
	};

	// Sélecteurs pour les Noms
	const nomEleve = document.getElementById('ceb_eleve_nom');
	const nomRep = document.getElementById('ceb_legal_nom');

	if (nomEleve) nomEleve.addEventListener('blur', forceUpperCase);
	if (nomRep) nomRep.addEventListener('blur', forceUpperCase);

	// Sélecteurs pour les Prénoms
	const prenomEleve = document.getElementById('ceb_eleve_prenom');
	const prenomRep = document.getElementById('ceb_legal_prenom');

	if (prenomEleve) prenomEleve.addEventListener('blur', forceTitleCase);
	if (prenomRep) prenomRep.addEventListener('blur', forceTitleCase);
});