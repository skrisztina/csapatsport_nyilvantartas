<?php
session_start();
include_once "kezelofgvk.php";

if(isset($_POST["szerkeszt"])){
    $csapatid = $_POST["csapat"];
    $csapatid = (int)$csapatid;
    $csapatid = htmlspecialchars($csapatid);
    $csapat = csapatot_leker($csapatid);
}

if(isset($_POST["tagleker"])){
    $csapatid = $_POST["tagok"];
    $csapatid = htmlspecialchars($csapatid);
    $csapat = csapatot_leker($csapatid);
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
            <?php if(!(isset($_SESSION["user"]))) {?>
                <li><a href="Bejelentkezes.php" target="_self">Bejelentkezés</a></li>
                <li><a href="Regisztracio.php" target="_self">Regisztráció</a></li>
            <?php } else { ?>
                <li><a href="Kijeletkezes.php" target="_self">Kijelentkezés</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>
<?php if (isset($_SESSION["user"]) && isset($_POST["szerkeszt"])){ ?>
<h1>Csapat módosítása:</h1>
<div>
    <form action="cspmodositas.php" method="post">
        <label for="cspid">Csapat azonosító:</label><br>
        <input id="cspid" type="text" name="csapatid" size="30" value="<?php echo $csapat["csapatid"]; ?>" disabled><br><br>
        <label for="cspnev">Csapat neve:</label><br>
        <input id="cspnev" type="text" name="csapatnev" size="30" value="<?php echo $csapat["nev"]; ?>"><br><br>
        <label for="cspvaros">Csapat városa:</label><br>
        <input id="cspvaros" type="text" name="csapatvaros" size="30" value="<?php echo $csapat["varos"]; ?>"><br><br>
        <label for="cspev">Alapítás éve: </label><br>
        <input id="cspev" type="text" name="csapatev" size="30" value="<?php echo $csapat["ev"]; ?>"><br><br>
        <input type="hidden" name="check" value="<?php echo $csapat["csapatid"]; ?>">
        <input type="submit" name="modosit" value="Módosít"><br><br>
    </form>
    <br>
    <form action="cspmodositas.php" method="post">
        <input type="submit" name="torles" value="Csapat törlése">
        <input type="hidden" name="id" value="<?php echo $csapat["csapatid"]; ?>">
    </form><br><br>
</div>
<?php } ?>
<?php if (isset($_POST["tagleker"])){ ?>
    <h2><?php echo $csapat["nev"]; ?></h2>
    <div>
        <table>
            <tr>
                <th>Sportolói azonosító:</th>
                <th>Név:</th>
                <th>Poszt:</th>
                <th>Állampolgárság:</th>
                <th>Születési dátum:</th>
                <?php if(isset($_SESSION["user"])){ ?>
                <th></th>
                <?php } ?>
            </tr>
            <?php
            $tag = tagok_leker($csapatid);
            while($sor = mysqli_fetch_assoc($tag)){
                echo '<form action="tagtorles.php" method="post">';
                echo '<tr>';
                echo '<td>'. $sor["sportoloid"] .'</td>';
                echo '<td>'. $sor["nev"] .'</td>';
                echo '<td>'. $sor["poszt"] .'</td>';
                echo '<td>'. $sor["allampolgarsag"] .'</td>';
                echo '<td>'. $sor["szuletesidatum"] .'</td>';
                if(isset($_SESSION["user"])){
                    echo '<td><input type="hidden" name="torlendo" value="'. $sor["sportoloid"] .'"><input type="submit" name="torol" value="Törlés"></td>';
                }
                echo '</tr>';
                echo '</form>';
            }
            ?>
        </table>
    </div>
<?php } ?>

</body>
</html>
