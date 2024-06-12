<?php
session_start();
include_once "kezelofgvk.php";

$errors = [];
$win = false;

if(isset($_POST["felvisz"])){
    $csapatid = $_POST["csapatid"];
    $nev = $_POST["nev"];
    $varos = $_POST["varos"];
    $alapitasev = $_POST["alapitasev"];

    if(isset($csapatid) && isset($nev) && isset($varos) && isset($alapitasev)){
        $tv_csapatid = (int)$csapatid;
        $tv_alapitasev = (int)$alapitasev;
        $tv_nev = htmlspecialchars($nev);
        $tv_varos = htmlspecialchars($varos);

        $siker = csapat_felvitel($tv_csapatid, $tv_nev, $tv_varos, $tv_alapitasev);

        if($siker == false){
            $errors[] = "Az új csapat felvitele sikertelen volt.";
        } else {
            $win = true;
            header("Location: Csapatok.php");
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
            <?php if(!(isset($_SESSION["user"]))) {?>
            <li><a href="Bejelentkezes.php" target="_self">Bejelentkezés</a></li>
            <li><a href="Regisztracio.php" target="_self">Regisztráció</a></li>
            <?php } else { ?>
            <li><a href="Kijeletkezes.php" target="_self">Kijelentkezés</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>
<h1>Csapatok</h1>
<div>
    <table>
        <tr>
            <th>Csapat azonosító:</th>
            <th>Csapatnév:</th>
            <th>Csapat városa:</th>
            <th>Alapítási év:</th>
            <?php if(isset($_SESSION["user"])){ ?>
            <th></th>
            <?php } ?>
        </tr>
        <?php
        $csapatok = csapat_leker();
        while($csapat = mysqli_fetch_assoc($csapatok)){
            echo '<form action="csapatmodositas.php" method="post">';
            echo '<tr>';
            echo '<td>'. $csapat["csapatid"] . '</td>';
            echo '<td>'. $csapat["nev"] .'</td>';
            echo '<td>'. $csapat["varos"] . '</td>';
            echo '<td>'. $csapat["alapitaseve"]. '</td>';
            if(isset($_SESSION["user"])) {
                echo '<td><input type="hidden" name="csapat" value="' . $csapat["csapatid"] . '"><input type="submit" name="szerkeszt" value="Szerkeszt"></td>';
            }
            echo '</tr>';
            echo '</form>';
        }
        mysqli_free_result($csapatok);
        ?>
    </table><br>
</div>
<h2>Csapatok győzelmeinek száma</h2>
<div>
    <table>
        <tr>
            <th>Csapat:</th>
            <th>Győzelmek száma:</th>
        </tr>
        <?php
        $gyozelmek = csapat_gyozelmek();
        while ($gyozelem = mysqli_fetch_assoc($gyozelmek)){
            echo '<tr>';
            echo '<td>'. $gyozelem["nev"] .'</td>';
            echo '<td>'. $gyozelem["db"] .'</td>';
            echo '</tr>';
        }
        mysqli_free_result($gyozelmek);
        ?>
    </table>
</div>
<h2>A legrégebben alapított csapat</h2>
<div>
<?php $legregi = legregebbi_csapat();
$legregebbi = mysqli_fetch_assoc($legregi);
echo '<h3>'. $legregebbi["nev"] .':</h3>';
echo '<p>(Alapítva: '. $legregebbi["alapitaseve"] .'-ben)</p><br>';
?>
    <table>
        <tr>
            <th>Név:</th>
            <th>Poszt:</th>
            <th>Születési dátum:</th>
            <th>Állampolgárság:</th>
        </tr>
        <?php
        $tagok = legregebbi_csapat_tagok();
        while ($tag = mysqli_fetch_assoc($tagok)){
            echo '<tr>';
            echo '<td>'. $tag["nev"] .'</td>';
            echo '<td>'. $tag["post"] .'</td>';
            echo '<td>'. $tag["szul"] .'</td>';
            echo '<td>'. $tag["nat"] .'</td>';
            echo '</tr>';
        }
        mysqli_free_result($tagok);
        ?>
    </table><br>
</div>
<h2>Csapat állampolgárság szerint</h2>
<div>
    <p>Válassza ki a listából annak a csapat nevét, amelyiknek az állampolgárság szerinti csoportosítására kíváncsi. </p><br>
    <form method="POST" action="Csapatok.php">
        <select name="valasztottcsp">
            <?php
            $letezocsp = csapat_leker();
            if(mysqli_num_rows($letezocsp) > 0){
                while($lista = mysqli_fetch_assoc($letezocsp)){ ?>
                    <option value="<?php echo $lista["csapatid"]; ?>" <?php if(isset($_POST["nationality"]) && $lista["csapatid"] == $_POST["valasztottcsp"]){echo "selected";} ?>><?php echo $lista["nev"]; ?></option>';
                <?php }
            } else {
                echo '<option value="">--Nincs választható csapat--</option>';
            }
            mysqli_free_result($letezocsp);
            ?>
        </select><br><br>
        <input type="submit" name="nationality" value="Megtekint">
    </form><br>
    <?php if(isset($_POST["nationality"])){
    $csapatidnat = $_POST["valasztottcsp"];

    if(isset($csapatidnat)){
    $v_csapatidnat = htmlspecialchars($csapatidnat);
    $nemzetszerint = csapat_nat($v_csapatidnat);

    if($nemzetszerint == false){
        error_log("Hiba a nemzet szerinti lekérdezésnél.");
    }
    }
        $natcsapat = csapatot_leker($csapatidnat); ?>
        <table>
            <caption><?php echo $natcsapat["nev"]; ?></caption>
            <tr>
                <th>Állampolgárság:</th>
                <th>Sportoló(db):</th>
            </tr>
            <?php
            $nemzet = csapat_nat($csapatidnat);
            while ($natsor = mysqli_fetch_assoc($nemzetszerint)){
                echo '<tr>';
                echo '<td>'. $natsor["allampolgarsag"] .'</td>';
                echo '<td>'. $natsor["szam"] .'</td>';
                echo '</tr>';
            }
            mysqli_free_result($nemzetszerint);
            ?>
        </table><br>
    <?php } ?>
</div>
<?php if(isset($_SESSION["user"])){ ?>
<h2>Új csapat felvitele</h2>
    <div>
        <form action="Csapatok.php" method="post">
            <label for="cspid">Csapat azonosító: (5 számjegyű szám)</label><br>
            <input id="cspid" type="text" name="csapatid" size="50"><br><br>
            <label for="cspnev">Csapat neve: </label><br>
            <input id="cspnev" type="text" name="nev" size="50"><br><br>
            <label for="cspvaros">Csapat városa:</label><br>
            <input id="cspvaros" type="text" name="varos" size="50"><br><br>
            <label for="cspev">Alapítási éve:</label><br>
            <input id="cspev" type="text" name="alapitasev" size="50"><br><br>
            <input type="submit" name="felvisz" value="Felvitel">
        </form>
    </div><br><br>
<?php }?>
<?php
if($win == false){
    foreach($errors as $err) {
        echo "<p>" . $err . "</p>";}
}
?>

</body>
</html>
