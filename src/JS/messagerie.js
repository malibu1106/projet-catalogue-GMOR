document.addEventListener("DOMContentLoaded", function () {
    // Récupère l'élément représentant l'ancre du dernier message
    let dernierMessage = document.getElementById("ancre_dernier_message");

    // Vérifie si l'élément existe avant de faire défiler
    if (dernierMessage) {
        // Fait défiler automatiquement jusqu'à l'élément du dernier message
        dernierMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });
    }

    // Variables pour détecter l'inactivité et le scroll
    let timeout;
    let isScrollingUp = false;

    // Fonction pour rafraîchir la page
    function refreshPage() {
        if (!isScrollingUp && document.getElementById('conversation_message').innerText.trim() === '') {
            location.reload();
        }
    }

    // Réinitialise le timer d'inactivité
    function resetTimer() {
        clearTimeout(timeout);
        timeout = setTimeout(refreshPage, 10000);
    }

    // Événements pour détecter l'activité de l'utilisateur
    document.addEventListener('mousemove', resetTimer);
    document.addEventListener('keydown', resetTimer);
    document.addEventListener('scroll', function () {
        isScrollingUp = window.scrollY < window.innerHeight;
        resetTimer();
    });

    // Initialise le timer d'inactivité
    resetTimer();
});

// Code existant pour gérer la liste d'utilisateurs et les messages
let userList = document.getElementById('user_list');
let newMessageInputZone = document.getElementById('new_conversation_message');
let receiverIdInput = document.getElementById('new_receiver_id');

if (userList) {
    userList.addEventListener("change", displayInputText);
}

function displayInputText() {
    newMessageInputZone.style.display = "block";
    receiverIdInput.value = userList.value;

    // Récupérer le texte de l'option sélectionnée
    var selectedOptionText = userList.options[userList.selectedIndex].text;

    // Mettre à jour le placeholder avec le nom et prénom de la personne sélectionnée
    newMessageInputZone.placeholder = "Envoyer un message à " + selectedOptionText;
}
