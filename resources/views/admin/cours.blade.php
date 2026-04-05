@extends('layouts.app')
@section('title','Gérer les Cours')
@section('page_title','➕ Gérer les Cours')
@section('nav_admin_cours','active')

@section('extra_styles')
<style>
.cours-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:18px;}
.cours-card{background:var(--card);border:1px solid var(--border2);border-radius:var(--r2);overflow:hidden;}
.cours-banner{height:120px;display:flex;align-items:center;justify-content:center;font-size:48px;}
.cours-body{padding:18px;}
.cours-title{font-size:15px;font-weight:700;margin-bottom:5px;}
.cours-desc{font-size:12px;color:var(--text2);margin-bottom:14px;line-height:1.5;
  display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.upload-area{border:2px dashed rgba(59,130,246,.25);border-radius:var(--r);padding:28px;
  text-align:center;cursor:pointer;transition:all .3s;background:rgba(59,130,246,.03);}
.upload-area:hover,.upload-area.drag{border-color:var(--accent);background:rgba(59,130,246,.06);}
.upload-ico{font-size:36px;margin-bottom:8px;opacity:.5;}
</style>
@endsection

@section('content')
<div class="sec-hdr">
  <h2 class="sec-title">➕ Gérer les Cours</h2>
  <button class="btn btn-p btn-sm" onclick="openM('mAddCours')">+ Nouveau cours</button>
</div>

<div class="cours-grid" id="adminCoursGrid">
  <p style="color:var(--text3);grid-column:1/-1;">⏳ Chargement...</p>
</div>

<!-- Modal créer cours -->
<div class="mo" id="mAddCours">
  <div class="md">
    <button class="md-close" onclick="closeM('mAddCours')">✕</button>
    <h3 class="md-title">📚 Créer un Cours</h3>
    <div class="fg"><label>Titre *</label><input id="cTit" type="text" placeholder="Ex: Les fonctions — Terminale C"></div>
    <div class="fg"><label>Description</label><textarea id="cDesc" placeholder="Décrivez le contenu..."></textarea></div>
    <div class="fg"><label>Matière *</label>
      <select id="cMat">
        <option value="Mathématiques">📐 Mathématiques</option>
        <option value="Français">📖 Français</option>
        <option value="SVT">🌿 SVT</option>
        <option value="PCT">⚗️ PCT</option>
        <option value="Histoire-Géographie">🗺️ Histoire-Géographie</option>
        <option value="Philosophie">🧠 Philosophie</option>
        <option value="Anglais">🇬🇧 Anglais</option>
      </select>
    </div>
    <div class="fg"><label>Niveau *</label>
      <select id="cLvl">
        <option value="primaire">🎒 Primaire</option>
        <option value="college">📚 Collège</option>
        <option value="lycee">🏫 Lycée</option>
        <option value="superieur">🎓 Supérieur</option>
      </select>
    </div>
    <div class="fg"><label>Lien externe</label><input id="cUrl" type="url" placeholder="https://..."></div>
    <div class="fg">
       <div class="fg"><label>Lien externe</label><input id="cUrl" type="url" placeholder="https://..."></div>
    <div class="fg">
      <label>💰 Prix FCFA <span style="color:var(--text3);font-size:10px;">— 0 = gratuit</span></label>
      <input id="cPrix" type="number" min="0" step="100" value="1">
    </div>
    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:11px;font-weight:600;color:var(--text3);margin-bottom:6px;letter-spacing:.8px;text-transform:uppercase;">Fichiers & Documents</label>
      <div class="upload-area" onclick="document.getElementById('cFiles').click()" id="fileDropArea">
        <div class="upload-ico">📎</div>
        <div style="font-size:13px;color:var(--text2);">Cliquez ou glissez vos fichiers ici</div>
        <div style="font-size:11px;color:var(--text3);margin-top:4px;">PDF, Word, Images — Max 10 Mo</div>
        <input type="file" id="cFiles" multiple accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" style="display:none;" onchange="handleFileUpload(this,'files')">
      </div>
      <div id="filesList" style="margin-top:8px;"></div>
    </div>
    <div style="margin-bottom:20px;">
      <label style="display:block;font-size:11px;font-weight:600;color:var(--text3);margin-bottom:6px;letter-spacing:.8px;text-transform:uppercase;">Vidéos</label>
      <div class="upload-area" onclick="document.getElementById('cVids').click()">
        <div class="upload-ico">🎬</div>
        <div style="font-size:13px;color:var(--text2);">Cliquez pour ajouter des vidéos</div>
        <div style="font-size:11px;color:var(--text3);margin-top:4px;">MP4, MOV — Max 100 Mo</div>
        <input type="file" id="cVids" multiple accept="video/*" style="display:none;" onchange="handleFileUpload(this,'videos')">
      </div>
      <div id="vidsList" style="margin-top:8px;"></div>
    </div>
    <button class="btn btn-p btn-full" onclick="saveCours()">✅ Publier le cours</button>
  </div>
</div>
@endsection

@section('scripts')
<script>
const MATIERES_EMOJI={'Mathématiques':'📐','Français':'📖','SVT':'🌿','PCT':'⚗️','Histoire-Géographie':'🗺️','Philosophie':'🧠','Anglais':'🇬🇧'};
const MATIERES_BG={'Mathématiques':'#0d1f3c','Français':'#1a0d2a','SVT':'#0d2a1a','PCT':'#2a1a0d','Histoire-Géographie':'#2a0d1a','Philosophie':'#1a0d2a','Anglais':'#0d1a2a'};
const LEVEL_TAG={primaire:'tag-g',college:'tag-b',lycee:'tag-p',superieur:'tag-y'};
let pendingFilesData=[], pendingVidsData=[], allCours=[];

async function loadCours() {
  try {
    const r = await api('/cours');
    allCours = r.cours||[];
    renderAdminCours();
  } catch(e) {}
}

function renderAdminCours() {
  const g=document.getElementById('adminCoursGrid');
  if (!allCours.length) { g.innerHTML='<p style="color:var(--text3);grid-column:1/-1;">Aucun cours. Créez-en un !</p>'; return; }
  g.innerHTML = allCours.map(c=>`
    <div class="cours-card">
      <div class="cours-banner" style="background:${MATIERES_BG[c.matiere]||'#1e3a5f'}">${MATIERES_EMOJI[c.matiere]||'📚'}</div>
      <div class="cours-body">
        <div class="cours-title">${c.title}</div>
        <div class="cours-desc">${c.description||'Aucune description.'}</div>
        <div style="display:flex;gap:6px;margin-bottom:12px;flex-wrap:wrap;">
          <span class="tag ${LEVEL_TAG[c.niveau]||'tag-b'}">${c.niveau}</span>
          <span class="tag tag-b">${c.matiere}</span>
          ${parseInt(c.prix||0)===0?'<span class="tag tag-g">Gratuit</span>':`<span class="tag tag-y">💰 ${parseInt(c.prix).toLocaleString('fr-FR')} FCFA</span>`}
        </div>
        <div style="display:flex;gap:8px;">
          <button class="btn btn-r btn-xs" onclick="deleteCours(${c.id})">🗑️ Supprimer</button>
        </div>
        <div style="font-size:11px;color:var(--text3);margin-top:8px;">📎 ${(c.files||[]).length} · 🎬 ${(c.videos||[]).length}</div>
      </div>
    </div>`).join('');
}

function handleFileUpload(input, type) {
  const arr = type==='files'?pendingFilesData:pendingVidsData;
  const listId = type==='files'?'filesList':'vidsList';
  Array.from(input.files).forEach(file=>{
    const rd=new FileReader();
    rd.onload=e=>{
      arr.push({name:file.name,data:e.target.result,type:file.type,size:formatSize(file.size)});
      renderPending(listId,arr,type);
    };
    rd.readAsDataURL(file);
  });
}

function renderPending(listId,arr,type) {
  const el=document.getElementById(listId); if(!el) return;
  el.innerHTML=arr.map((f,i)=>`
    <div style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:rgba(255,255,255,.04);border:1px solid var(--border2);border-radius:9px;margin-bottom:6px;">
      <span>${type==='videos'?'🎬':(f.type?.startsWith('image')?'🖼️':'📄')}</span>
      <div style="flex:1;overflow:hidden;"><div style="font-size:12px;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${f.name}</div><div style="font-size:11px;color:var(--text3);">${f.size}</div></div>
      <button style="background:none;border:none;color:var(--text3);cursor:pointer;" onclick="${type==='files'?'rmFile':'rmVid'}(${i})">✕</button>
    </div>`).join('');
}
function rmFile(i){pendingFilesData.splice(i,1);renderPending('filesList',pendingFilesData,'files');}
function rmVid(i){pendingVidsData.splice(i,1);renderPending('vidsList',pendingVidsData,'videos');}

async function saveCours() {
  const title=document.getElementById('cTit').value.trim();
  const desc=document.getElementById('cDesc').value;
  const mat=document.getElementById('cMat').value;
  const lvl=document.getElementById('cLvl').value;
  const url=document.getElementById('cUrl').value;
  const prix=parseInt(document.getElementById('cPrix').value)||0;
  if (!title||!mat||!lvl) return toast('Titre, matière et niveau obligatoires',true);
  const btn=document.querySelector('#mAddCours .btn-full');
  btn.disabled=true; btn.textContent='⏳ Publication...';
  try {
    await api('/cours','POST',{title,description:desc,matiere:mat,niveau:lvl,url_externe:url,prix,files:pendingFilesData,videos:pendingVidsData});
    pendingFilesData=[]; pendingVidsData=[];
    document.getElementById('filesList').innerHTML='';
    document.getElementById('vidsList').innerHTML='';
    ['cTit','cDesc','cUrl'].forEach(id=>document.getElementById(id).value='');
    document.getElementById('cPrix').value='';
    closeM('mAddCours');
    toast('✅ Cours publié !');
    loadCours();
  } catch(e) { toast(e.message,true); }
  finally { btn.disabled=false; btn.textContent='✅ Publier le cours'; }
}

async function deleteCours(id) {
  if (!confirm('Supprimer ce cours définitivement ?')) return;
  try {
    await api(`/cours/${id}`,'DELETE');
    toast('Cours supprimé');
    loadCours();
  } catch(e) { toast(e.message,true); }
}

// Drag & drop
const fda=document.getElementById('fileDropArea');
if(fda){
  fda.addEventListener('dragover',e=>{e.preventDefault();fda.classList.add('drag');});
  fda.addEventListener('dragleave',()=>fda.classList.remove('drag'));
  fda.addEventListener('drop',e=>{e.preventDefault();fda.classList.remove('drag');handleFileUpload({files:e.dataTransfer.files},'files');});
}

loadCours();
</script>
@endsection
