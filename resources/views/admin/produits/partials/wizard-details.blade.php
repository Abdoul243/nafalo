<div class="wz-step" data-step="1">
    <h2 class="wz-h2">Détails du produit</h2>

    <div class="wz-field">
        <label>Nom du produit <span class="req">*</span></label>
        <input type="text" name="nom" id="f-nom" value="{{ old('nom') }}" placeholder="{{ $placeholderNom ?? 'Ex: Guide complet Facebook Ads 2026' }}" required>
        <div class="wz-err" id="e-nom">Le nom est requis.</div>
    </div>

    <div class="wz-field">
        <label>Catégorie <span class="req">*</span></label>
        <select name="categorie_id" id="f-cat" required>
            <option value="">Dans quelle catégorie classer ce produit ?</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
            @endforeach
        </select>
        <div class="wz-err" id="e-cat">Choisissez une catégorie.</div>
    </div>

    <div class="wz-field">
        <label>Modèle de tarification <span class="req">*</span></label>
        <select name="type" id="f-type" onchange="majTarif()">
            <option value="payant">Paiement unique</option>
            <option value="gratuit">Gratuit</option>
        </select>
    </div>

    <div class="wz-2col" id="f-prix-wrap">
        <div class="wz-field" style="margin-bottom:0;">
            <label>Prix</label>
            <div class="wz-suffix">
                <input type="number" name="prix" id="f-prix" min="0" value="{{ old('prix') }}" placeholder="0">
                <span class="u">FCFA</span>
            </div>
            <div class="wz-err" id="e-prix">Indiquez un prix.</div>
        </div>
        <div class="wz-field" style="margin-bottom:0;">
            <label>Prix promotionnel</label>
            <div class="wz-suffix">
                <input type="number" name="prix_promo" id="f-promo" min="0" value="{{ old('prix_promo') }}" placeholder="0">
                <span class="u">FCFA</span>
            </div>
            <div class="wz-err" id="e-promo">Le prix promo doit être inférieur au prix.</div>
        </div>
    </div>
</div>
