<div class="wz-step" data-step="{{ $step }}" style="display:none;">
    <h2 class="wz-h2">Présentation</h2>
    <div class="wz-field">
        <label>Description</label>
        <textarea name="description" rows="5" placeholder="{{ $descPlaceholder ?? 'Ce que contient le produit, à qui il s\'adresse, ses bénéfices…' }}">{{ old('description') }}</textarea>
    </div>
    <div class="wz-field">
        <label>Image de couverture <span style="color:#9ca3af;font-weight:400;">(optionnel)</span></label>
        <div class="wz-upload" style="padding:1.25rem;" onclick="document.getElementById('f-img').click()">
            <i class="fas fa-image" style="font-size:1.5rem;"></i>
            <div id="f-img-n" style="margin-top:8px;font-size:0.85rem;">Cliquez pour ajouter une image</div>
        </div>
        <input type="file" id="f-img" name="image" accept="image/*" style="display:none;"
               onchange="document.getElementById('f-img-n').textContent=this.files[0]?this.files[0].name:'Cliquez pour ajouter une image'">
    </div>
</div>
