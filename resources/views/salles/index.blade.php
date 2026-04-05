@extends('layouts.app')
@section('title','Salles de Classe')
@section('page_title','🚪 Salles de Classe')
@section('nav_salles','active')

@section('head_scripts')
<script src="https://meet.jit.si/external_api.js"></script>
@endsection

@section('extra_styles')
<style>
.salles-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(310px,1fr));gap:20px;}
.salle-card{background:var(--card);border:1px solid var(--border2);border-radius:var(--r2);
  overflow:hidden;transition:all .3s;position:relative;}
.salle-card:hover{transform:translateY(-4px);box-shadow:var(--glow);border-color:rgba(59,130,246,.3);}
.salle-banner{height:130px;display:flex;align-items:center;justify-content:center;font-size:54px;}
.salle-body{padding:20px;}
.salle-name{font-size:16px;font-weight:800;margin-bottom:4px;}
.salle-desc{font-size:12px;color:var(--text2);margin-bottom:6px;}
.salle-room{font-size:10px;color:var(--accent);font-family:var(--mono);
  background:rgba(59,130,246,.06);padding:4px 8px;border-radius:6px;display:inline-block;margin-bottom:14px;}
.salle-footer{display:flex;align-items:center;justify-content:space-between;}
.salle-enter{background:linear-gradient(135deg,var(--accent),#2563eb);color:#fff;border:none;
  padding:12px 22px;border-radius:10px;cursor:pointer;font-size:13px;font-weight:800;
  font-family:var(--font);transition:all .25s;display:flex;align-items:center;gap:7px;}
.salle-enter:hover{transform:scale(1.04);box-shadow:0 8px 24px rgba(59,130,246,.5);}
.salle-active-wrap{margin-bottom:22px;display:none;}
#jframeSalle{width:100%;height:520px;border-radius:var(--r);border:1px solid var(--border2);background:#000;}
</style>
@endsection

@section('content')
<div class="sec-hdr">
  <h2 class="sec-title">🚪 Salles de Classe</h2>
</div>

<p style="color:var(--text2);font-size:13px;margin-bottom:24px;line-height:1.7;">
  Cliquez sur <strong style="color:var(--accent);">Entrer dans la salle</strong> pour rejoindre la visioconférence directement.
</p>

<!-- Salle active embed -->
<div class="salle-active-wrap" id="salleActiveWrap">
  <div class="card">
    <div class="card-header">
      <span class="card-title" id="salleActiveName">🚪 Salle</span>
      <button class="btn btn-r btn-sm" onclick="quitterSalle()">✕ Quitter</button>
    </div>
    <div id="jframeSalle"></div>
  </div>
</div>

<div class="salles-grid" id="sallesGrid">
  <p style="color:var(--text3);">⏳ Chargement...</p>
</div>
@endsection

@section('scripts')
<script>
const SALLE_BG=['#071a2e','#1a071e','#071a10','#1a1007','#1a0710','#071014'];
let jitsiSalle=null, currentUser=null;

async function loadSalles() {
  try {
    const r = await api('/boot');
    currentUser = r.user;
    renderSalles(r.salles||[]);
  } catch(e) {}
}

function renderSalles(list) {
  const g = document.getElementById('sallesGrid');
  if (!list.length) {
    g.innerHTML=`<div style="text-align:center;padding:60px;color:var(--text3);grid-column:1/-1;">
      <div style="font-size:52px;opacity:.2;margin-bottom:14px;">🚪</div>
      <div style="font-size:15px;font-weight:700;">Aucune salle créée</div>
    </div>`; return;
  }
  g.innerHTML = list.map((s,i)=>`
    <div class="salle-card">
      <div class="salle-banner" style="background:${SALLE_BG[i%SALLE_BG.length]}">${s.icon||'🎓'}</div>
      <div class="salle-body">
        <div class="salle-name">${s.name}</div>
        <div class="salle-desc">${s.description||'Salle de classe virtuelle'}</div>
        <div class="salle-room">meet.jit.si/${s.room}</div>
        <div class="salle-footer">
          <span style="font-size:12px;color:var(--text3);">${s.matiere||''}</span>
          <button class="salle-enter" onclick='entrerSalle(${JSON.stringify(s).replace(/'/g,"&apos;")})'>🚪 Entrer dans la salle</button>
        </div>
      </div>
    </div>`).join('');
}

function entrerSalle(s) {
  if (jitsiSalle) { try{jitsiSalle.dispose();}catch(e){} jitsiSalle=null; }
  const wrap=document.getElementById('salleActiveWrap');
  const frame=document.getElementById('jframeSalle');
  document.getElementById('salleActiveName').textContent='🚪 '+s.name;
  frame.innerHTML='';
  wrap.style.display='block';
  wrap.scrollIntoView({behavior:'smooth',block:'start'});
  const userName = currentUser?.name||'Élève';
  if (typeof JitsiMeetExternalAPI!=='undefined') {
    try {
      jitsiSalle=new JitsiMeetExternalAPI('meet.jit.si',{
        roomName:s.room,parentNode:frame,width:'100%',height:520,
        configOverwrite:{prejoinPageEnabled:false,startWithAudioMuted:false,startWithVideoMuted:false,disableDeepLinking:true,defaultLanguage:'fr'},
        interfaceConfigOverwrite:{SHOW_JITSI_WATERMARK:false,APP_NAME:'Académie Numérique'},
        userInfo:{displayName:userName,email:currentUser?.email||''},lang:'fr',
      });
      jitsiSalle.addEventListeners({readyToClose:quitterSalle,videoConferenceLeft:quitterSalle});
      toast('🚪 Vous avez rejoint : '+s.name); return;
    } catch(e) {}
  }
  frame.innerHTML=`<iframe allow="camera; microphone; fullscreen; display-capture;" src="https://meet.jit.si/${encodeURIComponent(s.room)}#config.prejoinPageEnabled=false&userInfo.displayName=${encodeURIComponent(userName)}" style="width:100%;height:520px;border:none;border-radius:12px;"></iframe>`;
  toast('🚪 Vous avez rejoint : '+s.name);
}

function quitterSalle() {
  if (jitsiSalle) { try{jitsiSalle.dispose();}catch(e){} jitsiSalle=null; }
  document.getElementById('jframeSalle').innerHTML='';
  document.getElementById('salleActiveWrap').style.display='none';
  toast('Vous avez quitté la salle');
}

loadSalles();
</script>
@endsection
