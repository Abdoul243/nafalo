<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', $boutique->nom) - {{ $boutique->nom }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --dark-color: #1e1e2f;
            --light-color: #f8f9fa;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
            color: #2d3436;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(67, 97, 238, 0.2);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .navbar-brand img {
            height: 40px;
            width: auto;
            border-radius: 8px;
            margin-right: 10px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all var(--transition-speed) ease;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
            transform: translateY(-2px);
        }

        .badge-cart {
            position: relative;
            top: -10px;
            left: -5px;
            background: var(--danger-color);
            color: white;
            border-radius: 20px;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all var(--transition-speed) ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(63, 55, 201, 0.1));
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 10px;
            color: var(--primary-color);
        }

        /* Main Content */
        main {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all var(--transition-speed) ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            background: white;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(67, 97, 238, 0.15);
        }

        .card-header {
            background: white;
            border-bottom: 2px solid rgba(67, 97, 238, 0.1);
            padding: 1.25rem;
            font-weight: 600;
        }

        .card-header h5 {
            margin: 0;
            color: var(--dark-color);
        }

        /* Buttons */
        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all var(--transition-speed) ease;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.4);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-color: transparent;
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #4cc9f0, #4895ef);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #f72585, #b5179e);
            border: none;
        }

        /* Forms */
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all var(--transition-speed) ease;
            background: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1, #bee5eb);
            color: #0c5460;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd, #ffe69c);
            color: #856404;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        /* Tables */
        .table {
            margin: 0;
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead th {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-bottom: 2px solid var(--primary-color);
            color: var(--dark-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .table tbody tr {
            transition: all var(--transition-speed) ease;
        }

        .table tbody tr:hover {
            background: rgba(67, 97, 238, 0.05);
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item.active {
            color: var(--dark-color);
            font-weight: 600;
        }

        /* Pagination */
        .pagination {
            gap: 5px;
        }

        .page-link {
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            color: var(--primary-color);
            font-weight: 500;
            transition: all var(--transition-speed) ease;
        }

        .page-link:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        /* Footer */
        footer {
            background: white;
            padding: 3rem 0 2rem;
            margin-top: 4rem;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.05);
        }

        footer h5 {
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        footer h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 3px;
        }

        footer a {
            color: var(--dark-color);
            text-decoration: none;
            transition: all var(--transition-speed) ease;
        }

        footer a:hover {
            color: var(--primary-color);
            padding-left: 5px;
        }

        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 50%;
            margin-right: 10px;
            transition: all var(--transition-speed) ease;
        }

        .social-links a:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            transform: translateY(-3px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .container {
                padding: 0 1rem;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .display-4 {
                font-size: 2rem;
            }
        }
    </style>
    
    @if($boutique->logo)
    <style>
        .navbar-brand {
            display: flex;
            align-items: center;
        }
    </style>
    @endif
    
    @stack('styles')
    
    <!-- Pixels Header -->
    @if(isset($pixelService))
        {!! $pixelService->injecter($boutique, 'header') !!}
    @endif
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('boutique.accueil') }}">
                @if($boutique->logo)
                    <img src="{{ $boutique->logo_url }}" alt="{{ $boutique->nom }}">
                @endif
                <span>{{ $boutique->nom }}</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('boutique.accueil') }}">
                            <i class="fas fa-home me-1"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('boutique.produit.index') }}">
                            <i class="fas fa-box me-1"></i> Produits
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('boutique.panier.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            @php $panierCount = count(session('panier_' . $boutique->id, [])); @endphp
                            @if($panierCount > 0)
                                <span class="badge-cart position-absolute top-0 start-100 translate-middle">
                                    {{ $panierCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    @if(session('client_acces_' . $boutique->id))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i>
                                <span class="ms-1 d-none d-md-inline">Mon compte</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('client.mes-achats.index') }}">
                                        <i class="fas fa-shopping-bag"></i>
                                        Mes achats
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('boutique.produit.index') }}">
                                        <i class="fas fa-box"></i>
                                        Boutique
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('client.acces.deconnexion') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt"></i>
                                            Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.acces.demande') }}">
                                <i class="fas fa-sign-in-alt"></i>
                                <span class="ms-1">Accès clients</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>{{ $boutique->nom }}</h5>
                    <p class="text-muted">{{ Str::limit($boutique->description, 150) }}</p>
                    @if($boutique->reseaux_sociaux)
                        <div class="social-links">
                            @foreach($boutique->reseaux_sociaux as $reseau => $lien)
                                @if($lien)
                                    <a href="{{ $lien }}" target="_blank">
                                        <i class="fab fa-{{ $reseau }}"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('boutique.accueil') }}">
                                <i class="fas fa-chevron-right me-2"></i> Accueil
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('boutique.produit.index') }}">
                                <i class="fas fa-chevron-right me-2"></i> Produits
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('client.acces.demande') }}">
                                <i class="fas fa-chevron-right me-2"></i> Accès clients
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        @if($boutique->email)
                            <li class="mb-2">
                                <i class="fas fa-envelope me-2 text-primary"></i>
                                <a href="mailto:{{ $boutique->email }}">{{ $boutique->email }}</a>
                            </li>
                        @endif
                        @if($boutique->telephone)
                            <li class="mb-2">
                                <i class="fas fa-phone me-2 text-primary"></i>
                                <a href="tel:{{ $boutique->telephone }}">{{ $boutique->telephone }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="text-center text-muted">
                <p class="mb-0">&copy; {{ date('Y') }} {{ $boutique->nom }}. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Animation smooth scroll pour les ancres
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href'))?.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Auto-fermeture des alertes après 5 secondes
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    </script>
    
    @stack('scripts')
    
    <!-- Pixels Footer -->
    @if(isset($pixelService))
        {!! $pixelService->injecter($boutique, 'footer') !!}
    @endif
</body>
</html>
