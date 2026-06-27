@extends('layouts.admin')
@section('title', 'Trouver un partenaire')

@push('styles')
<style>
/* ════════════════════════════════════════════════
   RECHERCHE IA PARTENAIRES — Nafalo
════════════════════════════════════════════════ */

/* ── Barre de recherche ── */
.ia-search-wrap {
    background: var(--bg-card);
    border: 2px solid var(--border);
    border-radius: 20px;
    padding: 1.75rem;
    margin-bottom: 1.5rem;
    transition: border-color .2s;
}
.ia-search-wrap:focus-within { border-color: var(--accent); }

.ia-input {
    flex: 1;
    border: 1.5px solid var(--border);
    border-radius: 14px;
    padding: .8rem 1.1rem;
    font-size: .95rem;
    background: var(--bg-page);
    color: var(--text-1);
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.ia-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(124,58,237,.12);
}
.ia-input::placeholder { color: var(--text-3); }

.btn-ia-search {
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 14px;
    padding: .8rem 1.4rem;
    font-weight: 700;
    font-size: .9rem;
    cursor: pointer;
    white-space: nowrap;
    display: flex; align-items: center; gap: 8px;
    transition: all .2s;
    flex-shrink: 0;
}
.btn-ia-search:hover  { opacity: .9; transform: translateY(-1px); }
.btn-ia-search:active { transform: none; }

/* ── Chips de suggestions ── */
.chips-wrap { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 1rem; }
.chip {
    background: var(--bg-page);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 4px 14px;
    font-size: .75rem;
    cursor: pointer;
    color: var(--text-2);
    transition: all .15s;
    font-weight: 500;
}
.chip:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: rgba(124,58,237,.06);
}

/* ── Bandeau d'analyse IA ── */
.ia-analyse-box {
    background: linear-gradient(135deg, rgba(124,58,237,.08) 0%, rgba(16,185,129,.06) 100%);
    border: 1px solid rgba(124,58,237,.2);
    border-radius: 16px;
    padding: 1.1rem 1.4rem;
    margin-bottom: 1.5rem;
    display: flex; align-items: flex-start; gap: 12px;
}
.ia-analyse-emoji { font-size: 2rem; flex-shrink: 0; line-height: 1; }
.ia-analyse-content { flex: 1; min-width: 0; }
.ia-analyse-title   { font-weight: 700; font-size: .82rem; color: var(--accent); margin-bottom: 4px; }
.ia-analyse-resume  { font-size: .88rem; color: var(--text-2); margin-bottom: 8px; }
.ia-niches-wrap     { display: flex; flex-wrap: wrap; gap: 5px; }
.ia-niche-tag {
    padding: 2px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600;
}
.ia-niche-tag.primary { background: rgba(124,58,237,.12); color: var(--accent); }
.ia-niche-tag.secondary { background: rgba(245,158,11,.1); color: #b45309; }

/* ── Résultats header ── */
.results-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1rem; flex-wrap: wrap; gap: .5rem;
}
.results-count {
    font-size: .82rem; color: var(--text-3);
    display: flex; align-items: center; gap: 6px;
}
.results-count strong { color: var(--text-1); font-size: 1rem; }

/* ── Cartes partenaires ── */
.partner-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1rem;
}

.partner-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.2rem;
    display: flex; flex-direction: column; gap: .75rem;
    transition: all .2s;
    position: relative;
    overflow: hidden;
}
.partner-card:hover {
    border-color: var(--accent);
    box-shadow: 0 6px 24px rgba(0,0,0,.12);
    transform: translateY(-2px);
}
.partner-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--accent), #10b981);
    opacity: 0; transition: opacity .2s;
}
.partner-card:hover::before { opacity: 1; }

.partner-header { display: flex; align-items: center; gap: 10px; }
.partner-avatar {
    width: 44px; height: 44px; border-radius: 12px;
    background: var(--bg-page);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0; overflow: hidden;
}
.partner-avatar img { width: 100%; height: 100%; object-fit: cover; }
.partner-nom {
    font-weight: 700; font-size: .88rem;
    color: var(--text-1); line-height: 1.2;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.partner-meta {
    font-size: .7rem; color: var(--text-3);
    display: flex; align-items: center; gap: 6px; margin-top: 2px;
}
.partner-desc {
    font-size: .78rem; color: var(--text-3); line-height: 1.5;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
    flex: 1;
}
.partner-stats {
    display: flex; gap: 8px;
}
.partner-stat {
    flex: 1; background: var(--bg-page); border-radius: 10px;
    padding: 6px 10px; text-align: center;
}
.partner-stat-val { font-size: .9rem; font-weight: 800; color: var(--text-1); }
.partner-stat-label { font-size: .62rem; color: var(--text-3); text-transform: uppercase; letter-spacing: .04em; }

.btn-inviter {
    width: 100%;
    background: var(--accent);
    color: white; border: none;
    border-radius: 10px; padding: 8px;
    font-size: .8rem; font-weight: 700;
    cursor: pointer; text-decoration: none;
    text-align: center; display: block;
    transition: all .2s;
}
.btn-inviter:hover { opacity: .85; color: white; }

/* Badge de popularité */
.pop-badge {
    position: absolute; top: 12px; right: 12px;
    font-size: .6rem; font-weight: 800; padding: 2px 8px;
    border-radius: 20px; text-transform: uppercase; letter-spacing: .04em;
}
.pop-badge.gold   { background: #fef9c3; color: #854d0e; }
.pop-badge.silver { background: #f1f5f9; color: #475569; }
.pop-badge.bronze { background: #fef3c7; color: #92400e; }

/* ── États ── */
.ia-empty {
    text-align: center; padding: 3rem 1rem;
    color: var(--text-3);
}
.ia-empty .ia-empty-icon { font-size: 3rem; margin-bottom: .75rem; }
.ia-empty h5 { color: var(--text-2); font-weight: 700; }

.ia-loading {
    display: flex; align-items: center; justify-content: center;
    gap: 12px; padding: 2rem;
    color: var(--text-3); font-size: .9rem;
}

/* ── Skeleton loader ── */
.skeleton-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; }
.skeleton-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 16px; padding: 1.2rem;
    animation: skeleton-pulse 1.4s ease infinite;
}
.skeleton-line {
    background: var(--border); border-radius: 4px; margin-bottom: 8px;
}
@keyframes skeleton-pulse {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.5; }
}
</style>
@endpush

@section('content')

{{-- ── En-tête ──────────────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1">🤖 Trouver un partenaire</h1>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Décrivez votre niche ou celle que vous recherchez — l'IA trouve les meilleurs partenaires classés par popularité
        </p>
    </div>
    <a href="{{ route('admin.copublications.index') }}"
       class="btn btn-sm btn-outline-secondary rounded-pill">
        ← Mes co-publications
    </a>
</div>

{{-- ── Barre de recherche IA ────────────────────────────────────────── --}}
<div class="ia-search-wrap">
    <div class="d-flex gap-2 align-items-center">
        <input type="text" id="iaInput" class="ia-input"
               placeholder="Ex : Je suis dans l'élevage, je cherche un partenaire en agriculture ou nutrition animale…"
               autocomplete="off">
        <button id="btnIa" class="btn-ia-search" onclick="lancerRecherche()">
            <span id="btnIaText">✨ Rechercher</span>
            <span id="btnIaLoader" class="d-none">
                <span class="spinner-border spinner-border-sm"></span> Analyse…
            </span>
        </button>
    </div>

    {{-- Suggestions rapides --}}
    <div class="chips-wrap">
        <span style="font-size:.72rem;color:var(--text-3);align-self:center;">Suggestions :</span>
        @foreach([
            ['🌾', 'Agriculture'],
            ['🐄', 'Élevage'],
            ['🐔', 'Aviculture'],
            ['🐟', 'Pêche'],
            ['🌿', 'Maraîchage'],
            ['🍽️', 'Cuisine africaine'],
            ['👗', 'Mode africaine'],
            ['💄', 'Beauté naturelle'],
            ['💊', 'Santé naturelle'],
            ['📚', 'Éducation'],
            ['💰', 'Finance personnelle'],
            ['🏗️', 'Immobilier'],
            ['💻', 'Technologie'],
            ['🏋️', 'Sport & bien-être'],
        ] as [$emoji, $label])
        <button class="chip" onclick="utiliserSuggestion('{{ $label }}')">
            {{ $emoji }} {{ $label }}
        </button>
        @endforeach
    </div>
</div>

{{-- ── Analyse IA (hidden by default) ─────────────────────────────── --}}
<div id="iaAnalyse" class="ia-analyse-box d-none">
    <div class="ia-analyse-emoji" id="iaEmoji">🔍</div>
    <div class="ia-analyse-content">
        <div class="ia-analyse-title">✨ Analyse IA</div>
        <div class="ia-analyse-resume" id="iaResume"></div>
        <div class="ia-niches-wrap" id="iaNiches"></div>
    </div>
</div>

{{-- ── État : skeleton loading ─────────────────────────────────────── --}}
<div id="stateLoading" class="d-none">
    <div class="skeleton-grid">
        @for($i = 0; $i < 6; $i++)
        <div class="skeleton-card">
            <div style="display:flex;gap:10px;margin-bottom:10px;">
                <div class="skeleton-line" style="width:44px;height:44px;border-radius:12px;flex-shrink:0;margin-bottom:0;"></div>
                <div style="flex:1;">
                    <div class="skeleton-line" style="width:70%;height:12px;"></div>
                    <div class="skeleton-line" style="width:40%;height:10px;"></div>
                </div>
            </div>
            <div class="skeleton-line" style="height:10px;width:100%;"></div>
            <div class="skeleton-line" style="height:10px;width:80%;"></div>
            <div style="display:flex;gap:8px;margin-top:4px;">
                <div class="skeleton-line" style="flex:1;height:40px;border-radius:10px;margin-bottom:0;"></div>
                <div class="skeleton-line" style="flex:1;height:40px;border-radius:10px;margin-bottom:0;"></div>
            </div>
            <div class="skeleton-line" style="height:34px;border-radius:10px;margin-bottom:0;"></div>
        </div>
        @endfor
    </div>
</div>

{{-- ── État : résultats ─────────────────────────────────────────────── --}}
<div id="stateResults" class="d-none">
    <div class="results-header">
        <div class="results-count">
            <strong id="totalCount">0</strong>
            <span>partenaires trouvés · classés du plus populaire au moins populaire</span>
        </div>
    </div>
    <div class="partner-grid" id="partnerGrid"></div>
</div>

{{-- ── État : aucun résultat ────────────────────────────────────────── --}}
<div id="stateEmpty" class="ia-empty d-none">
    <div class="ia-empty-icon">🔍</div>
    <h5>Aucun partenaire trouvé</h5>
    <p style="font-size:.85rem;">
        Essayez avec d'autres mots-clés ou soyez plus général dans votre description.
    </p>
</div>

{{-- ── État : accueil (initial) ────────────────────────────────────── --}}
<div id="stateWelcome">
    <div class="row g-3 mt-2">
        @foreach([
            ['🔍', 'Recherche intelligente', 'Décrivez votre niche en langage naturel, l\'IA comprend votre intention'],
            ['🎯', 'Niches complémentaires', 'L\'IA suggère des niches qui se marient bien avec la vôtre'],
            ['📊', 'Classement par ventes', 'Les résultats sont classés du vendeur le plus populaire au moins connu'],
            ['⚡', 'Invitation directe', 'Invitez un partenaire en 1 clic — le formulaire est pré-rempli'],
        ] as [$icon, $titre, $desc])
        <div class="col-sm-6 col-lg-3">
            <div class="card h-100 text-center" style="border-radius:16px;border:1px solid var(--border);">
                <div class="card-body p-3">
                    <div style="font-size:2rem;margin-bottom:.5rem;">{{ $icon }}</div>
                    <div class="fw-bold" style="font-size:.85rem;color:var(--text-1);margin-bottom:4px;">{{ $titre }}</div>
                    <div style="font-size:.75rem;color:var(--text-3);">{{ $desc }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<script>
const INVITE_BASE = "{{ route('admin.copublications.create') }}";
const SEARCH_URL  = "{{ route('admin.copublications.ia-search') }}";
const CSRF        = "{{ csrf_token() }}";

/* ══════════════════════════════════════════════════════
   GESTION DES ÉTATS
══════════════════════════════════════════════════════ */
function setEtat(etat) {
    // etat: 'welcome' | 'loading' | 'results' | 'empty'
    document.getElementById('stateWelcome').classList.toggle('d-none',  etat !== 'welcome');
    document.getElementById('stateLoading').classList.toggle('d-none',  etat !== 'loading');
    document.getElementById('stateResults').classList.toggle('d-none',  etat !== 'results');
    document.getElementById('stateEmpty').classList.toggle('d-none',    etat !== 'empty');
}

function setLoading(loading) {
    document.getElementById('btnIaText').classList.toggle('d-none', loading);
    document.getElementById('btnIaLoader').classList.toggle('d-none', !loading);
    document.getElementById('btnIa').disabled = loading;
    document.getElementById('iaInput').disabled = loading;
}

/* ══════════════════════════════════════════════════════
   RECHERCHE PRINCIPALE
══════════════════════════════════════════════════════ */
async function lancerRecherche() {
    const query = document.getElementById('iaInput').value.trim();
    if (!query || query.length < 2) {
        document.getElementById('iaInput').focus();
        return;
    }

    setLoading(true);
    setEtat('loading');
    document.getElementById('iaAnalyse').classList.add('d-none');

    try {
        const res = await fetch(SEARCH_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ query }),
        });

        if (!res.ok) {
            const err = await res.json();
            throw new Error(err.message || 'Erreur serveur');
        }

        const data = await res.json();

        // Afficher l'analyse IA
        afficherAnalyse(data.analyse);

        // Afficher les résultats
        if (data.boutiques && data.boutiques.length > 0) {
            afficherResultats(data.boutiques);
            setEtat('results');
        } else {
            setEtat('empty');
        }

    } catch (e) {
        console.error(e);
        setEtat('empty');
        document.getElementById('stateEmpty').querySelector('p').textContent =
            'Une erreur est survenue. Veuillez réessayer.';
    } finally {
        setLoading(false);
    }
}

/* ══════════════════════════════════════════════════════
   AFFICHAGE DE L'ANALYSE IA
══════════════════════════════════════════════════════ */
function afficherAnalyse(analyse) {
    if (!analyse) return;

    document.getElementById('iaEmoji').textContent    = analyse.emoji || '🔍';
    document.getElementById('iaResume').textContent   = analyse.resume || '';

    const nichesWrap = document.getElementById('iaNiches');
    nichesWrap.innerHTML = '';

    (analyse.niches || []).forEach(n => {
        const tag = document.createElement('span');
        tag.className = 'ia-niche-tag primary';
        tag.textContent = n;
        nichesWrap.appendChild(tag);
    });

    (analyse.niches_complementaires || []).forEach(n => {
        const tag = document.createElement('span');
        tag.className = 'ia-niche-tag secondary';
        tag.textContent = n + ' (complémentaire)';
        nichesWrap.appendChild(tag);
    });

    document.getElementById('iaAnalyse').classList.remove('d-none');
}

/* ══════════════════════════════════════════════════════
   AFFICHAGE DES CARTES PARTENAIRES
══════════════════════════════════════════════════════ */
function afficherResultats(boutiques) {
    document.getElementById('totalCount').textContent = boutiques.length;

    const grid = document.getElementById('partnerGrid');
    grid.innerHTML = '';

    boutiques.forEach((b, idx) => {
        const card = document.createElement('div');
        card.className = 'partner-card';
        card.innerHTML = buildCard(b, idx);
        grid.appendChild(card);
    });
}

function buildCard(b, idx) {
    const logoHtml = b.logo_url
        ? `<img src="${b.logo_url}" alt="${escHtml(b.nom)}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"><span style="display:none">🏪</span>`
        : `<span>🏪</span>`;

    const badgeHtml = idx === 0
        ? '<span class="pop-badge gold">🥇 Top</span>'
        : idx === 1
        ? '<span class="pop-badge silver">🥈</span>'
        : idx === 2
        ? '<span class="pop-badge bronze">🥉</span>'
        : '';

    const descHtml = b.description
        ? `<div class="partner-desc">${escHtml(b.description)}</div>`
        : `<div class="partner-desc" style="color:var(--text-3);font-style:italic;">Boutique Nafalo</div>`;

    const inviteUrl = INVITE_BASE
        + '?email=' + encodeURIComponent(b.email)
        + '&partenaire=' + encodeURIComponent(b.nom);

    return `
        ${badgeHtml}
        <div class="partner-header">
            <div class="partner-avatar">${logoHtml}</div>
            <div style="min-width:0;">
                <div class="partner-nom">${escHtml(b.nom)}</div>
                <div class="partner-meta">
                    <span>📧 ${escHtml(b.email)}</span>
                </div>
            </div>
        </div>
        ${descHtml}
        <div class="partner-stats">
            <div class="partner-stat">
                <div class="partner-stat-val">${b.total_ventes}</div>
                <div class="partner-stat-label">Ventes</div>
            </div>
            <div class="partner-stat">
                <div class="partner-stat-val">${b.nb_produits}</div>
                <div class="partner-stat-label">Produits</div>
            </div>
        </div>
        <button class="btn-inviter" onclick="voirScore(${b.id}, this)" style="background:var(--bg-elevated);color:var(--text-2);border:1px solid var(--border);margin-bottom:6px;">
            🎯 Score de compatibilité
        </button>
        <div id="score-${b.id}" class="d-none" style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:10px;padding:8px 12px;margin-bottom:6px;font-size:.75rem;"></div>
        <a href="${inviteUrl}" class="btn-inviter">
            🤝 Inviter à collaborer
        </a>
    `;
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

/* ══════════════════════════════════════════════════════
   FEATURE 7 — Score de compatibilité
══════════════════════════════════════════════════════ */
const SCORE_URL  = "{{ route('admin.ia.scorer-compatibilite') }}";
const SCORE_CSRF = CSRF;

async function voirScore(boutiqueId, btn) {
    const scoreDiv = document.getElementById('score-' + boutiqueId);
    if (!scoreDiv.classList.contains('d-none')) {
        scoreDiv.classList.add('d-none'); return;
    }

    btn.textContent = '⏳ Analyse…'; btn.disabled = true;

    try {
        const res  = await fetch(SCORE_URL, {
            method: 'POST',
            headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':SCORE_CSRF,'Accept':'application/json' },
            body: JSON.stringify({ boutique_id: boutiqueId }),
        });
        const data = await res.json();

        const score   = data.score ?? 0;
        const couleur = score >= 85 ? '#22c55e' : score >= 65 ? '#f59e0b' : '#ef4444';
        const niveauIcon = score >= 85 ? '🟢' : score >= 65 ? '🟡' : '🔴';

        const forts = (data.points_forts || []).map(p => `<li>${p}</li>`).join('');

        scoreDiv.innerHTML = `
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                <span style="font-size:1.5rem;font-weight:900;color:${couleur};">${score}%</span>
                <span style="font-weight:700;color:${couleur};">${niveauIcon} ${data.niveau || ''}</span>
            </div>
            <div style="color:var(--text-2);margin-bottom:4px;">${data.raison_courte || ''}</div>
            ${forts ? `<ul style="margin:4px 0 4px 14px;padding:0;color:var(--text-3);">${forts}</ul>` : ''}
            ${data.point_attention ? `<div style="color:#f59e0b;font-size:.7rem;">⚠️ ${data.point_attention}</div>` : ''}
        `;
        scoreDiv.classList.remove('d-none');
    } catch(e) {
        scoreDiv.innerHTML = '<span style="color:var(--text-3);">Analyse indisponible</span>';
        scoreDiv.classList.remove('d-none');
    } finally {
        btn.textContent = '🎯 Score de compatibilité'; btn.disabled = false;
    }
}

/* ══════════════════════════════════════════════════════
   SUGGESTIONS RAPIDES
══════════════════════════════════════════════════════ */
function utiliserSuggestion(label) {
    document.getElementById('iaInput').value = label;
    lancerRecherche();
}

/* ══════════════════════════════════════════════════════
   TOUCHE ENTRÉE
══════════════════════════════════════════════════════ */
document.getElementById('iaInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') lancerRecherche();
});
</script>
@endpush
