let menuBurger = document.getElementById('menuBurger'); // MENU EN LUI MEME (  <ul>...</ul>  )
let menuBurgerButton = document.getElementById('menuBurgerIcon'); // BOUTON DU MENU

menuBurgerButton.addEventListener("click", showOrHideMenuBurger);

function showOrHideMenuBurger() {
    if (menuBurger.style.display != "block") {
        menuBurger.style.display = "block"
    }
    else {
        menuBurger.style.display = "none";
    }
}