import Collection from './Collection.js';

export default class Navigation {
    constructor(el) {
		this._elNav = el;
		this._elBtnNav = this._elNav.querySelectorAll('li');
		this._elNomCollection = "";
        this.init();
	}


	init() {
		// Afficher les enregistrements de la collection "catégories" par défaut au chargement de la page 
		new Collection('categories')

		/**
		 * Gestion des boutons de la navigation
		 */	
		this._elBtnNav.forEach(function(item, pos, itemsMenu) {
			item.addEventListener('click', function() {
				// Gestion de l'item de menu actif
				itemsMenu.forEach((elt)=>elt.classList.remove('actif'));
				item.classList.add('actif');
				// Récupérer le nom de la collection
				this._elNomCollection = item.dataset.collection;
				// Obtenir les enregistrements de la collection
				new Collection(this._elNomCollection);
			}.bind(this));
		}.bind(this));
	}
}