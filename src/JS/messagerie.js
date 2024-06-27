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