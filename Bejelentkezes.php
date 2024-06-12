<?php

session_start();

include_once ("kezelofgvk.php");

$errors = [];
$win = false;

if (isset($_POST["belep"])){
    $felhnev = $_POST["felhasznalonev"];
    $jelszo = $_POST["jelszo"];

    if(isset($felhnev) && isset($jelszo)){
        $tv_felhnev = htmlspecialchars($felhnev);
        $user = felhasznalo_leker($tv_felhnev);

        if(password_verify($jelszo, $user["jelszo"] )){
            $_SESSION["user"] = $user;
            $win = true;
            header("Location: Index.php");
        } else {
            $errors[] = "Sikertelen bejelentkezés: Hibás felhasználónév vagy jelszó!";
        }
    } else {
        $errors[] = "Üres mező!";
    }
}

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
            <li><a href="Bejelentkezes.php" target="_self">Bejelentkezés</a></li>
            <li><a href="Regisztracio.php" target="_self">Regisztráció</a></li>
        </ul>
    </nav>
</header>
<h1>Bejelentkezés</h1>
<div>
    <form method="POST" action="" enctype="application/x-www-form-urlencoded" autocomplete="off">
        <label for="username">Felhasználónév:</label><br>
        <input id="username" type="text" name="felhasznalonev" size="50" required><br><br>
        <label for="jelszo">Jelszó:</label><br>
        <input id="jelszo" type="password" name="jelszo" size="50" required><br><br>
        <input type="submit" value="Bejelentkezés" name="belep"><br>
    </form>
    <?php
    if($win == false){
        foreach($errors as $err) {
            echo "<p>" . $err . "</p>";}
    }
    ?>
</div>


</body>
</html>
