@extends('layouts.app')
@section('title','Mon Profil')
@section('page_title','👤 Mon Profil')
@section('nav_profil','active')

@section('extra_styles')
<style>
.profil-grid{display:grid;grid-template-columns:300px 1fr;gap:24px;align-items:start;}
@media(max-width:768px){.profil-grid{grid-template-columns:1fr;}}
.av-big{width:100px;height:100px;border-radius:50%;margin:0 auto 16px;overflow:hidden;
  border:3px solid var(--border2);display:flex;align-items:center;justify-content:center;font-size:38px;background:var(--bg3);}
</style>
@endsection

@section('content')
<div class="sec-hdr"><h2 class="sec-title">👤 Mon Profil</h2></div>
<div class="profil-grid">
  <div class="card" style="text-align:center;">
    <div class="av-big" id="profilAvBig"></div>
    <div style="font-size:18px;font-weight:800;margin-bottom:4px;" id="profilName">—</div>
    <div style="font-size:13px;color:var(--text2);margin-bottom:4px;" id="profilClasse">—</div>
    <div style="font-size:13px;color:var(--text3);margin-bottom:2px;" id="profilCountry">—</div>
    <div style="font-size:13px;color:var(--accent);margin-bottom:16px;" id="profilLocalite">—</div>
    <label class="btn btn-p btn-sm" style="cursor:pointer;width:100%;justify-content:center;">
      📷 Changer la photo
      <input type="file" accept="image/*" style="display:none;" onchange="updatePhoto(this)">
    </label>
  </div>
  <div>
    <div class="card" style="margin-bottom:18px;">
      <div class="card-header"><span class="card-title">✏️ Modifier mes informations</span></div>
      <div class="fg"><label>Nom complet</label><input type="text" id="editName" placeholder="Votre nom"></div>
      <div class="fg"><label>Pays</label>
        <select id="editCountry" onchange="fillLocalitesProfil()">
          <option value="">— Pays —</option>
          <option>Bénin</option><option>Burkina Faso</option><option>Cameroun</option>
          <option>Côte d'Ivoire</option><option>France</option><option>Gabon</option>
          <option>Guinée</option><option>Madagascar</option><option>Mali</option>
          <option>Maroc</option><option>Niger</option>
          <option>République Démocratique du Congo</option>
          <option>Sénégal</option><option>Tchad</option><option>Togo</option>
          <option>Tunisie</option><option>Autre</option>
        </select>
      </div>
      <div class="fg"><label>Localité</label>
        <select id="editLocalite"><option value="">— Sélectionnez d'abord un pays —</option></select>
      </div>
      <div class="fg">
        <label>Nouveau mot de passe <span style="color:var(--text3);">(laisser vide pour ne pas changer)</span></label>
        <input type="password" id="editPass" placeholder="Nouveau mot de passe">
      </div>
      <button class="btn btn-p" onclick="saveProfile()">💾 Enregistrer</button>
    </div>
    <div class="card">
      <div class="card-title" style="margin-bottom:16px;">📊 Statistiques</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;" id="profilStats"></div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
/* Localités intégrées directement */
const LOCALITES={"Bénin":["Cotonou","Porto-Novo","Parakou","Djougou","Bohicon","Kandi","Lokossa","Ouidah","Abomey","Natitingou","Savalou","Savè","Nikki","Malanville","Pobè","Aplahoué","Comè","Dogbo","Zagnanado","Bembèrèkè","Tchaourou","Banikoara","Gogounou","Karimama","Ségbana","Kouandé","Péhunco","Boukoumbé","Tanguiéta","Matéri","Cobly","Toucountouna","Kérou","Ouaké","Bassila","Copargo","Dassa-Zoumè","Glazoué","Ouèssè","Bantè","Zogbodomey","Covè","Agbangnizoun","Abomey-Calavi","Sèmè-Kpodji","Aguégués","Adjarra","Adjohoun","Dangbo","Akpro-Missérété","Bonou","Avrankou","Ifangni","Kétou","Adja-Ouèrè","Grand-Popo","Houéyogbé","Athiémé","Bopa","Toviklin","Djakotomey","Klouékanmè","Lalo","Agoué","Godomey","Sô-Ava","Zê"],"Burkina Faso":["Ouagadougou","Bobo-Dioulasso","Koudougou","Banfora","Ouahigouya","Pouytenga","Kaya","Tenkodogo","Fada N'Gourma","Dédougou","Manga","Réo","Gaoua","Diébougou","Dori","Djibo","Titao","Kongoussi","Ziniaré","Zorgho","Boulsa","Toma","Nouna","Tougan","Solenzo","Boromo","Léo","Diapaga","Gayéri","Pama","Bogandé","Kombissiri","Sapouy","Silly","Koupèla","Garango","Bittou","Zabré","Thiou","Séguénéga","Gourcy"],"Cameroun":["Yaoundé","Douala","Bafoussam","Garoua","Bamenda","Maroua","Ngaoundéré","Bertoua","Kumba","Nkongsamba","Buea","Limbé","Edéa","Kribi","Ebolowa","Sangmélima","Mbalmayo","Dschang","Bafang","Mbouda","Foumban","Tibati","Meiganga","Guider","Mokolo","Mora","Kousséri","Yagoua","Kaéle","Garoua-Boulaï","Abong-Mbang","Batouri","Yokadouma","Lomié","Nanga-Eboko","Bafia","Obala"],"Côte d'Ivoire":["Abidjan","Bouaké","Daloa","San-Pédro","Yamoussoukro","Korhogo","Man","Divo","Gagnoa","Abobo","Anyama","Agboville","Abengourou","Bondoukou","Soubré","Séguéla","Odienné","Touba","Mankono","Katiola","Ferkessédougou","Boundiali","Bouna","Tanda","Aboisso","Grand-Bassam","Bingerville","Sassandra","Tabou","Guiglo","Duékoué","Danané","Zuenoula","Sinfra","Issia","Lakota","Fresco"],"France":["Paris","Lyon","Marseille","Toulouse","Nice","Nantes","Montpellier","Strasbourg","Bordeaux","Lille","Rennes","Reims","Saint-Étienne","Toulon","Le Havre","Grenoble","Dijon","Angers","Nîmes","Aix-en-Provence","Brest","Limoges","Tours","Clermont-Ferrand","Amiens","Perpignan","Metz","Besançon","Caen","Orléans","Rouen","Mulhouse","Nancy","Avignon","Poitiers","Cannes","La Rochelle","Pau","Versailles","Saint-Denis"],"Gabon":["Libreville","Port-Gentil","Franceville","Oyem","Moanda","Mouila","Lambaréné","Tchibanga","Koulamoutou","Makokou","Bitam","Booué","Lastoursville","Ndendé","Mitzic","Minvoul","Gamba","Mayumba","Fougamou"],"Guinée":["Conakry","Nzérékoré","Kindia","Kankan","Labé","Guéckédou","Mamou","Macenta","Faranah","Siguiri","Coyah","Dubréka","Boké","Kamsar","Télimélé","Pita","Dalaba","Dinguiraye","Kouroussa","Kissidougou","Beyla","Lola","Yomou"],"Madagascar":["Antananarivo","Toamasina","Antsirabe","Fianarantsoa","Mahajanga","Toliara","Antsiranana","Ambovombe","Antalaha","Mananjary","Morondava","Ambatondrazaka","Tsiroanomandidy","Maevatanana","Moramanga","Nosy Be"],"Mali":["Bamako","Sikasso","Mopti","Koutiala","Kayes","Ségou","Gao","Tombouctou","Bougouni","Kati","San","Djenné","Bandiagara","Douentza","Ansongo","Ménaka","Tessalit","Kolondiéba","Yanfolila","Dioïla"],"Maroc":["Casablanca","Rabat","Fès","Marrakech","Agadir","Tanger","Meknès","Oujda","Kénitra","Tétouan","Safi","Mohammedia","Khouribga","El Jadida","Béni Mellal","Nador","Settat","Larache","Guelmim","Dakhla","Laayoune","Essaouira","Er-Rachidia"],"Niger":["Niamey","Zinder","Maradi","Tahoua","Agadez","Dosso","Diffa","Tillabéri","Arlit","Birni N'Konni","Madaoua","Tessaoua","Nguigmi","Keita","Bouza","Illéla","Filingué","Téra","Gaya","Dogondoutchi"],"République Démocratique du Congo":["Kinshasa","Lubumbashi","Mbuji-Mayi","Kananga","Kisangani","Bukavu","Goma","Kolwezi","Likasi","Uvira","Bunia","Beni","Butembo","Matadi","Mbandaka","Bandundu","Kikwit","Tshikapa","Kabinda","Lodja","Kamina","Manono","Baraka","Fizi"],"Sénégal":["Dakar","Touba","Thiès","Rufisque","Kaolack","Ziguinchor","Saint-Louis","Diourbel","Tambacounda","Mbour","Louga","Kolda","Matam","Kaffrine","Sédhiou","Kédougou","Fatick","Tivaouane","Mbacké","Podor","Richard-Toll","Bakel","Vélingara","Bignona","Oussouye"],"Tchad":["N'Djamena","Moundou","Sarh","Abéché","Kélo","Bongor","Doba","Mongo","Ati","Faya-Largeau","Am Timan","Massaguet","Biltine","Adré","Iriba","Fada","Moussoro","Massakory","Mao","Bol","Pala","Fianga","Léré"],"Togo":["Lomé","Sokodé","Kara","Kpalimé","Atakpamé","Bassar","Tsévié","Aného","Mango","Dapaong","Niamtougou","Kanté","Bafilo","Sotouboua","Blitta","Anié","Badou","Notsé","Tabligbo","Vogan","Amlame","Kabou","Cinkassé"],"Tunisie":["Tunis","Sfax","Sousse","Kairouan","Bizerte","Gabès","Ariana","Gafsa","Monastir","Ben Arous","Kasserine","Médenine","Nabeul","Tataouine","Béja","Jendouba","Mahdia","Siliana","El Kef","Tozeur","Kébili","Zaghouan","Manouba"],"Autre":["Autre localité"]};

function fillLocalites(pays, selectId) {
  const sel=document.getElementById(selectId);
  const locs=LOCALITES[pays]||[];
  sel.innerHTML='<option value="">— Sélectionner une localité —</option>';
  locs.forEach(l=>{const o=document.createElement('option');o.value=l;o.textContent=l;sel.appendChild(o);});
}
function fillLocalitesProfil() { fillLocalites(document.getElementById('editCountry').value,'editLocalite'); }

let currentUser=null;

async function loadProfil() {
  try {
    const r=await api('/boot');
    currentUser=r.user;
    renderProfil();
    document.getElementById('profilStats').innerHTML=`
      <div style="background:rgba(59,130,246,.06);border:1px solid rgba(59,130,246,.15);border-radius:12px;padding:16px;text-align:center;">
        <div style="font-size:24px;font-weight:800;color:var(--accent);">${(r.cours||[]).length}</div>
        <div style="font-size:12px;color:var(--text3);margin-top:3px;">Cours disponibles</div>
      </div>
      <div style="background:rgba(139,92,246,.06);border:1px solid rgba(139,92,246,.15);border-radius:12px;padding:16px;text-align:center;">
        <div style="font-size:24px;font-weight:800;color:var(--accent2);">${(r.salles||[]).length}</div>
        <div style="font-size:12px;color:var(--text3);margin-top:3px;">Salles de classe</div>
      </div>`;
  } catch(e){}
}

function renderProfil() {
  const u=currentUser; if (!u) return;
  const av=document.getElementById('profilAvBig');
  if (u.photo){av.innerHTML=`<img src="${u.photo}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;}
  else{av.textContent=u.name.charAt(0).toUpperCase();av.style.background='linear-gradient(135deg,var(--accent),var(--accent2))';av.style.color='#fff';}
  document.getElementById('profilName').textContent=u.name;
  document.getElementById('profilClasse').textContent='🎒 '+(u.classe||'—');
  document.getElementById('profilCountry').textContent='🌍 '+(u.pays||'—');
  document.getElementById('profilLocalite').textContent=u.localite?'📍 '+u.localite:'';
  document.getElementById('editName').value=u.name;
  const cSel=document.getElementById('editCountry');
  cSel.value=u.pays||'';
  if (u.pays){
    fillLocalites(u.pays,'editLocalite');
    document.getElementById('editLocalite').value=u.localite||'';
  }
}

async function saveProfile() {
  const name=document.getElementById('editName').value.trim();
  const pays=document.getElementById('editCountry').value;
  const localite=document.getElementById('editLocalite').value;
  const pass=document.getElementById('editPass').value;
  if (!name){toast('Le nom est obligatoire',true);return;}
  if (pass&&pass.length<6){toast('Mot de passe : minimum 6 caractères',true);return;}
  try {
    const r=await api('/users/profile','POST',{name,pays,localite,password:pass||undefined});
    currentUser=r.user; renderProfil(); toast('✅ Profil mis à jour !');
  } catch(e){toast(e.message,true);}
}

async function updatePhoto(input) {
  if (!input.files[0]) return;
  const rd=new FileReader();
  rd.onload=async e=>{
    try{await api('/users/photo','POST',{photo:e.target.result});currentUser.photo=e.target.result;renderProfil();toast('✅ Photo mise à jour !');}
    catch(err){toast(err.message,true);}
  };
  rd.readAsDataURL(input.files[0]);
}

loadProfil();
</script>
@endsection
