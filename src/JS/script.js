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



/*BACKOFFICE GENERALE CARDS */
let cards = document.querySelector(".cards1");

function slidingCards1(){
    if (cards.style.width != "300px") {
        cards.style.width = "300px"
        cards.style.transition = "width 0.7s ease-in-out"
    } else if (cards.style.width = "300px") {
        cards.style.width = "50px"
    }
}

cards.addEventListener("click", slidingCards1)
///////CARDS2
let cards2 = document.querySelector(".cards2");

function slidingCards2(){
    if (cards2.style.width != "300px") {
        cards2.style.width = "300px"
        cards2.style.transition = "width 0.7s ease-in-out"
    } else if (cards2.style.width = "300px") {
        cards2.style.width = "50px"
    }
}

cards2.addEventListener("click", slidingCards2)
///////CARDS3
let cards3 = document.querySelector(".cards3");

function slidingCards3(){
    if (cards3.style.width != "300px") {
        cards3.style.width = "300px"
        cards3.style.transition = "width 0.7s ease-in-out"
    } else if (cards3.style.width = "300px") {
        cards3.style.width = "50px"
    }
}

cards3.addEventListener("click", slidingCards3)
///////CARDS4
let cards4 = document.querySelector(".cards4");

function slidingCards4(){
    if (cards4.style.width != "300px") {
        cards4.style.width = "300px"
        cards4.style.transition = "width 0.7s ease-in-out"
    } else if (cards4.style.width = "300px") {
        cards4.style.width = "50px"
    }
}

cards4.addEventListener("click", slidingCards4)