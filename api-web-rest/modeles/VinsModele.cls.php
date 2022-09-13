<?php
class VinsModele extends AccesBd 
{
    /**
	 * Récupérer tous les vins
	 * @param array $groupe
	 * @return array
	 */
	public function tout($groupe)
    {
        return $this->lire("SELECT vin.* FROM vin JOIN categorie 
            ON vin_cat_id_ce=cat_id", $groupe);
    }


	/**
	 * Récupérer un vin
	 * @param int $id
	 * @return array 
	 */
	public function un($id) {
        return $this->lireUn("SELECT cat_nom, vin.* FROM vin JOIN categorie 
            ON vin_cat_id_ce=cat_id WHERE vin_id=:vin_id", ['vin_id'=>$id]);
    }


	/**
	 * Ajouter un vin
	 * @param object $vin
	 * @return int
	 */
	public function ajouter($vin) {
        return $this->creer("INSERT INTO vin 
            (vin_nom, vin_detail, vin_provenance, vin_annee, vin_prix, vin_cat_id_ce) 
            VALUES (?, ?, ?, ?, ?, ?)"
            , [$vin->vin_nom, $vin->vin_detail, $vin->vin_provenance, $vin->vin_annee, $vin->vin_prix, $vin->vin_cat_id_ce]);
    }


	/**
	 * Remplacer/Modifier un vin
	 * @param int $id
	 * @param object $vin
	 * @return int
	 */
	public function remplacer($id, $vin) {
		return $this->modifier("UPDATE vin SET
					vin_nom=?, vin_detail=?, vin_provenance=?, vin_annee=?, vin_prix=?, vin_cat_id_ce=?
					WHERE vin_id=?"
			, [
				$vin->vin_nom, 
				$vin->vin_detail, 
				$vin->vin_provenance,
				$vin->vin_annee, 
				$vin->vin_prix, 
				$vin->vin_cat_id_ce,
				$id
			]);
	}


	/**
	 * Modifier un vin
	 * @param int $id
	 * @param string $paramAChanger
	 * @param string $valeurAInserer
	 * @return int
	 */
	public function changer($id, $paramAChanger, $valeurAInserer) {
		return $this->modifier("UPDATE vin SET $paramAChanger=? WHERE vin_id=?"
			, [
					$valeurAInserer,
					$id
				]);
	}


	/**
	 * Supprimer un vin
	 * @param int $id
	 * @return int
	 */
	public function retirer($id) {
        return $this->supprimer("DELETE FROM vin WHERE vin_id=:vin_id", ['vin_id'=>$id]);
    }
}