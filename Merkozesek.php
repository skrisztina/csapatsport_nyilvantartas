<?php
session_start();
include_once "kezelofgvk.php";

if (isset($_POST["ujmeccs"])){
    $csp1 = $_POST["csapat1"];
    $csp2 = $_POST["csapat2"];
    $hely = $_POST["hely"];
    $datum = $_POST["datum"];
    $gyoztes = $_POST["gyoztes"];
    $nyerpont = $_POST["nyertespont"];
    $veszpont = $_POST["vesztespont"];

    if(isset($csp1) && isset($csp2) && isset($hely) && isset($datum)){
        $v_csp1 = htmlspecialchars($csp1);
        $v_csp2 = htmlspecialchars($csp2);
        $v_hely = htmlspecialchars($hely);
        if(!(isset($gyoztes))){
            $v_gyoztes = null;
        } else {
            $v_gyoztes = htmlspecialchars($gyoztes);
        }
        if(!(isset($nyerpont))){
            $v_nyerpont = null;
        } else {
            $v_nyerpont = $nyerpont;
        }
        if(!(isset($veszpont))){
            $v_veszpont = null;
        } else {
            $v_veszpont = $veszpont;
        }
        $siker = merkozes_felvitel($v_csp1, $v_csp2, $datum, $v_hely, $v_gyoztes, $v_nyerpont, $v_veszpont);

        if($siker == false){
            error_log("Az új mérkőzés kiírása nem sikerült.");
        } else {
            header("Location: Merkozesek.php");
        }
    } else {
        error_log("Hiányzó adat!");
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
            <?php }else{?>
            <li><a href="Kijeletkezes.php" target="_self">Kijelentkezés</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>
<h1>Mérkőzések</h1>
<h2>Mérkőzések a következő hét napban:</h2>
<div>
<?php
$kovhet = merkozes_kovhet();
if(isset($kovhet) && ($kovhet != null || $kovhet != 0) ){
    ?>
    <table>
        <tr>
            <th>Dátum:</th>
            <th>Helyszín:</th>
            <th>Csapat:</th>
            <th>Csapat:</th>
        </tr>
        <?php
        while ($sor = mysqli_fetch_assoc($kovhet)){
            $csapat1 = csapatot_leker($sor["csapat1id"]);
            $csapat2 = csapatot_leker($sor["csapat2id"]);
            echo '<tr>';
            echo '<th>'. $sor["datum"] .'</th>';
            echo '<th>'. $sor["helyszin"] .'</th>';
            echo '<th>'. $csapat1["nev"] .'</th>';
            echo '<th>'. $csapat2["nev"] .'</th>';
            echo '</tr>';
        }
        mysqli_free_result($kovhet);
        ?>
    </table>
    <?php
}
?>
</div>
<h2>Összes mérkőzés:</h2>
<div>
    <table>
        <tr>
            <th>Dátum:</th>
            <th>Helyszín:</th>
            <th>Csapat:</th>
            <th>Csapat:</th>
            <th>Nyertes:</th>
            <th>Nyertes csapat pontszáma:</th>
            <th>Vesztes csapat pontszáma:</th>
            <?php if (isset($_SESSION["user"])){ ?>
                <th></th>
            <?php } ?>
        </tr>
        <?php
        $merkozesek = merkozesek_leker();
        while($merkozes = mysqli_fetch_assoc($merkozesek)){
            $csapat1 = csapatot_leker($merkozes["csapat1id"]);
            $csapat2 = csapatot_leker($merkozes["csapat2id"]);
            echo '<form action="merkozesmodositas.php" method="post">';
            echo '<tr>';
            echo '<td>'. $merkozes["datum"] .'</td>';
            echo '<td>'. $merkozes["helyszin"] .'</td>';
            echo '<td>'. $csapat1["nev"] .'</td>';
            echo '<td>'. $csapat2["nev"] .'</td>';
            if($merkozes["nyertes"] == null){
                $nyertes = "Még nincs adat";
            } else {
                $nyertes = $merkozes["nyertes"];
            }
            echo '<td>'. $nyertes .'</td>';
            if($merkozes["nyertespont"] == null){
                $nyertespont = "Még nincs adat";
            } else {
                $nyertespont = $merkozes["nyertespont"];
            }
            echo '<td>'. $nyertespont .'</td>';
            if($merkozes["vesztespont"] == null){
                $vesztespont = "Még nincs adat.";
            } else {
                $vesztespont = $merkozes["vesztespont"];
            }
            echo '<td>'. $vesztespont .'</td>';
            if(isset($_SESSION["user"])){
                echo '<td><input type="hidden" name="csp1" value="' . $merkozes["csapat1id"] . '"><input type="hidden" name="csp2" value="'. $merkozes["csapat2id"] .'"><input type="hidden" name="datum" value="'. $merkozes["datum"] .'">
                    <input type="hidden" name="hely" value="'. $merkozes["helyszin"] .'"><input type="submit" name="szerkeszt" value="Szerkeszt"></td>';
            }
            echo '</tr>';
            echo '</form>';
        }
        ?>
    </table><br>
</div>
<?php if (isset($_SESSION["user"])){ ?>
<h2>Új mérkőzés felvitele:</h2>
    <div>
        <form action="Merkozesek.php" method="post">
            <label for="team1">Csapat:     (kötelező)</label><br>
            <select id="team1" name="csapat1">
                <?php
                $csapatok = csapat_leker();
                if(mysqli_num_rows($csapatok) > 0){
                    while($csapat = mysqli_fetch_assoc($csapatok)){
                        echo '<option value="'.  $csapat["csapatid"].'">'. $csapat["nev"] . '</option>';
                    }
                } else {
                    echo '<option value="">--Nincs választható csapat--</option>';
                }
                mysqli_free_result($csapatok);
                ?>
            </select><br><br>
            <label for="team2">Csapat:     (kötelező)</label><br>
            <select id="team2" name="csapat2">
                <?php
                $csapatok = csapat_leker();
                if(mysqli_num_rows($csapatok) > 0){
                    while($csapat = mysqli_fetch_assoc($csapatok)){
                        echo '<option value="'.  $csapat["csapatid"].'">'. $csapat["nev"] . '</option>';
                    }
                } else {
                    echo '<option value="">--Nincs választható csapat--</option>';
                }
                mysqli_free_result($csapatok);
                ?>
            </select><br><br>
            <label for="hely">Helyszín:     (kötelező)</label><br>
            <input id="hely" type="text" name="hely" size="30"><br><br>
            <label for="date">Dátum:     (kötelező)</label><br>
            <input id="date" type="date" name="datum"><br><br>
            <label for="nyer">Nyertes Csapat:</label><br>
            <select id="nyer" name="gyoztes">
                <option value="<?php echo NULL; ?>">-Nincs adat-</option>
                <?php
                $csapatok = csapat_leker();
                if(mysqli_num_rows($csapatok) > 0){
                    while($csapat = mysqli_fetch_assoc($csapatok)){
                        echo '<option value="'.  $csapat["nev"].'">'. $csapat["nev"] . '</option>';
                    }
                } else {
                    echo '<option value="">--Nincs választható csapat--</option>';
                }
                mysqli_free_result($csapatok);
                ?>
            </select><br><br>
            <label for="nyer">Nyertes csapat pontszáma:</label><br>
            <input id="nyer" type="text" name="nyertespont" size="10"><br><br>
            <label for="vesz">Vesztes csapat pontszáma:</label><br>
            <input id="vesz" type="text" name="vesztespont" size="10"><br><br>
            <input type="submit" name="ujmeccs" value="Rögzítés">
        </form>
    </div><br><br>
<?php } ?>


</body>
</html>
