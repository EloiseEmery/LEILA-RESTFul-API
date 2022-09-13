import ApiRestEnregistrementRepository from './ApiRestEnregistrementRepository.js';

export default class Collection {
	constructor(elCollection) {
		this._elCollection = elCollection;
		this._elNomCollection = document.querySelector('.liste-enregistrements caption code')
		this._elBtnAjoutCollection = document.querySelector('.btn-ajouter')
		this._elEnteteCollection = document.querySelector('.liste-enregistrements thead tr');
		this._elBodyCollection = document.querySelector('.liste-enregistrements tbody');
		this._elMessageRetourCollection = document.querySelector('.message-retour');
		this.init();
	}


	init() {
		ApiRestEnregistrementRepository.obtenirEnregistrements(this._elCollection).then(reponse => {
			this.afficherCollection(reponse);
		});
	}


	/**
	 * Afficher les enregistrements d'une collection
	 * @param {Object} dataCollection 
	 */
	afficherCollection(dataCollection) {
		// Afficher le nom de la collection
		this._elNomCollection.innerText = this._elCollection;
		// Nettoyer le DOM 
		this._elEnteteCollection.innerHTML = '';
		this._elBodyCollection.innerHTML = '';
		this._elMessageRetourCollection.innerHTML = '';

		// Générer l'entête de la table à partir des propriétés d'un enregistrement
		if (Object.keys(dataCollection)[0] !== 'erreur') {
			for (const prop in dataCollection[0]) {
				let th = document.createElement('th');
				th.innerText = prop.slice(4);
				this._elEnteteCollection.appendChild(th);
			}
			// Générer le corps de la table à partir des données de la collection
			this.genererRangees(dataCollection);
		} else {
			let th = document.createElement('th');
			th.innerText = "Aucun enregistrement pour le moment dans cette collection"
			this._elEnteteCollection.appendChild(th);
		}
		// Afficher la rangee d'ajout d'un enregistrement dans une collection
		this.genererRangeeAjoutEnregistrement()
	}


	/**
	 * Gestion la rangee d'ajout d'un enregistrement dans une collection
	 */
	genererRangeeAjoutEnregistrement() {
		// Générer la colonne pour les boutons d'ajout
		let th = document.createElement('th');
		th.classList.add('action');
		this._elEnteteCollection.appendChild(th);

		// Générer un bouton d'ajout d'une collection dans l'entête
		let button = document.createElement('button');
		button.classList.add('btn-ajouter');
		button.innerHTML = 'Ajouter des ' + this._elCollection;
		this._elEnteteCollection.querySelector('.action').appendChild(button);

		/**
		 * Gestion de l'event click sur le bouton affichant la rangee d'ajout
		 */
		button.addEventListener('click', function () {
			if (rangeeTBodyAjout.classList.contains('hidden')) {
				rangeeTBodyAjout.classList.remove('hidden');
			} else {
				rangeeTBodyAjout.classList.add('hidden');
			}
		}.bind(this));

		// Générer la rangee d'ajout d'un enregistrement dans le corps de la table
		let rangeeTBodyAjout = document.createElement('tr');
		rangeeTBodyAjout.classList.add('hidden');
		rangeeTBodyAjout.classList.add('rangee-ajout');
		this._elBodyCollection.insertBefore(rangeeTBodyAjout, this._elBodyCollection.firstChild);

		// Générer les colonnes (label, input) de la rangee d'ajout
		ApiRestEnregistrementRepository.obtenirInformationCollection(this._elCollection).then(reponse => {
			// Générer les proprietes de la rangee d'ajout
			for (const prop in reponse) {
				let tdAjoutInput = document.createElement('td');
				rangeeTBodyAjout.appendChild(tdAjoutInput);

				if (typeof reponse[prop] == 'object') {
					// Récupérer les données
					let valuesArray = Object.values(reponse[prop]),
					proprieteColonne = Object.keys(reponse[prop]).toString()
					// Ajout du Select
					this.genererSelect(valuesArray, proprieteColonne, tdAjoutInput)
				} 
				else {
					const nomColonne = reponse[prop].split('_');
					if(nomColonne[1] == 'cat') {
						// Ajout du Select
						this.genererSelect(reponse, reponse[prop], tdAjoutInput)
					} else {
						// Ajout des inputs
						tdAjoutInput.innerHTML = `<label>${nomColonne[1]}</label><input type="text" name="${reponse[prop]}" value=" ">`;
					}
				}
			}
			// Inserer une colonne vide 
			let tdAjoutInput = document.createElement('td');
			rangeeTBodyAjout.insertBefore(tdAjoutInput, rangeeTBodyAjout.firstChild);

			// Générer le bouton d'ajout de la rangee d'ajout
			let tdAjoutBtn = document.createElement('td');
			tdAjoutBtn.innerHTML = '<button class="btn-ajouter">Ajouter</button>';
			tdAjoutBtn.classList.add('action');
			rangeeTBodyAjout.appendChild(tdAjoutBtn);

			// Gérer les events sur le bouton d'ajout
			this.bindBoutonsRangee(rangeeTBodyAjout, this._elNomCollection)
		})
	}


	/**
	 * Afficher les rangees(enregistrements) d'une collection
	 * @param {object[]} dataCollection 
	 */
	genererRangees(dataCollection) {
		// Générer le corps de la table à partir des données de la collection
		for (const article of dataCollection) {
			let rangeeTBody = document.createElement('tr');
			rangeeTBody.dataset.id = article.cat_id || article.pla_id || article.vin_id;
	
			for (const prop in article) {
				let td = document.createElement('td');
				let proprieteCollection = prop.split('_').pop();
				
				if (prop.match(/_id$/)) {
					td.innerText = article[prop];
				}
				else if (proprieteCollection == 'ce') {
						this.genererSelect(article, prop, td)
					}
				else {
					td.innerHTML = `<input type="text" name="${prop}" value="${article[prop]}">`;
				}
				rangeeTBody.appendChild(td);
			}

			// Ajouter les boutons d'action
			let td = document.createElement('td');
			td.innerHTML = '<button class="btn-modifier">modifier</button><button class="btn-supprimer">supprimer</button>';
			td.classList.add('action');
			rangeeTBody.appendChild(td);
			this._elBodyCollection.appendChild(rangeeTBody);

			// Gérer les events sur les boutons de modification et de suppression
			this.bindBoutonsRangee(rangeeTBody, this._elNomCollection)
		}
	}


	/**
	 * Générer un objet select avec des options
	 * @param {Object[]} article
	 * @param {string} prop
	 * @param {HTMLElement} td
	 */
	genererSelect(article, prop, td) {
		td.innerHTML = `<select type="text" name="${prop}"></select>`;
		// Si la propriete est un objet
		if (prop == 'cat_type') {
			// Générer les options du select 
			article.forEach(value => {
				for(const prop in value) {
					let option = document.createElement('option');
					option.value = value[prop]
					option.innerText = value[prop];
					td.firstChild.appendChild(option)
				}
			});
		}
		else {
			ApiRestEnregistrementRepository.obtenirEnregistrements('categories').then(reponse => {
				// Générer les options du select 
				reponse.forEach(element => {
					let option = document.createElement('option');
					option.value = Object.values(element)[0]
					option.innerText = Object.values(element)[1] + '(' + Object.values(element)[0] + ')'
					// Selectionner l'option par defaut
					if (Object.values(element)[0] == article[prop]) { option.setAttribute('selected', true) };
					td.firstChild.appendChild(option)
				});
			})
		} 
	}


	/**
	 * Gérer les évènements sur les boutons des rangees
	 * @param {Object} rangee 
	 * @param {Object} rangeeNomCollection 
	 */
	bindBoutonsRangee(rangee, rangeeNomCollection) {
			let rangeeBoutons = rangee.querySelectorAll('button')
			rangeeNomCollection = rangeeNomCollection.innerText

		for (let i = 0, l = rangeeBoutons.length; i < l; i++) {
			/**
			 * Gestion de l'event click sur les boutons de la rangee
			 * @param {Event} click
			 */
			rangeeBoutons[i].addEventListener('click', function (evt) {
				if (evt.target && evt.target.classList.contains('btn-ajouter')) {
					// Ajouter un enregistrement
					ApiRestEnregistrementRepository.AjouterElement(rangee, rangeeNomCollection).then(reponse => {
						this.afficherMessageRetour(reponse, 'L\'ajout')
						if( reponse != false) {this.genererRangees(reponse)};
						// Nettoyer le contenu des inputs
						let inputsRangeeAjout = rangee.querySelectorAll('input');
						inputsRangeeAjout.forEach(input => {
							input.value = '';
						});
					})
				}
				else if (evt.target && evt.target.classList.contains('btn-modifier')) {
					// Modifier un enregistrement
					ApiRestEnregistrementRepository.modifierElement(rangee, rangeeNomCollection).then(reponse => {
						this.afficherMessageRetour(reponse, 'La modification')
					});
				}
				else if (evt.target && evt.target.classList.contains('btn-supprimer')) {
					// Supprimer un enregistrement
					ApiRestEnregistrementRepository.supprimerElement(rangee, rangeeNomCollection).then(reponse => {
						this.afficherMessageRetour(reponse, 'La suppression')
					});
				} 
			}.bind(this));
		}
	}


	/**
	 * Afficher un message de retour après une action
	 * @param {Boolean || Object} reponse 
	 * @param {String} action 
	 */
	afficherMessageRetour(reponse, action) {
		if (reponse == false) {this._elMessageRetourCollection.innerText = `${action} à échouée`}
		else {this._elMessageRetourCollection.innerText = `${action} à été effectuée`}
	}
}