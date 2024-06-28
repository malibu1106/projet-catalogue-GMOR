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
let newMessageInputZone = document.getElementById('newMessage');
if (userList) { userList.addEventListener("change", displayInputText); }

function displayInputText() {
    newMessageInputZone.style.display = "block";
    document.getElementById('receiver_id').value = userList.value;

}