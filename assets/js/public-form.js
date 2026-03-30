document.addEventListener( 'DOMContentLoaded', () => {
	/**
	 * Convertit la valeur en UPPERCASE
	 *
	 * @param {Event} event
	 */
	const forceUpperCase = ( event ) => {
		if ( event.target && event.target.value ) {
			event.target.value = event.target.value.toUpperCase();
		}
	};

	/**
	 * Convertit la valeur en Title Case (MixedCase)
	 *
	 * @param {Event} event
	 */
	const forceTitleCase = ( event ) => {
		if ( event.target && event.target.value ) {
			event.target.value = event.target.value
				.toLowerCase()
				.replace( /(?:^|\s|-)\S/g, ( a ) => a.toUpperCase() );
		}
	};

	// Sélecteurs pour les Noms
	const nomEleve = document.getElementById( 'ceb_eleve_nom' );
	const nomRep = document.getElementById( 'ceb_legal_nom' );
	const nomRep2 = document.getElementById( 'ceb_legal2_nom' );

	if ( nomEleve ) {
		nomEleve.addEventListener( 'blur', forceUpperCase );
	}
	if ( nomRep ) {
		nomRep.addEventListener( 'blur', forceUpperCase );
	}
	if ( nomRep2 ) {
		nomRep2.addEventListener( 'blur', forceUpperCase );
	}

	// Sélecteurs pour les Prénoms
	const prenomEleve = document.getElementById( 'ceb_eleve_prenom' );
	const prenomRep = document.getElementById( 'ceb_legal_prenom' );
	const prenomRep2 = document.getElementById( 'ceb_legal2_prenom' );

	if ( prenomEleve ) {
		prenomEleve.addEventListener( 'blur', forceTitleCase );
	}
	if ( prenomRep ) {
		prenomRep.addEventListener( 'blur', forceTitleCase );
	}
	if ( prenomRep2 ) {
		prenomRep2.addEventListener( 'blur', forceTitleCase );
	}

	// Gestion de la Motivation
	const radios = document.querySelectorAll(
		'input[name="ceb_motivation_type"]'
	);
	const fileWrap = document.getElementById( 'ceb_motivation_fichier_wrap' );
	const textWrap = document.getElementById( 'ceb_motivation_texte_wrap' );
	const fileInput = document.getElementById( 'ceb_motivation_fichier' );

	const toggleMotivationFields = () => {
		const selectedRadio = document.querySelector(
			'input[name="ceb_motivation_type"]:checked'
		);
		if ( ! selectedRadio ) {
			return;
		}

		if ( selectedRadio.value === 'fichier' ) {
			if ( fileWrap ) {
				fileWrap.style.display = 'block';
			}
			if ( textWrap ) {
				textWrap.style.display = 'none';
			}
			if ( fileInput ) {
				fileInput.required = true;
			}
		} else {
			if ( fileWrap ) {
				fileWrap.style.display = 'none';
			}
			if ( textWrap ) {
				textWrap.style.display = 'block';
			}
			if ( fileInput ) {
				fileInput.required = false;
			}
		}
	};

	radios.forEach( ( radio ) => {
		radio.addEventListener( 'change', toggleMotivationFields );
	} );

	// Formatage des Téléphones
	const formatPhone = ( event ) => {
		if ( event.target && event.target.value ) {
			// Supprime tout caractère qui n'est pas un chiffre
			let value = event.target.value.replace( /\D/g, '' );

			// Limite la longueur à 10 chiffres
			value = value.substring( 0, 10 );

			// Formate visuellement (ex: 01 23 45 67 89)
			const match = value.match( /.{1,2}/g );
			if ( match ) {
				event.target.value = match.join( ' ' );
			} else {
				event.target.value = value;
			}
		}
	};

	const telRep = document.getElementById( 'ceb_legal_tel' );
	const telRep2 = document.getElementById( 'ceb_legal2_tel' );

	if ( telRep ) {
		telRep.addEventListener( 'input', formatPhone );
	}
	if ( telRep2 ) {
		telRep2.addEventListener( 'input', formatPhone );
	}

	// Initialisation
	toggleMotivationFields();
} );
