<script>
function majTarif(){ var t=document.getElementById('f-type'); if(!t) return; var w=document.getElementById('f-prix-wrap'); if(w) w.style.display = t.value==='gratuit' ? 'none' : ''; }
function majCat(){ var c=document.getElementById('f-cat'), n=document.getElementById('f-newcat'); if(!c||!n) return; if(c.value==='__new__'){ n.style.display='block'; n.focus(); } else { n.style.display='none'; } }
window.WZ = window.WZ || {};
(function(){
    var steps=[].slice.call(document.querySelectorAll('.wz-step')), TOTAL=steps.length, s=1;
    function set(id,v){ var e=document.getElementById(id); if(e) e.textContent=v; }
    function err(id){ var e=document.getElementById(id); if(e) e.style.display='block'; }
    function show(){
        steps.forEach(x=>x.style.display=(x.dataset.step==s)?'':'none');
        document.querySelectorAll('.wz-bar span').forEach(b=>b.classList.toggle('done', b.dataset.seg<=s));
        var bk=document.getElementById('wz-back'); if(bk) bk.style.display=s>1?'':'none';
        var c=document.getElementById('wz-cancel'); if(c) c.style.display=s>1?'none':'';
        document.getElementById('wz-next').textContent = s==TOTAL ? (window.WZ.createLabel||'Créer le produit') : 'Continuer';
        window.scrollTo({top:0,behavior:'smooth'});
    }
    function vDetails(){
        document.querySelectorAll('.wz-err').forEach(e=>e.style.display='none');
        var ok=true, tp=document.getElementById('f-type'), g=tp&&tp.value==='gratuit';
        if(!document.getElementById('f-nom').value.trim()){ err('e-nom'); ok=false; }
        var cv=document.getElementById('f-cat').value, nc=document.getElementById('f-newcat');
        if(!cv){ err('e-cat'); ok=false; }
        else if(cv==='__new__' && !(nc&&nc.value.trim())){ err('e-cat'); ok=false; }
        var pe=document.getElementById('f-prix');
        if(pe){ var p=pe.value, pr=document.getElementById('f-promo').value;
            if(!g && (p===''||parseFloat(p)<0)){ err('e-prix'); ok=false; }
            if(!g && pr!=='' && parseFloat(pr)>=parseFloat(p||0)){ err('e-promo'); ok=false; }
        }
        return ok;
    }
    function valid(){ if(s===1) return vDetails(); if(window.WZ.validate) return window.WZ.validate(s)!==false; return true; }
    function baseRecap(){
        set('r-nom', document.getElementById('f-nom').value||'—');
        var sel=document.getElementById('f-cat'), nc=document.getElementById('f-newcat');
        set('r-cat', (sel.value==='__new__' && nc && nc.value.trim()) ? nc.value.trim() : ((sel.options[sel.selectedIndex]||{}).text||'—'));
        var pe=document.getElementById('f-prix'); if(pe){ var tp=document.getElementById('f-type'); set('r-prix', (tp&&tp.value==='gratuit')?'Gratuit':(pe.value||'0')+' FCFA'); }
    }
    window.wzNext=function(){ if(!valid())return; if(s===TOTAL){ document.getElementById('wzf').submit(); return; } s++; if(s===TOTAL){ baseRecap(); if(window.WZ.recap) window.WZ.recap(); } show(); };
    window.wzPrev=function(){ if(s>1){ s--; show(); } };
    show();
})();
</script>
