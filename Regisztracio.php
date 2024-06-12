<?php
    session_start();

    include_once ("kezelofgvk.php");

    $errors = [];
    $win = false;

    if(isset($_POST["register"])){
        $felhnev = $_POST["felhasznalonev"];
        $nev = $_POST["nev"];
        $jelszo1 = $_POST["jelszo1"];
        $jelszo2 = $_POST["jelszo2"];

        if(isset($felhnev) && isset($nev) && isset($jelszo1) && isset($jelszo2)){
            if(strlen($felhnev) > 100){
                $errors[] = "A felhasználónév túl hosszú!";
            }
            if(strlen($nev) > 100 ){
                $errors[] = "A név túl hosszú!";
            }
            if($jelszo1 == $jelszo2 && strlen($jelszo1) <= 20 ){
                $jelszo = password_hash($jelszo1, PASSWORD_DEFAULT);
            } elseif ($jelszo1 != $jelszo2){
                $errors[] = "A két jelszó nem egyezik meg!";
            } else {
                $errors[] = "A jelszó túl hosszú!";
            }
            $tv_felhnev = htmlspecialchars($felhnev);
            $tv_nev = htmlspecialchars($nev);



            $siker = regisztracio($tv_felhnev, $tv_nev, $jelszo);

            if($siker == false){
                $errors[] = "A regisztáció nem volt sikeres.";
            } else {
                $win = true;
                header("Location: Bejelentkezes.php");
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
<h1>Regisztráció</h1>
<div>
    <form method="POST" action="Regisztracio.php" enctype="application/x-www-form-urlencoded" autocomplete="off" accept-charset="utf-8">
        <label for="name">Név:    (max. 100 karakter hosszú)</label><br>
        <input id="name" type="text" name="nev" size="50" required><br><br>
        <label for="username">Felhasználónév:    (max. 100 karakter hosszú)</label><br>
        <input id="username" type="text" name="felhasznalonev" size="50" required><br><br>
        <label for="jelszo1">Jelszó:    (max. 20 karakter hosszú)</label><br>
        <input id="jelszo1" type="password" name="jelszo1" size="50" required><br><br>
        <label for="jelszo2">Jelszó ismét:</label><br>
        <input id="jelszo2" type="password" name="jelszo2" size="50" required><br><br>
        <input type="submit" name="register" value="Regisztrálok">
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
