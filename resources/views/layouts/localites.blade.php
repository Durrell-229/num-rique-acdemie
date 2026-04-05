/**
 * ACADÉMIE NUMÉRIQUE — Localités par pays
 * Toutes les villes/communes principales de chaque pays
 */
const LOCALITES = {
  "Bénin": [
    "Cotonou","Porto-Novo","Parakou","Djougou","Bohicon","Kandi","Lokossa","Ouidah",
    "Abomey","Natitingou","Savalou","Save","Nikki","Malanville","Pobè","Aplahoué",
    "Comè","Dogbo","Zagnanado","Bembèrèkè","Tchaourou","Banikoara","Gogounou",
    "Karimama","Ségbana","Kouandé","Péhunco","Boukoumbé","Tanguiéta","Matéri",
    "Cobly","Toucountouna","Kérou","Ouaké","Bassila","Copargo","Dassa-Zoumè",
    "Glazoué","Ouèssè","Bantè","Zogbodomey","Covè","Agbangnizoun","Abomey-Calavi",
    "Sèmè-Kpodji","Aguégués","Adjarra","Adjohoun","Dangbo","Akpro-Missérété",
    "Bonou","Avrankou","Ifangni","Kétou","Adja-Ouèrè","Grand-Popo","Houéyogbé",
    "Athiémé","Bopa","Toviklin","Djakotomey","Klouékanmè","Lalo","Agoué",
    "Abomey-Calavi (Godomey)","Calavi","Sô-Ava","Zê"
  ],
  "Burkina Faso": [
    "Ouagadougou","Bobo-Dioulasso","Koudougou","Banfora","Ouahigouya","Pouytenga",
    "Kaya","Tenkodogo","Fada N'Gourma","Dédougou","Manga","Réo","Gaoua","Diébougou",
    "Dori","Djibo","Titao","Kongoussi","Ziniaré","Zorgho","Boulsa","Toma",
    "Nouna","Tougan","Solenzo","Boromo","Leo","Diapaga","Gayéri","Pama",
    "Bogandé","Boulsa","Kombissiri","Sapouy","Silly","Koupèla","Garango",
    "Bittou","Zabré","Nako","Comin-Yanga","Thiou","Séguénéga","Gourcy"
  ],
  "Cameroun": [
    "Yaoundé","Douala","Bafoussam","Garoua","Bamenda","Maroua","Ngaoundéré",
    "Bertoua","Kumba","Nkongsamba","Buea","Limbé","Edéa","Kribi","Ebolowa",
    "Sangmélima","Mbalmayo","Dschang","Bafang","Mbouda","Foumban","Tibati",
    "Meiganga","Guider","Mokolo","Mora","Kousséri","Meri","Waza","Yagoua",
    "Kaéle","Mindif","Pitoa","Garoua-Boulaï","Abong-Mbang","Batouri","Yokadouma",
    "Moloundou","Lomié","Mbang","Nanga-Eboko","Bafia","Ndikinimeki","Obala",
    "Soa","Biyem-Assi","Mendong","Essos","Nkoabang"
  ],
  "Côte d'Ivoire": [
    "Abidjan","Bouaké","Daloa","San-Pédro","Yamoussoukro","Korhogo","Man",
    "Divo","Gagnoa","Abobo","Anyama","Agboville","Abengourou","Bondoukou",
    "Soubré","Séguéla","Odienné","Touba","Mankono","Katiola","Ferké","Tingrela",
    "Boundiali","Tengrela","Ouangolodougou","Ferkessédougou","Kong","Nassian",
    "Bouna","Téhini","Doropo","Tanda","Agnibilékrou","Aboisso","Adiaké",
    "Grand-Bassam","Bingerville","Jacqueville","Sassandra","Tabou","Guiglo",
    "Toulepleu","Bloléquin","Duékoué","Bangolo","Biankouma","Danané","Zouan-Hounien",
    "Zuenoula","Sinfra","Zoukougbeu","Issia","Lakota","Guitry","Fresco"
  ],
  "France": [
    "Paris","Lyon","Marseille","Toulouse","Nice","Nantes","Montpellier","Strasbourg",
    "Bordeaux","Lille","Rennes","Reims","Saint-Étienne","Toulon","Le Havre",
    "Grenoble","Dijon","Angers","Nîmes","Aix-en-Provence","Brest","Limoges",
    "Tours","Clermont-Ferrand","Amiens","Perpignan","Metz","Besançon","Caen",
    "Orléans","Rouen","Mulhouse","Nancy","Avignon","Poitiers","Cannes","Antibes",
    "Calais","La Rochelle","Dunkerque","Pau","Valenciennes","Créteil","Argenteuil",
    "Versailles","Cergy","Évry","Massy","Vitry-sur-Seine","Saint-Denis (93)"
  ],
  "Gabon": [
    "Libreville","Port-Gentil","Franceville","Oyem","Moanda","Mouila","Lambaréné",
    "Tchibanga","Koulamoutou","Makokou","Bitam","Booué","Lastoursville","Ndendé",
    "Mitzic","Minvoul","Médouneu","Gamba","Mayumba","Omboué","Fougamou"
  ],
  "Guinée": [
    "Conakry","Nzérékoré","Kindia","Kankan","Labé","Guéckédou","Mamou","Macenta",
    "Faranah","Siguiri","Coyah","Dubréka","Boke","Kamsar","Télimélé","Pita",
    "Dalaba","Dinguiraye","Kouroussa","Kissidougou","Beyla","Lola","Yomou"
  ],
  "Madagascar": [
    "Antananarivo","Toamasina","Antsirabe","Fianarantsoa","Mahajanga","Toliara",
    "Antsiranana","Ambovombe","Antálaha","Mananjary","Morondava","Ambatondrazaka",
    "Tsiroanomandidy","Maevatanana","Maintirano","Miandrivazo","Moramanga","Nosy Be"
  ],
  "Mali": [
    "Bamako","Sikasso","Mopti","Koutiala","Kayes","Ségou","Gao","Kidal","Tombouctou",
    "Bougouni","Kati","San","Markala","Niono","Bla","Djenné","Bandiagara",
    "Douentza","Youwarou","Niafunké","Diré","Goundam","Ansongo","Ménaka",
    "Kidal","Tin-Essako","Abeïbara","Tessalit","Kolondiéba","Yanfolila","Dioïla"
  ],
  "Maroc": [
    "Casablanca","Rabat","Fès","Marrakech","Agadir","Tanger","Meknès","Oujda",
    "Kenitra","Tétouan","Safi","Mohammedia","Khouribga","El Jadida","Béni Mellal",
    "Nador","Settat","Berrechid","Ksar El Kébir","Larache","Guelmim","Taourirt",
    "Dakhla","Laayoune","Essaouira","Ifrane","Azrou","Midelt","Er-Rachidia","Zagora"
  ],
  "Niger": [
    "Niamey","Zinder","Maradi","Tahoua","Agadez","Dosso","Diffa","Tillabéri",
    "Arlit","Birni N'Konni","Madaoua","Tessaoua","Maïné-Soroa","Nguigmi","Bilma",
    "Tchin-Tabaraden","Keita","Bouza","Illéla","Filingué","Téra","Tillabéri",
    "Gaya","Boboye","Loga","Doutchi","Dogondoutchi","Matankari"
  ],
  "République Démocratique du Congo": [
    "Kinshasa","Lubumbashi","Mbuji-Mayi","Kananga","Kisangani","Bukavu","Goma",
    "Kolwezi","Kalemie","Likasi","Uvira","Bunia","Beni","Butembo","Matadi",
    "Mbandaka","Bandundu","Kikwit","Tshikapa","Kabinda","Mweka","Lodja",
    "Lusambo","Kongolo","Kamina","Manono","Kabalo","Kalemie","Baraka","Fizi"
  ],
  "Sénégal": [
    "Dakar","Touba","Thiès","Rufisque","Kaolack","Ziguinchor","Saint-Louis",
    "Diourbel","Tambacounda","Mbour","Louga","Kolda","Matam","Kaffrine",
    "Sédhiou","Kédougou","Fatick","Tivaouane","Mbacké","Dara","Podor","Richard-Toll",
    "Dagana","Bakel","Vélingara","Bignona","Oussouye","Goudomp","Bounkiling",
    "Marsassoum","Nioro du Rip","Karang","Sokone","Foundiougne","Passy"
  ],
  "Tchad": [
    "N'Djamena","Moundou","Sarh","Abéché","Kélo","Bongor","Doba","Mongo",
    "Ati","Faya-Largeau","Am Timan","Massaguet","Goz Beïda","Biltine","Adré",
    "Iriba","Amdjarass","Fada","Zouar","Bardaï","Moussoro","Massakory",
    "Mao","Bol","Bagasola","Liwa","Pala","Fianga","Léré"
  ],
  "Togo": [
    "Lomé","Sokodé","Kara","Palimé","Atakpamé","Bassar","Tsévié","Aného",
    "Mango","Dapaong","Niamtougou","Kanté","Bafilo","Sotouboua","Blitta",
    "Anié","Badou","Kpalimé","Notsé","Tabligbo","Vogan","Amlame","Atakpamé",
    "Tchamba","Tchamba","Kabou","Guérin-Kouka","Sansanné-Mango","Cinkassé"
  ],
  "Tunisie": [
    "Tunis","Sfax","Sousse","Kairouan","Bizerte","Gabès","Ariana","Gafsa",
    "Monastir","Ben Arous","Kasserine","Médenine","Nabeul","Tataouine","Béja",
    "Jendouba","Mahdia","Siliana","El Kef","Tozeur","Kébili","Zaghouan","Manouba"
  ],
  "Autre": ["Autre localité"]
};

// Fonction pour peupler le select localité selon le pays
function updateLocalites(paysSelect, localiteSelect) {
  const pays = paysSelect.value;
  const locs = LOCALITES[pays] || ["Autre localité"];
  localiteSelect.innerHTML = '<option value="">— Sélectionner une localité —</option>';
  locs.forEach(loc => {
    const opt = document.createElement('option');
    opt.value = loc;
    opt.textContent = loc;
    localiteSelect.appendChild(opt);
  });
}
