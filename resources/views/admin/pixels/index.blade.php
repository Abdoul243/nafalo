@extends('layouts.admin')

@section('title', 'Pixels marketing')

@push('styles')
<style>
    .pixels-layout {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 2rem;
        align-items: start;
    }
    .pixels-intro h2 { font-size: 1.8rem; font-weight: 900; color: #111; margin-bottom: 0.5rem; }
    .pixels-intro p { color: #888; font-size: 0.95rem; line-height: 1.6; max-width: 480px; }
    .pixels-intro a { color: #667eea; font-size: 0.9rem; }

    .pixel-cards { display: flex; flex-direction: column; gap: 1rem; }

    .pixel-card {
        background: white;
        border: 1.5px solid #f0f0f0;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.2s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }
    .pixel-card.active { border-color: #667eea40; box-shadow: 0 4px 20px rgba(102,126,234,0.1); }

    .pixel-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.2rem 1.5rem;
        cursor: pointer;
    }
    .pixel-card-icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .pixel-card-info { flex: 1; }
    .pixel-card-info h6 { font-weight: 700; font-size: 0.95rem; color: #111; margin: 0 0 2px; }
    .pixel-card-info p { font-size: 0.8rem; color: #aaa; margin: 0; line-height: 1.4; }

    /* Toggle switch */
    .pixel-toggle { position: relative; width: 46px; height: 26px; flex-shrink: 0; }
    .pixel-toggle input { opacity: 0; width: 0; height: 0; }
    .pixel-toggle-slider {
        position: absolute; inset: 0;
        background: #e0e0e0; border-radius: 26px;
        cursor: pointer; transition: 0.3s;
    }
    .pixel-toggle-slider:before {
        content: '';
        position: absolute;
        width: 20px; height: 20px;
        border-radius: 50%; background: white;
        left: 3px; top: 3px;
        transition: 0.3s;
        box-shadow: 0 1px 4px rgba(0,0,0,0.15);
    }
    .pixel-toggle input:checked + .pixel-toggle-slider { background: #667eea; }
    .pixel-toggle input:checked + .pixel-toggle-slider:before { transform: translateX(20px); }

    .pixel-card-body {
        padding: 0 1.5rem;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
    }
    .pixel-card-body.open {
        max-height: 300px;
        padding: 0 1.5rem 1.5rem;
    }
    .pixel-card-body label { font-size: 0.85rem; font-weight: 600; color: #555; margin-bottom: 0.4rem; }
    .pixel-card-body input, .pixel-card-body textarea {
        font-size: 0.875rem;
        border-radius: 10px;
        border: 1.5px solid #e0e0e0;
        padding: 0.6rem 0.9rem;
    }
    .pixel-card-body input:focus, .pixel-card-body textarea:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        outline: none;
    }

    .save-btn {
        background: #111; color: white; border: none;
        border-radius: 12px; padding: 0.75rem 2rem;
        font-weight: 700; font-size: 0.9rem;
        width: 100%; margin-top: 1rem;
        transition: all 0.2s;
    }
    .save-btn:hover { background: #333; transform: translateY(-1px); }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
        .pixels-layout {
            grid-template-columns: 1fr;
        }
        .pixels-intro p { max-width: 100%; }
    }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <h1 class="h4 mb-0 fw-bold">Pixels</h1>
</div>

<form action="{{ route('admin.pixels.store') }}" method="POST" id="pixels-form">
@csrf

<div class="pixels-layout">

    {{-- Intro --}}
    <div class="pixels-intro">
        <h2>Pixels</h2>
        <p>Ajoutez des pixels de suivi comme Facebook Pixel, Google Tag Manager, TikTok Pixel et du code JavaScript personnalisé pour surveiller les performances de votre boutique.</p>
        <a href="#" class="d-inline-flex align-items-center gap-1">
            <i class="fas fa-external-link-alt" style="font-size:0.75rem;"></i> En savoir plus
        </a>

        {{-- Pixels existants --}}
        @if($pixels->count() > 0)
        <div class="mt-4">
            <h6 class="fw-bold mb-3">Pixels configurés</h6>
            @foreach($pixels as $pixel)
            <div class="d-flex align-items-center justify-content-between bg-white border rounded-3 p-3 mb-2">
                <div class="d-flex align-items-center gap-3">
                    @php
                        $iconMap = [
                            'facebook' => ['icon' => 'fab fa-facebook', 'bg' => '#1877f215', 'color' => '#1877f2'],
                            'google' => ['icon' => 'fab fa-google', 'bg' => '#ea433515', 'color' => '#ea4335'],
                            'tiktok' => ['icon' => 'fab fa-tiktok', 'bg' => '#00000015', 'color' => '#000'],
                            'gtm' => ['icon' => 'fas fa-tag', 'bg' => '#4285f415', 'color' => '#4285f4'],
                        ];
                        $type = strtolower($pixel->nom);
                        $iconInfo = collect($iconMap)->first(fn($v, $k) => str_contains($type, $k)) ?? ['icon' => 'fas fa-code', 'bg' => '#66666615', 'color' => '#666'];
                    @endphp
                    <div style="width:36px;height:36px;border-radius:10px;background:{{ $iconInfo['bg'] }};display:flex;align-items:center;justify-content:center;">
                        <i class="{{ $iconInfo['icon'] }}" style="color:{{ $iconInfo['color'] }};font-size:1rem;"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:0.875rem;">{{ $pixel->nom }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ Str::limit($pixel->code_pixel, 40) }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="pixel-toggle mb-0">
                        <input type="checkbox" class="toggle-activation" data-id="{{ $pixel->id }}"
                               {{ $pixel->est_actif ? 'checked' : '' }}>
                        <span class="pixel-toggle-slider"></span>
                    </label>
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle"
                            data-confirm-message="Supprimer ce pixel ?"
                            data-target-form="delete-{{ $pixel->id }}"
                            style="width:32px;height:32px;padding:0;">
                        <i class="fas fa-trash" style="font-size:0.7rem;"></i>
                    </button>
                    <form id="delete-{{ $pixel->id }}" action="{{ route('admin.pixels.destroy', $pixel) }}"
                          method="POST" class="d-none">
                        @csrf @method('DELETE')
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Cartes pixels --}}
    <div>
        <p class="text-muted small mb-3 fw-semibold">Ajouter un nouveau pixel</p>
        <div class="pixel-cards">

            {{-- Google Tag Manager --}}
            <div class="pixel-card" id="card-gtm">
                <div class="pixel-card-header" onclick="toggleCard('gtm')">
                    <div class="pixel-card-icon" style="background:#4285f415;">
                        <i class="fas fa-tag" style="color:#4285f4;"></i>
                    </div>
                    <div class="pixel-card-info">
                        <h6>ID Google Tag Manager</h6>
                        <p>Ajoutez votre ID Google Tag Manager pour suivre le trafic de votre boutique</p>
                    </div>
                    <label class="pixel-toggle mb-0" onclick="event.stopPropagation()">
                        <input type="checkbox" id="toggle-gtm" onchange="toggleCard('gtm', this.checked)">
                        <span class="pixel-toggle-slider"></span>
                    </label>
                </div>
                <div class="pixel-card-body" id="body-gtm">
                    <div class="mb-3">
                        <label>ID Google Tag Manager</label>
                        <input type="text" class="form-control" name="gtm_id" placeholder="GTM-XXXXXXX">
                    </div>
                </div>
            </div>

            {{-- Facebook Pixel --}}
            <div class="pixel-card" id="card-facebook">
                <div class="pixel-card-header" onclick="toggleCard('facebook')">
                    <div class="pixel-card-icon" style="background:#1877f215;">
                        <i class="fab fa-facebook" style="color:#1877f2;"></i>
                    </div>
                    <div class="pixel-card-info">
                        <h6>ID Pixel Facebook</h6>
                        <p>Ajoutez votre ID Pixel Facebook pour suivre le trafic de votre boutique</p>
                    </div>
                    <label class="pixel-toggle mb-0" onclick="event.stopPropagation()">
                        <input type="checkbox" id="toggle-facebook" onchange="toggleCard('facebook', this.checked)">
                        <span class="pixel-toggle-slider"></span>
                    </label>
                </div>
                <div class="pixel-card-body" id="body-facebook">
                    <div class="mb-3">
                        <label>ID Pixel Facebook</label>
                        <input type="text" class="form-control" name="facebook_pixel_id" placeholder="910349101512582">
                    </div>
                    <div class="mb-2">
                        <label>Jeton d'API de Conversion Facebook</label>
                        <input type="text" class="form-control" name="facebook_api_token" placeholder="EAAoGc3dz...">
                        <small class="text-muted" style="font-size:0.75rem;">Optionnel — améliore le suivi des conversions</small>
                    </div>
                </div>
            </div>

            {{-- TikTok Pixel --}}
            <div class="pixel-card" id="card-tiktok">
                <div class="pixel-card-header" onclick="toggleCard('tiktok')">
                    <div class="pixel-card-icon" style="background:#00000012;">
                        <i class="fab fa-tiktok" style="color:#000;"></i>
                    </div>
                    <div class="pixel-card-info">
                        <h6>ID Pixel TikTok</h6>
                        <p>Ajoutez votre ID Pixel TikTok pour suivre le trafic et les conversions</p>
                    </div>
                    <label class="pixel-toggle mb-0" onclick="event.stopPropagation()">
                        <input type="checkbox" id="toggle-tiktok" onchange="toggleCard('tiktok', this.checked)">
                        <span class="pixel-toggle-slider"></span>
                    </label>
                </div>
                <div class="pixel-card-body" id="body-tiktok">
                    <div class="mb-2">
                        <label>ID Pixel TikTok</label>
                        <input type="text" class="form-control" name="tiktok_pixel_id" placeholder="CXXXXXXXXXXXXXXX">
                    </div>
                </div>
            </div>

            {{-- Code JS personnalisé --}}
            <div class="pixel-card" id="card-custom">
                <div class="pixel-card-header" onclick="toggleCard('custom')">
                    <div class="pixel-card-icon" style="background:#66666615;">
                        <span style="font-weight:900;color:#555;font-size:0.8rem;">JS</span>
                    </div>
                    <div class="pixel-card-info">
                        <h6>Code JavaScript personnalisé</h6>
                        <p>Ajoutez du code JS personnalisé pour suivre les événements de conversion</p>
                    </div>
                    <label class="pixel-toggle mb-0" onclick="event.stopPropagation()">
                        <input type="checkbox" id="toggle-custom" onchange="toggleCard('custom', this.checked)">
                        <span class="pixel-toggle-slider"></span>
                    </label>
                </div>
                <div class="pixel-card-body" id="body-custom">
                    <div class="mb-2">
                        <label>Code JavaScript</label>
                        <textarea class="form-control" name="custom_js" rows="4"
                                  placeholder="<script>...votre code...</script>"></textarea>
                        <small class="text-muted" style="font-size:0.75rem;">Collez le code complet fourni par la plateforme</small>
                    </div>
                </div>
            </div>

        </div>

        <button type="submit" class="save-btn">
            <i class="fas fa-save me-2"></i> Enregistrer
        </button>
    </div>

</div>
</form>

@endsection

@push('scripts')
<script>
function toggleCard(id, forceOpen = null) {
    const body = document.getElementById('body-' + id);
    const card = document.getElementById('card-' + id);
    const toggle = document.getElementById('toggle-' + id);

    const isOpen = body.classList.contains('open');
    const shouldOpen = forceOpen !== null ? forceOpen : !isOpen;

    if (shouldOpen) {
        body.classList.add('open');
        card.classList.add('active');
        toggle.checked = true;
    } else {
        body.classList.remove('open');
        card.classList.remove('active');
        toggle.checked = false;
    }
}

// Toggle activation des pixels existants
document.querySelectorAll('.toggle-activation').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const id = this.dataset.id;
        fetch(`{{ url('admin/pixels') }}/${id}/toggle-activation`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
    });
});
</script>
@endpush