@extends('modeles.comptable')

@section('contenu1')
<div id="contenu">
    <h2>Validation des fiches de frais</h2>
    <div class="corpsForm">
        <form action="{{ route('comptable.fiches') }}" method="GET">
            <p>
                <label for="idVisiteur">Visiteur : </label>
                <select id="idVisiteur" name="idVisiteur" class="form-control">
                    <option value="">-- Choisir un visiteur --</option>
                    @foreach($lesVisiteurs as $unVisiteur)
                        <option value="{{ $unVisiteur['id'] }}" {{ ($idVisiteurSelectionne == $unVisiteur['id']) ? 'selected' : '' }}>
                            {{ $unVisiteur['nom'] }} {{ $unVisiteur['prenom'] }}
                        </option>
                    @endforeach
                </select>
            </p>
            <p>
                <label for="lstMois">Mois : </label>
                <select id="lstMois" name="lstMois" class="form-control">
                    <option value="">-- Choisir un mois --</option>
                    @foreach($lesMois as $unMois)
                        @php
                            $moisBrut = $unMois['mois'];
                            $numAnnee = substr($moisBrut, 0, 4);
                            $numMois = substr($moisBrut, 4, 2);
                        @endphp
                        <option value="{{ $moisBrut }}" {{ ($leMoisSelectionne == $moisBrut) ? 'selected' : '' }}>
                            {{ $numMois }}/{{ $numAnnee }}
                        </option>
                    @endforeach
                </select>
            </p>
            <div class="piedForm">
                <input id="ok" type="submit" value="Chercher" class="btn btn-success">
            </div>
        </form>
    </div>

    @if($idVisiteurSelectionne && $leMoisSelectionne && count($lesFiches) > 0)
    <div id="listeFiches" style="margin-top: 20px;">
        <h3>Résultats de la recherche</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Visiteur</th>
                    <th>Mois</th>
                    <th>État</th>
                    <th>Montant</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lesFiches as $uneFiche)
                <tr>
                    <td>{{ $uneFiche['nom'] }} {{ $uneFiche['prenom'] }}</td>
                    <td>{{ $uneFiche['mois'] }}</td>
                    <td>{{ $uneFiche['libEtat'] }}</td>
                    <td>{{ $uneFiche['montantValide'] }} €</td>
                    <td>
                        <a href="{{ route('comptable.voirFiche', ['idVisiteur' => $uneFiche['idVisiteur'], 'mois' => $uneFiche['mois']]) }}">
                           Consulter
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @elseif($idVisiteurSelectionne && $leMoisSelectionne)
        <p style="color:red; margin-top:20px;">Aucune fiche trouvée pour ce visiteur ce mois-là.</p>
    @endif
</div>
@endsection