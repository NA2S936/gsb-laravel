@extends('modeles.visiteur')

@section('menu')
    {{-- On remplace l'include qui plante par le bon nom de fichier --}}
    @include('sommaire') 
@endsection

@section('contenu1')
<div id="contenu">
    <h2>Renseigner ma fiche de frais du mois {{ $numMois }}-{{ $numAnnee }}</h2>
    <form method="POST" action="{{ route('chemin_sauvegardeFrais') }}">
        {{ csrf_field() }}
        <div class="corpsForm">
            <fieldset>
                <legend>Eléments forfaitisés</legend>
                @foreach($lesFrais as $frais)
                    <p>
                        <label style="width: 150px; display: inline-block;">{{ $frais['libelle'] }}</label>
                        <input type="text" 
                               name="lesFrais[{{ $frais['idfrais'] }}]" 
                               size="10" maxlength="5" 
                               value="{{ $frais['quantite'] }}" 
                               class="form-control" 
                               style="width: 100px; display: inline-block;">
                    </p>
                @endforeach
            </fieldset>
        </div>
        <div class="piedForm" style="margin-top: 20px;">
            <p>
                <input id="ok" type="submit" value="Valider" class="btn btn-success">
                <input id="annuler" type="reset" value="Effacer" class="btn btn-danger">
            </p>
        </div>
    </form>
</div>
@endsection