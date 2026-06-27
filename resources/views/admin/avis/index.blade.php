@extends('layouts.admin')
@section('title', 'Avis clients')

@push('styles')
<style>
.avis-stars i { color: #e5e7eb; font-size: .72rem; }
.avis-stars i.filled { color: #f59e0b; }
.avis-comment {
    font-size: .83rem; color: #374151; line-height: 1.5;
    font-style: italic; margin: 6px 0 0;
}
.btn-toggle-vis {
    height: 30px; padding: 0 12px; font-size: .75rem; font-weight: 600;
    border-radius: 7px; border: 1px solid #e5e7eb; background: #f9fafb;
    color: #6b7280; cursor: pointer; display: inline-flex; align-items: center;
    gap: 5px; transition: all .15s;
}
.btn-toggle-vis.visible { border-color: #a7f3d0; background: #ecfdf5; color: #059669; }
.btn-toggle-vis:hover   { border-color: #374151; color: #111827; background: #fff; }
</style>
@endpush

@section('content')
<div class="cw-page">

    {{-- Toolbar --}}
    <div class="cw-toolbar">
        <div style="display:flex;gap:16px;align-items:center;flex:1;flex-wrap:wrap;">
            <div style="display:flex;gap:12px;align-items:center;">
                <div style="text-align:center;">
                    <div style="font-size:1.4rem;font-weight:800;color:#111827;">{{ $stats['total'] }}</div>
                    <div style="font-size:.7rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">Total</div>
                </div>
                <div style="width:1px;height:30px;background:#f1f5f9;"></div>
                <div style="text-align:center;">
                    <div style="font-size:1.4rem;font-weight:800;color:#059669;">{{ $stats['visibles'] }}</div>
                    <div style="font-size:.7rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">Visibles</div>
                </div>
                <div style="width:1px;height:30px;background:#f1f5f9;"></div>
                <div style="text-align:center;">
                    <div style="font-size:1.4rem;font-weight:800;color:#f59e0b;">{{ $stats['moyenne'] }}/5</div>
                    <div style="font-size:.7rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">Moyenne</div>
                </div>
            </div>
        </div>

        <form method="GET" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <select name="note" onchange="this.form.submit()" style="height:38px;font-size:.82rem;border:1px solid #e5e7eb;border-radius:10px;padding:0 10px;background:#f9fafb;outline:none;">
                <option value="">⭐ Toutes notes</option>
                @for($n = 5; $n >= 1; $n--)
                <option value="{{ $n }}" {{ request('note') == $n ? 'selected' : '' }}>{{ $n }} étoile{{ $n > 1 ? 's' : '' }}</option>
                @endfor
            </select>
            <select name="est_visible" onchange="this.form.submit()" style="height:38px;font-size:.82rem;border:1px solid #e5e7eb;border-radius:10px;padding:0 10px;background:#f9fafb;outline:none;">
                <option value="">Tous statuts</option>
                <option value="1" {{ request('est_visible') === '1' ? 'selected' : '' }}>Visibles</option>
                <option value="0" {{ request('est_visible') === '0' ? 'selected' : '' }}>Masqués</option>
            </select>
            @if(request()->hasAny(['note','est_visible']))
            <a href="{{ route('admin.avis.index') }}" class="cw-btn-secondary"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="cw-table-wrap">
        <table class="cw-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Produit</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th style="width:100px;"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($avis as $avi)
            @php
                $nom = $avi->client->nom ?? 'Anonyme';
                $colors = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6'];
                $color  = $colors[crc32($nom) % count($colors)];
                $init   = strtoupper(substr($nom, 0, 1));
            @endphp
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:9px;">
                        <div class="cw-avatar" style="background:{{ $color }};font-size:.72rem;">{{ $init }}</div>
                        <div>
                            <div style="font-weight:600;color:#111827;font-size:.83rem;">{{ $nom }}</div>
                            <div style="font-size:.71rem;color:#9ca3af;">{{ $avi->client->email ?? '' }}</div>
                        </div>
                    </div>
                </td>
                <td style="font-size:.8rem;color:#374151;max-width:160px;">
                    <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Str::limit($avi->produit->nom, 28) }}</div>
                </td>
                <td>
                    <div class="avis-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $avi->note ? 'filled' : '' }}"></i>
                        @endfor
                    </div>
                </td>
                <td style="max-width:220px;">
                    @if($avi->commentaire)
                        <div class="avis-comment">"{{ Str::limit($avi->commentaire, 80) }}"</div>
                    @else
                        <span style="color:#9ca3af;font-size:.78rem;font-style:italic;">—</span>
                    @endif
                </td>
                <td style="color:#6b7280;font-size:.78rem;white-space:nowrap;">{{ $avi->created_at->format('d M Y') }}</td>
                <td>
                    @if($avi->est_visible)
                        <span class="cw-badge cw-badge-green"><span class="cw-dot" style="background:#059669;"></span> Visible</span>
                    @else
                        <span class="cw-badge cw-badge-gray"><span class="cw-dot" style="background:#9ca3af;"></span> Masqué</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:5px;justify-content:flex-end;">
                        <button class="btn-toggle-vis {{ $avi->est_visible ? 'visible' : '' }} toggle-vis"
                                data-id="{{ $avi->id }}" title="{{ $avi->est_visible ? 'Masquer' : 'Afficher' }}">
                            <i class="fas {{ $avi->est_visible ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            {{ $avi->est_visible ? 'Masquer' : 'Afficher' }}
                        </button>
                        <button class="cw-btn-row" style="color:#dc2626;border-color:#fecaca;"
                                data-confirm-message="Supprimer cet avis ?"
                                data-target-form="del-{{ $avi->id }}" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="del-{{ $avi->id }}" action="{{ route('admin.avis.destroy', $avi) }}" method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="cw-empty">
                        <i class="fas fa-star"></i>
                        <p>Aucun avis trouvé</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($avis->hasPages())
    <div class="cw-pages">{{ $avis->withQueryString()->links() }}</div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.toggle-vis').forEach(btn => {
    btn.addEventListener('click', function() {
        fetch(`{{ url('admin/avis') }}/${this.dataset.id}/toggle-visibilite`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        }).then(() => location.reload());
    });
});
</script>
@endpush
