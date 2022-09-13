export default class ApiRestEnregistrementRepository {
	/**
	 * Ajouter un enregistrement
	 * @param {Object} rangee 
	 * @param {String} collection
	 * @returns {Boolean || Array}
	 */
	static async AjouterElement(rangee, collection) {
		const donneesJson = ApiRestEnregistrementRepository.rangeeEnJson(rangee);
		let reponse = await fetch(
			'http://localhost:8888/SESSION4/TP1_prog_avance/api-web-rest/index.php/' + collection,
			{
				method: 'POST',
				body: donneesJson
			}
		);
		let reponseJson = await reponse.json();
				
		if (reponseJson && Object.values(reponseJson) != 0) {
			let idRangeeJson = JSON.stringify(reponseJson),
				proprieteNouvelleRangee = JSON.parse(donneesJson),
				idNouvelleRangee = JSON.parse(idRangeeJson);

			const donneesNouvelleRangee = [{
				...idNouvelleRangee,
				...proprieteNouvelleRangee
			}];
			return donneesNouvelleRangee
		}
		else { return false }
	}


	/**
	 * Obtenir tous enregistrements d'une collection
	 * @param {String} collection
	 * @returns {Array}
	 */
	static async obtenirEnregistrements(collection) {
		let reponse = await fetch('http://localhost:8888/SESSION4/TP1_prog_avance/api-web-rest/index.php/' + collection);
		// Traiter la réponse...
		let reponseJson = await reponse.json();
		return reponseJson
	}


	/**
	 * Modifier un enregistrement
	 * @param {Object} rangee 
	 * @param {String} collection 
	 * @returns {Boolean || Array}
	 */
	static async modifierElement(rangee, collection) {
		const donneesJson = ApiRestEnregistrementRepository.rangeeEnJson(rangee);
		
		let reponse = await fetch(
			'http://localhost:8888/SESSION4/TP1_prog_avance/api-web-rest/index.php/' + collection + '/' + rangee.dataset.id,
			{
				method: 'PUT',
				body: donneesJson
			}
		);

		let reponseJson = await reponse.json();
		if(reponseJson && reponseJson == 1){ return reponseJson }
		else { return false }
	}


	/**
	 * Supprimer un enregistrement
	 * @param {Object} rangee 
	 * @param {String} collection
	 * @returns {Boolean || Array}
	 */
	static async supprimerElement(rangee, collection) {
		let reponse = await fetch(
			'http://localhost:8888/SESSION4/TP1_prog_avance/api-web-rest/index.php/' + collection + '/' + rangee.dataset.id,
			{ method: 'DELETE' }
		);
		let reponseJson = await reponse.json();
		// Supprimer la rangée localement (DOM)
		if (reponseJson && reponseJson.nombre > 0) {
			rangee.remove();
		}
		if(reponseJson && reponseJson.nombre == 1){ return reponseJson }
		else { return false }
	}


	/**
	 * Convertir les données d'une rangée en JSON
	 * @param {Object} rangee 
	 * @returns {String}
	 */
	static rangeeEnJson(rangee) {
		const lesElts = rangee.querySelectorAll('input, select');
		let objetDonnees = {};
		for (const elt of lesElts) {
			objetDonnees[elt.name] = elt.value;
		}
		return JSON.stringify(objetDonnees);
	}


	/**
	 * Obtenir les propriétés d'une collection 
	 * pour l'affichage de celles-ci s'il n'y a aucun enregistrement récupéré
	 * @param {String} collection
	 * @returns {Array} propriétés de la collection
	 */
	static async obtenirInformationCollection(collectionNom) {
		let reponse = await fetch("./ressources/column-name-db.json")
		// Traiter la réponse...
		let reponseJson = await reponse.json()

		for (const prop in reponseJson) {
			if (collectionNom === prop) {
				let collectionProprietes = reponseJson[prop]
				return collectionProprietes;
			}
		}
	}
}