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

/*DEBUT TEMPORAIRE */
/*SELECTEUR COULEUR CUSTOM DU BACKOFFICE*/
document.addEventListener('DOMContentLoaded', function() {
    var customSelect = document.querySelector('.custom-select');
    var select = customSelect.querySelector('select');
    var selectStyled = customSelect.querySelector('.select-styled');
    var options = customSelect.querySelector('.select-options');

    selectStyled.addEventListener('click', function(e) {
        e.stopPropagation();
        this.classList.toggle('active');
        options.style.display = options.style.display === 'none' ? 'block' : 'none';
    });

    options.querySelectorAll('li').forEach(function(option) {
        option.addEventListener('click', function() {
            selectStyled.textContent = this.textContent;
            select.value = this.getAttribute('data-value');
            options.style.display = 'none';
        });
    });

    document.addEventListener('click', function() {
        selectStyled.classList.remove('active');
        options.style.display = 'none';
    });
});
/*FIN TEMPORAIRE */

