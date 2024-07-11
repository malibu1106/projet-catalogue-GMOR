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




//BACKOFFICE ADD : Prévisualisation d'image 
document.addEventListener('DOMContentLoaded', function () {

    const imageInputs = document.querySelectorAll('input[type="file"]');
    console.log("Nombre d'inputs file trouvés :", imageInputs.length);

    imageInputs.forEach(input => {
        input.addEventListener('change', function (e) {
            console.log("Fichier sélectionné pour", this.id);
            const file = this.files[0];
            const previewId = 'preview' + this.id.slice(-1);
            const previewDiv = document.getElementById(previewId);
            console.log("Preview div:", previewId);

            if (file) {
                console.log("Fichier sélectionné:", file.name);
                const reader = new FileReader();

                reader.onload = function (e) {
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

// Filtrage des utilisateurs
document.addEventListener('DOMContentLoaded', function () {
    const nameFilter = document.getElementById('nameFilter');
    const firstNameFilter = document.getElementById('firstNameFilter');
    const groupFilter = document.getElementById('groupFilter');
    const userCards = document.querySelectorAll('.user-card-container');

    function filterUsers() {
        const nameValue = nameFilter.value.toLowerCase();
        const firstNameValue = firstNameFilter.value.toLowerCase();
        const groupValue = groupFilter.value.toLowerCase();

        userCards.forEach(card => {
            const lastName = card.querySelector('.profil-lastname').textContent.toLowerCase();
            const firstName = card.querySelector('.profil-firstname').textContent.toLowerCase();
            const roles = card.querySelector('.profil-roles').textContent.toLowerCase();

            const nameMatch = lastName.includes(nameValue);
            const firstNameMatch = firstName.includes(firstNameValue);
            const groupMatch = groupValue === '' || roles.includes(groupValue);

            if (nameMatch && firstNameMatch && groupMatch) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    nameFilter.addEventListener('input', filterUsers);
    firstNameFilter.addEventListener('input', filterUsers);
    groupFilter.addEventListener('change', filterUsers);
});
// Filtrage des utilisateurs FIN


//BACKOFFICE-ADD.php TRIE PAR ORDRE CROISSANT/DÉCROISSANT
document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('table');
    const headers = table.querySelectorAll('th[data-sort]');
    let sortDirection = 1;

    headers.forEach(header => {
        header.addEventListener('click', function() {
            const sortAttribute = this.getAttribute('data-sort');
            const rowsArray = Array.from(table.querySelectorAll('tbody tr'));

            rowsArray.sort((a, b) => {
                const aText = a.querySelector(`td:nth-child(${Array.from(headers).indexOf(header) + 2})`).textContent.trim();
                const bText = b.querySelector(`td:nth-child(${Array.from(headers).indexOf(header) + 2})`).textContent.trim();

                if (!isNaN(parseFloat(aText)) && !isNaN(parseFloat(bText))) {
                    return (parseFloat(aText) - parseFloat(bText)) * sortDirection;
                } else {
                    return aText.localeCompare(bText) * sortDirection;
                }
            });

            sortDirection *= -1;
            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';
            rowsArray.forEach(row => tbody.appendChild(row));
        });
    });
});

function cancelOrder(orderId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
        fetch('update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ order_id: orderId, action: 'cancel' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const card = document.querySelector('.card2[data-order-id="' + orderId + '"]');
                const progressBar = card.querySelector('.progress-bar');
                progressBar.style.width = '0%';
                progressBar.setAttribute('aria-valuenow', '0');
                progressBar.innerText = 'Annulée';

                // Afficher l'image du tampon "cancel"
                const stampImage = card.querySelector('.stamp-cancel');
                stampImage.style.display = 'block';
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

function confirmDelete(orderId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')) {
        window.location.href = '../tools/delete_orders.php?id=' + orderId;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const progressButtons = document.querySelectorAll('.btn-progress');

    progressButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            fetch('update_order_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ order_id: orderId, action: 'progress' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour la barre de progression et le texte du statut
                    const card = document.querySelector('.card2[data-order-id="' + orderId + '"]');
                    const progressBar = card.querySelector('.progress-bar');
                    let currentProgress = parseInt(progressBar.getAttribute('aria-valuenow'));
                    let nextProgress = currentProgress + 25;

                    if (nextProgress > 100) {
                        nextProgress = 100;
                    }

                    progressBar.style.width = nextProgress + '%';
                    progressBar.setAttribute('aria-valuenow', nextProgress);

                    let newStatusText = '';
                    switch (nextProgress) {
                        case 25:
                            newStatusText = 'En attente';
                            break;
                        case 50:
                            newStatusText = 'En cours de traitement';
                            break;
                        case 75:
                            newStatusText = 'Expédiée';
                            break;
                        case 100:
                            newStatusText = 'Livrée';
                            break;
                        case 0:
                            newStatusText = 'Annulée';
                            break;
                        default:
                            newStatusText = progressBar.innerText;
                    }

                    progressBar.innerText = newStatusText;

                    // Cacher l'image du tampon "cancel" si la commande n'est plus annulée
                    const stampImage = card.querySelector('.stamp-cancel');
                    if (nextProgress !== 0) {
                        stampImage.style.display = 'none';
                    }
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});
