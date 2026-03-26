<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche de frais GSB</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #1e4d82; padding-bottom: 10px; }
        .logo { color: #1e4d82; font-size: 24px; font-weight: bold; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; color: #1e4d82; }
        .footer { margin-top: 50px; text-align: right; font-style: italic; border-top: 1px solid #ccc; padding-top: 10px; }
        h3 { color: #1e4d82; border-left: 5px solid #1e4d82; padding-left: 10px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">GSB - État de Frais</div>
        <p>Document récapitulatif des frais engagés</p>
    </div>

    <p><strong>Visiteur ID :</strong> {{ $idVisiteur }}</p>
    <p><strong>Période (Mois) :</strong> {{ $mois }}</p>
    <p><strong>Date d'édition :</strong> {{ date('d/m/Y') }}</p>

    <h3>1. Éléments forfaitisés</h3>
    <table>
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

    <h3>2. Éléments hors forfait</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Libellé</th>
                <th style="text-align: right;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @if(count($lesFraisHorsForfait) > 0)
                @foreach($lesFraisHorsForfait as $unFrais)
                <tr>
                    <td>{{ $unFrais['date'] }}</td>
                    <td>{{ $unFrais['libelle'] }}</td>
                    <td style="text-align: right;">{{ number_format($unFrais['montant'], 2) }} €</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" style="text-align: center;">Aucun frais hors forfait pour ce mois.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Validé par le service comptabilité GSB
    </div>
</body>
</html>