<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ACADÉMIE NUMÉRIQUE — Connexion</title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
:root{--bg:#04080f;--bg2:#080f1a;--accent:#3b82f6;--accent2:#8b5cf6;--gold:#f59e0b;--danger:#ef4444;--text:#f1f5f9;--text2:#94a3b8;--text3:#475569;--border2:rgba(255,255,255,0.06);--font:'Outfit',sans-serif;--mono:'JetBrains Mono',monospace;}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:var(--font);background:var(--bg);color:var(--text);min-height:100vh;}
body::before{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;background:radial-gradient(ellipse 80% 50% at 10% 10%,rgba(59,130,246,.06) 0%,transparent 60%),radial-gradient(ellipse 60% 60% at 90% 90%,rgba(139,92,246,.05) 0%,transparent 60%);}
body::after{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;background-image:linear-gradient(rgba(59,130,246,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(59,130,246,.03) 1px,transparent 1px);background-size:80px 80px;}
.z1{position:relative;z-index:1;}
.auth-screen{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
.auth-card{width:100%;max-width:480px;background:rgba(8,15,26,.95);border:1px solid var(--border2);border-radius:24px;padding:44px 40px;backdrop-filter:blur(30px);box-shadow:0 0 0 1px rgba(255,255,255,.04),0 40px 80px rgba(0,0,0,.7),0 0 30px rgba(59,130,246,.2);animation:fadeUp .5s ease;}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
.brand{text-align:center;margin-bottom:36px;}
.brand-icon{width:72px;height:72px;border-radius:20px;margin:0 auto 16px;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:34px;box-shadow:0 8px 32px rgba(59,130,246,.4);}
.brand-name{font-size:22px;font-weight:800;}.brand-sub{font-size:11px;color:var(--text3);letter-spacing:3px;margin-top:4px;font-family:var(--mono);}
.auth-tabs{display:flex;gap:4px;background:rgba(255,255,255,.04);border:1px solid var(--border2);border-radius:12px;padding:4px;margin-bottom:24px;}
.auth-tab{flex:1;padding:9px;border-radius:9px;cursor:pointer;font-size:13px;font-weight:600;color:var(--text2);transition:all .2s;border:none;background:none;font-family:var(--font);}
.auth-tab.active{background:var(--accent);color:#fff;box-shadow:0 4px 12px rgba(59,130,246,.4);}
.fg{margin-bottom:14px;}
.fg label{display:block;font-size:11px;font-weight:600;color:var(--text3);margin-bottom:6px;letter-spacing:.8px;text-transform:uppercase;}
.fg input,.fg select{width:100%;padding:12px 14px;background:rgba(255,255,255,.04);border:1px solid var(--border2);border-radius:10px;color:var(--text);font-family:var(--font);font-size:14px;transition:all .25s;outline:none;}
.fg input:focus,.fg select:focus{border-color:var(--accent);background:rgba(59,130,246,.06);box-shadow:0 0 0 3px rgba(59,130,246,.1);}
.fg select option{background:var(--bg2);}
.btn{padding:13px 20px;border:none;border-radius:11px;font-family:var(--font);font-size:14px;font-weight:700;cursor:pointer;transition:all .25s;width:100%;display:flex;align-items:center;justify-content:center;gap:7px;}
.btn-p{background:linear-gradient(135deg,var(--accent),#2563eb);color:#fff;}
.btn-p:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(59,130,246,.5);}
.btn-admin{background:linear-gradient(135deg,var(--gold),#d97706);color:#000;font-weight:800;}
.btn-admin:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(245,158,11,.5);}
.btn:disabled{opacity:.6;cursor:not-allowed;transform:none!important;}
.err-msg{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-bottom:14px;display:none;}
.ok-msg{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);border-radius:10px;padding:10px 14px;font-size:13px;color:#6ee7b7;margin-bottom:14px;display:none;}
.sw{text-align:center;margin-top:16px;font-size:13px;color:var(--text2);}
.sw a{color:var(--accent);cursor:pointer;font-weight:600;}
.admin-badge{background:linear-gradient(135deg,rgba(245,158,11,.15),rgba(239,68,68,.15));border:1px solid rgba(245,158,11,.3);border-radius:10px;padding:10px 14px;margin-bottom:20px;font-size:12px;color:#fcd34d;display:flex;align-items:center;gap:8px;}
</style>
</head>
<body>
<div class="z1">
<div class="auth-screen">
  <div class="auth-card">
    <div class="brand">
      <div class="brand-icon">🎓</div>
      <div class="brand-name">ACADÉMIE NUMÉRIQUE</div>
      <div class="brand-sub">PLATEFORME D'APPRENTISSAGE EN LIGNE</div>
    </div>
    <div class="auth-tabs">
      <button class="auth-tab active" onclick="switchTab('login',this)">Connexion</button>
      <button class="auth-tab" onclick="switchTab('eleve',this)">Inscription Élève</button>
      <button class="auth-tab" onclick="switchTab('staff',this)">Espace Staff</button>
    </div>

    <!-- LOGIN -->
    <div id="tab-login">
      <div class="err-msg" id="loginErr"></div>
      <div class="fg"><label>Email</label><input type="email" id="lEmail" placeholder="votre@email.com"></div>
      <div class="fg"><label>Mot de passe</label><input type="password" id="lPass" placeholder="••••••••" onkeydown="if(event.key==='Enter')doLogin()"></div>
      <button class="btn btn-p" id="btnLogin" onclick="doLogin()">Se connecter →</button>
      <div class="sw">Pas encore inscrit ? <a onclick="switchTab('eleve',document.querySelectorAll('.auth-tab')[1])">Créer un compte élève</a></div>
    </div>

    <!-- INSCRIPTION ÉLÈVE -->
    <div id="tab-eleve" style="display:none;">
      <div class="err-msg" id="eleveErr"></div>
      <div class="ok-msg" id="eleveOk"></div>
      <div class="fg"><label>Nom complet *</label><input type="text" id="eName" placeholder="Ex: Aminata Diallo"></div>
      <div class="fg"><label>Email *</label><input type="email" id="eEmail" placeholder="votre@email.com"></div>
      <div class="fg"><label>Mot de passe *</label><input type="password" id="ePass" placeholder="Minimum 6 caractères"></div>
      <div class="fg"><label>Pays *</label>
        <select id="eCountry" onchange="fillLocalites(this.value,'eLocalite')">
          <option value="">— Sélectionner un pays —</option>
          <option>Bénin</option><option>Burkina Faso</option><option>Cameroun</option>
          <option>Côte d'Ivoire</option><option>France</option><option>Gabon</option>
          <option>Guinée</option><option>Madagascar</option><option>Mali</option>
          <option>Maroc</option><option>Niger</option>
          <option>République Démocratique du Congo</option>
          <option>Sénégal</option><option>Tchad</option><option>Togo</option>
          <option>Tunisie</option><option>Autre</option>
        </select>
      </div>
      <div class="fg"><label>Localité *</label>
        <select id="eLocalite"><option value="">— Sélectionnez d'abord un pays —</option></select>
      </div>
      <div class="fg"><label>Classe *</label>
        <select id="eClasse">
          <option value="">— Sélectionner une classe —</option>
          <optgroup label="🎒 Primaire"><option>CP1</option><option>CP2</option><option>CE1</option><option>CE2</option><option>CM1</option><option>CM2</option></optgroup>
          <optgroup label="📚 Collège"><option>6ème</option><option>5ème</option><option>4ème</option><option>3ème</option></optgroup>
          <optgroup label="🏫 Lycée"><option>Seconde</option><option>Première A</option><option>Première C</option><option>Première D</option><option>Terminale A</option><option>Terminale B</option><option>Terminale C</option><option>Terminale D</option></optgroup>
          <optgroup label="🎓 Supérieur"><option>Licence 1</option><option>Licence 2</option><option>Licence 3</option><option>Master 1</option><option>Master 2</option><option>Doctorat</option><option>Formation Professionnelle</option></optgroup>
        </select>
      </div>
      <button class="btn btn-p" id="btnEleve" onclick="doRegister()">Créer mon compte élève →</button>
    </div>

    <!-- ESPACE STAFF -->
    <div id="tab-staff" style="display:none;">
      <div class="admin-badge">⚠️ Espace réservé au personnel de l'établissement</div>
      <div class="err-msg" id="staffErr"></div>
      <div class="ok-msg" id="staffOk"></div>
      <div class="fg"><label>Rôle</label>
        <select id="sRole" onchange="updateCodeHint()">
          <option value="admin">👑 Administrateur</option>
          <option value="enseignant">👨‍🏫 Enseignant</option>
        </select>
      </div>
      <div class="fg"><label>Nom complet *</label><input type="text" id="sName" placeholder="Ex: Prof. Diallo Jean"></div>
      <div class="fg"><label>Email *</label><input type="email" id="sEmail" placeholder="staff@academie.com"></div>
      <div class="fg"><label>Mot de passe *</label><input type="password" id="sPass" placeholder="Minimum 6 caractères"></div>
      <div class="fg">
        <label>Code d'accès *</label>
        <input type="password" id="sCode" placeholder="Code fourni par l'établissement">
        <small id="codeHint" style="font-size:11px;color:var(--text3);margin-top:4px;display:block;">Code administrateur requis</small>
      </div>
      <button class="btn btn-admin" id="btnStaff" onclick="doStaffRegister()">Créer le compte →</button>
    </div>
  </div>
</div>
</div>

<script>
/* Localités par pays — dans le script, jamais affichées en texte */
const LOCALITES={"Bénin":["Cotonou","Porto-Novo","Parakou","Djougou","Bohicon","Kandi","Lokossa","Ouidah","Abomey","Natitingou","Savalou","Savè","Nikki","Malanville","Pobè","Aplahoué","Comè","Dogbo","Zagnanado","Bembèrèkè","Tchaourou","Banikoara","Gogounou","Karimama","Ségbana","Kouandé","Péhunco","Boukoumbé","Tanguiéta","Matéri","Cobly","Toucountouna","Kérou","Ouaké","Bassila","Copargo","Dassa-Zoumè","Glazoué","Ouèssè","Bantè","Zogbodomey","Covè","Agbangnizoun","Abomey-Calavi","Sèmè-Kpodji","Aguégués","Adjarra","Adjohoun","Dangbo","Akpro-Missérété","Bonou","Avrankou","Ifangni","Kétou","Adja-Ouèrè","Grand-Popo","Houéyogbé","Athiémé","Bopa","Toviklin","Djakotomey","Klouékanmè","Lalo","Agoué","Godomey","Sô-Ava","Zê"],"Burkina Faso":["Ouagadougou","Bobo-Dioulasso","Koudougou","Banfora","Ouahigouya","Pouytenga","Kaya","Tenkodogo","Fada N'Gourma","Dédougou","Manga","Réo","Gaoua","Diébougou","Dori","Djibo","Titao","Kongoussi","Ziniaré","Zorgho","Boulsa","Toma","Nouna","Tougan","Solenzo","Boromo","Léo","Diapaga","Gayéri","Pama","Bogandé","Kombissiri","Sapouy","Silly","Koupèla","Garango","Bittou","Zabré","Thiou","Séguénéga","Gourcy"],"Cameroun":["Yaoundé","Douala","Bafoussam","Garoua","Bamenda","Maroua","Ngaoundéré","Bertoua","Kumba","Nkongsamba","Buea","Limbé","Edéa","Kribi","Ebolowa","Sangmélima","Mbalmayo","Dschang","Bafang","Mbouda","Foumban","Tibati","Meiganga","Guider","Mokolo","Mora","Kousséri","Yagoua","Kaéle","Garoua-Boulaï","Abong-Mbang","Batouri","Yokadouma","Lomié","Nanga-Eboko","Bafia","Obala"],"Côte d'Ivoire":["Abidjan","Bouaké","Daloa","San-Pédro","Yamoussoukro","Korhogo","Man","Divo","Gagnoa","Abobo","Anyama","Agboville","Abengourou","Bondoukou","Soubré","Séguéla","Odienné","Touba","Mankono","Katiola","Ferkessédougou","Boundiali","Bouna","Tanda","Aboisso","Grand-Bassam","Bingerville","Sassandra","Tabou","Guiglo","Duékoué","Danané","Zuenoula","Sinfra","Issia","Lakota","Fresco"],"France":["Paris","Lyon","Marseille","Toulouse","Nice","Nantes","Montpellier","Strasbourg","Bordeaux","Lille","Rennes","Reims","Saint-Étienne","Toulon","Le Havre","Grenoble","Dijon","Angers","Nîmes","Aix-en-Provence","Brest","Limoges","Tours","Clermont-Ferrand","Amiens","Perpignan","Metz","Besançon","Caen","Orléans","Rouen","Mulhouse","Nancy","Avignon","Poitiers","Cannes","La Rochelle","Pau","Versailles","Saint-Denis"],"Gabon":["Libreville","Port-Gentil","Franceville","Oyem","Moanda","Mouila","Lambaréné","Tchibanga","Koulamoutou","Makokou","Bitam","Booué","Lastoursville","Ndendé","Mitzic","Minvoul","Gamba","Mayumba","Fougamou"],"Guinée":["Conakry","Nzérékoré","Kindia","Kankan","Labé","Guéckédou","Mamou","Macenta","Faranah","Siguiri","Coyah","Dubréka","Boké","Kamsar","Télimélé","Pita","Dalaba","Dinguiraye","Kouroussa","Kissidougou","Beyla","Lola","Yomou"],"Madagascar":["Antananarivo","Toamasina","Antsirabe","Fianarantsoa","Mahajanga","Toliara","Antsiranana","Ambovombe","Antalaha","Mananjary","Morondava","Ambatondrazaka","Tsiroanomandidy","Maevatanana","Moramanga","Nosy Be"],"Mali":["Bamako","Sikasso","Mopti","Koutiala","Kayes","Ségou","Gao","Tombouctou","Bougouni","Kati","San","Djenné","Bandiagara","Douentza","Ansongo","Ménaka","Tessalit","Kolondiéba","Yanfolila","Dioïla"],"Maroc":["Casablanca","Rabat","Fès","Marrakech","Agadir","Tanger","Meknès","Oujda","Kénitra","Tétouan","Safi","Mohammedia","Khouribga","El Jadida","Béni Mellal","Nador","Settat","Larache","Guelmim","Dakhla","Laayoune","Essaouira","Er-Rachidia"],"Niger":["Niamey","Zinder","Maradi","Tahoua","Agadez","Dosso","Diffa","Tillabéri","Arlit","Birni N'Konni","Madaoua","Tessaoua","Nguigmi","Keita","Bouza","Illéla","Filingué","Téra","Gaya","Dogondoutchi"],"République Démocratique du Congo":["Kinshasa","Lubumbashi","Mbuji-Mayi","Kananga","Kisangani","Bukavu","Goma","Kolwezi","Likasi","Uvira","Bunia","Beni","Butembo","Matadi","Mbandaka","Bandundu","Kikwit","Tshikapa","Kabinda","Lodja","Kamina","Manono","Baraka","Fizi"],"Sénégal":["Dakar","Touba","Thiès","Rufisque","Kaolack","Ziguinchor","Saint-Louis","Diourbel","Tambacounda","Mbour","Louga","Kolda","Matam","Kaffrine","Sédhiou","Kédougou","Fatick","Tivaouane","Mbacké","Podor","Richard-Toll","Bakel","Vélingara","Bignona","Oussouye"],"Tchad":["N'Djamena","Moundou","Sarh","Abéché","Kélo","Bongor","Doba","Mongo","Ati","Faya-Largeau","Am Timan","Massaguet","Biltine","Adré","Iriba","Fada","Moussoro","Massakory","Mao","Bol","Pala","Fianga","Léré"],"Togo":["Lomé","Sokodé","Kara","Kpalimé","Atakpamé","Bassar","Tsévié","Aného","Mango","Dapaong","Niamtougou","Kanté","Bafilo","Sotouboua","Blitta","Anié","Badou","Notsé","Tabligbo","Vogan","Amlame","Kabou","Cinkassé"],"Tunisie":["Tunis","Sfax","Sousse","Kairouan","Bizerte","Gabès","Ariana","Gafsa","Monastir","Ben Arous","Kasserine","Médenine","Nabeul","Tataouine","Béja","Jendouba","Mahdia","Siliana","El Kef","Tozeur","Kébili","Zaghouan","Manouba"],"Autre":["Autre localité"]};

function fillLocalites(pays, selectId) {
  const sel = document.getElementById(selectId);
  const locs = LOCALITES[pays] || [];
  sel.innerHTML = '<option value="">— Sélectionner une localité —</option>';
  locs.forEach(l => { const o=document.createElement('option'); o.value=l; o.textContent=l; sel.appendChild(o); });
}

function updateCodeHint() {
  document.getElementById('codeHint').textContent =
    document.getElementById('sRole').value==='admin' ? 'Code administrateur requis' : 'Code enseignant requis';
}

function switchTab(tab, el) {
  ['login','eleve','staff'].forEach(t => document.getElementById('tab-'+t).style.display='none');
  document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
  document.getElementById('tab-'+tab).style.display='block';
  el.classList.add('active');
}

function showErr(id,msg){const e=document.getElementById(id);e.textContent=msg;e.style.display='block';}
function showOk(id,msg){const e=document.getElementById(id);e.textContent=msg;e.style.display='block';}
function hideMsg(id){document.getElementById(id).style.display='none';}

async function postAPI(url, data) {
  const r = await fetch(url, {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify(data)
  });
  const json = await r.json();
  if (!r.ok) throw new Error(json.error||json.message||'Erreur serveur');
  return json;
}

async function doLogin() {
  const email=document.getElementById('lEmail').value.trim();
  const pass=document.getElementById('lPass').value;
  hideMsg('loginErr');
  if (!email||!pass){showErr('loginErr','Remplissez tous les champs.');return;}
  const btn=document.getElementById('btnLogin');
  btn.disabled=true; btn.textContent='⏳ Connexion...';
  try {
    const r=await postAPI('/api/auth/login',{email,password:pass});
    localStorage.setItem('an_token',r.token);
    window.location.href='/dashboard';
  } catch(e){
    showErr('loginErr',e.message);
    btn.disabled=false; btn.textContent='Se connecter →';
  }
}

async function doRegister() {
  const name=document.getElementById('eName').value.trim();
  const email=document.getElementById('eEmail').value.trim();
  const pass=document.getElementById('ePass').value;
  const pays=document.getElementById('eCountry').value;
  const localite=document.getElementById('eLocalite').value;
  const classe=document.getElementById('eClasse').value;
  hideMsg('eleveErr'); hideMsg('eleveOk');
  if (!name||!email||!pass||!pays||!localite||!classe){showErr('eleveErr','Tous les champs (*) sont obligatoires.');return;}
  if (pass.length<6){showErr('eleveErr','Mot de passe : minimum 6 caractères.');return;}
  const btn=document.getElementById('btnEleve');
  btn.disabled=true; btn.textContent='⏳ Création...';
  try {
    const r=await postAPI('/api/auth/register',{name,email,password:pass,pays,localite,classe});
    localStorage.setItem('an_token',r.token);
    showOk('eleveOk','✅ Compte créé ! Connexion en cours...');
    setTimeout(()=>window.location.href='/dashboard',1200);
  } catch(e){
    showErr('eleveErr',e.message);
    btn.disabled=false; btn.textContent='Créer mon compte élève →';
  }
}

async function doStaffRegister() {
  const role=document.getElementById('sRole').value;
  const name=document.getElementById('sName').value.trim();
  const email=document.getElementById('sEmail').value.trim();
  const pass=document.getElementById('sPass').value;
  const code=document.getElementById('sCode').value;
  hideMsg('staffErr'); hideMsg('staffOk');
  if (!name||!email||!pass||!code){showErr('staffErr','Tous les champs sont obligatoires.');return;}
  if (pass.length<6){showErr('staffErr','Mot de passe : minimum 6 caractères.');return;}
  const btn=document.getElementById('btnStaff');
  btn.disabled=true; btn.textContent='⏳ Création...';
  try {
    const r=await postAPI('/api/auth/register-admin',{name,email,password:pass,role,admin_code:code});
    localStorage.setItem('an_token',r.token);
    showOk('staffOk',`✅ Compte ${role} créé ! Connexion en cours...`);
    setTimeout(()=>window.location.href='/dashboard',1200);
  } catch(e){
    showErr('staffErr',e.message);
    btn.disabled=false; btn.textContent='Créer le compte →';
  }
}

window.addEventListener('load',async()=>{
  const token=localStorage.getItem('an_token');
  if (!token) return;
  try {
    const r=await fetch('/api/auth/me',{headers:{'X-Token':token}});
    if (r.ok) window.location.href='/dashboard';
    else localStorage.removeItem('an_token');
  } catch(e){}
});
</script>
</body>
</html>
