<?php
class CategoriesModele extends AccesBd 
{
    /**
	 * Récupérer toutes les catégories
	 * @param array $groupe
	 * @return array
	 */
	public function tout($groupe)
    {
        return $this->lire("SELECT * FROM categorie ORDER by cat_id", $groupe);
    }

	
	/**
	 * Récupérer une catégorie
	 * @param int $id
	 * @return array
	 */
	public function un($id) {
        return $this->lireUn("SELECT categorie.* FROM categorie WHERE cat_id=:cat_id", ['cat_id'=>$id]);
    }


	/**
	 * Ajouter une catégorie
	 * @param object $categorie
	 * @return int
	 */
    public function ajouter($categorie) {
        return $this->creer("INSERT INTO categorie 
            (cat_nom, cat_type) 
            VALUES (?, ?)"
            , [$categorie->cat_nom, $categorie->cat_type]);
    }


	/**
	 * Remplacer/Modifier une catégorie
	 * @param int $id
	 * @param object $categorie
	 * @return int
	 */
    public function remplacer($id, $categorie) {
		return $this->modifier("UPDATE categorie SET
					cat_nom=?, cat_type=? WHERE cat_id=?"
			, [
					$categorie->cat_nom, 
					$categorie->cat_type, 
					$id
				]);
	}	


	/**
	 * Modifier une catégorie
	 * @param int $id
	 * @param string $paramAChanger
	 * @param string $valeurAInserer
	 * @return int
	 */
	public function changer($id, $paramAChanger, $valeurAInserer) {
		return $this->modifier("UPDATE categorie SET $paramAChanger=? WHERE cat_id=?"
			, [
					$valeurAInserer,
					$id
				]);
	}	


	/**
	 * Supprimer une catégorie
	 * @param int $id
	 * @return int
	 */
	public function retirer($id) {
		return $this->supprimer("DELETE FROM categorie WHERE cat_id=:cat_id", ['cat_id'=>$id]);
	}
}