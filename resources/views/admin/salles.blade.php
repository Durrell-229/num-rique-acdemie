@extends('layouts.app')
@section('title','Gérer les Salles')
@section('page_title','🏫 Gérer les Salles')
@section('nav_admin_salles','active')

@section('extra_styles')
<style>
.salles-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(310px,1fr));gap:20px;}
.salle-card{background:var(--card);border:1px solid var(--border2);border-radius:var(--r2);overflow:hidden;position:relative;}
.salle-banner{height:130px;display:flex;align-items:center;justify-content:center;font-size:54px;}
.salle-body{padding:20px;}
.salle-name{font-size:16px;font-weight:800;margin-bottom:4px;}
.salle-desc{font-size:12px;color:var(--text2);margin-bottom:6px;}
.salle-room{font-size:10px;color:var(--accent);font-family:var(--mono);background:rgba(59,130,246,.06);padding:4px 8px;border-radius:6px;display:inline-block;margin-bottom:14px;}
.salle-del-btn{position:absolute;top:10px;right:10px;background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);border-radius:8px;padding:5px 9px;font-size:12px;color:#fca5a5;cursor:pointer;}
.salle-del-btn:hover{background:rgba(239,68,68,.3);}
</style>
@endsection

@section('content')
<div class="sec-hdr">
  <h2 class="sec-title">🏫 Gérer les Salles</h2>
  <button class="btn btn-p btn-sm" onclick="openM('mAddSalle')">+ Nouvelle salle</button>
</div>

<div class="salles-grid" id="adminSallesGrid">
  <p style="color:var(--text3);">⏳ Chargement...</p>
</div>

<!-- Modal créer salle -->
<div class="mo" id="mAddSalle">
  <div class="md">
    <button class="md-close" onclick="closeM('mAddSalle')">✕</button>
    <h3 class="md-title">🚪 Créer une Salle</h3>
    <div class="fg"><label>Nom *</label><input id="sTit" type="text" placeholder="Ex: Classe Mathématiques Terminale C"></div>
    <div class="fg"><label>Matière</label>
      <select id="sMat">
        <option value="📐 Mathématiques">📐 Mathématiques</option>
        <option value="📖 Français">📖 Français</option>
        <option value="🌿 SVT">🌿 SVT</option>
        <option value="⚗️ PCT">⚗️ PCT</option>
        <option value="🗺️ Histoire-Géographie">🗺️ Histoire-Géographie</option>
        <option value="🧠 Philosophie">🧠 Philosophie</option>
        <option value="🇬🇧 Anglais">🇬🇧 Anglais</option>
        <option value="🎓 Général">🎓 Général</option>
      </select>
    </div>
    <div class="fg"><label>Description</label><input id="sDesc" type="text" placeholder="Ex: Cours du lundi 14h-16h"></div>
    <div class="fg">
      <label>Identifiant Jitsi * <span style="color:var(--text3);font-size:10px;">(sans espaces)</span></label>
      <input id="sRoom" type="text" placeholder="classe-maths-terminale-c" oninput="prevSalle()">
    </div>
    <div style="background:rgba(59,130,246,.06);border:1px solid rgba(59,130,246,.15);border-radius:10px;padding:10px 14px;font-size:12px;color:var(--accent);font-family:var(--mono);margin-bottom:16px;" id="sPrev">https://meet.jit.si/classe-...</div>
    <div class="fg"><label>Icône</label>
      <select id="sIcon">
        <option value="📐">📐 Mathématiques</option><option value="📖">📖 Français</option>
        <option value="🌿">🌿 SVT</option><option value="⚗️">⚗️ PCT</option>
        <option value="🗺️">🗺️ Histoire-Géo</option><option value="🧠">🧠 Philosophie</option>
        <option value="🇬🇧">🇬🇧 Anglais</option><option value="🎓">🎓 Général</option>
      </select>
    </div>
    <button class="btn btn-p btn-full" onclick="saveSalle()">✅ Créer la salle</button>
  </div>
</div>
@endsection

@section('scripts')
<script>
const SALLE_BG=['#071a2e','#1a071e','#071a10','#1a1007','#1a0710','#071014'];

async function loadSalles() {
  try {
    const r = await api('/salles');
    renderSalles(r.salles||[]);
  } catch(e) {}
}

function renderSalles(list) {
  const g=document.getElementById('adminSallesGrid');
  if (!list.length) { g.innerHTML='<p style="color:var(--text3);">Aucune salle créée.</p>'; return; }
  g.innerHTML = list.map((s,i)=>`
    <div class="salle-card">
      <button class="salle-del-btn" onclick="deleteSalle(${s.id})">🗑️ Supprimer</button>
      <div class="salle-banner" style="background:${SALLE_BG[i%SALLE_BG.length]}">${s.icon||'🎓'}</div>
      <div class="salle-body">
        <div class="salle-name">${s.name}</div>
        <div class="salle-desc">${s.description||'Salle de classe virtuelle'}</div>
        <div class="salle-room">meet.jit.si/${s.room}</div>
        <div style="font-size:12px;color:var(--text3);">${s.matiere||''}</div>
      </div>
    </div>`).join('');
}

function prevSalle() {
  const r=document.getElementById('sRoom').value.replace(/\s+/g,'-')||'...';
  document.getElementById('sPrev').textContent='https://meet.jit.si/'+r;
}

async function saveSalle() {
  const name=document.getElementById('sTit').value.trim();
  const mat=document.getElementById('sMat').value;
  const desc=document.getElementById('sDesc').value;
  const room=document.getElementById('sRoom').value.replace(/\s+/g,'-').toLowerCase().replace(/[^a-z0-9\-]/g,'');
  const icon=document.getElementById('sIcon').value;
  if (!name||!room) return toast('Nom et identifiant Jitsi obligatoires',true);
  const btn=document.querySelector('#mAddSalle .btn-full');
  btn.disabled=true; btn.textContent='⏳ Création...';
  try {
    await api('/salles','POST',{name,matiere:mat,description:desc,room,icon});
    ['sTit','sDesc','sRoom'].forEach(id=>document.getElementById(id).value='');
    closeM('mAddSalle');
    toast('✅ Salle créée !');
    loadSalles();
  } catch(e) { toast(e.message,true); }
  finally { btn.disabled=false; btn.textContent='✅ Créer la salle'; }
}

async function deleteSalle(id) {
  if (!confirm('Supprimer cette salle ?')) return;
  try {
    await api(`/salles/${id}`,'DELETE');
    toast('Salle supprimée');
    loadSalles();
  } catch(e) { toast(e.message,true); }
}

loadSalles();
</script>
@endsection
