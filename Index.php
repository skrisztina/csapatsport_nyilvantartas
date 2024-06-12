<?php
session_start();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <link rel="stylesheet" type="text/css" href="css/media.css">
    <link rel="icon" href="images/img.jpg">
    <title>Csapatsport</title>
    <meta name="author" content="Sánta Krisztina Csilla">
    <meta name="description" content="Csapatsport adatbázis">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<header>
    <nav>
        <ul class="navigation">
            <li><a href="Index.php" target="_self">Nyitó oldal</a></li>
            <li><a href="Csapatok.php" target="_self">Csapatok</a></li>
            <li><a href="Tagok.php" target="_self">Csapattagok</a></li>
            <li><a href="Merkozesek.php" target="_self">Mérkőzések</a></li>
            <?php if(!(isset($_SESSION["user"]))) {?>
            <li><a href="Bejelentkezes.php" target="_self">Bejelentkezés</a></li>
            <li><a href="Regisztracio.php" target="_self">Regisztráció</a></li>
            <?php } else { ?>
            <li><a href="Kijeletkezes.php" target="_self">Kijelentkezés</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>
<h2>Csapatsport</h2>
<div>
    <?php if(isset($_SESSION["user"])){ ?>
    <p>Üdvözlünk <?php echo $_SESSION["user"]["nev"]; ?>!</p>
    <?php } ?>
    <p>Ezt az alkalmazást azért hoztuk létre, hogy a térség csapatsport iránt érdeklődő lakosai tájékozódni tudjanak az aktuális csapatokról, játékosokról, valamint a mérkőzésekről. </p>
    <br> <p>További információkért válasszon a menüpontok közül.</p>
</div>
</body>
</html>