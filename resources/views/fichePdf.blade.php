<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Fiche de frais</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; }
        h2, h3 { margin: 0 0 8px 0; }
    </style>
</head>
<body>
    <h2>Fiche de frais</h2>
    <p><strong>Visiteur :</strong> {{ $idVisiteur }} &nbsp; <strong>Mois :</strong> {{ $mois }}</p>

    @if($lesInfos)
        <p><strong>Etat :</strong> {{ $lesInfos['libEtat'] ?? '' }} - <strong>Date modif :</strong> {{ $lesInfos['dateModif'] ?? '' }}</p>
        <p><strong>Montant validé :</strong> {{ $lesInfos['montantValide'] ?? '' }}</p>
    @endif

    <h3>Frais au forfait</h3>
    <table>
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
                    <td style="text-align:center">{{ $f['quantite'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
