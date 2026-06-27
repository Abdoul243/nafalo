@extends('layouts.admin')
@section('title', 'Pixels marketing')

@push('styles')
<style>
.px-card { background:#fff;border:1.5px solid #f0f0f0;border-radius:16px;overflow:hidden;transition:all .2s;box-shadow:0 2px 10px rgba(0,0,0,.04); }
.px-card.active { border-color: #f59e0b44; box-shadow:0 4px 20px rgba(245,158,11,.1); }
.px-card-head { display:flex;align-items:center;gap:12px;padding:16px 20px;cursor:pointer; }
.px-icon { width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0; }
.px-info h6 { font-weight:700;font-size:.9rem;color:#111827;margin:0 0 2px; }
.px-info p  { font-size:.78rem;color:#9ca3af;margin:0;line-height:1.4; }
.px-toggle { position:relative;width:46px;height:26px;flex-shrink:0; }
.px-toggle input { opacity:0;width:0;height:0; }
.px-slider { position:absolute;inset:0;background:#e5e7eb;border-radius:26px;cursor:pointer;transition:.3s; }
.px-slider:before { content:'';position:absolute;width:20px;height:20px;border-radius:50%;background:#fff;left:3px;top:3px;transition:.3s;box-shadow:0 1px 4px rgba(0,0,0,.15); }
.px-toggle input:checked + .px-slider { background:#f59e0b; }
.px-toggle input:checked + .px-slider:before { transform:translateX(20px); }
.px-body { padding:0 20px;max-height:0;overflow:hidden;transition:max-height .3s ease,padding .3s ease; }
.px-body.open { max-height:300px;padding:0 20px 20px; }
.px-body label { font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:5px; }
.px-body input,.px-body textarea { width:100%;border:1.5px solid #e5e7eb;border-radius:10px;padding:8px 12px;font-size:.85rem;outline:none;transition:border .2s; }
.px-body input:focus,.px-body textarea:focus { border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.08); }
</style>
@endpush

@section('content')
<div class="cw-page">

    <div style="display:grid;grid-template-columns:1fr 420px;gap:32px;align-items:start;">

        {{-- Left: pixels existants --}}
        <div>
            <p style="font-size:.83rem;color:#6b7280;margin-bottom:20px;line-height:1.6;">
                Ajoutez des pixels de suivi comme Facebook Pixel, Google Tag Manager, TikTok Pixel
                et du JavaScript personnalisé pour mesurer les performances de votre boutique.
            </p>

            @if($pixels->count() > 0)
            <div style="margin-bottom:24px;">
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:12px;">Pixels configurés</div>
                @foreach($pixels as $pixel)
                @php
                    $iconMap = [
                        'facebook' => ['icon' => 'fab fa-facebook', 'bg' => '#1877f215', 'color' => '#1877f2'],
                        'google'   => ['icon' => 'fab fa-google',   'bg' => '#ea433515', 'color' => '#ea4335'],
                        'tiktok'   => ['icon' => 'fab fa-tiktok',   'bg' => '#00000015', 'color' => '#000'],
                        'gtm'      => ['icon' => 'fas fa-tag',      'bg' => '#4285f415', 'color' => '#4285f4'],
                    ];
                    $type     = strtolower($pixel->nom);
                    $iconInfo = collect($iconMap)->first(fn($v,$k) => str_contains($type,$k)) ?? ['icon' => 'fas fa-code','bg' => '#66666615','color' => '#666'];
                @endphp
                <div style="display:flex;align-items:center;gap:12px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:12px 16px;margin-bottom:8px;">
                    <div style="width:36px;height:36px;border-radius:10px;background:{{ $iconInfo['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="{{ $iconInfo['icon'] }}" style="color:{{ $iconInfo['color'] }};font-size:.95rem;"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;font-size:.83rem;color:#111827;">{{ $pixel->nom }}</div>
                        <div style="font-size:.73rem;color:#9ca3af;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Str::limit($pixel->code_pixel, 40) }}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <label class="px-toggle" style="margin:0;">
                            <input type="checkbox" class="toggle-activation" data-id="{{ $pixel->id }}"
                                   {{ $pixel->est_actif ? 'checked' : '' }}>
                            <span class="px-slider"></span>
                        </label>
                        <button type="button" class="cw-btn-row" style="color:#dc2626;border-color:#fecaca;"
                                data-confirm-message="Supprimer ce pixel ?"
                                data-target-form="px-del-{{ $pixel->id }}">
                            <i class="fas fa-trash" style="font-size:.7rem;"></i>
                        </button>
                        <form id="px-del-{{ $pixel->id }}" action="{{ route('admin.pixels.destroy', $pixel) }}" method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Right: ajouter pixel --}}
        <div>
            <form action="{{ route('admin.pixels.store') }}" method="POST">
            @csrf
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:12px;">Ajouter un pixel</div>
            <div style="display:flex;flex-direction:column;gap:8px;">

                {{-- GTM --}}
                <div class="px-card" id="card-gtm">
                    <div class="px-card-head" onclick="togglePx('gtm')">
                        <div class="px-icon" style="background:#4285f415;"><i class="fas fa-tag" style="color:#4285f4;"></i></div>
                        <div class="px-info" style="flex:1;">
                            <h6>Google Tag Manager</h6>
                            <p>Suivez le trafic de votre boutique</p>
                        </div>
                        <label class="px-toggle" onclick="event.stopPropagation()" style="margin:0;">
                            <input type="checkbox" id="tog-gtm" onchange="togglePx('gtm', this.checked)">
                            <span class="px-slider"></span>
                        </label>
                    </div>
                    <div class="px-body" id="body-gtm">
                        <label>ID Google Tag Manager</label>
                        <input type="text" name="gtm_id" placeholder="GTM-XXXXXXX">
                    </div>
                </div>

                {{-- Facebook --}}
                <div class="px-card" id="card-facebook">
                    <div class="px-card-head" onclick="togglePx('facebook')">
                        <div class="px-icon" style="background:#1877f215;"><i class="fab fa-facebook" style="color:#1877f2;"></i></div>
                        <div class="px-info" style="flex:1;">
                            <h6>Pixel Facebook</h6>
                            <p>Suivi et conversions Facebook Ads</p>
                        </div>
                        <label class="px-toggle" onclick="event.stopPropagation()" style="margin:0;">
                            <input type="checkbox" id="tog-facebook" onchange="togglePx('facebook', this.checked)">
                            <span class="px-slider"></span>
                        </label>
                    </div>
                    <div class="px-body" id="body-facebook">
                        <div style="margin-bottom:10px;">
                            <label>ID Pixel Facebook</label>
                            <input type="text" name="facebook_pixel_id" placeholder="910349101512582">
                        </div>
                        <label>Jeton API de Conversion <span style="font-weight:400;color:#9ca3af;">(optionnel)</span></label>
                        <input type="text" name="facebook_api_token" placeholder="EAAoGc3dz...">
                    </div>
                </div>

                {{-- TikTok --}}
                <div class="px-card" id="card-tiktok">
                    <div class="px-card-head" onclick="togglePx('tiktok')">
                        <div class="px-icon" style="background:#00000012;"><i class="fab fa-tiktok" style="color:#000;"></i></div>
                        <div class="px-info" style="flex:1;">
                            <h6>Pixel TikTok</h6>
                            <p>Suivi et conversions TikTok Ads</p>
                        </div>
                        <label class="px-toggle" onclick="event.stopPropagation()" style="margin:0;">
                            <input type="checkbox" id="tog-tiktok" onchange="togglePx('tiktok', this.checked)">
                            <span class="px-slider"></span>
                        </label>
                    </div>
                    <div class="px-body" id="body-tiktok">
                        <label>ID Pixel TikTok</label>
                        <input type="text" name="tiktok_pixel_id" placeholder="CXXXXXXXXXXXXXXX">
                    </div>
                </div>

                {{-- Custom JS --}}
                <div class="px-card" id="card-custom">
                    <div class="px-card-head" onclick="togglePx('custom')">
                        <div class="px-icon" style="background:#66666615;">
                            <span style="font-weight:900;color:#555;font-size:.8rem;">JS</span>
                        </div>
                        <div class="px-info" style="flex:1;">
                            <h6>JavaScript personnalisé</h6>
                            <p>Code JS de suivi sur mesure</p>
                        </div>
                        <label class="px-toggle" onclick="event.stopPropagation()" style="margin:0;">
                            <input type="checkbox" id="tog-custom" onchange="togglePx('custom', this.checked)">
                            <span class="px-slider"></span>
                        </label>
                    </div>
                    <div class="px-body" id="body-custom">
                        <label>Code JavaScript</label>
                        <textarea name="custom_js" rows="4" placeholder="<script>...votre code...</script>" style="resize:vertical;"></textarea>
                    </div>
                </div>

            </div>
            <button type="submit" class="cw-btn-primary" style="width:100%;margin-top:16px;justify-content:center;">
                <i class="fas fa-save"></i> Enregistrer
            </button>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePx(id, forceOpen = null) {
    const body   = document.getElementById('body-' + id);
    const card   = document.getElementById('card-' + id);
    const toggle = document.getElementById('tog-' + id);
    const isOpen = body.classList.contains('open');
    const shouldOpen = forceOpen !== null ? forceOpen : !isOpen;
    body.classList.toggle('open', shouldOpen);
    card.classList.toggle('active', shouldOpen);
    if (toggle) toggle.checked = shouldOpen;
}
document.querySelectorAll('.toggle-activation').forEach(cb => {
    cb.addEventListener('change', function() {
        fetch(`{{ url('admin/pixels') }}/${this.dataset.id}/toggle-activation`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
    });
});
</script>
@endpush
