<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choisir une boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html, body { overflow-x: hidden; }
        body {
            background: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .selector-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.12);
        }
        @media (max-width: 480px) {
            .selector-card { padding: 1.25rem; border-radius: 14px; }
            .boutique-item:hover { transform: none; }
        }
        .selector-card h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #111;
            margin-bottom: 0.25rem;
        }
        .selector-card .subtitle {
            color: #aaa;
            font-size: 0.875rem;
            margin-bottom: 2rem;
        }
        .boutique-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.2rem;
            border: 1.5px solid #f0f0f0;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: inherit;
            margin-bottom: 0.75rem;
        }
        .boutique-item:hover {
            border-color: #111;
            background: #fafafa;
            color: inherit;
            transform: translateX(4px);
        }
        .boutique-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1rem;
            flex-shrink: 0;
            overflow: hidden;
        }
        .boutique-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .boutique-info { flex: 1; }
        .boutique-name { font-weight: 700; font-size: 0.95rem; color: #111; }
        .boutique-domain { font-size: 0.78rem; color: #aaa; }
        .boutique-arrow { color: #ccc; transition: all 0.2s; }
        .boutique-item:hover .boutique-arrow { color: #111; transform: translateX(3px); }

        .btn-create {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 1rem;
            background: #ffd60a;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.95rem;
            color: #111;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 1rem;
            text-decoration: none;
        }
        .btn-create:hover {
            background: #f0c800;
            color: #111;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,214,10,0.4);
        }
        .close-btn {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: none;
            border: none;
            color: #aaa;
            font-size: 1.2rem;
            cursor: pointer;
            line-height: 1;
        }
        .close-btn:hover { color: #333; }
    </style>
</head>
<body>
    <div class="selector-card position-relative">
        <h2>Changer de boutique</h2>
        <p class="subtitle">Sélectionnez la boutique sur laquelle vous souhaitez travailler</p>

        @foreach($boutiques as $boutique)
        <a href="{{ route('admin.boutiques.select', $boutique->id) }}" class="boutique-item">
            <div class="boutique-avatar">
                @if($boutique->logo)
                    <img src="{{ asset('storage/' . $boutique->logo) }}" alt="{{ $boutique->nom }}">
                @else
                    {{ strtoupper(substr($boutique->nom, 0, 1)) }}
                @endif
            </div>
            <div class="boutique-info">
                <div class="boutique-name">{{ $boutique->nom }}</div>
                <div class="boutique-domain">
                    {{ $boutique->domaine_personnalise ?? 'digital-store.test' }}
                </div>
            </div>
            @if(session('boutique_id') == $boutique->id)
                <span class="badge bg-success" style="font-size:0.7rem;">Active</span>
            @endif
            <i class="fas fa-arrow-right boutique-arrow"></i>
        </a>
        @endforeach

        <a href="{{ route('admin.boutiques.create') }}" class="btn-create">
            <i class="fas fa-plus"></i> Créer une boutique
        </a>
    </div>
</body>
</html>
