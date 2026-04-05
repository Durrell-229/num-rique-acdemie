@extends('layouts.app')
@section('title','Tableau de Bord')
@section('page_title','📊 Tableau de Bord')
@section('nav_dashboard','active')

@section('head_scripts')
<script src="https://meet.jit.si/external_api.js"></script>
@endsection

@section('extra_styles')
<style>
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
.stat-card{background:var(--card);border:1px solid var(--border2);border-radius:var(--r2);
  padding:22px;transition:all .3s;position:relative;overflow:hidden;}
.stat-card:hover{border-color:rgba(59,130,246,.25);transform:translateY(-2px);}
.stat-ico{font-size:26px;margin-bottom:10px;}
.stat-val{font-size:28px;font-weight:800;font-family:var(--mono);color:var(--accent);}
.stat-val.v2{color:var(--accent2);}
.stat-val.v3{color:var(--accent3);}
.stat-val.v4{color:var(--gold);}
.stat-lbl{font-size:11px;color:var(--text3);margin-top:3px;letter-spacing:.5px;}
.welcome{background:linear-gradient(135deg,rgba(59,130,246,.08),rgba(139,92,246,.05));
  border:1px solid rgba(59,130,246,.15);border-radius:var(--r2);padding:28px 32px;
  margin-bottom:24px;position:relative;overflow:hidden;}
.welcome::after{content:'🎓';position:absolute;right:28px;top:50%;transform:translateY(-50%);
  font-size:80px;opacity:.06;pointer-events:none;}
.welcome h2{font-size:22px;font-weight:800;margin-bottom:6px;}
.welcome p{color:var(--text2);font-size:13px;line-height:1.6;}
.hi{color:var(--accent);}
.g2{display:grid;grid-template-columns:1.6fr 1fr;gap:22px;margin-bottom:24px;}
.pb{background:rgba(255,255,255,.06);border-radius:20px;height:4px;overflow:hidden;margin-top:8px;}
.pf{height:100%;border-radius:20px;background:linear-gradient(90deg,var(--accent),var(--accent2));}
.live-card{background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.18);
  border-radius:var(--r);padding:14px;margin-bottom:12px;}
.pulse{display:inline-block;width:7px;height:7px;background:var(--danger);border-radius:50%;
  margin-right:5px;animation:pu 1.4s infinite;}
@keyframes pu{0%,100%{opacity:1}50%{opacity:.3}}
.join-btn{background:var(--danger);color:#fff;border:none;padding:8px 14px;border-radius:8px;
  cursor:pointer;font-size:12px;font-weight:700;font-family:var(--font);transition:all .2s;}
.join-btn:hover{transform:scale(1.04);}
.cours-mini{display:flex;align-items:center;gap:13px;padding:12px;
  background:rgba(255,255,255,.03);border:1px solid var(--border2);border-radius:12px;
  margin-bottom:10px;cursor:pointer;transition:all .2s;}
.cours-mini:hover{border-color:rgba(59,130,246,.3);}
.cours-mini-ico{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;
  justify-content:center;font-size:20px;flex-shrink:0;}
@media(max-width:1100px){.stats-grid{grid-template-columns:repeat(2,1fr)}.g2{grid-template-columns:1fr}}
@media(max-width:480px){.stats-grid{grid-template-columns:1fr 1fr;}}
</style>
@endsection

@section('content')
<div class="welcome">
  <h2>Bienvenue, <span class="hi" id="wName">—</span> ! 👋</h2>
  <p id="wInfo">Chargement...</p>
</div>

<div class="stats-grid">
  <div class="stat-card"><div class="stat-ico">📚</div><div class="stat-val" id="sCours">—</div><div class="stat-lbl">COURS DISPONIBLES</div></div>
  <div class="stat-card"><div class="stat-ico">🚪</div><div class="stat-val v2" id="sSalles">—</div><div class="stat-lbl">SALLES DE CLASSE</div></div>
  <div class="stat-card"><div class="stat-ico">👥</div><div class="stat-val v3" id="sElev">—</div><div class="stat-lbl">ÉLÈVES INSCRITS</div></div>
  <div class="stat-card"><div class="stat-ico">🌍</div><div class="stat-val v4" id="sPays">—</div><div class="stat-lbl">PAYS REPRÉSENTÉS</div></div>
</div>

<div class="g2">
  <div class="card">
    <div class="card-header">
      <span class="card-title">📚 Derniers cours publiés</span>
      <a href="{{ route('cours.index') }}" class="btn btn-s btn-sm">Voir tout →</a>
    </div>
    <div id="dCours"><p style="color:var(--text3);font-size:13px;">Chargement...</p></div>
  </div>
  <div class="card">
    <div class="card-header">
      <span class="card-title">🔴 Salles actives</span>
      <span style="font-size:11px;padding:4px 10px;border-radius:20px;font-weight:600;background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.25);"><span class="pulse"></span>LIVE</span>
    </div>
    <div id="dSalles"><p style="color:var(--text3);font-size:13px;">Chargement...</p></div>
    <a href="{{ route('salles.index') }}" class="btn btn-p btn-sm" style="margin-top:14px;width:100%;">Voir les salles →</a>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <span class="card-title">👥 Statistiques élèves</span>
    <span style="font-size:12px;color:var(--text3);" id="onlLabel">—</span>
  </div>
  {{-- On affiche uniquement le compteur, pas les noms --}}
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:8px;">
    <div style="background:rgba(59,130,246,.06);border:1px solid rgba(59,130,246,.15);border-radius:14px;padding:20px;text-align:center;">
      <div style="font-size:32px;font-weight:800;color:var(--accent);" id="cntEleves">—</div>
      <div style="font-size:12px;color:var(--text3);margin-top:4px;">Élèves inscrits</div>
    </div>
    <div style="background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.15);border-radius:14px;padding:20px;text-align:center;">
      <div style="font-size:32px;font-weight:800;color:var(--accent3);" id="cntOnline">—</div>
      <div style="font-size:12px;color:var(--text3);margin-top:4px;">En ligne maintenant</div>
    </div>
    <div style="background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.15);border-radius:14px;padding:20px;text-align:center;">
      <div style="font-size:32px;font-weight:800;color:var(--gold);" id="cntPays">—</div>
      <div style="font-size:12px;color:var(--text3);margin-top:4px;">Pays représentés</div>
    </div>
  </div>
</div>

<div class="foot">Design by <span>Leonard</span> · <span id="fDate"></span> · ACADÉMIE NUMÉRIQUE © 2025</div>
@endsection

@section('scripts')
<script>
const MATIERES_EMOJI={'Mathématiques':'📐','Français':'📖','SVT':'🌿','PCT':'⚗️','Histoire-Géographie':'🗺️','Philosophie':'🧠','Anglais':'🇬🇧'};
const MATIERES_BG={'Mathématiques':'#0d1f3c','Français':'#1a0d2a','SVT':'#0d2a1a','PCT':'#2a1a0d','Histoire-Géographie':'#2a0d1a','Philosophie':'#1a0d2a','Anglais':'#0d1a2a'};
const SALLE_BG=['#071a2e','#1a071e','#071a10','#1a1007'];

document.getElementById('fDate').textContent = new Date().toLocaleDateString('fr-FR',{weekday:'long',day:'2-digit',month:'long',year:'numeric'});

async function loadDashboard() {
  try {
    const r = await api('/boot');
    const data = r;
    const user = data.user;
    const stats = data.stats || {};
    const cours = data.cours || [];
    const salles = data.salles || [];

    document.getElementById('wName').textContent = (user.name||'').split(' ')[0];
    document.getElementById('wInfo').textContent = `📍 ${user.pays||'—'} · ${user.localite||''} · 🎒 ${user.classe||'—'}`;
    document.getElementById('sCours').textContent  = stats.cours   || cours.length;
    document.getElementById('sSalles').textContent = stats.salles  || salles.length;
    document.getElementById('sElev').textContent   = stats.eleves  || 0;
    document.getElementById('sPays').textContent   = stats.pays    || 0;
    document.getElementById('cntEleves').textContent = stats.eleves || 0;
    document.getElementById('cntOnline').textContent = stats.en_ligne || 0;
    document.getElementById('cntPays').textContent   = stats.pays || 0;
    document.getElementById('onlLabel').textContent  = `${stats.eleves||0} inscrits · ${stats.en_ligne||0} en ligne`;

    // Derniers cours
    const dCours = document.getElementById('dCours');
    if (!cours.length) { dCours.innerHTML='<p style="color:var(--text3);font-size:13px;">Aucun cours publié.</p>'; }
    else {
      dCours.innerHTML = cours.slice(0,4).map((c,i)=>`
        <div class="cours-mini" onclick="window.location.href='/cours'">
          <div class="cours-mini-ico" style="background:${MATIERES_BG[c.matiere]||'#1e3a5f'}">${MATIERES_EMOJI[c.matiere]||'📚'}</div>
          <div style="flex:1;overflow:hidden;">
            <div style="font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${c.title}</div>
            <div style="font-size:12px;color:var(--text3);margin-top:2px;">${c.matiere||''} · ${c.niveau||''}</div>
            <div class="pb"><div class="pf" style="width:${30+i*18}%"></div></div>
          </div>
        </div>`).join('');
    }

    // Salles
    const dSalles = document.getElementById('dSalles');
    if (!salles.length) { dSalles.innerHTML='<p style="color:var(--text3);font-size:13px;">Aucune salle créée.</p>'; }
    else {
      dSalles.innerHTML = salles.slice(0,3).map(s=>`
        <div class="live-card">
          <div style="font-size:14px;font-weight:700;margin-bottom:3px;"><span class="pulse"></span>${s.icon||'🎓'} ${s.name}</div>
          <div style="font-size:12px;color:var(--text2);margin-bottom:10px;">${s.matiere||'Salle virtuelle'}</div>
          <button class="join-btn" onclick="window.location.href='/salles'">🚪 Entrer</button>
        </div>`).join('');
    }

    // Ping
    api('/auth/ping','POST').catch(()=>{});
  } catch(e) { console.error(e); }
}

loadDashboard();
setInterval(()=>api('/auth/ping','POST').catch(()=>{}), 30000);
</script>
@endsection
