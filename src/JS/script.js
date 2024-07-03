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



// Code permettant de selectionner toutes les checkbox a l'aide de celle en haut du tableau
document.addEventListener("DOMContentLoaded", function () {
    // Sélectionne la checkbox avec l'ID "selectAllProducts" et la stocke dans une constante
    const selectAllProducts = document.getElementById("selectAllProducts");
    
    // Sélectionne toutes les checkboxes ayant le nom "delete_ids[]" et les stocke dans une constante
    const deleteCheckboxes = document.querySelectorAll('input[name="delete_ids[]"]');

    // Ajoute un écouteur d'événement "change" à la checkbox "selectAllProducts"
    selectAllProducts.addEventListener("change", function () {
        // Pour chaque checkbox dans "deleteCheckboxes"
        deleteCheckboxes.forEach((checkbox) => {
            // Change l'état "checked" de chaque checkbox pour qu'il corresponde à celui de "selectAllProducts"
            checkbox.checked = selectAllProducts.checked;
        });
    });
});



/* BACKOFFICE-UTILISATEUR */
// Animation pour les cartes utilisateurs
document.addEventListener('DOMContentLoaded', function() {
    const userCards = document.querySelectorAll('.user-card');
    userCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });

    // Filtrage des utilisateurs
    const nameFilter = document.getElementById('nameFilter');
    const firstNameFilter = document.getElementById('firstNameFilter');
    const groupFilter = document.getElementById('groupFilter');
    const userCardContainers = document.querySelectorAll('.user-card-container');

    function filterUsers() {
        const nameValue = nameFilter.value.toLowerCase();
        const firstNameValue = firstNameFilter.value.toLowerCase();
        const groupValue = groupFilter.value.toLowerCase();

        userCardContainers.forEach(container => {
            const card = container.querySelector('.user-card');
            const name = card.querySelector('.card-title').textContent.toLowerCase();
            const roles = Array.from(card.querySelectorAll('.role-badge')).map(badge => badge.textContent.toLowerCase());

            const nameMatch = name.includes(nameValue);
            const firstNameMatch = name.includes(firstNameValue);
            const groupMatch = groupValue === '' || roles.includes(groupValue);

            if (nameMatch && firstNameMatch && groupMatch) {
                container.style.display = '';
            } else {
                container.style.display = 'none';
            }
        });
    }

    nameFilter.addEventListener('input', filterUsers);
    firstNameFilter.addEventListener('input', filterUsers);
    groupFilter.addEventListener('change', filterUsers);
});
    // Filtrage des utilisateurs FIN