<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use PdoGsb;
use MyDate;
class gererFraisController extends Controller{

    function saisirFrais(Request $request){
        if( session('visiteur') != null){
            $visiteur = session('visiteur');
            $idVisiteur = $visiteur['id'];
            $anneeMois = MyDate::getAnneeMoisCourant();
            $mois = $anneeMois['mois'];
            if(PdoGsb::estPremierFraisMois($idVisiteur,$mois)){
                 PdoGsb::creeNouvellesLignesFrais($idVisiteur,$mois);
            }
            $lesFrais = PdoGsb::getLesFraisForfait($idVisiteur,$mois);
            $view = view('majFraisForfait')
                    ->with('lesFrais', $lesFrais)
                    ->with('numMois',$anneeMois['numMois'])
                    ->with('erreurs',null)
                    ->with('numAnnee',$anneeMois['numAnnee'])
                    ->with('visiteur',$visiteur)
                    ->with('message',"")
                    ->with ('method',$request->method());
            return $view;
        }
        else{
            return view('connexion')->with('erreurs',null);
        }
    }
    function sauvegarderFrais(Request $request){
        if( session('visiteur')!= null){
            $visiteur = session('visiteur');
            $idVisiteur = $visiteur['id'];
            $anneeMois = MyDate::getAnneeMoisCourant();
            $mois = $anneeMois['mois'];
            $lesFrais = $request['lesFrais'];
            $lesLibFrais = $request['lesLibFrais'];
            $nbNumeric = 0;
            foreach($lesFrais as $unFrais){
                if(is_numeric($unFrais))
                    $nbNumeric++;
            }
            $view = view('majFraisForfait')->with('lesFrais', $lesFrais)
                    ->with('numMois',$anneeMois['numMois'])
                    ->with('numAnnee',$anneeMois['numAnnee'])
                    ->with('visiteur',$visiteur)
                    ->with('lesLibFrais',$lesLibFrais)
                    ->with ('method',$request->method());
            if($nbNumeric == 4){
                $message = "Votre fiche a été mise à jour";
                $erreurs = null;
                PdoGsb::majFraisForfait($idVisiteur,$mois,$lesFrais);
        	}
		    else{
                $erreurs[] ="Les valeurs des frais doivent être numériques";
                $message = '';
            }
            return $view->with('erreurs',$erreurs)
                        ->with('message',$message);
        }
        else{
            return view('connexion')->with('erreurs',null);
        }
    }

    public function validerFrais(Request $request){
        if( session('visiteur')!= null){
            $visiteur = session('visiteur');
            $idVisiteur = $visiteur['id'];
            $anneeMois = MyDate::getAnneeMoisCourant();
            $mois = $anneeMois['mois'];
            $lesFrais = PdoGsb::getLesFraisForfait($idVisiteur,$mois);
            $lesLibFrais = PdoGsb::getLibelleFrais();
            $view = view('majFraisForfait')->with('lesFrais', $lesFrais)
                    ->with('numMois',$anneeMois['numMois'])
                    ->with('numAnnee',$anneeMois['numAnnee'])
                    ->with('visiteur',$visiteur)
                    ->with('lesLibFrais',$lesLibFrais)
                    ->with ('method',$request->method())
                    ->with('erreurs',null)
                    ->with('message',"Votre fiche a été validée");
            PdoGsb::validerFicheFrais($idVisiteur,$mois);
            return $view;
        }
        else{
            return view('connexion')->with('erreurs',null);
        }
    }

    public function Validerpaiement(Request $request){
    if(session('comptable') != null){
        $comptable = session('comptable');

        $pdo = new \App\MyApp\PdoGsb();

        // Récupère toutes les fiches à valider
        $lesFiches = $pdo->getLesFichesValider();

        // Récupère tous les mois disponibles pour le comptable/visiteur
        $lesMois = $pdo->getLesMoisDisponibles($comptable['id']); // si tu veux les mois

        // Choisir le mois par défaut (le plus récent)
        $leMois = !empty($lesMois) ? array_key_first($lesMois) : null;

        return view('ValidationFicheFrais')
            ->with('lesFiches', $lesFiches)
            ->with('lesMois', $lesMois)
            ->with('leMois', $leMois)
            ->with('comptable', $comptable);

    } else{
        return view('connexion')->with('erreurs', null);
    }
}


    /**
     * Affiche les détails d'une fiche pour un comptable
     * URL: /comptable/fiche/{idVisiteur}/{mois}
     */
    public function voirFiche($idVisiteur, $mois){
        if(session('comptable') != null){
            $comptable = session('comptable');
            $pdo = new \App\MyApp\PdoGsb();
            $lesInfos = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
            $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
            return view('ficheComptable')
                ->with('lesInfos', $lesInfos)
                ->with('lesFraisForfait', $lesFraisForfait)
                ->with('idVisiteur', $idVisiteur)
                ->with('mois', $mois)
                ->with('comptable', $comptable);
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    /**
     * Télécharge la fiche en PDF. Si la librairie DOMPDF (barryvdh) est installée, génère un vrai PDF.
     * Sinon renvoie la vue en téléchargement HTML comme solution de secours.
     */
    public function telechargerPdf($idVisiteur, $mois){
        if(session('comptable') != null){
            $pdo = new \App\MyApp\PdoGsb();
            $lesInfos = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
            $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
            $data = [
                'lesInfos' => $lesInfos,
                'lesFraisForfait' => $lesFraisForfait,
                'idVisiteur' => $idVisiteur,
                'mois' => $mois,
            ];

            // Si la facade existe (package barryvdh/laravel-dompdf installé)
            if(class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')){
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('fichePdf', $data);
                $filename = 'fiche_'.$idVisiteur.'_'.$mois.'.pdf';
                return $pdf->download($filename);
            }

            // Sinon, proposer le rendu HTML en téléchargement (solution de secours)
            $html = view('fichePdf')->with($data)->render();
            $filename = 'fiche_'.$idVisiteur.'_'.$mois.'.html';
            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
        }
        return view('connexion')->with('erreurs', null);
    }


}














