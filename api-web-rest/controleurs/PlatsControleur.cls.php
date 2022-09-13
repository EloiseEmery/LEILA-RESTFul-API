<?php
class PlatsControleur extends Controleur {
    /**
     * Récupérer tous les plats
	 * @param array $params
     */
    public function tout($params)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $groupe = (isset($params['groupe'])) ? $params['groupe'] : NULL;
        $this->reponse['corps'] = $this->modele->tout($groupe); 
    }

	
	/**
	 * Récupérer un plat
	 * @param int $id
	 */
    public function un($id)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = $this->modele->un($id);
    }


	/**
	 * Ajouter un plat
	 * @param string $plat
	 */
    public function ajouter($plat)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 201 Created';
        $this->reponse['corps'] = ['pla_id' => $this->modele->ajouter(json_decode($plat))];
    }


	/**
	 * Remplacer/Modifier un plat
	 * @param int $id
	 * @param string $plat
	 */
    public function remplacer($id, $plat)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = $this->modele->remplacer($id, json_decode($plat));
    }


	/**
	 * Modifier un plat
	 * @param int $id
	 * @param string $fragmentPlat
	 */
    public function changer($id, $fragmentPlat)
    {
		$this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
		$fragementAModifier = json_decode($fragmentPlat);

		// Récupérer l'information du plat à modifier
		foreach($fragementAModifier as $key => $value) {
			$paramAChanger = $key;
			$valeurAInserer = $value;
		}

		$this->reponse['corps'] = $this->modele->changer($id, $paramAChanger, $valeurAInserer);
    }


	/**
	 * Supprimer un plat
	 * @param int $id
	 */
    public function retirer($id)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = ['nombre' => $this->modele->retirer($id)];
    }
}