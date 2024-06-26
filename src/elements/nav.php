<nav>
<div class="menuButtonsContainer">
    <span id="menuBurgerIcon">☰</span>
    <span id="debugButton" style="font-size:1em">Debug</span>
    

        <ul class="menu" id="userMenu">
        <?php if(isset($_SESSION['user'])){
            echo'
            <li>
                <a href="../pages/profil.php">
                    <img class="userMenuIcon" src="../img/illustration/account.png">
                </a>
            </li>      

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
                <a href="tools/login.php">
                    <img class="userMenuIcon" src="../img/illustration/account.png">
                </a>
            </li>';
        }
        ?>


            <li>
                <a href="pages/cart.php">
                    <img class="userMenuIcon" src="../img/illustration/cart.png">
                </a>
            </li>
        </ul>
</div>


    <ul class="menu hideMenuBurger" id="menuBurger">
        <li>
            <a href="#">Lien 1</a>
        </li>
        <li>
            <a href="#">Lien 2</a>
        </li>
        <li>
            <a href="#">Lien 3</a>
        </li>
        <li>
            <a href="#">Lien 4</a>
        </li>
        <li>
            <a href="#">Lien 5</a>
        </li>
        <li>
            <a href="#">Lien 6</a>
        </li>
    </ul>

</nav>
