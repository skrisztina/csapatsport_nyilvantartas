<?php
session_start();
include_once "kezelofgvk.php";

if(isset($_POST["szerkeszt"])){
    $csp1id = $_POST["csp1"];
    $csp2id = $_POST["csp2"];
    $dat = $_POST["datum"];
    $hely = $_POST["hely"];
    $csapat1 = csapatot_leker($csp1id);
    $csapat2 = csapatot_leker($csp2id);

    $meccs = merkozest_leker($csp1id, $csp2id, $dat);
}

if(isset($_POST["modosit"])){
    $csapat1id = $_POST["csp1id"];
    $csapat2id = $_POST["csp2id"];
    $datum = $_POST["dat"];
    $helyszin = $_POST["hely"];
    $gyoztes = $_POST["gyoztes"];
    $nyertespont = $_POST["nyertespont"];
    $vesztespont = $_POST["vesztespont"];

    if(isset($csapat1id) && isset($csapat2id) && isset($datum) && isset($helyszin)){
        $v_helyszin = htmlspecialchars($helyszin);
        if(!(isset($gyoztes))){
            $v_gyoztes = null;
        } else {
            $v_gyoztes = htmlspecialchars($gyoztes);
        }
        if(!(isset($nyertespont))){
            $v_nyertespont = null;
        } else {
            $v_nyertespont = htmlspecialchars($nyertespont);
        }
        if(!(isset($vesztespont))){
            $v_vesztespont = null;
        } else {
            $v_vesztespont = htmlspecialchars($vesztespont);
        }
        $siker = merkozes_modosit($csapat1id, $csapat2id, $datum, $v_helyszin, $v_gyoztes, $v_nyertespont, $v_vesztespont);

        if($siker == false){
            error_log("Hiba a módosítás során!");
        } else {
            header("Location: Merkozesek.php");
        }
    } else {
        error_log("Hiányzó mező!");
    }
}

if (isset($_POST["torol"])){
    $cspid1 = $_POST["cspid1"];
    $cspid2 = $_POST["cspid2"];
    $meccsdat = $_POST["meccsdat"];

    if(isset($cspid1) && isset($cspid2) && isset($meccsdat)){
        $siker = merkozes_torles($cspid1, $cspid2, $meccsdat);
        if($siker == false){
            error_log("A meccs törlése nem sikerült.");
        } else {
            header("Location: Merkozesek.php");
        }
    } else {
        error_log("A meccs törlése nem sikerült!");
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
<h2>Mérkőzés módosítása:</h2>
<div>
    <form action="merkozesmodositas.php" method="post">
        <label for="csp1">Csapat:</label><br>
        <input type="hidden" name="csp1id" value="<?php echo $csapat1["csapatid"]; ?>">
        <input id="csp1" type="text" name="csp1" value="<?php echo $csapat1["nev"]; ?>" disabled><br><br>
        <label for="csp2">Csapat:</label><br>
        <input type="hidden" name="csp2id" value="<?php echo $csapat2["csapatid"]; ?>">
        <input id="csp2" type="text" name="csp2" value="<?php echo $csapat2["nev"]; ?>" disabled><br><br>
        <label for="date">Dátum:</label><br>
        <input type="hidden" name="dat" value="<?php echo $meccs["datum"]; ?>">
        <input id="date" type="date" name="data" value="<?php echo $meccs["datum"]; ?>" disabled><br><br>
        <label for="hely">Helyszín:</label><br>
        <input id="hely" type="text" name="hely" value="<?php echo $hely; ?>"><br><br>
        <label for="nyer">Nyertes csapat:</label>
        <select id="nyer" name="gyoztes">
            <option value="<?php echo NULL; ?>">-Nincs adat-</option>
            <option value="<?php echo $csapat1["nev"]; ?>" <?php if($meccs["nyertes"] == $csapat1["nev"]){echo 'selected';} ?>><?php echo $csapat1["nev"]; ?></option>
            <option value="<?php echo $csapat2["nev"]; ?>" <?php if($meccs["nyertes"] == $csapat2["nev"]){echo 'selected';} ?>><?php echo $csapat2["nev"]; ?></option>
        </select><br><br>
        <label for="nypont">Nyertes csapat pontszáma:</label><br>
        <input id="nypont" type="text" name="nyertespont" value="<?php echo $meccs["nyertespont"]; ?>" size="10"><br><br>
        <label for="vpont">Vesztes csapat pontszáma:</label><br>
        <input id="vpont" name="vesztespont" value="<?php echo $meccs["vesztespont"]; ?>" size="10"><br><br>
        <input type="submit" name="modosit" value="Módosít">
    </form><br><br>
    <form action="merkozesmodositas.php" method="post">
        <input type="submit" name="torol" value="Mérkőzés Törlése">
        <input type="hidden" name="cspid1" value="<?php echo $csapat1["csapatid"]; ?>">
        <input type="hidden" name="cspid2" value="<?php echo $csapat2["csapatid"]; ?>">
        <input type="hidden" name="meccsdat" value="<?php echo $meccs["datum"]; ?>">
    </form><br><br>
</div>
</body>
</html>
