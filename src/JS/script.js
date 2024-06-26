/* BURGER MENU */
let menuBurger = document.getElementById('menuBurger'); // MENU EN LUI MEME (  <ul>...</ul>  )
let menuBurgerButton = document.getElementById('menuBurgerIcon'); // BOUTON DU MENU

menuBurgerButton.addEventListener("click", showOrHideMenuBurger);

function showOrHideMenuBurger() {
    if (menuBurger.classList.contains("hideMenuBurger")) {
        menuBurger.classList.remove("hideMenuBurger");
        menuBurger.classList.add("showMenuBurger");
        menuBurgerButton.classList.add('rotateIcon');
    } else if (menuBurger.classList.contains("showMenuBurger")) {
        menuBurger.classList.remove("showMenuBurger");
        menuBurger.classList.add("hideMenuBurger");
        menuBurgerButton.classList.remove('rotateIcon');
    }
}




/* DEBUG ZONE */
let debugZone = document.getElementById('debug');
let debugButton = document.getElementById('debugButton');

debugButton.addEventListener("click", showOrDebugZone);

function showOrDebugZone() {
    if (debugZone.style.display != "block") {
        debugZone.style.display = "block";
    }
    else {
        debugZone.style.display = "none";
    }
}


