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

	// Validation de l'âge (entre 7 et 17 ans)
	const ddnEleve = document.getElementById( 'ceb_eleve_ddn' );
	if ( ddnEleve ) {
		const validateAge = ( event ) => {
			// On ne déclenche la validation complète que lors d'un "blur" ou à la soumission pour ne pas interrompre Chrome
			if ( event.type !== 'blur' && event.type !== 'submit' ) {
				return;
			}

			const ddn = event.target.value;
			if ( ! ddn ) {
				event.target.setCustomValidity( '' );
				return;
			}

			const birthDate = new Date( ddn );
			const today = new Date();
			let age = today.getFullYear() - birthDate.getUTCFullYear();
			const m = today.getMonth() - birthDate.getUTCMonth();
			if (
				m < 0 ||
				( m === 0 && today.getDate() < birthDate.getUTCDate() )
			) {
				age--;
			}

			if ( age < 7 || age > 17 ) {
				event.target.setCustomValidity(
					"L'âge du candidat doit être compris entre 7 et 17 ans."
				);
				event.target.reportValidity();
			} else {
				event.target.setCustomValidity( '' );
			}
		};

		ddnEleve.addEventListener( 'blur', validateAge );
	}

	// Sauvegarde et restauration des données du formulaire via sessionStorage
	const form = document.getElementById( 'ceb-application-form' );
	if ( form ) {
		const storageKey = 'ceb_application_form_data';

		// Fonction pour sauvegarder les données
		const saveFormData = () => {
			const formData = new FormData( form );
			const data = {};
			for ( const [ key, value ] of formData.entries() ) {
				// Ne pas sauvegarder les champs de type fichier et nonces/action
				const input = form.elements[ key ];
				if (
					input &&
					input.type !== 'file' &&
					input.type !== 'hidden' &&
					key !== 'ceb_application_nonce'
				) {
					data[ key ] = value;
				}
			}
			sessionStorage.setItem( storageKey, JSON.stringify( data ) );
		};

		// Fonction pour restaurer les données
		const restoreFormData = () => {
			const savedData = sessionStorage.getItem( storageKey );
			if ( savedData ) {
				try {
					const data = JSON.parse( savedData );
					for ( const key in data ) {
						if (
							Object.prototype.hasOwnProperty.call( data, key )
						) {
							const inputs = form.elements[ key ];
							if ( inputs ) {
								if (
									inputs instanceof RadioNodeList ||
									( inputs.length &&
										inputs[ 0 ].type === 'radio' )
								) {
									// C'est un groupe de boutons radio
									for ( let i = 0; i < inputs.length; i++ ) {
										if (
											inputs[ i ].value === data[ key ]
										) {
											inputs[ i ].checked = true;
											break;
										}
									}
								} else {
									// C'est un champ simple
									inputs.value = data[ key ];
								}
							}
						}
					}
					// Mettre à jour l'affichage conditionnel après restauration
					toggleMotivationFields();
				} catch ( e ) {
					// Ignorer les erreurs de parsing
				}
			}
		};

		// Vérifier si la soumission a réussi pour vider le sessionStorage
		const urlParams = new URLSearchParams( window.location.search );
		if ( urlParams.get( 'success' ) === '1' ) {
			sessionStorage.removeItem( storageKey );
		} else {
			// Restaurer les données au chargement si on n'est pas sur la page de succès
			restoreFormData();
		}

		// Sauvegarder à chaque modification
		form.addEventListener( 'input', saveFormData );
		form.addEventListener( 'change', saveFormData );

		// Prévenir les échecs silencieux de la validation HTML5
		form.addEventListener(
			'invalid',
			( event ) => {
				const target = event.target;
				if ( target ) {
					// Trouver l'élément visible le plus proche si l'élément invalide est caché (ex. champs de motivation cachés)
					if ( target.offsetParent === null ) {
						// Si l'élément invalide est un champ de fichier caché par exemple
						toggleMotivationFields();

						// Assurez-vous que le message est affiché via le navigateur
						// focus() permet généralement de scroller vers le champ
						setTimeout( () => {
							target.focus();
						}, 100 );
					}
				}
			},
			true
		); // capture phase pour intercepter tous les événements 'invalid'

		// Ajouter un écouteur de soumission pour valider explicitement avant
		form.addEventListener( 'submit', ( event ) => {
			if ( ddnEleve ) {
				// Re-déclencher la validation de l'âge à la soumission pour s'assurer qu'elle est évaluée
				const validateAgeOnSubmit = new Event( 'submit' );
				ddnEleve.dispatchEvent( validateAgeOnSubmit );

				if ( ! form.checkValidity() ) {
					event.preventDefault();
				}
			}
		} );
	}

	// IGN Autocomplete
	function initIGNAutocomplete( addressName, postalCodeName, cityName ) {
		const addressInput = document.querySelector(
			`input[name="${ addressName }"]`
		);

		if ( ! addressInput ) {
			return;
		}

		const postalCodeInput = document.querySelector(
			`input[name="${ postalCodeName }"]`
		);
		const cityInput = document.querySelector(
			`input[name="${ cityName }"]`
		);

		// Création dynamique du wrapper parent pour positionner la liste absolue
		const wrapper = document.createElement( 'div' );
		wrapper.className = 'ceb-autocomplete-wrapper';
		addressInput.parentNode.insertBefore( wrapper, addressInput );
		wrapper.appendChild( addressInput );

		const resultsContainer = document.createElement( 'div' );
		resultsContainer.className = 'ceb-address-suggestions';
		resultsContainer.style.display = 'none';
		wrapper.appendChild( resultsContainer );

		let debounceTimer;
		let highlightedIndex = -1;

		addressInput.addEventListener( 'keyup', function ( e ) {
			if (
				[ 'ArrowDown', 'ArrowUp', 'Enter', 'Escape' ].includes( e.key )
			) {
				return;
			}

			clearTimeout( debounceTimer );
			const query = this.value;

			if ( query.length < 5 ) {
				resultsContainer.style.display = 'none';
				return;
			}

			debounceTimer = setTimeout( () => {
				fetch(
					`https://data.geopf.fr/geocodage/completion?text=${ encodeURIComponent(
						query
					) }&type=StreetAddress`
				)
					.then( ( r ) => r.json() )
					.then( ( data ) => {
						resultsContainer.innerHTML = '';
						highlightedIndex = -1;
						if ( data.results && data.results.length > 0 ) {
							resultsContainer.style.display = 'block';
							data.results.forEach( ( result ) => {
								const div = document.createElement( 'div' );
								div.className = 'ceb-suggestion-item';
								div.textContent = result.fulltext;
								div.dataset.street = result.fulltext
									.split( ',' )[ 0 ]
									.trim();
								div.dataset.zipcode = result.zipcode;
								div.dataset.city = result.city;
								resultsContainer.appendChild( div );
							} );
						} else {
							resultsContainer.style.display = 'none';
						}
					} );
			}, 300 );
		} );

		addressInput.addEventListener( 'keydown', function ( e ) {
			const suggestions = resultsContainer.querySelectorAll(
				'.ceb-suggestion-item'
			);
			if ( suggestions.length === 0 ) {
				return;
			}

			if ( e.key === 'ArrowDown' ) {
				e.preventDefault();
				highlightedIndex =
					( highlightedIndex + 1 ) % suggestions.length;
				updateHighlight( suggestions );
			} else if ( e.key === 'ArrowUp' ) {
				e.preventDefault();
				highlightedIndex =
					highlightedIndex <= 0
						? suggestions.length - 1
						: highlightedIndex - 1;
				updateHighlight( suggestions );
			} else if ( e.key === 'Enter' ) {
				e.preventDefault();
				if ( highlightedIndex > -1 ) {
					selectSuggestion( suggestions[ highlightedIndex ] );
				}
			} else if ( e.key === 'Escape' ) {
				resultsContainer.style.display = 'none';
			}
		} );

		function updateHighlight( suggestions ) {
			suggestions.forEach( ( s, i ) =>
				s.classList.toggle( 'highlighted', i === highlightedIndex )
			);
		}

		function selectSuggestion( suggestion ) {
			addressInput.value = suggestion.dataset.street;
			if ( postalCodeInput ) {
				postalCodeInput.value = suggestion.dataset.zipcode;
			}
			if ( cityInput ) {
				cityInput.value = suggestion.dataset.city;
			}

			// CRUCIAL : Déclencher manuellement l'événement 'input' pour que le script SessionStorage sauvegarde ces nouvelles valeurs !
			addressInput.dispatchEvent(
				new Event( 'input', { bubbles: true } )
			);
			if ( postalCodeInput ) {
				postalCodeInput.dispatchEvent(
					new Event( 'input', { bubbles: true } )
				);
			}
			if ( cityInput ) {
				cityInput.dispatchEvent(
					new Event( 'input', { bubbles: true } )
				);
			}

			resultsContainer.style.display = 'none';
		}

		resultsContainer.addEventListener( 'click', ( e ) => {
			if ( e.target.classList.contains( 'ceb-suggestion-item' ) ) {
				selectSuggestion( e.target );
			}
		} );

		document.addEventListener( 'click', ( e ) => {
			if ( ! wrapper.contains( e.target ) ) {
				resultsContainer.style.display = 'none';
			}
		} );
	}

	// Initialisation pour le Responsable 1
	initIGNAutocomplete(
		'ceb_legal_adresse',
		'ceb_legal_cp',
		'ceb_legal_ville'
	);

	// Initialisation pour le Responsable 2 (si le champ existe)
	initIGNAutocomplete(
		'ceb_legal2_adresse',
		'ceb_legal2_cp',
		'ceb_legal2_ville'
	);

	// Initialisation
	toggleMotivationFields();
} );
