<?php
class PlatsModele extends AccesBd 
{
	/**
	 * Récupérer tous les plats
	 * @param array $groupe
	 * @return array
	 */
	public function tout($groupe)
    {
        return $this->lire("SELECT plat.* FROM plat JOIN categorie 
            ON pla_cat_id_ce=cat_id", $groupe);
    }


	/**
	 * Récupérer un plat
	 * @param int $id
	 * @return array
	 */
    public function un($id) {
        return $this->lireUn("SELECT cat_nom, plat.* FROM plat JOIN categorie 
            ON pla_cat_id_ce=cat_id WHERE pla_id=:pla_id", ['pla_id'=>$id]);
    }


	/**
	 * Ajouter un plat
	 * @param object $plat
	 * @return int
	 */
    public function ajouter($plat) {
        return $this->creer("INSERT INTO plat 
            (pla_nom, pla_detail, pla_portion, pla_prix, pla_cat_id_ce) 
            VALUES (?, ?, ?, ?, ?)"
            , [$plat->pla_nom, $plat->pla_detail, $plat->pla_portion, $plat->pla_prix, $plat->pla_cat_id_ce]);
    }
	

	/**
	 * Remplacer/Modifier un plat
	 * @param int $id
	 * @param object $plat
	 * @return int
	 */
	public function remplacer($id, $plat) {
		return $this->modifier("UPDATE plat SET
					pla_nom=?, pla_detail=?, pla_portion=?, pla_prix=?, pla_cat_id_ce=?
					WHERE pla_id=?"
			, [
				$plat->pla_nom, 
				$plat->pla_detail, 
				$plat->pla_portion, 
				$plat->pla_prix, 
				$plat->pla_cat_id_ce,
				$id
			]);
	} 

	
	/**
	 * Modifier un plat
	 * @param int $id
	 * @param string $paramAChanger
	 * @param string $valeurAInserer
	 * @return int
	 */
	public function changer($id, $paramAChanger, $valeurAInserer) {
		return $this->modifier("UPDATE plat SET $paramAChanger=? WHERE pla_id=?"
			, [
					$valeurAInserer,
					$id
				]);
	}	


	/**
	 * Supprimer un plat
	 * @param $id
	 * @return int
	 */
    public function retirer($id) {
        return $this->supprimer("DELETE FROM plat WHERE pla_id=:pla_id", ['pla_id'=>$id]);
    }
}