<?php
// Fonction fictive pour obtenir le nombre de notifications
function obtenirNombreNotifications()
{
  return; // Remplacez cela par votre logique réelle
}

$nombreNotifications = obtenirNombreNotifications();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="navbar.css">
  <script src="navbar.js" defer></script>
  <title>Navbar</title>
</head>

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
        <span><?php echo $nombreNotifications; ?></span>
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

</html>