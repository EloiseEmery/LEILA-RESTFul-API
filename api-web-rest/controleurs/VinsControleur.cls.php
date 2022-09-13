<?php
class VinsControleur extends Controleur {
	/**
	 * Récupérer tous les vins
	 * @param array $params
	 */
    public function tout($params)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $groupe = (isset($params['groupe'])) ? $params['groupe'] : NULL;
        $this->reponse['corps'] = $this->modele->tout($groupe);
    }


	/**
	 * Récupérer un vin
	 * @param int $id
	 */
    public function un($id)
    {
		$this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = $this->modele->un($id);
    }


	/**
	 * Ajouter un vin
	 * @param string $vin
	 */
    public function ajouter($vin)
    {
		$this->reponse['entete_statut'] = 'HTTP/1.1 201 Created';
        $this->reponse['corps'] = ['vin_id' => $this->modele->ajouter(json_decode($vin))];
    }


	/**
	 * Remplacer/Modifier un vin
	 * @param int $id
	 * @param string $vin
	 */
    public function remplacer($id, $vin)
    {
		$this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = $this->modele->remplacer($id, json_decode($vin));
    }


	/**
	 * Modifier un vin
	 * @param int $id
	 * @param string $fragmentVin
	 */
    public function changer($id, $fragmentVin)
    {
		$this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
		$fragementAModifier = json_decode($fragmentVin);

		// Récupérer l'information du vin à modifier
		foreach($fragementAModifier as $key => $value) {
			$paramAChanger = $key;
			$valeurAInserer = $value;
		}

		$this->reponse['corps'] = $this->modele->changer($id, $paramAChanger, $valeurAInserer);
    }


	/**
	 * Supprimer un vin
	 * @param int $id
	 */
	public function retirer($id)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = ['nombre' => $this->modele->retirer($id)];
    }
}