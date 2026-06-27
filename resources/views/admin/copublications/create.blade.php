@extends('layouts.admin')
@section('title', 'Inviter un partenaire')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.copublications.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
    <div>
        <h1 class="h4 fw-bold mb-0">?? Inviter un partenaire</h1>
        <p class="text-muted small mb-0">Associez-vous à un autre marchand pour co-publier un produit</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm" style="border-radius:20px;overflow:hidden;">
            <div class="card-body p-4">

                {{-- Explication --}}
                <div class="alert alert-info border-0 rounded-3 mb-4" style="background:#eff6ff;">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    <strong>Comment ça fonctionne ?</strong><br>
                    <small>
                        Invitez un autre marchand Nafalo. Dès qu'il accepte, chaque vente de ce produit
                        sera automatiquement répartie selon les pourcentages définis.
                        Chacun reçoit un email de notification avec son gain net.
                    </small>
                </div>

                <form action="{{ route('admin.copublications.store') }}" method="POST">
                    @csrf

                    {{-- Produit --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Produit concerné *</label>
                        <select name="produit_id" class="form-select rounded-3 @error('produit_id') is-invalid @enderror" required>
                            <option value="">— Choisir un produit —</option>
                            @foreach($produits as $p)
                                <option value="{{ $p->id }}"
                                    {{ (old('produit_id', $produitSelectionne?->id) == $p->id) ? 'selected' : '' }}>
                                    {{ $p->nom }} — {{ number_format($p->prix, 0, ',', ' ') }} F CFA
                                </option>
                            @endforeach
                        </select>
                        @error('produit_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email du partenaire --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email du partenaire *</label>
                        <input type="email" name="email_copublicateur"
                               class="form-control rounded-3 @error('email_copublicateur') is-invalid @enderror"
                               placeholder="partenaire@example.com"
                               value="{{ old('email_copublicateur', $emailPartenaire ?? '') }}" required>
                        <div class="form-text">Le partenaire doit déjà avoir un compte Nafalo.</div>
                        @error('email_copublicateur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Répartition des gains --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Répartition des gains *</label>
                        <div class="alert alert-light border rounded-3 mb-3">
                            <small class="text-muted">
                                Les pourcentages sont calculés sur le montant net (après déduction de la commission Nafalo de 5 %).
                                Le total doit être exactement 100 %.
                            </small>
                        </div>

                        @error('pourcentage')
                            <div class="alert alert-danger rounded-3">{{ $message }}</div>
                        @enderror

                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-primary">Votre part (%)</label>
                                <div class="input-group">
                                    <input type="number" name="pourcentage_proprietaire" id="pct-proprio"
                                           class="form-control rounded-start-3 @error('pourcentage_proprietaire') is-invalid @enderror"
                                           min="1" max="99" step="1"
                                           value="{{ old('pourcentage_proprietaire', 70) }}"
                                           oninput="syncPourcentage(this)" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary">Part du partenaire (%)</label>
                                <div class="input-group">
                                    <input type="number" name="pourcentage_copublicateur" id="pct-copub"
                                           class="form-control rounded-start-3 @error('pourcentage_copublicateur') is-invalid @enderror"
                                           min="1" max="99" step="1"
                                           value="{{ old('pourcentage_copublicateur', 30) }}"
                                           oninput="syncPourcentage2(this)" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>

                        {{-- Barre de visualisation --}}
                        <div class="mt-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-primary fw-semibold">Vous : <span id="label-proprio">70</span> %</span>
                                <span class="text-secondary fw-semibold">Partenaire : <span id="label-copub">30</span> %</span>
                            </div>
                            <div class="progress" style="height:10px;border-radius:10px;">
                                <div class="progress-bar bg-primary" id="bar-proprio" style="width:70%;"></div>
                                <div class="progress-bar bg-secondary" id="bar-copub" style="width:30%;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Message facultatif --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Message pour le partenaire <span class="text-muted fw-normal">(optionnel)</span></label>
                        <textarea name="message" rows="3"
                                  class="form-control rounded-3"
                                  placeholder="Ex : Bonjour, j'aimerais qu'on collabore sur ce produit !">{{ old('message') }}</textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.copublications.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            Annuler
                        </a>
                        <button type="submit" class="btn rounded-pill px-5">
                            <i class="fas fa-paper-plane me-1"></i> Envoyer l'invitation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function syncPourcentage(input) {
    const val   = Math.min(99, Math.max(1, parseInt(input.value) || 0));
    const other = 100 - val;
    document.getElementById('pct-copub').value = other;
    updateBarre(val, other);
}
function syncPourcentage2(input) {
    const val   = Math.min(99, Math.max(1, parseInt(input.value) || 0));
    const other = 100 - val;
    document.getElementById('pct-proprio').value = other;
    updateBarre(other, val);
}
function updateBarre(proprio, copub) {
    document.getElementById('bar-proprio').style.width = proprio + '%';
    document.getElementById('bar-copub').style.width   = copub   + '%';
    document.getElementById('label-proprio').textContent = proprio;
    document.getElementById('label-copub').textContent   = copub;
}
</script>
@endpush

