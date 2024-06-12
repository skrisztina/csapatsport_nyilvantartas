<?php
session_start();
include_once "kezelofgvk.php";

if(isset($_POST["modosit"])){
    $cspid = $_POST["check"];
    $cspnev = $_POST["csapatnev"];
    $cspvaros = $_POST["csapatvaros"];
    $cspev = $_POST["csapatev"];

    if(isset($cspid)){
        if(isset($cspev) && isset($cspnev) && isset($cspvaros)){
            $tv_cspnev = htmlspecialchars($cspnev);
            $tv_cspvaros = htmlspecialchars($cspvaros);
            $tv_cspev = htmlspecialchars($cspev);

            $siker = csapat_modosit($cspid, $tv_cspnev, $tv_cspvaros, $tv_cspev);

            if($siker == false){
                die("Nem sikerült módosítani a csapatot.");
            } else {
                header("Location: Csapatok.php");
            }
        } else {
            error_log("Üres mező!");
        }
    } else {
        error_log("A csapatazonosítót nem lehet megváltoztatni!");
    }
}

if(isset($_POST["torles"])){
    $torlendo = $_POST["id"];
    if(isset($torlendo)){
        $tv_torlendo = htmlspecialchars($torlendo);

        $siker = csapat_torles($tv_torlendo);

        if($siker == false){
            die("Nem sikerült törölni a csapatot.");
        } else {
            header("Location: Csapatok.php");
        }
    } else {
        error_log("Hiányzó adat");
    }
}

?>
