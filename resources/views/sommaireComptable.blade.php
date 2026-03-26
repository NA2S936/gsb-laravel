@extends('modeles.visiteur')

@section('menu')
    <div id="menuGauche">
        <div id="infosUtil">
        </div>  
        <ul id="menuList">
            <li>
                <strong>Bonjour {{ $comptable['nom'] . ' ' . $comptable['prenom'] }}</strong>
            </li>
            <li class="smenu">
                {{-- On utilise 'comptable.fiches' pour correspondre à ton web.php --}}
                <a href="{{ route('comptable.fiches') }}" title="Validation d'une fiche de frais">Validation d'une fiche de frais</a>
            </li>
            <li class="smenu">
                <a href="{{ route('chemin_deconnexion') }}" title="Se déconnecter">Déconnexion</a>
            </li>
        </ul>
    </div>
@endsection