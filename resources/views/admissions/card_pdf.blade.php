<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 0; /* Supprime les marges par défaut du PDF */
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
        .card {
            width: 242.65pt; /* Équivalent à 8.5cm */
            height: 153pt;   /* Équivalent à 5.4cm */
            padding: 10pt;
            background-color: #fff;
            position: relative;
        }
        .header { 
            background: #4f46e5; 
            color: white; 
            padding: 5pt; 
            text-align: center; 
            font-weight: bold;
            font-size: 10pt;
            border-radius: 3pt;
            margin-bottom: 8pt;
        }
        .photo-container { 
            width: 62pt; 
            height: 80pt; 
            border: 1pt solid #ccc; 
            float: left;
            overflow: hidden;
        }
        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .info { 
            margin-left: 72pt; 
        }
        .name { 
            font-weight: bold; 
            font-size: 11pt; 
            text-transform: uppercase; 
            color: #1f2937;
        }
        .matricule { 
            color: #4f46e5; 
            font-weight: bold; 
            font-size: 9pt; 
            margin-bottom: 4pt;
        }
        .details {
            font-size: 8pt;
            color: #4b5563;
            line-height: 1.2;
        }
        .footer { 
            position: absolute;
            bottom: 8pt;
            left: 0;
            right: 0;
            font-size: 7pt; 
            text-align: center; 
            color: #9ca3af; 
        }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">UniLink - CARTE ÉTUDIANT</div>
        
        <div class="photo-container">
            @if($etudiant->photo && file_exists(public_path('storage/' . $etudiant->photo)))
                <img src="{{ public_path('storage/' . $etudiant->photo) }}">
            @else
                <div style="background: #f3f4f6; width: 100%; height: 100%;"></div>
            @endif
        </div>

        <div class="info">
            <div class="name">{{ $etudiant->nom }} {{ $etudiant->prenom }}</div>
            <div class="matricule">{{ $etudiant->matricule }}</div>
            <div class="details">
                <strong>Filière:</strong> {{ $etudiant->filiere->nom ?? 'N/A' }}<br>
                <strong>Niveau:</strong> {{ $etudiant->niveau->nom ?? 'N/A' }}
            </div>
        </div>

        <div class="clear"></div>
        <div class="footer">Valable pour l'année académique 2025-2026</div>
    </div>
</body>
</html>