<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/konami.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>secrets-page</title>
</head>
<body>
    <div class="flex-cont">
        <div class="flood-container">
            
        </div>

        <div class="chat-container">
            <button class="activate-button" onclick="activateChat()">ACTIVER</button>
            <div class="chat-content">
                <div class="video" id="video-mdp">
                    <!-- Votre vidéo ici -->
                </div>
                <div class="chat-input-container">

                    <input type="password" id="passwordInput" class="password-input" placeholder="Entrez le mot de passe..." onkeypress="checkEnter(event)">
                    <button class="send-button" onclick="checkPassword()">Envoyer</button>
                </div>
            </div>
            <div id="errorContainer" class="error-container">
                <div id="errorMessage" class="error-message">YOU DIDN'T SAY THE MAGIC WORD!</div>
            </div>
        </div>
    </div>

    <script>
        function activateChat() {
            const activateButton = document.querySelector('.activate-button');
            activateButton.style.display = 'none'; // Cacher le bouton ACTIVER

            const chatContainer = document.querySelector('.chat-container');
            chatContainer.style.height = 'auto'; // Ajuster la hauteur de la fenêtre de chat

            const videoContainer = document.getElementById('video-mdp');
            videoContainer.innerHTML = '<video src="../img/illustration/please.mp4" autoplay loop></video>';
        }

        function checkPassword() {
            const passwordInput = document.getElementById('passwordInput');
            const password = passwordInput.value.trim().toLowerCase(); // Convertir en minuscules

            if (password === 's\'il te plait') {
                // Afficher la vidéo si le mot de passe est correct
                activateVideo();

                // Effacer l'entrée du mot de passe
                passwordInput.value = '';

                // Masquer le message d'erreur
                hideErrorMessage();
                
                // Afficher un message de confirmation dans le chat
                const chatMessagesInner = document.getElementById('chatMessagesInner');
                const messageElement = document.createElement('div');
                messageElement.className = 'message received';
                messageElement.textContent = 'Mot de passe correct ! Vidéo activée.';
                chatMessagesInner.appendChild(messageElement);

                // Faire défiler vers le bas du chat
                chatMessagesInner.scrollTop = chatMessagesInner.scrollHeight;
            } else {
                // Afficher le message d'erreur à droite
                showErrorMessage();

                // Afficher un message d'erreur dans la boîte de dialogue des messages
                displayErrorMessageFlood();
            }
        }

        function displayErrorMessageFlood() {
            const floodContainer = document.querySelector('.flood-container');
            let count = 0;
            const intervalId = setInterval(function() {
                if (count < 200) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message'; // Utiliser la classe pour le style vert
                    errorDiv.textContent = 'YOU DIDN\'T SAY THE MAGIC WORD!';
                    floodContainer.appendChild(errorDiv);

                    count++;
                } else {
                    clearInterval(intervalId); // Arrêter l'intervalle après 200 messages
                    floodContainer.innerHTML = ''; // Effacer tous les enfants
                    hideErrorMessage(); // Masquer le message d'erreur après l'intervalle
                }
            }, 50); // 0.3s (300ms) d'intervalle entre chaque message
        }

        function activateVideo() {
            const videoContainer = document.getElementById('video-mdp');
            videoContainer.innerHTML = '<video src="../img/illustration/please.mp4" autoplay loop></video>';
        }

        function showErrorMessage() {
            const errorContainer = document.getElementById('errorContainer');
            errorContainer.style.display = 'block';
        }

        function hideErrorMessage() {
            const errorContainer = document.getElementById('errorContainer');
            errorContainer.style.display = 'none';

            // Supprimer tous les messages d'erreur de la boîte de dialogue des messages
            const chatMessagesInner = document.getElementById('chatMessagesInner');
            chatMessagesInner.innerHTML = ''; // Effacer tous les enfants
        }

        function sendMessage() {
            const chatMessages = document.getElementById('chatMessages');
            const chatInput = document.getElementById('chatInput');
            const message = chatInput.value.trim();

            if (message !== '') {
                // Créer un nouvel élément de message
                const messageElement = document.createElement('div');
                messageElement.className = 'message sent';
                messageElement.textContent = message;

                // Ajouter le message au chat
                chatMessages.appendChild(messageElement);

                // Effacer l'entrée
                chatInput.value = '';

                // Faire défiler vers le bas du chat
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }

        function checkEnter(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        }
    </script>
</body>
</html>
