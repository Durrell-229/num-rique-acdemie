@extends('layouts.app')
@section('title','Paiements')
@section('page_title','💰 Historique des Paiements')
@section('nav_admin_paiements','active')

@section('extra_styles')
<style>
.search{background:rgba(255,255,255,.05);border:1px solid var(--border2);border-radius:10px;
  padding:10px 14px;color:var(--text);font-family:var(--font);font-size:13px;width:280px;outline:none;}
.pay-row{display:flex;align-items:center;justify-content:space-between;padding:14px;
  background:rgba(255,255,255,.03);border:1px solid var(--border2);border-radius:var(--r);margin-bottom:10px;}
.pay-info{flex:1;}
.pay-title{font-size:14px;font-weight:600;}
.pay-sub{font-size:12px;color:var(--text2);margin-top:3px;}
.pay-amount{font-size:16px;font-weight:800;color:var(--accent3);font-family:var(--mono);}
</style>
@endsection

@section('content')
<div class="sec-hdr">
  <h2 class="sec-title">💰 Historique des Paiements</h2>
  <input class="search" type="text" placeholder="🔍 Rechercher..." oninput="filterPaiements(this.value)">
</div>

<!-- Totaux -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
  <div class="card" style="text-align:center;padding:16px;">
    <div style="font-size:28px;font-weight:800;color:var(--accent3);font-family:var(--mono);" id="totalRevenu">—</div>
    <div style="font-size:11px;color:var(--text3);margin-top:3px;">Revenus totaux (FCFA)</div>
  </div>
  <div class="card" style="text-align:center;padding:16px;">
    <div style="font-size:28px;font-weight:800;color:var(--accent);" id="totalPaiements">—</div>
    <div style="font-size:11px;color:var(--text3);margin-top:3px;">Paiements validés</div>
  </div>
  <div class="card" style="text-align:center;padding:16px;">
    <div style="font-size:28px;font-weight:800;color:var(--accent2);" id="totalEleves">—</div>
    <div style="font-size:11px;color:var(--text3);margin-top:3px;">Élèves ayant payé</div>
  </div>
</div>

<div id="paiementsGrid"></div>
@endsection

@section('scripts')
<script>
let allPaiements=[];

async function loadPaiements() {
  try {
    const r = await api('/paiements');
    allPaiements = r.paiements||[];
    // Calculer totaux
    const valides = allPaiements.filter(p=>p.statut==='valide');
    const revenu  = valides.reduce((s,p)=>s+parseInt(p.montant||0),0);
    const elevesUniques = new Set(valides.map(p=>p.user_email)).size;
    document.getElementById('totalRevenu').textContent    = revenu.toLocaleString('fr-FR')+' FCFA';
    document.getElementById('totalPaiements').textContent = valides.length;
    document.getElementById('totalEleves').textContent    = elevesUniques;
    renderPaiements(allPaiements);
  } catch(e) {}
}

function renderPaiements(list) {
  const g=document.getElementById('paiementsGrid');
  if (!list.length) { g.innerHTML='<p style="color:var(--text3);">Aucun paiement enregistré.</p>'; return; }
  g.innerHTML = list.map(p=>`
    <div class="pay-row">
      <div class="pay-info">
        <div class="pay-title">📚 ${p.cours_title||'—'}</div>
        <div class="pay-sub">👤 ${p.user_name||'—'} · 📧 ${p.user_email||'—'}</div>
        <div class="pay-sub">🔑 ${p.transaction_id||'—'} · 🕐 ${new Date(p.created_at).toLocaleDateString('fr-FR')}</div>
      </div>
      <div style="text-align:right;">
        <div class="pay-amount">${parseInt(p.montant||0).toLocaleString('fr-FR')} FCFA</div>
        <span class="tag ${p.statut==='valide'?'tag-g':p.statut==='echoue'?'tag-r':'tag-y'}" style="margin-top:6px;display:inline-block;">
          ${p.statut==='valide'?'✅ Validé':p.statut==='echoue'?'❌ Échoué':'⏳ En attente'}
        </span>
      </div>
    </div>`).join('');
}

function filterPaiements(q) {
  renderPaiements(allPaiements.filter(p=>(p.cours_title+p.user_name+p.user_email+p.transaction_id).toLowerCase().includes(q.toLowerCase())));
}

loadPaiements();
</script>
@endsection
