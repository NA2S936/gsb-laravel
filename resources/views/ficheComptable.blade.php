@extends('modeles.comptable')

@section('contenu1')
    <h2>Fiche de frais - Détails</h2>

    <p><strong>Visiteur :</strong> {{ $idVisiteur }} &nbsp; <strong>Mois :</strong> {{ $mois }}</p>

    @if($lesInfos)
        <p><strong>Etat :</strong> {{ $lesInfos['libEtat'] ?? '' }} - <strong>Date modif :</strong> {{ $lesInfos['dateModif'] ?? '' }}</p>
        <p><strong>Montant validé :</strong> {{ $lesInfos['montantValide'] ?? '' }}</p>
    @endif

    <h3>Frais au forfait</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Libellé</th>
                <th>Quantité</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lesFraisForfait as $f)
                <tr>
                    <td>{{ $f['libelle'] }}</td>
                    <td>{{ $f['quantite'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top:1em;">
        <a href="{{ route('comptable.telechargerPdf', ['idVisiteur' => $idVisiteur, 'mois' => $mois]) }}">Télécharger en PDF</a>
        &nbsp;|&nbsp;
        <a href="{{ route('comptable.fiches') }}">Retour à la liste</a>
    </p>
@endsection
