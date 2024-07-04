
<nav>
<div class="menuButtonsContainer">
    <span id="menuBurgerIcon">☰</span>
    
    

        <ul class="menu" id="userMenu">
        <?php if(isset($_SESSION['user'])){
            echo'
            <li>
                <a href="../pages/profil.php">
                    <img class="userMenuIcon" src="../img/illustration/account.png">
                </a>
            </li> ';

            if (strpos($_SESSION['user']['group'], 'admin') !== false || strpos($_SESSION['user']['group'], 'sub') !== false) {
                echo '
                <li>
                <a href="../pages/backoffice-general.php">
                    <img class="userMenuIcon" src="../img/illustration/settings_back.png">
                </a>
                </li>';
            }     
            require_once("connect.php");
            $sql = "SELECT * FROM messages WHERE receiver_user_id = :user_id AND message_read = 0";
            $query = $db->prepare($sql);            
            $query->bindValue(':user_id', $_SESSION['user']['id']);
            $query->execute();
            $unread = $query->fetch(PDO::FETCH_ASSOC);
            echo '
            <li>
                <a href="../pages/messagerie.php">';
                if($unread){echo '<img class="userMenuIcon" src="../img/illustration/message_unread.png">';}
                else{echo '<img class="userMenuIcon" src="../img/illustration/message.png">';}
                    
                    
                    echo'
                </a>
            </li>
            <li>
                <a href="../tools/logout.php">
                    <img class="userMenuIcon" src="../img/illustration/disconnect.png">
                </a>
            </li>';
        }
        else{
            echo'
            <li>
                <a href="../tools/login.php">
                    <img class="userMenuIcon" src="../img/illustration/account.png">
                </a>
            </li>';
        }
        ?>


            <li>
                <a href="../pages/cart.php">
                    <img class="userMenuIcon" src="../img/illustration/cart.png">
                </a>
            </li>
        </ul>
</div>


    <ul class="menu hideMenuBurger" id="menuBurger">
        <li>
            <a href="#">Robes</a>
        </li>
        <li>
            <a href="#">T-shirts</a>
        </li>
        <li>
            <a href="#">Pantalons</a>
        </li>
        <li>
            <a href="#">Vestes</a>
        </li>
        <li>
            <a href="#">Pulls</a>
        </li>
        <li>
            <a href="#">Hommes</a>
        </li>
    </ul>

</nav>
