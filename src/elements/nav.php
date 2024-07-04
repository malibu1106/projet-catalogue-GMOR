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

            echo '
            <li>
                <a href="../pages/messagerie.php">
                    <img class="userMenuIcon" src="../img/illustration/message.png">
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
            <a href="pages/produits.php?category=Robe">Robes</a>
        </li>
        <li>
            <a href="pages/produits.php?category=T-shirt">T-shirts</a>
        </li>
        <li>
            <a href="pages/produits.php?category=Pantalon">Pantalons</a>
        </li>
        <li>
            <a href="pages/produits.php?category=Veste">Vestes</a>
        </li>
        <li>
            <a href="pages/produits.php?category=pull">Pulls</a>
        </li>
    </ul>

</nav>
