<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Académie Numérique - Plateforme d'apprentissage professionnelle</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#04080f;--bg2:#080f1a;--bg3:#0c1524;
  --accent:#2563eb;
  --accent2:#7c3aed;
  --accent3:#06b6d4;
  --danger:#ef4444;
  --gold:#f59e0b;
  --text:#f1f5f9;
  --text2:#94a3b8;
  --text3:#475569;
  --card:rgba(8,15,26,0.9);
  --border:rgba(37,99,235,0.12);
  --border2:rgba(255,255,255,0.06);
  --glow:0 0 30px rgba(37,99,235,0.2);
  --font:'Outfit',sans-serif;
  --mono:'JetBrains Mono',monospace;
  --r:14px;
  --r2:20px;
}
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{font-family:var(--font);background:var(--bg);color:var(--text);min-height:100vh;overflow-x:hidden;}
body::before{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
  background:radial-gradient(ellipse 80% 50% at 10% 10%,rgba(37,99,235,.06) 0%,transparent 60%),
    radial-gradient(ellipse 60% 60% at 90% 90%,rgba(124,58,237,.05) 0%,transparent 60%);}
body::after{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
  background-image:linear-gradient(rgba(37,99,235,.03) 1px,transparent 1px),
                   linear-gradient(90deg,rgba(37,99,235,.03) 1px,transparent 1px);
  background-size:80px 80px;}
.z1{position:relative;z-index:1;}

/* ═══════ NAVBAR ═══════ */
.navbar{position:fixed;top:0;left:0;right:0;background:rgba(4,8,15,.95);backdrop-filter:blur(20px);
  border-bottom:1px solid var(--border2);z-index:100;padding:18px 48px;display:flex;justify-content:space-between;align-items:center;}
.logo{display:flex;align-items:center;gap:12px;}
.logo-icon{width:44px;height:44px;background:linear-gradient(135deg,var(--accent),var(--accent2));
  border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;}
.logo-text{font-size:20px;font-weight:800;}
.logo-sub{font-size:10px;color:var(--text3);letter-spacing:2px;}
.nav-links{display:flex;gap:40px;align-items:center;}
.nav-link{color:var(--text2);text-decoration:none;font-weight:500;transition:color .2s;}
.nav-link:hover{color:var(--accent);}
.btn-connect{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;
  padding:12px 28px;border-radius:40px;text-decoration:none;font-weight:600;transition:all .2s;}
.btn-connect:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(37,99,235,.4);}

/* ═══════ SLIDER / CARROUSEL ═══════ */
.slider-container{position:relative;min-height:100vh;overflow:hidden;}
.slider{position:relative;height:100vh;width:100%;}
.slide{position:absolute;top:0;left:0;width:100%;height:100%;opacity:0;visibility:hidden;
  transition:opacity 0.8s ease-in-out, visibility 0.8s ease-in-out;
  display:flex;align-items:center;justify-content:center;text-align:center;
  background-size:cover;background-position:center;background-repeat:no-repeat;}
.slide.active{opacity:1;visibility:visible;}
.slide::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(4,8,15,0.7);z-index:0;}
.slide-content{position:relative;z-index:1;max-width:1000px;padding:0 24px;}
.slide-badge{background:rgba(37,99,235,.12);border:1px solid rgba(37,99,235,.2);border-radius:60px;
  padding:10px 24px;display:inline-block;font-size:13px;color:var(--accent);margin-bottom:32px;}
.slide-title{font-size:72px;font-weight:800;margin-bottom:28px;background:linear-gradient(135deg,#fff,var(--accent2),var(--accent));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1.2;}
.slide-subtitle{font-size:20px;color:var(--text2);margin-bottom:48px;max-width:700px;margin-left:auto;margin-right:auto;line-height:1.6;}
.slide-buttons{display:flex;gap:20px;justify-content:center;flex-wrap:wrap;}
.btn-primary{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;
  padding:16px 40px;border-radius:60px;text-decoration:none;font-weight:700;font-size:16px;transition:all .2s;display:inline-block;}
.btn-primary:hover{transform:translateY(-3px);box-shadow:0 15px 40px rgba(37,99,235,.5);}
.btn-secondary{background:rgba(255,255,255,.05);border:1px solid var(--border2);color:var(--text);
  padding:16px 40px;border-radius:60px;text-decoration:none;font-weight:700;font-size:16px;transition:all .2s;display:inline-block;}
.btn-secondary:hover{background:rgba(255,255,255,.1);transform:translateY(-3px);}

/* Navigation dots */
.slider-dots{position:absolute;bottom:30px;left:0;right:0;display:flex;justify-content:center;gap:12px;z-index:10;}
.dot{width:12px;height:12px;border-radius:50%;background:rgba(255,255,255,.3);cursor:pointer;transition:all .3s;}
.dot.active{background:var(--accent);width:30px;border-radius:20px;}
.dot:hover{background:var(--accent);}

/* Navigation arrows */
.slider-prev,.slider-next{position:absolute;top:50%;transform:translateY(-50%);width:50px;height:50px;
  background:rgba(255,255,255,.1);border:1px solid var(--border2);border-radius:50%;cursor:pointer;
  display:flex;align-items:center;justify-content:center;font-size:24px;color:var(--text);
  transition:all .3s;z-index:10;backdrop-filter:blur(10px);}
.slider-prev{left:30px;}
.slider-next{right:30px;}
.slider-prev:hover,.slider-next:hover{background:rgba(37,99,235,.3);border-color:var(--accent);transform:translateY(-50%) scale(1.1);}

/* ═══════ HERO ═══════ */
.hero{min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:160px 48px 100px;
  position:relative;background-image:url('/images/WhatsApp%20Image%202026-04-04%20at%2000.01.43.jpeg');background-size:cover;background-position:center;background-repeat:no-repeat;}
.hero::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(4,8,15,0.75);z-index:0;}
.hero-content{position:relative;z-index:1;max-width:1000px;}
.hero-badge{background:rgba(37,99,235,.12);border:1px solid rgba(37,99,235,.2);border-radius:60px;
  padding:10px 24px;display:inline-block;font-size:13px;color:var(--accent);margin-bottom:32px;}
.hero-title{font-size:72px;font-weight:800;margin-bottom:28px;background:linear-gradient(135deg,#fff,var(--accent2),var(--accent));
 -webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1.2;}
.hero-subtitle{font-size:20px;color:var(--text2);margin-bottom:48px;max-width:700px;margin-left:auto;margin-right:auto;line-height:1.6;}
.hero-buttons{display:flex;gap:20px;justify-content:center;flex-wrap:wrap;}
.btn-primary{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;
  padding:16px 40px;border-radius:60px;text-decoration:none;font-weight:700;font-size:16px;transition:all .2s;}
.btn-primary:hover{transform:translateY(-3px);box-shadow:0 15px 40px rgba(37,99,235,.5);}
.btn-secondary{background:rgba(255,255,255,.05);border:1px solid var(--border2);color:var(--text);
  padding:16px 40px;border-radius:60px;text-decoration:none;font-weight:700;font-size:16px;transition:all .2s;}
.btn-secondary:hover{background:rgba(255,255,255,.1);transform:translateY(-3px);}

/* ═══════ CLIENTS SECTION ═══════ */
.clients{padding:60px 48px;border-top:1px solid var(--border2);border-bottom:1px solid var(--border2);}
.clients-title{text-align:center;font-size:13px;color:var(--text3);letter-spacing:2px;margin-bottom:40px;text-transform:uppercase;}
.clients-grid{max-width:1200px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:30px;}
.client-logo{font-size:20px;font-weight:700;color:var(--text2);opacity:.7;transition:opacity .2s;}
.client-logo:hover{opacity:1;color:var(--accent);}

/* ═══════ FEATURES ═══════ */
.features{padding:100px 48px;}
.section-header{text-align:center;max-width:800px;margin:0 auto 60px;}
.section-title{font-size:42px;font-weight:800;margin-bottom:20px;}
.section-subtitle{font-size:18px;color:var(--text2);line-height:1.6;}
.features-grid{max-width:1300px;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:40px;}
.feature-card{background:var(--card);border:1px solid var(--border2);border-radius:var(--r2);
  padding:40px 32px;transition:all .3s;text-align:center;}
.feature-card:hover{transform:translateY(-8px);border-color:rgba(37,99,235,.3);box-shadow:var(--glow);}
.feature-img{width:100%;height:200px;object-fit:cover;border-radius:var(--r);margin-bottom:24px;}
.feature-title{font-size:22px;font-weight:700;margin-bottom:16px;}
.feature-desc{color:var(--text2);font-size:15px;line-height:1.6;}

/* ═══════ DISCOVER SECTION ═══════ */
.discover{padding:100px 48px;background:var(--bg2);}
.discover-grid{max-width:1300px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;}
.discover-title{font-size:42px;font-weight:800;margin-bottom:24px;}
.discover-desc{font-size:18px;color:var(--text2);margin-bottom:32px;line-height:1.6;}
.discover-list{list-style:none;}
.discover-list li{display:flex;align-items:center;gap:12px;margin-bottom:20px;font-size:16px;}
.discover-list li::before{content:'✓';color:var(--accent3);font-size:20px;font-weight:700;}
.discover-image{width:100%;border-radius:var(--r2);overflow:hidden;}
.discover-image img{width:100%;height:auto;display:block;border-radius:var(--r2);}
.discover-cards{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-top:30px;}
.discover-card{background:var(--card);border:1px solid var(--border2);border-radius:var(--r2);
  padding:24px;text-align:center;transition:all .3s;}
.discover-card:hover{transform:translateY(-4px);border-color:rgba(37,99,235,.3);}
.discover-card-icon{font-size:40px;margin-bottom:12px;}
.discover-card-title{font-size:16px;font-weight:700;}

/* ═══════ COURSES SHOWCASE ═══════ */
.courses-showcase{padding:100px 48px;}
.courses-showcase .section-title{text-align:center;}
.courses-grid{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:30px;margin-top:48px;}
.course-card{background:var(--card);border:1px solid var(--border2);border-radius:var(--r2);overflow:hidden;transition:all .3s;}
.course-card:hover{transform:translateY(-4px);border-color:rgba(37,99,235,.3);}
.course-image{width:100%;height:200px;object-fit:cover;}
.course-info{padding:24px;}
.course-title{font-size:18px;font-weight:700;margin-bottom:8px;}
.course-desc{font-size:13px;color:var(--text2);margin-bottom:12px;}
.course-price{font-size:14px;font-weight:700;color:var(--accent);}

/* ═══════ DASHBOARD PREVIEW ═══════ */
.dashboard-preview{padding:100px 48px;background:linear-gradient(135deg,rgba(37,99,235,.05),rgba(124,58,237,.05));}
.dashboard-grid{max-width:1300px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;}
.dashboard-title{font-size:42px;font-weight:800;margin-bottom:24px;}
.dashboard-desc{font-size:18px;color:var(--text2);margin-bottom:32px;line-height:1.6;}
.dashboard-stats{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-top:32px;}
.dashboard-stat{background:var(--card);border:1px solid var(--border2);border-radius:var(--r);padding:20px;text-align:center;}
.dashboard-stat-number{font-size:32px;font-weight:800;color:var(--accent);}
.dashboard-stat-label{font-size:12px;color:var(--text3);margin-top:5px;}
.dashboard-image{width:100%;border-radius:var(--r2);overflow:hidden;}
.dashboard-image img{width:100%;height:auto;display:block;border-radius:var(--r2);}

/* ═══════ CTA ═══════ */
.cta{padding:100px 48px;text-align:center;}
.cta-card{max-width:900px;margin:0 auto;background:linear-gradient(135deg,rgba(37,99,235,.08),rgba(124,58,237,.05));
  border:1px solid var(--border);border-radius:var(--r2);padding:60px 48px;}
.cta-title{font-size:42px;font-weight:800;margin-bottom:20px;}
.cta-subtitle{font-size:18px;color:var(--text2);margin-bottom:40px;}

/* ═══════ FOOTER ═══════ */
.footer{padding:60px 48px 32px;border-top:1px solid var(--border2);}
.footer-content{max-width:1300px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;margin-bottom:40px;}
.footer-logo .logo-icon{width:48px;height:48px;font-size:24px;}
.footer-logo .logo-text{font-size:18px;}
.footer-col h4{font-size:14px;font-weight:700;margin-bottom:20px;color:var(--text);}
.footer-col a{display:block;color:var(--text2);text-decoration:none;margin-bottom:12px;font-size:13px;transition:color .2s;}
.footer-col a:hover{color:var(--accent);}
.footer-bottom{text-align:center;padding-top:32px;border-top:1px solid var(--border2);}
.footer-bottom p{color:var(--text3);font-size:12px;font-family:var(--mono);}
.footer-bottom span{color:var(--accent);}

/* Responsive */
@media(max-width:1024px){
  .hero-title{font-size:52px;}
  .features-grid{grid-template-columns:repeat(2,1fr);}
  .discover-grid,.dashboard-grid{grid-template-columns:1fr;}
  .courses-grid{grid-template-columns:repeat(2,1fr);}
  .footer-content{grid-template-columns:repeat(2,1fr);}
}
@media(max-width:768px){
  .hero-title{font-size:36px;}
  .hero{padding:120px 24px 60px;}
  .features-grid{grid-template-columns:1fr;}
  .courses-grid{grid-template-columns:1fr;}
  .clients-grid{justify-content:center;}
  .navbar{padding:16px 24px;}
  .nav-links{display:none;}
  .features,.discover,.courses-showcase,.dashboard-preview,.cta{padding:60px 24px;}
}
</style>
</head>
<body>


<div class="z1">

<!-- Navbar -->
<nav class="navbar">
  <div class="logo">
    <div class="logo-icon">🎓</div>
    <div><div class="logo-text">ACADÉMIE NUMÉRIQUE</div><div class="logo-sub">PLATEFORME D'APPRENTISSAGE</div></div>
  </div>
  <div class="nav-links">
    <a href="#accueil" class="nav-link">Accueil</a>
    <a href="#formations" class="nav-link">Formations</a>
    <a href="#avantages" class="nav-link">Avantages</a>
    <a href="#ressources" class="nav-link">Ressources</a>
    <a href="{{ route('login') }}" class="btn-connect">🔐 Connexion</a>
  </div>
</nav>



<!-- ═══════════════ SLIDER 7 SLIDES ═══════════════ -->
<div class="slider-container" id="accueil">
  <div class="slider">
    <!-- Slide 1 - Éducation moderne -->
    <div class="slide active" style="background-image: url('/images/edu1.jpeg');">
      <div class="slide-content">
        <div class="slide-badge">🎓 ÉDUCATION MODERNE</div>
        <h1 class="slide-title">Une école nouvelle génération</h1>
        <p class="slide-subtitle">Découvrez une plateforme qui élève votre expérience d'apprentissage avec une personnalisation IA.</p>
        <div class="slide-buttons">
          <a href="{{ route('register') }}" class="btn-primary">🚀 Commencer gratuitement</a>
          <a href="#formations" class="btn-secondary">📚 Découvrir nos cours</a>
        </div>
      </div>
    </div>

    <!-- Slide 2 - Innovation pédagogique -->
    <div class="slide" style="background-image: url('/images/edu2.png');">
      <div class="slide-content">
        <div class="slide-badge">💡 INNOVATION PÉDAGOGIQUE</div>
        <h1 class="slide-title">Apprenez avec l'intelligence artificielle</h1>
        <p class="slide-subtitle">Des parcours d'apprentissage personnalisés adaptés à votre niveau et vos objectifs.</p>
        <div class="slide-buttons">
          <a href="{{ route('register') }}" class="btn-primary">🚀 Commencer gratuitement</a>
          <a href="#formations" class="btn-secondary">📚 Explorer les cours</a>
        </div>
      </div>
    </div>

    <!-- Slide 3 - Cours en ligne -->
    <div class="slide" style="background-image: url('/images/edu3.png');">
      <div class="slide-content">
        <div class="slide-badge">💻 COURS EN LIGNE</div>
        <h1 class="slide-title">Apprenez où que vous soyez</h1>
        <p class="slide-subtitle">Accédez à vos cours sur tous vos appareils, à tout moment.</p>
        <div class="slide-buttons">
          <a href="{{ route('register') }}" class="btn-primary">🚀 Commencer gratuitement</a>
          <a href="#formations" class="btn-secondary">📚 Voir les formations</a>
        </div>
      </div>
    </div>

    <!-- Slide 4 - Certifications -->
    <div class="slide" style="background-image: url('/images/slide3.png');">
      <div class="slide-content">
        <div class="slide-badge">📜 CERTIFICATIONS OFFICIELLES</div>
        <h1 class="slide-title">Valorisez vos compétences</h1>
        <p class="slide-subtitle">Obtenez des certificats reconnus à la fin de chaque formation.</p>
        <div class="slide-buttons">
          <a href="{{ route('register') }}" class="btn-primary">🚀 Commencer gratuitement</a>
          <a href="#formations" class="btn-secondary">📚 Découvrir les cours</a>
        </div>
      </div>
    </div>

    <!-- Slide 5 - Salles virtuelles -->
    <div class="slide" style="background-image: url('/images/slide4.png');">
      <div class="slide-content">
        <div class="slide-badge">🚪 SALLES VIRTUELLES</div>
        <h1 class="slide-title">Des classes interactives en direct</h1>
        <p class="slide-subtitle">Rejoignez nos salles Jitsi pour des cours en visioconférence avec vos professeurs.</p>
        <div class="slide-buttons">
          <a href="{{ route('register') }}" class="btn-primary">🚀 Rejoindre une classe</a>
          <a href="#formations" class="btn-secondary">📚 Voir les cours</a>
        </div>
      </div>
    </div>

    <!-- Slide 6 - Skill-based learning -->
    <div class="slide" style="background-image: url('/images/slide.png');">
      <div class="slide-content">
        <div class="slide-badge">🎯 SKILL-BASED LEARNING</div>
        <h1 class="slide-title">Développez les compétences du futur</h1>
        <p class="slide-subtitle">Notre Skill Navigator vous aide à identifier et combler vos lacunes.</p>
        <div class="slide-buttons">
          <a href="{{ route('register') }}" class="btn-primary">🚀 Commencer gratuitement</a>
          <a href="#avantages" class="btn-secondary">📚 En savoir plus</a>
        </div>
      </div>
    </div>

    <!-- Slide 7 - Communauté -->
    <div class="slide" style="background-image: url('/images/slide7.png');">
      <div class="slide-content">
        <div class="slide-badge">👥 COMMUNAUTÉ ACTIVE</div>
        <h1 class="slide-title">Rejoignez une communauté de 500+ étudiants</h1>
        <p class="slide-subtitle">Échangez, partagez et apprenez ensemble dans notre espace collaboratif.</p>
        <div class="slide-buttons">
          <a href="{{ route('register') }}" class="btn-primary">🚀 Rejoindre la communauté</a>
          <a href="#formations" class="btn-secondary">📚 Découvrir les cours</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Dots navigation -->
  <div class="slider-dots">
    <div class="dot active" onclick="currentSlide(0)"></div>
    <div class="dot" onclick="currentSlide(1)"></div>
    <div class="dot" onclick="currentSlide(2)"></div>
    <div class="dot" onclick="currentSlide(3)"></div>
    <div class="dot" onclick="currentSlide(4)"></div>
    <div class="dot" onclick="currentSlide(5)"></div>
    <div class="dot" onclick="currentSlide(6)"></div>
  </div>

  <!-- Arrows navigation -->
  <div class="slider-prev" onclick="prevSlide()">❮</div>
  <div class="slider-next" onclick="nextSlide()">❯</div>
</div>

<!-- Clients section -->
<section class="clients">
  <div class="clients-title">Confiance de plus de 700 organisations</div>
  <div class="clients-grid">
    <div class="client-logo">🎓 ESSENT</div>
    <div class="client-logo">🏗️ BAM</div>
    <div class="client-logo">🌟 UNICA</div>
    <div class="client-logo">🎮 FUN DA</div>
    <div class="client-logo">💼 Adecco</div>
    <div class="client-logo">⏱️ TIMING</div>
    <div class="client-logo">🤝 Social Deal</div>
  </div>
</section>

<!-- Features avec tes images -->
<section class="features" id="avantages">
  <div class="section-header">
    <h2 class="section-title">Les professeurs Certifiés</h2>
    <p class="section-subtitle">Une plateforme conçue pour votre réussite professionnelle</p>
  </div>
  <div class="features-grid">
    <div class="feature-card">
      <img src="/images/skill.png" alt="Skill-based learning" class="feature-img">
      <h3 class="feature-title">Skill-based learning intégré</h3>
      <p class="feature-desc">Notre outil Skill Navigator vous aide à combler efficacement vos lacunes, contribuant au succès individuel et organisationnel.</p>
    </div>
    <div class="feature-card">
      <img src="/images/ia.jpg" alt="Autonomie" class="feature-img">
      <h3 class="feature-title">Stimulez l'autonomie</h3>
      <p class="feature-desc">Évitez que vos employés se perdent dans d'immenses bibliothèques avec notre page "Découvrir" alimentée par l'IA.</p>
    </div>
    <div class="feature-card">
      <img src="/images/pertinent.jpg" alt="Recherche intelligente" class="feature-img">
      <h3 class="feature-title">Trouvez du contenu pertinent</h3>
      <p class="feature-desc">Mettez fin aux longues recherches avec notre moteur de recherche IA et nos recommandations personnalisées.</p>
    </div>
  </div>
</section>

<!-- Discover Section avec une de tes images -->
<section class="discover" id="ressources">
  <div class="discover-grid">
    <div>
      <h2 class="discover-title">Découvrez rapidement <br>le bon contenu</h2>
      <p class="discover-desc">Vos employés veulent trouver du contenu pertinent mais se perdent dans des montagnes de formations ? Rendez la recherche facile avec notre LXP !</p>
      <ul class="discover-list">
        <li>Page de découverte personnalisée</li>
        <li>Fonctionnalités de recherche IA</li>
        <li>Recommandations basées sur vos compétences</li>
        <li>Mises à jour par email</li>
      </ul>
      <div class="discover-cards">
        <div class="discover-card"><div class="discover-card-icon">🔍</div><div class="discover-card-title">Recherche IA</div></div>
        <div class="discover-card"><div class="discover-card-icon">🎯</div><div class="discover-card-title">Personnalisation</div></div>
        <div class="discover-card"><div class="discover-card-icon">📧</div><div class="discover-card-title">Alertes email</div></div>
        <div class="discover-card"><div class="discover-card-icon">📊</div><div class="discover-card-title">Skill Maps</div></div>
      </div>
    </div>
    <div class="discover-image">
      <img src="/images/WhatsApp%20Image%202026-04-04%20at%2000.01.44.jpeg" alt="Découverte">
    </div>
  </div>
</section>

<!-- Courses Showcase avec une de tes images -->
<section class="courses-showcase" id="formations">
  <div class="section-header">
    <h2 class="section-title">Nos Formations </h2>
    <p class="section-subtitle">Découvrez nos cours les plus suivis par nos étudiants</p>
  </div>
  <div class="courses-grid">
    <div class="course-card">
      <img src="/images/Mathématiques.webp" alt="Mathématiques" class="course-image">
      <div class="course-info">
        <h3 class="course-title">Mathématiques - Terminale C</h3>
        <p class="course-desc">Préparez votre examen avec nos cours complets et exercices corrigés.</p>
        <div class="course-price">Gratuit</div>
      </div>
    </div>
    <div class="course-card">
      <img src="/images/SVT.webp" alt="Développement Web" class="course-image">
      <div class="course-info">
        <h3 class="course-title">Science de la vie et de la terre (SVT)</h3>
        <p class="course-desc">Préparez votre examen avec nos cours complets et exercices corrigés.</p>
        <div class="course-price">15 000 FCFA</div>
      </div>
    </div>
    <div class="course-card">
      <img src="/images/français.webp" alt="Développement Web" class="course-image">
      <div class="course-info">
        <h3 class="course-title">Français</h3>
        <p class="course-desc">Préparez votre examen avec nos cours complets et exercices corrigés.</p>
        <div class="course-price">5 000 FCFA</div>
      </div>
    </div>
    <div class="course-card">
      <img src="/images/anglais.jpg" alt="Développement Web" class="course-image">
      <div class="course-info">
        <h3 class="course-title">Anglais</h3>
        <p class="course-desc">Préparez votre examen avec nos cours complets et exercices corrigés.</p>
        <div class="course-price">15 000 FCFA</div>
      </div>
    </div>
    <div class="course-card">
      <img src="/images/WhatsApp%20Image%202026-04-04%20at%2000.01.43%20(1).jpeg" alt="Développement Web" class="course-image">
      <div class="course-info">
        <h3 class="course-title">Hist-Géo</h3>
        <p class="course-desc">Préparez votre examen avec nos cours complets et exercices corrigés.</p>
        <div class="course-price">15 000 FCFA</div>
      </div>
    </div>
    <div class="course-card">
      <img src="/images/WhatsApp%20Image%202026-04-04%20at%2000.01.43%20(1).jpeg" alt="Développement Web" class="course-image">
      <div class="course-info">
        <h3 class="course-title">Philosophie</h3>
        <p class="course-desc">Préparez votre examen avec nos cours complets et exercices corrigés.</p>
        <div class="course-price">15 000 FCFA</div>
      </div>
    </div>
    <div class="course-card">
      <img src="/images/WhatsApp%20Image%202026-04-04%20at%2000.01.43%20(2).jpeg" alt="Anglais" class="course-image">
      <div class="course-info">
        <h3 class="course-title">Physique Chimie et Technologie (PCT)</h3>
        <p class="course-desc">Préparez votre examen avec nos cours complets et exercices corrigés.</p>
        <div class="course-price">10 000 FCFA</div>
      </div>
    </div>
  </div>
</section>

<!-- Dashboard Preview avec une de tes images -->
<section class="dashboard-preview">
  <div class="dashboard-grid">
    <div>
      <h2 class="dashboard-title">Votre processus d'apprentissage <br>en un coup d'œil</h2>
      <p class="dashboard-desc">Donnez le contrôle à vos employés sur leur parcours d'apprentissage, leurs qualifications et leurs compétences.</p>
      <div class="dashboard-stats">
        <div class="dashboard-stat"><div class="dashboard-stat-number">500+</div><div class="dashboard-stat-label">Élèves formés</div></div>
        <div class="dashboard-stat"><div class="dashboard-stat-number">50+</div><div class="dashboard-stat-label">Cours disponibles</div></div>
        <div class="dashboard-stat"><div class="dashboard-stat-number">20+</div><div class="dashboard-stat-label">Enseignants</div></div>
        <div class="dashboard-stat"><div class="dashboard-stat-number">15+</div><div class="dashboard-stat-label">Pays</div></div>
      </div>
    </div>
    <div class="dashboard-image">
      <img src="/images/WhatsApp%20Image%202026-04-04%20at%2000.01.43%20(3).jpeg" alt="Dashboard">
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta">
  <div class="cta-card">
    <h2 class="cta-title">Prêt à commencer votre apprentissage ?</h2>
    <p class="cta-subtitle">Rejoignez des milliers d'étudiants et développez vos compétences avec l'IA</p>
    <a href="{{ route('register') }}" class="btn-primary">📚 S'inscrire gratuitement</a>
  </div>
</section>

<!-- Footer -->
<footer class="footer">
  <div class="footer-content">
    <div class="footer-logo">
      <div class="logo" style="margin-bottom:16px;">
        <div class="logo-icon">🎓</div>
        <div><div class="logo-text">ACADÉMIE NUMÉRIQUE</div><div class="logo-sub">FORMATION PROFESSIONNELLE</div></div>
      </div>
      <p style="color:var(--text3);font-size:13px;">L'apprentissage personnalisé<br>par l'IA pour tous.</p>
    </div>
    <div class="footer-col"><h4>Plateforme</h4><a href="#">Fonctionnalités</a><a href="#">Tarifs</a><a href="#">Démo</a><a href="#">FAQ</a></div>
    <div class="footer-col"><h4>Ressources</h4><a href="#">Blog</a><a href="#">Guides</a><a href="#">Webinaires</a><a href="#">Support</a></div>
    <div class="footer-col"><h4>Légal</h4><a href="#">Mentions légales</a><a href="#">CGU</a><a href="#">Confidentialité</a><a href="#">Cookies</a></div>
  </div>
  <div class="footer-bottom"><p>Design by <span>Leonard</span> · ACADÉMIE NUMÉRIQUE © 2025</p></div>
</footer>

</div>

<!-- CTA -->
<section class="cta">...</section>

<!-- Footer -->
<footer class="footer">...</footer>

<script>
// Slider automatique - 7 slides
let currentIndex = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
let autoSlideInterval;

function showSlide(index) {
    if (index < 0) {
        currentIndex = slides.length - 1;
    } else if (index >= slides.length) {
        currentIndex = 0;
    } else {
        currentIndex = index;
    }
    
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    slides[currentIndex].classList.add('active');
    dots[currentIndex].classList.add('active');
}

function nextSlide() {
    showSlide(currentIndex + 1);
    resetAutoSlide();
}

function prevSlide() {
    showSlide(currentIndex - 1);
    resetAutoSlide();
}

function currentSlide(index) {
    showSlide(index);
    resetAutoSlide();
}

function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    startAutoSlide();
}

function startAutoSlide() {
    autoSlideInterval = setInterval(() => {
        nextSlide();
    }, 3000); // Change de slide toutes les 10 secondes
}

document.addEventListener('DOMContentLoaded', () => {
    startAutoSlide();
    
    const sliderContainer = document.querySelector('.slider-container');
    if (sliderContainer) {
        sliderContainer.addEventListener('mouseenter', () => {
            clearInterval(autoSlideInterval);
        });
        
        sliderContainer.addEventListener('mouseleave', () => {
            startAutoSlide();
        });
    }
});
</script>


</body>
</html>