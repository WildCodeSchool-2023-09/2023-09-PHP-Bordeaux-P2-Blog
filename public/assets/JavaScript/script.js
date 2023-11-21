var searchInput = document.getElementById("searchInput");
var submitSearch = document.getElementById("submitsearch");
var originalPlaceholder = searchInput.getAttribute("placeholder");
var originalBackgroundImage = searchInput.style.backgroundImage;

// Gestionnaire de clic pour le champ de recherche
searchInput.addEventListener("click", function () {
    searchInput.style.display = "inline-block";
    searchInput.style.width = "40%";
    searchInput.style.border = "1px solid #40585d";
    searchInput.style.opacity = "1";
    searchInput.style.padding = "8px 20px 8px 20px";
    searchInput.style.backgroundImage = "none";
    searchInput.style.boxShadow = "0 0 1px black";

    submitSearch.style.display = "inline-block";
    searchInput.setAttribute("placeholder", "");
});

// Gestionnaire de clic pour le document entier
document.addEventListener("click", function (event) {
    var isClickInsideForm = document
        .getElementById("searchForm")
        .contains(event.target);

    if (!isClickInsideForm) {
        // Rétablir la forme d'origine si le clic est en dehors du formulaire
        searchInput.style.width = "30%";
        searchInput.style.border = "1px solid #000";
        searchInput.style.boxShadow = "none";
        submitSearch.style.display = "none";
        searchInput.setAttribute("placeholder", originalPlaceholder);
        searchInput.style.backgroundImage = originalBackgroundImage;
    }
});
