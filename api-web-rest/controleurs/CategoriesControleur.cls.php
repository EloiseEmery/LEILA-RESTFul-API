<?php
class CategoriesControleur extends Controleur {
	/**
	 * Récupérer toutes les catégories
	 * @param array $params
	 */
    public function tout($params)
    {
        $groupe = $params['groupe'] ?? false;
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = $this->modele->tout($groupe);
    }


	/**
	 * Récupérer une catégorie
	 * @param int $id
	 */
    public function un($id)
    {
		$this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = $this->modele->un($id);
    }


	/**
	 * Ajouter une catégorie
	 * @param string $categorie
	 */ 
    public function ajouter($categorie)
    {
		$this->reponse['entete_statut'] = 'HTTP/1.1 201 Created';
        $this->reponse['corps'] = ['cat_id' => $this->modele->ajouter(json_decode($categorie))];
    }


	/**
	 * Remplacer/Modifier une catégorie
	 * @param string $id
	 * @param string $categorie
	 */ 
    public function remplacer($id, $categorie)
    {
		$this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = $this->modele->remplacer($id, json_decode($categorie));
    }


	/**
	 * Modifier une catégorie
	 * @param int $id
	 * @param string $fragmentCategorie
	 */ 
    public function changer($id, $fragmentCategorie)
    {
		$this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
		$fragementAModifier = json_decode($fragmentCategorie);

		// Récupérer l'information de la catégorie à modifier
		foreach($fragementAModifier as $key => $value) {
			$paramAChanger = $key;
			$valeurAInserer = $value;
		}

		$this->reponse['corps'] = $this->modele->changer($id, $paramAChanger, $valeurAInserer);
    }


	/**
	 * Supprimer une catégorie
	 * @param int $id
	 */
    public function retirer($id)
    {
		$this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = ['nombre' => $this->modele->retirer($id)];
    }
}