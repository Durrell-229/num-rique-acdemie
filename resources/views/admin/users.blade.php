@extends('layouts.app')
@section('title','Gérer les Élèves')
@section('page_title','⚙️ Gérer les Élèves')
@section('nav_admin_users','active')

@section('extra_styles')
<style>
.search{background:rgba(255,255,255,.05);border:1px solid var(--border2);border-radius:10px;
  padding:10px 14px;color:var(--text);font-family:var(--font);font-size:13px;
  width:280px;outline:none;transition:all .3s;}
.search:focus{border-color:var(--accent);}
.admin-row{display:flex;align-items:center;justify-content:space-between;padding:14px;
  background:rgba(255,255,255,.03);border:1px solid var(--border2);border-radius:var(--r);margin-bottom:10px;}
.admin-row-info{flex:1;}
.admin-row-name{font-size:14px;font-weight:600;}
.admin-row-sub{font-size:12px;color:var(--text2);margin-top:2px;}
.admin-row-actions{display:flex;gap:8px;}
.av-mini{width:42px;height:42px;border-radius:50%;overflow:hidden;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:16px;}
</style>
@endsection

@section('content')
<div class="sec-hdr">
  <h2 class="sec-title">⚙️ Gérer les Élèves</h2>
  <input class="search" type="text" placeholder="🔍 Rechercher par nom, email..." oninput="filterUsers(this.value)">
</div>

<!-- Compteurs rapides -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
  <div class="card" style="text-align:center;padding:16px;">
    <div style="font-size:28px;font-weight:800;color:var(--accent);" id="cntTotal">—</div>
    <div style="font-size:11px;color:var(--text3);margin-top:3px;">Total utilisateurs</div>
  </div>
  <div class="card" style="text-align:center;padding:16px;">
    <div style="font-size:28px;font-weight:800;color:var(--accent3);" id="cntEleves">—</div>
    <div style="font-size:11px;color:var(--text3);margin-top:3px;">Élèves inscrits</div>
  </div>
  <div class="card" style="text-align:center;padding:16px;">
    <div style="font-size:28px;font-weight:800;color:var(--gold);" id="cntPays">—</div>
    <div style="font-size:11px;color:var(--text3);margin-top:3px;">Pays représentés</div>
  </div>
</div>

<div id="usersGrid"></div>
@endsection

@section('scripts')
<script>
const AV_COLORS=['#1e3a8a','#4c1d95','#065f46','#78350f','#7f1d1d','#1e3a5f'];
let allUsers=[], currentUserId=null;

async function loadUsers() {
  try {
    const [r, stats] = await Promise.all([api('/users'), api('/users/stats')]);
    allUsers = r.users||[];
    currentUserId = null;
    // Récupérer l'id de l'utilisateur courant
    try { const me=await api('/auth/me'); currentUserId=me.user?.id; } catch(e){}
    document.getElementById('cntTotal').textContent  = stats.total_users||0;
    document.getElementById('cntEleves').textContent = stats.eleves||0;
    document.getElementById('cntPays').textContent   = stats.pays||0;
    renderUsers(allUsers);
  } catch(e) {}
}

function renderUsers(list) {
  const g=document.getElementById('usersGrid');
  if (!list.length) { g.innerHTML='<p style="color:var(--text3);">Aucun utilisateur trouvé.</p>'; return; }
  g.innerHTML = list.map((u,i)=>`
    <div class="admin-row">
      <div style="display:flex;align-items:center;gap:12px;flex:1;">
        <div class="av-mini" style="background:${u.photo?'transparent':AV_COLORS[i%AV_COLORS.length]};">
          ${u.photo?`<img src="${u.photo}" style="width:100%;height:100%;object-fit:cover;">`:u.name.charAt(0).toUpperCase()}
        </div>
        <div class="admin-row-info">
          <div class="admin-row-name">${u.name}
            <span class="tag ${u.role==='admin'?'tag-y':u.role==='enseignant'?'tag-p':'tag-b'}" style="margin-left:6px;">${u.role}</span>
          </div>
          <div class="admin-row-sub">📧 ${u.email} · 🌍 ${u.pays||'—'} ${u.localite?'· 📍 '+u.localite:''} · 🎒 ${u.classe||'—'}</div>
          <div class="admin-row-sub">
            <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:${u.online?'#10b981':'#475569'};margin-right:4px;"></span>
            ${u.online?'En ligne':'Hors ligne'}
          </div>
        </div>
      </div>
      <div class="admin-row-actions">
        ${String(u.id)!==String(currentUserId)?`<button class="btn btn-r btn-xs" onclick="deleteUser(${u.id})">🗑️</button>`:'<span style="font-size:11px;color:var(--text3);">Vous</span>'}
      </div>
    </div>`).join('');
}

function filterUsers(q) {
  renderUsers(allUsers.filter(u=>(u.name+u.email+(u.pays||'')+(u.localite||'')+(u.classe||'')).toLowerCase().includes(q.toLowerCase())));
}

async function deleteUser(id) {
  if (String(id)===String(currentUserId)) return toast('Impossible de supprimer votre propre compte',true);
  if (!confirm('Supprimer cet utilisateur définitivement ?')) return;
  try {
    await api(`/users/${id}`,'DELETE');
    toast('Utilisateur supprimé');
    loadUsers();
  } catch(e) { toast(e.message,true); }
}

loadUsers();
</script>
@endsection
