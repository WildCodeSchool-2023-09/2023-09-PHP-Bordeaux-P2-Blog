<!-- <?php
require_once 'vendor/autoload.php'; 

$loader = new Twig_Loader_Filesystem('src/View/Home/navbar.html.twig');

$twig = new Twig_Environment($loader);

$nombreNotifications = obtenirNombreNotifications();

echo $twig->render('navbar.html.twig', ['nombreNotifications' => $nombreNotifications]);

session_start();

$estConnecte = isset($_SESSION['estConnecte']) ? $_SESSION['estConnecte'] : false;

echo $twig->render('navbar.html.twig', ['estConnecte' => $estConnecte]);
?> -->

<body>
  <header>
    <div class="navbar">
      <div class="logo">
        <img src="public/assets/images/BUMP.png" alt="Logo">
      </div>
      <div class="button-container">
        <button class="accueil">ACCUEIL</button>
        <button class="contact">CONTACT</button>
        <button class="blog">MON BLOG</button>
        <button class="notifications">NOTIFICATIONS </button>
      </div>
      <div class="navbar-mobile">
        <div class="background"></div>
        <div class="menu-icon">
          <div></div>
          <div></div>
          <div></div>
        </div>
        <div class="logo-container">
          <img src="https://via.placeholder.com/124x60" alt="Logo">
        </div>
      </div>
      <div class="notification">
        <span>{{ nombreNotifications }}</span>
      </div>
      </nav>
      <div class="popup" id="popup">
        <div class="popup-content">
          <span class="close" onclick="closePopup()">&times;</span>
          <p>Rejoins la communauté BUMP!</p>
        </div>
      </div>
  </header>
</body>
</html> -->