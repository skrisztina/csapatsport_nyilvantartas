<?php
session_start();
include_once "kezelofgvk.php";

if (isset($_POST["torles"])){
    $torlendotag = $_POST["tag"];
    if(isset($torlendotag)){
        $tv_torlendo = htmlspecialchars($torlendotag);
        $siker = tag_torles($tv_torlendo);

        if($siker == false){
            error_log("Nem sikerült a csapattag törlése.");
        } else {
            header("Location: Tagok.php");
        }
    } else {
        error_log("Hiányzó adat!");
    }
}

if(isset($_POST["ujsp"])){
    $csapatid = $_POST["valasztottcsp"];
    $spid = $_POST["spid"];
    $nev = $_POST["spnev"];
    $allampolg = $_POST["spnat"];
    $poszt = $_POST["sppost"];
    $szuldat = strtotime($_POST["spszul"]);
    $szuldatum = date('Y-m-d', $szuldat);

    if(isset($csapatid) && isset($spid) && isset($nev) && isset($allampolg) && isset($poszt) && isset($szuldatum)){
        $tv_csapatid = htmlspecialchars($csapatid);
        $tv_spid = htmlspecialchars($spid);
        $tv_nev = htmlspecialchars($nev);
        $tv_allampolg = htmlspecialchars($allampolg);
        $tv_poszt = htmlspecialchars($poszt);

        $result = tag_felvitel($tv_csapatid, $tv_spid, $tv_nev, $tv_poszt, $tv_allampolg, $szuldatum);

        if($result == false){
            error_log("A csapattag felvitele nem sikerült.");
        } else {
            header("Location: Tagok.php");
        }
    } else {
        error_log("Üres mező!");
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
            <?php if(!(isset($_SESSION["user"]))){?>
            <li><a href="Bejelentkezes.php" target="_self">Bejelentkezés</a></li>
            <li><a href="Regisztracio.php" target="_self">Regisztráció</a></li>
            <?php }else{ ?>
            <li><a href="Kijeletkezes.php" target="_self">Kijelentkezés</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>
<h1>Csapattagok</h1>
<div>
    <table>
        <tr>
            <th>Név:</th>
            <th>Sportolói azonosító:</th>
            <th>Csapat:</th>
            <th>Poszt:</th>
            <th>Állampolgárság:</th>
            <th>Születési dátum:</th>
            <?php if(isset($_SESSION["user"])){ ?>
            <th></th>
            <?php } ?>
        </tr>
        <?php
        $tagok = tagok_leker();
        while($sor = mysqli_fetch_assoc($tagok)){
            echo '<form action="Tagok.php" method="post">';
            echo '<tr>';
            echo '<td>'. $sor["spnev"] .'</td>';
            echo '<td>'.  $sor["id"] .'</td>';
            echo '<td>'. $sor["csp"] . '</td>';
            echo '<td>'. $sor["spposzt"] .'</td>';
            echo '<td>'. $sor["spnat"] .'</td>';
            echo '<td>'. $sor["spszul"] .'</td>';
            if (isset($_SESSION["user"])){
                echo '<td><input type="hidden" name="tag" value="'. $sor["id"] .'"><input type="submit" name="torles" value="Törlés"></td>';
            }
            echo '</tr>';
            echo '</form>';
        }
        mysqli_free_result($tagok);
        ?>
    </table>
</div><br>
<h2>Legfiatalabb játékosok</h2>
<div>
    <table>
        <tr>
            <th>Név:</th>
            <th>Poszt:</th>
            <th>Születési Dátum:</th>
            <th>Állampolgárság:</th>
            <th>Csapat:</th>
        </tr>
        <?php
        $fiatalok = legfiatalabb_tagok();
        while ($fiatal = mysqli_fetch_assoc($fiatalok)){
            echo '<tr>';
            echo '<td>'. $fiatal["nev"] .'</td>';
            echo '<td>'. $fiatal["poszt"] .'</td>';
            echo '<td>'. $fiatal["szuletesidatum"] .'</td>';
            echo '<td>'. $fiatal["allampolgarsag"] .'</td>';
            echo '<td>'. $fiatal["csapatnev"] .'</td>';
            echo '</tr>';
        }
        mysqli_free_result($fiatalok);
        ?>
    </table>
</div><br>
<?php if(isset($_SESSION["user"])){ ?>
    <h2>Új csapattag felvitele:</h2>
    <div>
        <form action="Tagok.php" method="post">
            <label for="cspval">Csapat:</label><br>
            <select id="cspval" name="valasztottcsp">
                <?php
                $letezocsp = csapat_leker();
                if(mysqli_num_rows($letezocsp) > 0){
                    while($lista = mysqli_fetch_assoc($letezocsp)){
                        echo '<option value="'.  $lista["csapatid"].'">'. $lista["nev"] . '</option>';
                    }
                } else {
                    echo '<option value="">--Nincs választható csapat--</option>';
                }
                mysqli_free_result($letezocsp);
                ?>
            </select><br><br>
            <label for="csp">Sportolói azonosító:</label><br>
            <input id="cs" type="text" name="spid", size="30"><br><br>
            <label for="name">Név:</label><br>
            <input id="name" type="text" name="spnev" size="30"><br><br>
            <label for="nat">Állampolgárság:</label><br>
            <input id="nat" type="text" name="spnat" size="30"><br><br>
            <label for="date">Születési dátum:</label><br>
            <input id="date" type="date" name="spszul"><br><br>
            <label for="poszt">Poszt:</label><br>
            <input id="poszt" type="text" name="sppost" size="30"><br><br>
            <input type="submit" name="ujsp" value="Rögzítés"><br><br>
        </form>
    </div>
<?php } ?>
</body>
</html>
