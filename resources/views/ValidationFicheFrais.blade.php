@extends('modeles.comptable')

@section('contenu1')
    <!-- formulaire de sélection du mois -->
    <h2>Validation des fiches de frais</h2>
    <h3>Fiches de frais à sélectionner :</h3>

    <form action="{{ route('chemin_ajoutFrais') }}" method="get">
        {{ csrf_field() }} <!-- Laravel ajoute un champ caché avec un token -->

        <div class="corpsForm">
            <p>
                <label for="lstMois">Mois : </label>
                <select id="lstMois" name="lstMois">
                    @foreach($lesMois as $mois)
                        @if ($mois['mois'] == $leMois)
                            <option selected value="{{ $mois['mois'] }}">
                                {{ $mois['numMois'] }}/{{ $mois['numAnnee'] }}
                            </option>
                        @else
                            <option value="{{ $mois['mois'] }}">
                                {{ $mois['numMois'] }}/{{ $mois['numAnnee'] }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </p>
        </div>

        <div class="piedForm">
            <p>
                <input id="ok" type="submit" value="Valider" size="20" />
                <input id="annuler" type="reset" value="Effacer" size="20" />
            </p>
        </div>
    </form>

    <!-- liste des fiches -->
    <div id="listeFiches">
        <h3>Fiches à l'état validé</h3>
        @if(!empty($lesFiches) && count($lesFiches) > 0)
            <table border="1" cellpadding="6" cellspacing="0">
                <thead>
                    <tr>
                        <th>Visiteur</th>
                        <th>Mois</th>
                        <th>Montant</th>
                        <th>Date modif</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lesFiches as $f)
                        <tr>
                            <td>{{ $f['nom'] . ' ' . $f['prenom'] }}</td>
                            <td>{{ $f['mois'] }}</td>
                            <td>{{ $f['montantValide'] }}</td>
                            <td>{{ $f['dateModif'] }}</td>
                            <td>
                                <a href="{{ route('comptable.voirFiche', ['idVisiteur' => $f['idVisiteur'], 'mois' => $f['mois']]) }}">Voir</a>
                                &nbsp;|&nbsp;
                                <a href="{{ route('comptable.telechargerPdf', ['idVisiteur' => $f['idVisiteur'], 'mois' => $f['mois']]) }}">PDF</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucune fiche à afficher.</p>
        @endif
    </div>
@endsection
