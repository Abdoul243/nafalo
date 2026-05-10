@extends('layouts.admin')

@section('title', 'Statistiques des ventes')

@push('styles')
<style>
/* Filtres responsive */
@media (max-width: 640px) {
    .stats-filter-row > [class*="col-md-"] { flex: 0 0 100%; max-width: 100%; }
}
/* Stat cards: 3→1 col sur très petit écran */
@media (max-width: 575px) {
    .stats-kpi-row > [class*="col-md-"] { flex: 0 0 100%; max-width: 100%; }
}
/* Charts: empilés */
@media (max-width: 767px) {
    .stats-charts-row > [class*="col-md-"] { flex: 0 0 100%; max-width: 100%; }
    #ventesChart { height: 220px !important; }
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Statistiques des ventes</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 stats-filter-row">
            <div class="col-md-3">
                <label for="date_debut" class="form-label">Date début</label>
                <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ $dateDebut }}">
            </div>
            <div class="col-md-3">
                <label for="date_fin" class="form-label">Date fin</label>
                <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{ $dateFin }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-chart-line"></i> Actualiser
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4 g-2 stats-kpi-row">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total des ventes</h6>
                <h2>{{ $stats['total_ventes'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Chiffre d'affaires</h6>
                <h2>{{ number_format($stats['chiffre_affaires'], 2) }} FCFA</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Panier moyen</h6>
                <h2>{{ number_format($stats['panier_moyen'], 2) }} FCFA</h2>
            </div>
        </div>
    </div>
</div>

<div class="row stats-charts-row">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Évolution des ventes</h5>
            </div>
            <div class="card-body">
                <canvas id="ventesChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Top 10 produits</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Ventes</th>
                            <th>Quantité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProduits as $produit)
                        <tr>
                            <td>{{ $produit->produit->nom }}</td>
                            <td>{{ $produit->total_ventes }}</td>
                            <td>{{ $produit->quantite_totale }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ventesCtx = document.getElementById('ventesChart').getContext('2d');
    const ventesData = @json($ventesParJour);
    
    new Chart(ventesCtx, {
        type: 'line',
        data: {
            labels: ventesData.map(item => item.date),
            datasets: [{
                label: 'Nombre de ventes',
                data: ventesData.map(item => item.total),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                yAxisID: 'y'
            }, {
                label: 'Chiffre d\'affaires (FCFA)',
                data: ventesData.map(item => item.montant),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Nombre de ventes'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Chiffre d\'affaires (FCFA)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
});
</script>
@endpush
