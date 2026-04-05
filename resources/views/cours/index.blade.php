@extends('layouts.app')
@section('title','Cours')
@section('page_title','📚 Cours Disponibles')
@section('nav_cours','active')

@section('extra_styles')
<style>
.search{background:rgba(255,255,255,.05);border:1px solid var(--border2);border-radius:10px;
  padding:10px 14px;color:var(--text);font-family:var(--font);font-size:13px;
  width:240px;outline:none;transition:all .3s;}
.search:focus{border-color:var(--accent);}
.tabs{display:flex;gap:3px;background:rgba(255,255,255,.04);border:1px solid var(--border2);
  border-radius:12px;padding:4px;margin-bottom:22px;width:fit-content;flex-wrap:wrap;}
.tab{padding:7px 14px;border-radius:9px;cursor:pointer;font-size:12px;font-weight:600;
  color:var(--text2);transition:all .2s;border:none;background:none;font-family:var(--font);}
.tab.active{background:var(--accent);color:#fff;}
.cours-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:18px;}
.cours-card{background:var(--card);border:1px solid var(--border2);border-radius:var(--r2);
  overflow:hidden;transition:all .3s;cursor:pointer;}
.cours-card:hover{transform:translateY(-4px);border-color:rgba(59,130,246,.3);box-shadow:var(--glow);}
.cours-banner{height:120px;display:flex;align-items:center;justify-content:center;font-size:48px;}
.cours-body{padding:18px;}
.cours-title{font-size:15px;font-weight:700;margin-bottom:5px;}
.cours-desc{font-size:12px;color:var(--text2);margin-bottom:14px;line-height:1.5;
  display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.cours-footer{display:flex;align-items:center;justify-content:space-between;gap:6px;flex-wrap:wrap;}
.cours-by{font-size:11px;color:var(--text3);}
/* Modal cours */
.media-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;margin-top:16px;}
.media-item{background:var(--card);border:1px solid var(--border2);border-radius:var(--r);overflow:hidden;}
.media-thumb{width:100%;height:120px;background:var(--bg3);display:flex;align-items:center;
  justify-content:center;font-size:36px;cursor:pointer;position:relative;overflow:hidden;}
.media-thumb:hover .media-play{opacity:1;}
.media-play{position:absolute;inset:0;background:rgba(0,0,0,.4);display:flex;
  align-items:center;justify-content:center;font-size:28px;opacity:0;transition:opacity .2s;}
.media-info{padding:12px;}
.media-name{font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.media-size{font-size:11px;color:var(--text3);margin-top:2px;}
</style>
@endsection

@section('content')
<div class="sec-hdr">
  <h2 class="sec-title">📚 Cours Disponibles</h2>
  <input class="search" type="text" placeholder="🔍 Rechercher..." id="searchInput" oninput="filterCours(this.value)">
</div>

<div class="tabs">
  <button class="tab active" onclick="filterLevel('all',this)">Tous</button>
  <button class="tab" onclick="filterLevel('primaire',this)">Primaire</button>
  <button class="tab" onclick="filterLevel('college',this)">Collège</button>
  <button class="tab" onclick="filterLevel('lycee',this)">Lycée</button>
  <button class="tab" onclick="filterLevel('superieur',this)">Supérieur</button>
</div>

<div class="cours-grid" id="coursGrid">
  <p style="color:var(--text3);grid-column:1/-1;">⏳ Chargement des cours...</p>
</div>

<!-- Modal détail cours -->
<div class="mo" id="mCours">
  <div class="md" style="max-width:600px;">
    <button class="md-close" onclick="closeM('mCours')">✕</button>
    <div id="mCoursBody"></div>
  </div>
</div>

<!-- Modal lecteur vidéo -->
<div class="mo" id="mPlayer">
  <div class="md" style="max-width:820px;background:#000;border-color:rgba(255,255,255,.1);">
    <button class="md-close" style="background:rgba(255,255,255,.1);color:#fff;" onclick="closeM('mPlayer');stopVideo()">✕</button>
    <video id="playerVideo" controls style="width:100%;border-radius:10px;max-height:480px;margin-top:8px;"></video>
    <div style="padding:12px 0 4px;font-size:14px;font-weight:600;" id="playerName"></div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
<script>
const MATIERES_EMOJI={'Mathématiques':'📐','Français':'📖','SVT':'🌿','PCT':'⚗️','Histoire-Géographie':'🗺️','Philosophie':'🧠','Anglais':'🇬🇧'};
const MATIERES_BG={'Mathématiques':'#0d1f3c','Français':'#1a0d2a','SVT':'#0d2a1a','PCT':'#2a1a0d','Histoire-Géographie':'#2a0d1a','Philosophie':'#1a0d2a','Anglais':'#0d1a2a'};
const LEVEL_TAG={primaire:'tag-g',college:'tag-b',lycee:'tag-p',superieur:'tag-y'};
let allCours = [], currentLevel = 'all', currentUser = null;

async function loadCours() {
  try {
    const r = await api('/boot');
    allCours = r.cours || [];
    currentUser = r.user;
    renderCours(allCours);
  } catch(e) { document.getElementById('coursGrid').innerHTML='<p style="color:var(--danger);">Erreur de chargement.</p>'; }
}

function renderCours(list) {
  const g = document.getElementById('coursGrid');
  if (!list.length) { g.innerHTML='<p style="color:var(--text3);grid-column:1/-1;">Aucun cours disponible.</p>'; return; }
  g.innerHTML = list.map(c=>`
    <div class="cours-card" onclick="showCours(${c.id})">
      <div class="cours-banner" style="background:${MATIERES_BG[c.matiere]||'#1e3a5f'}">${MATIERES_EMOJI[c.matiere]||'📚'}</div>
      <div class="cours-body">
        <div class="cours-title">${c.title}</div>
        <div class="cours-desc">${c.description||'Aucune description.'}</div>
        <div class="cours-footer">
          <span class="tag ${LEVEL_TAG[c.niveau]||'tag-b'}">${c.niveau||''}</span>
          <span class="tag tag-b">${c.matiere||''}</span>
          ${parseInt(c.prix||0)===0?'<span class="tag tag-g">Gratuit</span>':`<span class="tag tag-y">💰 ${parseInt(c.prix).toLocaleString('fr-FR')} FCFA</span>`}
        </div>
      </div>
    </div>`).join('');
}

function filterCours(q) {
  const filtered = allCours.filter(c => {
    const matchLevel = currentLevel==='all' || c.niveau===currentLevel;
    const matchQ = (c.title+(c.matiere||'')+(c.description||'')).toLowerCase().includes(q.toLowerCase());
    return matchLevel && matchQ;
  });
  renderCours(filtered);
}

function filterLevel(lv, el) {
  currentLevel = lv;
  document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active')); el.classList.add('active');
  filterCours(document.getElementById('searchInput').value);
}

async function showCours(id) {
  const c = allCours.find(x=>String(x.id)===String(id)); if (!c) return;
  const isAdmin = currentUser && ['admin','enseignant'].includes(currentUser.role);
  const prix = parseInt(c.prix||0);
  const estGratuit = prix === 0;
  document.getElementById('mCoursBody').innerHTML='<div style="text-align:center;padding:40px;color:var(--text3);">⏳ Chargement...</div>';
  openM('mCours');
  try {
    const reqs = [api(`/cours/${id}`)];
    if (!isAdmin && !estGratuit) reqs.push(api(`/paiements/verifier?cours_id=${id}`));
    const results = await Promise.all(reqs);
    const detail = results[0].cours || c;
    let paye = isAdmin || estGratuit;
    if (!isAdmin && !estGratuit && results[1]) paye = results[1].paye === true;
    // Mettre à jour le cache local
    const idx = allCours.findIndex(x=>String(x.id)===String(id));
    if (idx!==-1) allCours[idx] = Object.assign({},allCours[idx],detail);
    document.getElementById('mCoursBody').innerHTML = buildCoursHtml(detail, paye);
  } catch(e) {
    document.getElementById('mCoursBody').innerHTML = buildCoursHtml(c, false);
  }
}

function buildCoursHtml(c, paye) {
  const isAdmin = currentUser && ['admin','enseignant'].includes(currentUser.role);
  const accesOk = paye || isAdmin;
  const prix = parseInt(c.prix||0);
  const url = c.url_externe||'';

  const filesHtml = (c.files||[]).length && accesOk ? `
    <div style="margin-top:16px;">
      <div style="font-size:13px;font-weight:700;margin-bottom:10px;">📎 Fichiers attachés</div>
      <div class="media-grid">
        ${c.files.map(f=>`
          <div class="media-item">
            <div class="media-thumb" onclick="openFile('${f.data}','${f.mime_type||''}')">
              ${(f.mime_type||'').startsWith('image')?`<img src="${f.data}" style="width:100%;height:100%;object-fit:cover;">`:
              `<span>${(f.mime_type||'').includes('pdf')?'📄':'📎'}</span>`}
              <div class="media-play">▶</div>
            </div>
            <div class="media-info"><div class="media-name">${f.name}</div><div class="media-size">${f.size_label||''}</div></div>
          </div>`).join('')}
      </div>
    </div>` : '';

  const videosHtml = (c.videos||[]).length && accesOk ? `
    <div style="margin-top:16px;">
      <div style="font-size:13px;font-weight:700;margin-bottom:10px;">🎬 Vidéos du cours</div>
      <div class="media-grid">
        ${c.videos.map(v=>`
          <div class="media-item">
            <div class="media-thumb" onclick="playVideo('${v.data}','${v.name}')">
              <span>🎬</span><div class="media-play">▶</div>
            </div>
            <div class="media-info"><div class="media-name">${v.name}</div><div class="media-size">${v.size_label||''}</div></div>
          </div>`).join('')}
      </div>
    </div>` : '';

  const verrou = !accesOk && prix > 0 ? `
    <div style="margin-top:20px;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);border-radius:14px;padding:20px;text-align:center;">
      <div style="font-size:36px;margin-bottom:10px;">🔒</div>
      <div style="font-size:14px;font-weight:700;color:#fcd34d;margin-bottom:6px;">Contenu verrouillé</div>
      <div style="font-size:12px;color:var(--text3);margin-bottom:16px;">${(c.files||[]).length} fichier(s) · ${(c.videos||[]).length} vidéo(s)</div>
      <button data-cours-id="${c.id}" data-transaction-amount="${prix}"
        data-transaction-description="Accès : ${c.title}"
        data-customer-email="${currentUser?.email||''}" data-customer-lastname="${currentUser?.name||''}"
        style="padding:16px 36px;font-size:17px;font-weight:800;font-family:var(--font);border:none;border-radius:14px;cursor:pointer;background:linear-gradient(135deg,#10b981,#059669);color:#fff;"
        onclick="ouvrirPaiement(this)">
        💳 Payer — ${prix.toLocaleString('fr-FR')} FCFA
      </button>
    </div>` : '';

  return `
    <div style="text-align:center;font-size:52px;margin-bottom:14px;">${MATIERES_EMOJI[c.matiere]||'📚'}</div>
    <h2 style="font-size:20px;font-weight:800;margin-bottom:8px;">${c.title}</h2>
    <div style="display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap;">
      <span class="tag ${LEVEL_TAG[c.niveau]||'tag-b'}">${c.niveau||''}</span>
      <span class="tag tag-p">${c.matiere||''}</span>
      ${prix===0?'<span class="tag tag-g">✅ Gratuit</span>':`<span class="tag tag-y">💰 ${prix.toLocaleString('fr-FR')} FCFA</span>`}
    </div>
    <p style="color:var(--text2);font-size:13px;line-height:1.7;margin-bottom:14px;">${c.description||'Aucune description.'}</p>
    ${url&&accesOk?`<a href="${url}" target="_blank" class="btn btn-p" style="display:flex;text-decoration:none;margin-bottom:14px;">🔗 Accéder au cours en ligne</a>`:''}
    ${filesHtml}${videosHtml}${verrou}`;
}

function openFile(data,type) {
  if (type&&type.startsWith('image')) { const w=window.open(); w.document.write(`<img src="${data}" style="max-width:100%;">`); }
  else { const a=document.createElement('a'); a.href=data; a.target='_blank'; a.click(); }
}
function playVideo(data,name) {
  document.getElementById('playerVideo').src=data;
  document.getElementById('playerName').textContent=name;
  openM('mPlayer');
}
function stopVideo() { const v=document.getElementById('playerVideo'); v.pause(); v.src=''; }

function ouvrirPaiement(btn) {
  const coursId=btn.getAttribute('data-cours-id');
  const amount=parseInt(btn.getAttribute('data-transaction-amount'));
  const widget = FedaPay.init({
    public_key:'pk_live_X_DmtE7HnbtVA7i1nmjgcXJ0',
    transaction:{amount,description:btn.getAttribute('data-transaction-description')},
    customer:{email:btn.getAttribute('data-customer-email'),lastname:btn.getAttribute('data-customer-lastname')},
    onComplete:async(resp)=>{
      if (resp.reason===FedaPay.DIALOG_DISMISSED) { toast('Paiement annulé.',true); return; }
      if (resp.transaction?.status==='approved') {
        try {
          await api('/paiements/confirmer','POST',{cours_id:parseInt(coursId),transaction_id:String(resp.transaction.id||''),montant:amount});
          toast('✅ Paiement confirmé ! Accès débloqué.');
          showCours(coursId);
        } catch(e) { toast('✅ Paiement reçu. Rafraîchissez si besoin.'); }
      } else { toast('Paiement non complété.',true); }
    }
  });
  widget.open();
}

loadCours();
</script>
@endsection
