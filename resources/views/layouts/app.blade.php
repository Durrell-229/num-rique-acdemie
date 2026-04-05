<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>ACADÉMIE NUMÉRIQUE — @yield('title','Plateforme')</title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
@yield('head_scripts')
<style>
:root{--bg:#04080f;--bg2:#080f1a;--bg3:#0c1524;--accent:#3b82f6;--accent2:#8b5cf6;--accent3:#10b981;--danger:#ef4444;--gold:#f59e0b;--text:#f1f5f9;--text2:#94a3b8;--text3:#475569;--card:rgba(8,15,26,0.9);--border2:rgba(255,255,255,0.06);--glow:0 0 30px rgba(59,130,246,0.2);--font:'Outfit',sans-serif;--mono:'JetBrains Mono',monospace;--r:14px;--r2:20px;}
*{margin:0;padding:0;box-sizing:border-box;}html{scroll-behavior:smooth;}
body{font-family:var(--font);background:var(--bg);color:var(--text);min-height:100vh;overflow-x:hidden;}
body::before{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;background:radial-gradient(ellipse 80% 50% at 10% 10%,rgba(59,130,246,.06) 0%,transparent 60%),radial-gradient(ellipse 60% 60% at 90% 90%,rgba(139,92,246,.05) 0%,transparent 60%);}
body::after{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;background-image:linear-gradient(rgba(59,130,246,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(59,130,246,.03) 1px,transparent 1px);background-size:80px 80px;}
.z1{position:relative;z-index:1;}
.sidebar{position:fixed;left:0;top:0;bottom:0;width:260px;background:rgba(8,15,26,.98);border-right:1px solid var(--border2);z-index:100;display:flex;flex-direction:column;transition:transform .3s cubic-bezier(.4,0,.2,1);backdrop-filter:blur(20px);}
.sb-logo{padding:22px 20px;border-bottom:1px solid var(--border2);display:flex;align-items:center;gap:12px;}
.sb-logo .ico{width:40px;height:40px;border-radius:12px;flex-shrink:0;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:20px;}
.sb-logo .lt{font-size:13px;font-weight:800;}.sb-logo .ls{font-size:9px;color:var(--text3);letter-spacing:2px;font-family:var(--mono);}
.sb-nav{flex:1;padding:14px 12px;overflow-y:auto;}
.sb-section{font-size:10px;font-weight:700;color:var(--text3);letter-spacing:2px;padding:0 10px;margin:16px 0 6px;text-transform:uppercase;}
.ni{display:flex;align-items:center;gap:11px;padding:10px 12px;border-radius:11px;cursor:pointer;transition:all .2s;font-size:13px;color:var(--text2);margin-bottom:2px;font-weight:500;border:1px solid transparent;text-decoration:none;}
.ni:hover{background:rgba(255,255,255,.05);color:var(--text);}
.ni.active{background:rgba(59,130,246,.12);color:var(--accent);border-color:rgba(59,130,246,.2);}
.ni .ic{font-size:16px;width:20px;text-align:center;flex-shrink:0;}.ni .nl{flex:1;}
.sb-foot{padding:14px;border-top:1px solid var(--border2);}
.user-row{display:flex;align-items:center;gap:10px;}
.user-av{width:38px;height:38px;border-radius:50%;object-fit:cover;flex-shrink:0;border:2px solid var(--border2);}
.user-av-ph{width:38px;height:38px;border-radius:50%;flex-shrink:0;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;color:#fff;}
.user-info{flex:1;overflow:hidden;}
.user-name{font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.user-role{font-size:11px;color:var(--text3);}
.logout-btn{background:none;border:none;cursor:pointer;color:var(--text3);font-size:18px;padding:6px;transition:color .2s;border-radius:7px;}
.logout-btn:hover{color:var(--danger);background:rgba(239,68,68,.1);}
.topbar{position:fixed;top:0;left:260px;right:0;height:62px;background:rgba(4,8,15,.95);border-bottom:1px solid var(--border2);backdrop-filter:blur(20px);z-index:99;display:flex;align-items:center;justify-content:space-between;padding:0 24px;}
.tb-l{display:flex;align-items:center;gap:14px;}.hbg{display:none;background:none;border:none;color:var(--text);font-size:22px;cursor:pointer;padding:4px;}
.pg-t{font-size:17px;font-weight:700;}.tb-r{display:flex;align-items:center;gap:10px;}
.clock{font-family:var(--mono);font-size:12px;color:var(--accent);background:rgba(59,130,246,.08);padding:6px 12px;border-radius:8px;border:1px solid rgba(59,130,246,.15);}
.content{margin-left:260px;margin-top:62px;padding:28px;min-height:calc(100vh - 62px);}
.card{background:var(--card);border:1px solid var(--border2);border-radius:var(--r2);padding:24px;backdrop-filter:blur(10px);}
.card-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.card-title{font-size:15px;font-weight:700;}
.btn{padding:13px 20px;border:none;border-radius:11px;font-family:var(--font);font-size:14px;font-weight:700;cursor:pointer;transition:all .25s;display:inline-flex;align-items:center;justify-content:center;gap:7px;text-decoration:none;}
.btn-full{width:100%;}
.btn-p{background:linear-gradient(135deg,var(--accent),#2563eb);color:#fff;}
.btn-p:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(59,130,246,.5);}
.btn-s{background:rgba(255,255,255,.06);border:1px solid var(--border2);color:var(--text);}
.btn-s:hover{background:rgba(255,255,255,.1);}
.btn-r{background:linear-gradient(135deg,var(--danger),#dc2626);color:#fff;}
.btn-r:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(239,68,68,.4);}
.btn-g{background:linear-gradient(135deg,var(--accent3),#059669);color:#fff;}
.btn-g:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(16,185,129,.4);}
.btn-sm{padding:8px 14px;font-size:12px;border-radius:9px;}
.btn-xs{padding:6px 11px;font-size:11px;border-radius:7px;}
.sec-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;gap:12px;flex-wrap:wrap;}
.sec-title{font-size:20px;font-weight:800;}
.tag{font-size:11px;padding:3px 9px;border-radius:20px;font-weight:600;display:inline-block;}
.tag-b{background:rgba(59,130,246,.12);color:#60a5fa;border:1px solid rgba(59,130,246,.2);}
.tag-p{background:rgba(139,92,246,.12);color:#a78bfa;border:1px solid rgba(139,92,246,.2);}
.tag-g{background:rgba(16,185,129,.12);color:#34d399;border:1px solid rgba(16,185,129,.2);}
.tag-y{background:rgba(245,158,11,.12);color:#fbbf24;border:1px solid rgba(245,158,11,.2);}
.tag-r{background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.2);}
.mo{position:fixed;inset:0;background:rgba(0,0,0,.85);z-index:200;display:none;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(8px);}
.mo.open{display:flex;}
.md{background:var(--bg2);border:1px solid var(--border2);border-radius:24px;padding:32px;width:100%;max-width:520px;position:relative;animation:fadeUp .3s ease;max-height:90vh;overflow-y:auto;}
.md-title{font-size:18px;font-weight:800;margin-bottom:22px;}
.md-close{position:absolute;top:16px;right:16px;background:rgba(255,255,255,.06);border:1px solid var(--border2);border-radius:8px;color:var(--text2);font-size:16px;cursor:pointer;padding:6px 10px;transition:all .2s;}
.md-close:hover{color:var(--danger);}
.fg{margin-bottom:14px;}
.fg label{display:block;font-size:11px;font-weight:600;color:var(--text3);margin-bottom:6px;letter-spacing:.8px;text-transform:uppercase;}
.fg input,.fg select,.fg textarea{width:100%;padding:12px 14px;background:rgba(255,255,255,.04);border:1px solid var(--border2);border-radius:10px;color:var(--text);font-family:var(--font);font-size:14px;transition:all .25s;outline:none;}
.fg input:focus,.fg select:focus,.fg textarea:focus{border-color:var(--accent);background:rgba(59,130,246,.06);box-shadow:0 0 0 3px rgba(59,130,246,.1);}
.fg select option{background:var(--bg2);}.fg textarea{resize:vertical;min-height:80px;}
.toast{position:fixed;bottom:24px;right:24px;z-index:999;background:var(--bg2);border:1px solid var(--border2);border-radius:14px;padding:14px 18px;font-size:13px;min-width:250px;box-shadow:0 20px 60px rgba(0,0,0,.5);display:none;animation:fadeUp .3s ease;}
.toast.show{display:flex;align-items:center;gap:10px;}
.toast.tok{border-color:rgba(16,185,129,.4);}.toast.terr{border-color:rgba(239,68,68,.4);}
.sb-ovl{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:98;}
.sb-ovl.open{display:block;}
.foot{text-align:center;padding:22px;color:var(--text3);font-size:11px;font-family:var(--mono);border-top:1px solid var(--border2);margin-top:40px;}
.foot span{color:var(--accent);}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@media(max-width:768px){.sidebar{transform:translateX(-100%);}.sidebar.open{transform:translateX(0);box-shadow:0 0 60px rgba(0,0,0,.8);}.topbar{left:0;}.content{margin-left:0;}.hbg{display:block;}}
@yield('extra_styles')
</style>
</head>
<body>
<div class="z1">
  <div class="sb-ovl" id="sbOvl" onclick="closeSB()"></div>

  <nav class="sidebar" id="sb">
    <div class="sb-logo">
      <div class="ico">🎓</div>
      <div><div class="lt">ACADÉMIE NUMÉRIQUE</div><div class="ls">PLATEFORME EN LIGNE</div></div>
    </div>
    <div class="sb-nav" id="sbNav"></div>
    <div class="sb-foot">
      <div class="user-row">
        <div id="sbAvWrap"></div>
        <div class="user-info">
          <div class="user-name" id="sbName">—</div>
          <div class="user-role" id="sbRole">—</div>
        </div>
        <button class="logout-btn" onclick="doLogout()" title="Déconnexion">🚪</button>
      </div>
    </div>
  </nav>

  <header class="topbar">
    <div class="tb-l">
      <button class="hbg" onclick="toggleSB()">☰</button>
      <span class="pg-t">@yield('page_title','Tableau de Bord')</span>
    </div>
    <div class="tb-r"><div class="clock" id="clk">00:00:00</div></div>
  </header>

  <main class="content">@yield('content')</main>
</div>

<div class="toast" id="toast"><span id="toastIco"></span><span id="toastMsg"></span></div>

<script>
const API_BASE = '/api';
let _token = localStorage.getItem('an_token');

async function api(url, method='GET', body=null) {
  const opts = { method, headers: { 'Content-Type':'application/json', 'X-Token':_token||'', 'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||'' } };
  if (body && method!=='GET') opts.body = JSON.stringify(body);
  const r = await fetch(API_BASE+url, opts);
  const json = await r.json();
  if (!r.ok) throw new Error(json.error||json.message||'Erreur');
  return json;
}

function toast(msg, isErr=false) {
  const t=document.getElementById('toast');
  document.getElementById('toastIco').textContent=isErr?'❌':'✅';
  document.getElementById('toastMsg').textContent=msg;
  t.className='toast show '+(isErr?'terr':'tok');
  clearTimeout(window._tt); window._tt=setTimeout(()=>t.className='toast',3800);
}

function openM(id){document.getElementById(id).classList.add('open');}
function closeM(id){document.getElementById(id).classList.remove('open');}
function toggleSB(){document.getElementById('sb').classList.toggle('open');document.getElementById('sbOvl').classList.toggle('open');}
function closeSB(){document.getElementById('sb').classList.remove('open');document.getElementById('sbOvl').classList.remove('open');}
function formatSize(b){if(b<1024)return b+' o';if(b<1048576)return(b/1024).toFixed(1)+' Ko';return(b/1048576).toFixed(1)+' Mo';}

setInterval(()=>{document.getElementById('clk').textContent=new Date().toLocaleTimeString('fr-FR');},1000);
document.getElementById('clk').textContent=new Date().toLocaleTimeString('fr-FR');
document.querySelectorAll('.mo').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open');}));

function buildSidebar(user) {
  const role=user.role, isAdmin=role==='admin', isStaff=role==='admin'||role==='enseignant';
  const p=window.location.pathname;
  const a=(path)=>p===path?'active':'';
  let html=`
    <div class="sb-section">Navigation</div>
    <a class="ni ${a('/dashboard')}" href="/dashboard"><span class="ic">📊</span><span class="nl">Tableau de Bord</span></a>
    <a class="ni ${a('/cours')}" href="/cours"><span class="ic">📚</span><span class="nl">Cours</span></a>
    <a class="ni ${a('/salles')}" href="/salles"><span class="ic">🚪</span><span class="nl">Salles de Classe</span></a>
    <a class="ni ${a('/profil')}" href="/profil"><span class="ic">👤</span><span class="nl">Mon Profil</span></a>`;
  if (isStaff) html+=`
    <div class="sb-section">Enseignement</div>
    <a class="ni ${a('/admin/cours')}" href="/admin/cours"><span class="ic">➕</span><span class="nl">Gérer les Cours</span></a>
    <a class="ni ${a('/admin/salles')}" href="/admin/salles"><span class="ic">🏫</span><span class="nl">Gérer les Salles</span></a>`;
  if (isAdmin) html+=`
    <div class="sb-section">Administration</div>
    <a class="ni ${a('/admin/users')}" href="/admin/users"><span class="ic">⚙️</span><span class="nl">Gérer les Élèves</span></a>
    <a class="ni ${a('/admin/paiements')}" href="/admin/paiements"><span class="ic">💰</span><span class="nl">Paiements</span></a>`;
  document.getElementById('sbNav').innerHTML=html;
  const wrap=document.getElementById('sbAvWrap');
  wrap.innerHTML=user.photo?`<img class="user-av" src="${user.photo}" alt="">`:
    `<div class="user-av-ph">${user.name.charAt(0).toUpperCase()}</div>`;
  document.getElementById('sbName').textContent=user.name;
  document.getElementById('sbRole').textContent=isAdmin?'👑 Administrateur':role==='enseignant'?'👨‍🏫 Enseignant':'🎓 Élève';
}

async function doLogout() {
  try{await api('/auth/logout','POST');}catch(e){}
  localStorage.removeItem('an_token');
  window.location.href='/';
}

window.addEventListener('load', async()=>{
  const token=localStorage.getItem('an_token');
  if (!token){window.location.href='/';return;}
  try {
    const r=await api('/auth/me');
    const user=r.user;
    const path=window.location.pathname;
    const isAdmin=user.role==='admin', isStaff=user.role==='admin'||user.role==='enseignant';
    if (['/admin/users','/admin/paiements'].includes(path)&&!isAdmin){window.location.href='/dashboard';return;}
    if (['/admin/cours','/admin/salles'].includes(path)&&!isStaff){window.location.href='/dashboard';return;}
    buildSidebar(user);
    setInterval(()=>api('/auth/ping','POST').catch(()=>{}),30000);
  } catch(e){
    localStorage.removeItem('an_token');
    window.location.href='/';
  }
});
</script>
@yield('scripts')
</body>
</html>
