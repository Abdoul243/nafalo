<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Boutiques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <h1 class="mb-4">Boutiques actives</h1>

        @if($boutiques->isEmpty())
            <div class="alert alert-info">Aucune boutique active pour le moment.</div>
        @else
            <div class="row">
                @foreach($boutiques as $boutique)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $boutique->nom }}</h5>
                                <p class="card-text text-muted flex-grow-1">
                                    {{ $boutique->description ?: 'Sans description.' }}
                                </p>
                                @if($boutique->domaine_personnalise)
                                    <a class="btn btn-primary" href="http://{{ $boutique->domaine_personnalise }}/boutique">
                                        Ouvrir la boutique
                                    </a>
                                @else
                                    <span class="text-warning">Domaine personnalisé non configuré</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
