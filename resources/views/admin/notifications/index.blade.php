@extends('layouts.admin')
@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">🔔 Notifications</h1>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Toutes vos alertes et mises à jour</p>
    </div>
    <button onclick="marquerToutesLues(); setTimeout(() => location.reload(), 500)"
        class="btn btn-light" style="font-size:0.85rem;border-radius:10px;">
        <i class="fas fa-check-double me-1"></i> Tout marquer comme lu
    </button>
</div>

@if($notifications->isEmpty())
    <div class="card text-center py-5">
        <div class="card-body">
            <div style="font-size:3rem;margin-bottom:1rem;">🔕</div>
            <h5 class="fw-bold">Aucune notification</h5>
            <p class="text-muted">Vous n'avez pas encore de notifications. Elles apparaîtront ici lors de nouvelles ventes, avis, ou invitations.</p>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body p-0">
            @foreach($notifications as $notif)
            <div class="d-flex gap-3 p-3 {{ !$notif->estLue() ? 'border-start border-primary border-3' : '' }}"
                 style="{{ !$notif->estLue() ? 'background:#fafbff;' : '' }} border-bottom: 1px solid #f1f5f9;">
                <div style="width:42px;height:42px;border-radius:12px;background:{{ $notif->couleurBg() }};color:{{ $notif->couleur() }};display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
                    <i class="{{ $notif->icone() }}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div>
                            <div class="fw-bold" style="font-size:0.88rem;color:#0f172a;">{{ $notif->titre }}</div>
                            <div class="text-muted mt-1" style="font-size:0.82rem;line-height:1.5;">{{ $notif->message }}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            @if(!$notif->estLue())
                                <span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>
                            @endif
                            <span class="text-muted" style="font-size:0.72rem;white-space:nowrap;">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="mt-2 d-flex gap-2">
                        @if($notif->lien && !$notif->estLue())
                            <a href="#" onclick="ouvrirNotif({{ $notif->id }}, '{{ $notif->lien }}')"
                               class="btn btn-sm btn-primary" style="font-size:0.75rem;border-radius:8px;padding:3px 10px;">
                                <i class="fas fa-arrow-right me-1"></i> Voir
                            </a>
                        @elseif($notif->lien)
                            <a href="{{ $notif->lien }}" class="btn btn-sm btn-light" style="font-size:0.75rem;border-radius:8px;padding:3px 10px;">
                                <i class="fas fa-arrow-right me-1"></i> Voir
                            </a>
                        @endif
                        @if(!$notif->estLue())
                            <button onclick="ouvrirNotif({{ $notif->id }}, null)"
                                class="btn btn-sm btn-light" style="font-size:0.75rem;border-radius:8px;padding:3px 10px;">
                                <i class="fas fa-check me-1"></i> Marquer lu
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="mt-3">{{ $notifications->links() }}</div>
@endif
@endsection
