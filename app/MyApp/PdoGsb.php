<?php
namespace App\MyApp;
use PDO;

/**
 * Classe d'accès aux données. 
 * Utilise des services de la classe PDO
 * pour l'application GSB
 */
class PdoGsb {   
    private static $serveur = 'mysql:host=localhost';
    private static $bdd = 'dbname=gsbfrais';   
    private static $user = 'root';    
    private static $mdp = '';    
    private $monPdo;
    private static $monPdoGsb = null;

    /**
     * Constructeur public pour l'injection Laravel
     */
    public function __construct() {
        $this->monPdo = new PDO(self::$serveur.';'.self::$bdd, self::$user, self::$mdp); 
        $this->monPdo->query("SET CHARACTER SET utf8");
    }

    public function _destruct() {
        $this->monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     */
    public static function getPdoGsb() {
        if (self::$monPdoGsb == null) {
            self::$monPdoGsb = new PdoGsb();
        }
        return self::$monPdoGsb;  
    }

    /**
     * Retourne les informations d'un visiteur
     */
    public function getInfosVisiteur($login, $mdp) {
        $req = "select id, nom, prenom from visiteur 
                where login='$login' and mdp='$mdp'";
        $rs = $this->monPdo->query($req);
        $ligne = $rs->fetch();
        return $ligne;
    }

    /**
     * Retourne les informations d'un comptable
     */
    public function getInfosComptable($login, $mdp) {
        $req = "select id, nom, prenom from comptable 
                where login='$login' and mdp='$mdp'";
        $rs = $this->monPdo->query($req);
        $ligne = $rs->fetch();
        return $ligne;
    }

    /**
     * Retourne les frais forfaitisés d'un visiteur
     */
    public function getLesFraisForfait($idVisiteur, $mois) {
        $req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
                lignefraisforfait.quantite as quantite 
                from lignefraisforfait 
                inner join fraisforfait on fraisforfait.id = lignefraisforfait.idfraisforfait
                where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
                order by lignefraisforfait.idfraisforfait"; 
        $res = $this->monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }

    /**
     * Retourne les frais hors forfait (Celle qui manquait sur ton image 1 !)
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois) {
        $req = "select * from lignefraishorsforfait 
                where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
                and lignefraishorsforfait.mois = '$mois' ";
        $res = $this->monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }

    /**
     * Met à jour les frais forfaitisés
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais) {
        $lesCles = array_keys($lesFrais);
        foreach($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $req = "update lignefraisforfait set quantite = $qte 
                    where idvisiteur = '$idVisiteur' and mois = '$mois' 
                    and idfraisforfait = '$unIdFrais'";
            $this->monPdo->exec($req);
        }
    }

    /**
     * Teste si c'est la première fiche du mois
     */
    public function estPremierFraisMois($idVisiteur, $mois) {
        $ok = false;
        $req = "select count(*) as nblignesfrais from fichefrais 
                where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
        $res = $this->monPdo->query($req);
        $laLigne = $res->fetch();
        if ($laLigne['nblignesfrais'] == 0) { $ok = true; }
        return $ok;
    }

    /**
     * Crée une nouvelle fiche
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois) {
        $lastModif = date('Y-m-d');
        $req = "insert into fichefrais(idvisiteur, mois, nbjustificatifs, montantvalide, datemodif, idetat) 
                values('$idVisiteur','$mois',0,0,'$lastModif','CR')";
        $this->monPdo->exec($req);
        $reqId = "select id from fraisforfait order by id";
        $resId = $this->monPdo->query($reqId);
        $lesIdFrais = $resId->fetchAll();
        foreach($lesIdFrais as $unIdFrais) {
            $id = $unIdFrais['id'];
            $req = "insert into lignefraisforfait(idvisiteur, mois, idfraisforfait, quantite) 
                    values('$idVisiteur','$mois','$id',0)";
            $this->monPdo->exec($req);
        }
    }

    /**
     * Infos de la fiche de frais
     */
    public function getLesInfosFicheFrais($idVisiteur,$mois) {
        $req = "select fichefrais.idEtat as idEtat, fichefrais.dateModif as dateModif, 
                fichefrais.nbJustificatifs as nbJustificatifs, 
                fichefrais.montantValide as montantValide, etat.libelle as libEtat 
                from  fichefrais inner join etat on fichefrais.idEtat = etat.id 
                where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
        $res = $this->monPdo->query($req);
        $laLigne = $res->fetch();
        return $laLigne;
    }

    /**
     * Valider la fiche (VA)
     */
    public function validerFicheFrais($idVisiteur, $mois) {
        $req = "update fichefrais set idEtat = 'VA', dateModif = now() 
                where idvisiteur = '$idVisiteur' and mois = '$mois'";
        $this->monPdo->exec($req);
    }

    /**
     * Liste des visiteurs
     */
    public function getLesVisiteurs() {
        $req = "select id, nom, prenom from visiteur order by nom";
        $res = $this->monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }

    /**
     * Liste des mois
     */
    public function getTousLesMoisDisponibles() {
        $req = "select distinct mois as mois from fichefrais order by mois desc";
        $res = $this->monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }
}
