@extends('modeles.comptable')

@section('contenu1')
<div id="contenu">
    <h2>Détail de la fiche de frais</h2>
    <h3>Visiteur : {{ $idVisiteur }} - Mois : {{ $mois }}</h3>

    <div class="encadre">
        <fieldset>
            <legend>Eléments forfaitisés</legend>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        @foreach($lesFraisForfait as $unFrais)
                            <th>{{ $unFrais['libelle'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($lesFraisForfait as $unFrais)
                            <td style="text-align: center;">{{ $unFrais['quantite'] }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </div>

    <div class="encadre" style="margin-top: 20px;">
        <fieldset>
            <legend>Descriptif des éléments hors forfait</legend>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Libellé</th>
                        <th style="text-align: right;">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($lesFraisHorsForfait) > 0)
                        @foreach($lesFraisHorsForfait as $unFraisHorsForfait)
                            <tr>
                                <td>{{ $unFraisHorsForfait['date'] }}</td>
                                <td>{{ $unFraisHorsForfait['libelle'] }}</td>
                                <td style="text-align: right;">{{ number_format($unFraisHorsForfait['montant'], 2) }} €</td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="3" style="text-align: center;">Aucun frais hors forfait.</td></tr>
                    @endif
                </tbody>
            </table>
        </fieldset>
    </div>

    <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 15px; align-items: center;">
        <a href="{{ route('comptable.telechargerPdf', ['idVisiteur' => $idVisiteur, 'mois' => $mois]) }}" 
           class="btn btn-success" 
           style="padding: 6px 15px; text-decoration: none; border-radius: 4px; min-width: 160px; text-align: center; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
           Enregistrer en PDF
        </a>

        <form action="{{ route('comptable.validerFiche', ['idVisiteur' => $idVisiteur, 'mois' => $mois]) }}" method="POST" style="margin: 0;">
            {{ csrf_field() }}
            <input type="submit" value="Valider la fiche" class="btn btn-success" 
                   style="padding: 6px 15px; cursor: pointer; border-radius: 4px; min-width: 160px; height: 34px;"
                   onclick="return confirm('Voulez-vous valider cette fiche ?');">
        </form>
    </div>
</div>
@endsection