@extends('layouts.admin')
@section('title', 'Notifications')

@section('content')
<div class="cw-page">

    <div class="cw-toolbar">
        <div>
            <div style="font-weight:700;color:#111827;">Notifications</div>
            <div style="font-size:.75rem;color:#9ca3af;">Toutes vos alertes et mises à jour</div>
        </div>
        @if(!$notifications->isEmpty())
        <button class="cw-btn-secondary" onclick="marquerToutesLues(); setTimeout(() => location.reload(), 500)">
            <i class="fas fa-check-double"></i> Tout marquer comme lu
        </button>
        @endif
    </div>

    @if($notifications->isEmpty())
    <div class="cw-empty">
        <i class="fas fa-bell-slash"></i>
        <p>Aucune notification pour l'instant</p>
        <span style="font-size:.78rem;color:#9ca3af;">Elles apparaîtront ici lors de nouvelles ventes, avis ou invitations.</span>
    </div>
    @else
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;">
        @foreach($notifications as $notif)
        <div style="display:flex;align-items:flex-start;gap:12px;padding:16px 20px;border-bottom:1px solid #f8fafc;{{ !$notif->estLue() ? 'background:#f8fafc;border-left:3px solid #f59e0b;' : '' }}transition:background .1s;">
            <div style="width:40px;height:40px;border-radius:10px;background:{{ $notif->couleurBg() }};color:{{ $notif->couleur() }};display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0;">
                <i class="{{ $notif->icone() }}"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:.86rem;font-weight:700;color:#111827;margin-bottom:2px;">{{ $notif->titre }}</div>
                <div style="font-size:.8rem;color:#6b7280;line-height:1.5;">{{ $notif->message }}</div>
                <div style="display:flex;align-items:center;gap:8px;margin-top:8px;flex-wrap:wrap;">
                    @if(!$notif->estLue())
                        <span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;display:inline-block;flex-shrink:0;"></span>
                    @endif
                    <span style="font-size:.72rem;color:#9ca3af;">{{ $notif->created_at->diffForHumans() }}</span>
                    @if($notif->lien && !$notif->estLue())
                        <a href="#" onclick="ouvrirNotif({{ $notif->id }}, '{{ $notif->lien }}')"
                           style="display:inline-flex;align-items:center;gap:4px;height:26px;padding:0 10px;font-size:.73rem;font-weight:600;border-radius:7px;border:1px solid #f59e0b;background:#fffbeb;color:#b45309;text-decoration:none;">
                            <i class="fas fa-arrow-right"></i> Voir
                        </a>
                    @elseif($notif->lien)
                        <a href="{{ $notif->lien }}"
                           style="display:inline-flex;align-items:center;gap:4px;height:26px;padding:0 10px;font-size:.73rem;font-weight:600;border-radius:7px;border:1px solid #e5e7eb;background:#f8fafc;color:#374151;text-decoration:none;">
                            <i class="fas fa-arrow-right"></i> Voir
                        </a>
                    @endif
                    @if(!$notif->estLue())
                        <button onclick="ouvrirNotif({{ $notif->id }}, null)"
                                style="display:inline-flex;align-items:center;gap:4px;height:26px;padding:0 10px;font-size:.73rem;font-weight:600;border-radius:7px;border:1px solid #e5e7eb;background:#f8fafc;color:#374151;cursor:pointer;">
                            <i class="fas fa-check"></i> Marquer lu
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="cw-pages">{{ $notifications->links() }}</div>
    @endif

</div>
@endsection
