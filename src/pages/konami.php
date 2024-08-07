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
    <style>
        body {
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-image: url(/img/illustration/JP.jpg);
    font-family: Arial, sans-serif;
}

.chat-container {
    background-color: #00000000; /* Rose pastel */
    width: 80%;
    height: 80%;
    max-width: 600px;
    max-height: 600px;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.activate-button {
    background-color: #28a745; /* Vert */
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    cursor: pointer;
    margin-bottom: 10px;
}

.activate-button:hover {
    background-color: #218838;
}

.chat-content {
    display: flex;
    flex-direction: column;
    width: 90%;
    height: 90%;
}

.video {
    flex: 2; /* Deux tiers de la hauteur */
    background-color: #ffffff;
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

.video video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
}

.greg-vid{
    background-color: #c8233300;
    height: 495px;
    width: 280px;
    position: absolute;
    left: 100px;
    border-radius: 10px;
}

.chat-input-container {
    flex: 1; /* Un tiers de la hauteur */
    display: flex;
    align-items: center;
}

.password-input {
    flex: 1;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-right: 10px;
}

.send-button {
    background-color: #dc3545; /* Rouge */
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    cursor: pointer;
}

.send-button:hover {
    background-color: #c82333;
}
.red-text {
    color: red;
}

.chat-messages-container {
    height: 200px; /* Hauteur fixe de la boîte de dialogue */
    overflow-y: auto; /* Scroll vertical si nécessaire */
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 10px;
}

.chat-messages-inner {
    overflow-y: auto; /* Scroll vertical dans la boîte de dialogue */
    max-height: 100%; /* Hauteur maximale de la boîte de dialogue */
}

.message {
    margin-bottom: 5px;
    padding: 5px 10px;
    border-radius: 5px;
}

.message.sent {
    background-color: #d4edda; /* Vert clair */
}

.message.received {
    background-color: #f2f2f2;
}

.message.received.error {
    color: red;
    font-weight: bold;
}

.error-container {
    position: fixed;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    background-color: rgba(255, 0, 0, 0.8);
    color: white;
    padding: 10px;
    border-radius: 5px;
    display: none; /* Masquer par défaut */
}

.error-message {
    font-size: 14px;
    text-align: center;
    font-weight: bold;
    color: rgb(255, 255, 255); /* Couleur verte */
}

.flood-container {
    width: 400px;
    height: 80%;
    background-color: #161616;
    overflow: hidden; /* Masquer le débordement */
    border-radius: 10px;
}

.flex-cont {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    height: 100vh;
    width: 100%;
}


    </style>
</head>
<body>
    <div class="flex-cont">
        <div class="flood-container">
            
        </div>

        <div class="chat-container">
            <button class="activate-button" onclick="activateChat()">DETRUIRE LA BASE DE DONNÉES</button>
            <div class="chat-content">
                <div class="video" id="video-mdp">
                    <!-- Votre vidéo ici -->
                </div>
                <div class="chat-input-container">
                    <input type="password" id="passwordInput" class="password-input" placeholder="Entrez le mot de passe..." onkeypress="checkEnter(event)">
                    <button class="send-button" onclick="checkPassword()">Envoyer</button>
                </div>
            </div>
            <div class="greg-vid">
                
            </div>
            <div id="errorContainer" class="error-container">
                <div id="errorMessage" class="error-message">YOU DIDN'T SAY THE MAGIC WORD!</div>
            </div>
        </div>
    </div>

    <script>
        let errorSequenceActivated = false;

        function activateChat() {
            

            const chatContainer = document.querySelector('.chat-container');
            chatContainer.style.height = 'auto';

            const videoContainer = document.getElementById('video-mdp');
            videoContainer.innerHTML = '<video src="../img/illustration/please.mp4" autoplay loop></video>';

            setTimeout(activateErrorSequence, 11000);
        }

        function activateErrorSequence() {
            errorSequenceActivated = true;
            showErrorMessage();
            displayErrorMessageFlood();
            showGregVideo();
        }

        function checkPassword() {
            const passwordInput = document.getElementById('passwordInput');
            const password = passwordInput.value.trim().toLowerCase();

            if (password === 's\'il te plait') {
                document.getElementById('popup').style.display = 'block';
                passwordInput.value = '';
                hideErrorMessage();
                hideGregVideo();
                clearFloodContainer();
                
                // Afficher le popup

                const chatMessagesInner = document.getElementById('chatMessagesInner');
                const messageElement = document.createElement('div');
                messageElement.className = 'message received';
                messageElement.textContent = 'Mot de passe correct ! Vidéo activée.';
                chatMessagesInner.appendChild(messageElement);

                chatMessagesInner.scrollTop = chatMessagesInner.scrollHeight;
                errorSequenceActivated = false;
            } else if (!errorSequenceActivated) {
                activateErrorSequence();
            }
        }
        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }

        function displayErrorMessageFlood() {
            const floodContainer = document.querySelector('.flood-container');
            let count = 0;
            const intervalId = setInterval(function() {
                if (count < 200) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.textContent = 'YOU DIDN\'T SAY THE MAGIC WORD!';
                    
                    if (count % 3 === 0) {
                        errorDiv.style.color = 'red';
                    }
                    
                    floodContainer.appendChild(errorDiv);

                    count++;
                } else {
                    clearInterval(intervalId);
                }
            }, 50);
        }

        function clearFloodContainer() {
            const floodContainer = document.querySelector('.flood-container');
            floodContainer.innerHTML = '';
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

            const chatMessagesInner = document.getElementById('chatMessagesInner');
            chatMessagesInner.innerHTML = '';
        }

        function showGregVideo() {
            const gregVideoContainer = document.querySelector('.greg-vid');
            gregVideoContainer.innerHTML = '<video src="/img/illustration/4512.mp4" autoplay loop></video>';
            gregVideoContainer.style.display = 'block';
        }

        function hideGregVideo() {
            const gregVideoContainer = document.querySelector('.greg-vid');
            gregVideoContainer.innerHTML = '';
            gregVideoContainer.style.display = 'none';
        }

        function sendMessage() {
            const chatMessages = document.getElementById('chatMessages');
            const chatInput = document.getElementById('chatInput');
            const message = chatInput.value.trim();

            if (message !== '') {
                const messageElement = document.createElement('div');
                messageElement.className = 'message sent';
                messageElement.textContent = message;

                chatMessages.appendChild(messageElement);

                chatInput.value = '';

                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }

        function checkEnter(event) {
            if (event.key === 'Enter') {
                checkPassword();
            }
        }
    </script>
    <div id="popup" class="popup">
    <div class="popup-content">
        <div class="popup-header">
            <span class="popup-title">Alerte</span>
            <span class="close-button" onclick="closePopup()">&times;</span>
        </div>
        <div class="popup-body">
            <p>BASE DE DONNÉES DÉTRUITE</p>
        </div>
    </div>
</div>
</body>
</html>


