<?php

/*-------------------- Use case connexion visiteur ---------------------------*/
Route::get('/', [
    'as' => 'chemin_connexion',
    'uses' => 'connexionController@connecter'
]);

Route::post('/', [
    'as' => 'chemin_valider',
    'uses' => 'connexionController@valider'
]);

Route::get('deconnexion', [
    'as' => 'chemin_deconnexion',
    'uses' => 'connexionController@deconnecter'
]);

/*-------------------- Use case état des frais ---------------------------*/
Route::get('selectionMois', [
    'as' => 'chemin_selectionMois',
    'uses' => 'etatFraisController@selectionnerMois'
]);

Route::post('listeFrais', [
    'as' => 'chemin_listeFrais',
    'uses' => 'etatFraisController@voirFrais'
]);

/*-------------------- Use case gérer les frais ---------------------------*/
Route::get('gererFrais', [
    'as' => 'chemin_gestionFrais',
    'uses' => 'gererFraisController@saisirFrais'
]);

Route::post('sauvegarderFrais', [
    'as' => 'chemin_sauvegardeFrais',
    'uses' => 'gererFraisController@sauvegarderFrais'
]);

/*-------------------- Routes Comptable ---------------------------*/

// Liste des fiches à valider
Route::get('comptable/fiches', [
    'as' => 'comptable.fiches',
    'uses' => 'gererFraisController@Validerpaiement'
]);

// Consulter le détail d'une fiche (GET)
Route::get('comptable/fiche/{idVisiteur}/{mois}', [
    'as' => 'comptable.voirFiche',
    'uses' => 'gererFraisController@voirFiche'
]);

// ACTION DE VALIDER LA FICHE (POST) <-- C'est celle qui manquait !
Route::post('comptable/fiche/{idVisiteur}/{mois}', [
    'as' => 'comptable.validerFiche',
    'uses' => 'gererFraisController@validerFiche'
]);

// Télécharger en PDF
Route::get('comptable/fiche/{idVisiteur}/{mois}/pdf', [
    'as' => 'comptable.telechargerPdf',
    'uses' => 'gererFraisController@telechargerPdf'
]);