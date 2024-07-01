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



//BACKOFFICE ADD : Prévisualisation d'image 
document.addEventListener('DOMContentLoaded', function() {

    const imageInputs = document.querySelectorAll('input[type="file"]');
    console.log("Nombre d'inputs file trouvés :", imageInputs.length);
    
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            console.log("Fichier sélectionné pour", this.id);
            const file = this.files[0];
            const previewId = 'preview' + this.id.slice(-1);
            const previewDiv = document.getElementById(previewId);
            console.log("Preview div:", previewId);
            
            if (file) {
                console.log("Fichier sélectionné:", file.name);
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    console.log("Fichier lu avec succès");
                    previewDiv.innerHTML = `<img src="${e.target.result}" alt="Image preview">`;
                }
                
                reader.readAsDataURL(file);
            } else {
                console.log("Aucun fichier sélectionné");
                previewDiv.innerHTML = '';
            }
        });
    });
});