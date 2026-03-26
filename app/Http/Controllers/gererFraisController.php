<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MyApp\PdoGsb;
use Session;
use Barryvdh\DomPDF\Facade\Pdf;

class gererFraisController extends Controller
{
    public function saisirFrais(Request $request) {
        if (Session::has('visiteur')) {
            $visiteur = Session::get('visiteur');
            $idVisiteur = $visiteur['id'];
            $mois = date("Ym");
            $pdo = PdoGsb::getPdoGsb();
            if ($pdo->estPremierFraisMois($idVisiteur, $mois)) {
                $pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
            }
            $lesFrais = $pdo->getLesFraisForfait($idVisiteur, $mois);
            return view('modeles.majFraisForfait')
                ->with('lesFrais', $lesFrais)->with('numMois', date("m"))->with('numAnnee', date("Y"))
                ->with('visiteur', $visiteur)->with('erreurs', null)->with('message', "");
        }
        return redirect()->route('chemin_connexion');
    }

    public function sauvegarderFrais(Request $request) {
        if (Session::has('visiteur')) {
            $visiteur = Session::get('visiteur');
            $idVisiteur = $visiteur['id'];
            $mois = date("Ym");
            $lesFrais = $request->input('lesFrais');
            $pdo = PdoGsb::getPdoGsb();
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
            return redirect()->route('chemin_gestionFrais');
        }
        return redirect()->route('chemin_connexion');
    }

    public function Validerpaiement(Request $request) {
        if (Session::has('comptable')) {
            $comptable = Session::get('comptable');
            $pdo = PdoGsb::getPdoGsb(); 
            $lesVisiteurs = $pdo->getLesVisiteurs();
            $lesMois = $pdo->getTousLesMoisDisponibles();
            $idVisiteurSelectionne = $request->get('idVisiteur');
            $leMoisSelectionne = $request->get('lstMois');
            $lesFiches = [];

            if ($idVisiteurSelectionne && $leMoisSelectionne) {
                $ficheTrouvee = $pdo->getLesInfosFicheFrais($idVisiteurSelectionne, $leMoisSelectionne);
                if ($ficheTrouvee) {
                    // On récupère les infos du visiteur pour ne pas avoir d'erreur "Undefined key nom"
                    $infosVisiteur = null;
                    foreach($lesVisiteurs as $v) {
                        if($v['id'] == $idVisiteurSelectionne) { $infosVisiteur = $v; break; }
                    }
                    $ficheTrouvee['idVisiteur'] = $idVisiteurSelectionne;
                    $ficheTrouvee['mois'] = $leMoisSelectionne;
                    $ficheTrouvee['nom'] = $infosVisiteur['nom'];
                    $ficheTrouvee['prenom'] = $infosVisiteur['prenom'];
                    $lesFiches[] = $ficheTrouvee;
                }
            }
            return view('modeles.ValidationFicheFrais')
                ->with('lesVisiteurs', $lesVisiteurs)->with('lesMois', $lesMois)->with('lesFiches', $lesFiches)
                ->with('idVisiteurSelectionne', $idVisiteurSelectionne)->with('leMoisSelectionne', $leMoisSelectionne)->with('comptable', $comptable);
        }
        return redirect()->route('chemin_connexion');
    }

    public function voirFiche($idVisiteur, $mois) {
        if (Session::has('comptable')) {
            $comptable = Session::get('comptable');
            $pdo = PdoGsb::getPdoGsb();
            $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
            return view('modeles.ficheComptable')
                ->with('lesFraisForfait', $lesFraisForfait)->with('lesFraisHorsForfait', $lesFraisHorsForfait)
                ->with('idVisiteur', $idVisiteur)->with('mois', $mois)->with('comptable', $comptable);
        }
        return redirect()->route('chemin_connexion');
    }

    public function validerFiche($idVisiteur, $mois) {
        if (Session::has('comptable')) {
            $pdo = PdoGsb::getPdoGsb();
            $pdo->validerFicheFrais($idVisiteur, $mois);
            return redirect()->route('comptable.fiches');
        }
        return redirect()->route('chemin_connexion');
    }

    public function telechargerPdf($idVisiteur, $mois) {
        $pdo = PdoGsb::getPdoGsb();
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        $pdf = Pdf::loadView('modeles.fichePdf', [
            'lesFraisForfait' => $lesFraisForfait, 'lesFraisHorsForfait' => $lesFraisHorsForfait,
            'idVisiteur' => $idVisiteur, 'mois' => $mois
        ]);
        return $pdf->download('Fiche_Frais_'.$idVisiteur.'.pdf');
    }
}