<?php
// Front Controller (Contrôleur Pilote)
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");

$urlRequete = $_SERVER['REQUEST_URI'];

$routeur = new Routeur(
    parse_url($urlRequete, PHP_URL_PATH),
    $_GET,
    $_SERVER['REQUEST_METHOD']
);

$routeur->invoquerRoute();

class Routeur 
{
    private $route = '';
    private $params = '';
    private $methode = '';

    function __construct($r, $p, $m)
    {
        $this->route = $r;
        $this->params = $p;
        $this->methode = $m;

        // Autochargement des fichiers de classe
        spl_autoload_register(function($nomClasse) {
            $nomFichier = "$nomClasse.cls.php";
            if(file_exists("controleurs/$nomFichier")) {
                include("controleurs/$nomFichier");
            }
            else if(file_exists("modeles/$nomFichier")) {
                include("modeles/$nomFichier");
            }
        });
    }

    public function invoquerRoute()
    {
        $collection = "plats";
        $idEntite = "";

        $partiesRoute = explode('/', $this->route);
    
        if(count($partiesRoute) > 5 && trim(urldecode($partiesRoute[5])) != '') {
            $collection = trim(urldecode($partiesRoute[5]));
            if(count($partiesRoute) > 6 && trim(urldecode($partiesRoute[6])) != '') {
                $idEntite = trim(urldecode($partiesRoute[6]));
            }
        }

        $nomControleur = ucfirst($collection).'Controleur';
        $nomModele = ucfirst($collection).'Modele';

        if(class_exists($nomControleur)) {
            $controleur = new $nomControleur($nomModele);
            switch($this->methode) {
                case 'GET': 
                    if(is_numeric($idEntite)) {
                        $controleur->un($idEntite);
                    }
                    else {
                        $controleur->tout($this->params);
                    }
                    break;
                case 'POST': 
                    $controleur->ajouter(file_get_contents('php://input'));
                    break;
                case 'PUT': 
                    if(is_numeric($idEntite)) {
                        $controleur->remplacer($idEntite, file_get_contents('php://input'));
                    }
                    else {
                       // TODO: à implémenter
                    }
                    break;
                case 'PATCH': 
                    if(is_numeric($idEntite)) {
                        $controleur->changer($idEntite, file_get_contents('php://input'));
                    }
                    else {
                       // TODO: à implémenter
                    }
                    break;
                case 'DELETE': 
                    if(is_numeric($idEntite)) {
                        $controleur->retirer($idEntite);
                    }
                    else {
                       // TODO: à implémenter
                    }
                    break;
            }
        } 
        else {
            exit("Mauvaise requête (à compléter)");
        }
    }
}