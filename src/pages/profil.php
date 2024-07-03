<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../CSS/backoffice-style.css">
    <title>Profile</title>
    <style>
        /* STYLE CARTE */
        .backoffice-profil {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(#777,#222);
}

.profil-card {
    position: relative;
    width: 340px;
    height: 460px;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
}
.profil-circle{
    position: absolute;
    top: -190px;
    left: 50%;
    transform: translateX(-50%);
    width: 500px;
    height: 500px;
    background: #333;
    clip-path: circle();
}
.profil-circle::before {
    content: '';
    position: absolute;
    top: -8px;
    left: -16px;
    width: 100%;
    height: 100%;
    background: transparent;
    box-shadow: 0 0 0 20px rgba(255, 0, 0, 0.5);
    border-radius: 50%;
    z-index: 3;
    pointer-events: none;
}
.profil-circle .imgBx {
    position: absolute;
    left: 50%;
    bottom: 0;
    transform: translateX(-50%);
    width: 340px;
    height: 310px;
    background: rgb(191, 204, 13);
}
.profil-circle .imgBx img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.5s;
    transform-origin: top;
}
.profil-circle .imgBx:hover img{
    transform: scale(1.5);
}
.profil-content {
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 150px;
    padding: 20px 30px;
    font-family: "Poppins", sans-serif;
}
.fa-linkedin {
    background: #0077b5;
    color: #fff;
    padding: 2px 4px;
    border-radius: 2px;
}
.profil-name {
    font-weight: bold;
    font-size: 1.4em;
    color: #333;
    margin-top: 7px;
}
.fa-envelope-open{
    color: rgb(184, 0, 0);
}
.profil-roles {
    font-size: 14px;
    font-weight: 400;
    color: #e91e63;
}
.mailIcon{
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.btn-modif-role {
    background-color: red;
    border: none;
    border-radius: 10px;
    width: 100px;
    height: 20px;
    font-size: 14px;
}
    </style>
</head>
<?php require_once('../elements/header.php'); ?>
<body>

    <main class="backoffice-profil">
        <div class="profil-card">
            <div class="profil-circle">
                <div class="imgBx">
                    <img src="/img/temporaire/No-trep.png" alt="">
                </div>
            </div>
            <div class="profil-content">
                <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                <h3 class="profil-name">Blue Marry</h3>
                <div class="mailIcon">
                    <h4 class="profil-roles">Admin Comptable</h4>
                    <a href="#">
                    <i class="fa fa-envelope-open" aria-hidden="true"></i>
                    </a>
                </div>
                <div>
                    <button class="btn-modif-role">Modifier role</button>
                </div>
            </div>
        </div>
    </main>

</body>
    <script type="text/javascript" src="../JS/script.js" defer></script>
    <?php require_once('../elements/footer.php'); ?>
</html>
