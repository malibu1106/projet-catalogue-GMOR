// Attend que le document soit entièrement chargé
document.addEventListener("DOMContentLoaded", function () {
    // Récupère l'élément représentant l'ancre du dernier message
    let dernierMessage = document.getElementById("ancre_dernier_message");

    // Vérifie si l'élément existe avant de faire défiler
    if (dernierMessage) {
        // Fait défiler automatiquement jusqu'à l'élément du dernier message
        dernierMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });
    }
});

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