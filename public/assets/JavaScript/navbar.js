function openPopup() {
  document.getElementById("popup").style.display = "block";
}

function closePopup() {
  document.getElementById("popup").style.display = "none";
}

setTimeout(openPopup, 1000);



function toggleMobileMenu() {
  var mobileMenu = document.querySelector('.desktop-menu');
  mobileMenu.style.display = (mobileMenu.style.display === 'flex') ? 'none' : 'flex';
}

var mobileMenuIcon = document.querySelector('.mobile-menu');
mobileMenuIcon.addEventListener('click', toggleMobileMenu);
