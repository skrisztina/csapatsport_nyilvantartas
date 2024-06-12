<?php

function felhasznalo_csatlakozas(){
    $conn = mysqli_connect('localhost', 'root', '') or die ("Hibás csatlakozás!");

    if(mysqli_select_db($conn, "csapatsport") == false){
        return null;
    }

    mysqli_query($conn, 'SET NAMES UTF8');
    mysqli_query($conn, 'SET character_set_results=utf8');
    mysqli_set_charset($conn, 'utf8');

    return $conn;
}

function regisztracio($felhnev, $nev, $jelszo){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO felhasznalo(felhasznalonev, nev, jelszo) VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt, "sss", $felhnev, $nev, $jelszo);

    try {
        $success = mysqli_stmt_execute($stmt);
    } catch (Exception $e){
        echo 'Message: '.$e->getMessage();
    }

    if($success == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $success;
}

function felhasznalo_leker($felhnev){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $stmt = mysqli_prepare($conn, "SELECT * FROM felhasznalo WHERE felhasznalonev = ?");
    mysqli_stmt_bind_param($stmt, "s", $felhnev);
    $result = mysqli_stmt_execute($stmt);

    if ($result == false){
        return false;
    }

    mysqli_stmt_bind_result($stmt, $felhasznalonev, $nev, $jelszo);
    $felhasznalo = array();
    mysqli_stmt_fetch($stmt);
    $felhasznalo["felhasznalonev"] = $felhasznalo;
    $felhasznalo["nev"] = $nev;
    $felhasznalo["jelszo"] = $jelszo;

    mysqli_close($conn);
    return $felhasznalo;

}

function csapat_leker(){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }

    $result = mysqli_query($conn,"SELECT * FROM csapat ORDER BY nev");

    if($result == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $result;
}

function csapat_felvitel($csapatid, $nev, $varos, $alapitasev){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $stmt = mysqli_prepare($conn, "INSERT INTO csapat(csapatid, nev, varos, alapitaseve) VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "issi", $csapatid, $nev, $varos, $alapitasev);
    $success = mysqli_stmt_execute($stmt);

    if($success == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $success;
}

function csapatot_leker($csapatid){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }

    $stmt = mysqli_prepare($conn, "SELECT * FROM csapat WHERE csapatid = ?");
    mysqli_stmt_bind_param($stmt, "i", $csapatid);

    $result = mysqli_stmt_execute($stmt);

    if($result == false){
        die(mysqli_error($conn));
    }
    mysqli_stmt_bind_result($stmt, $cspid, $nev, $varos, $ev);
    $csapat = array();
    mysqli_stmt_fetch($stmt);
    $csapat["csapatid"] = $cspid;
    $csapat["nev"] = $nev;
    $csapat["varos"] = $varos;
    $csapat["ev"] = $ev;

    mysqli_close($conn);
    return $csapat;
}

function csapat_modosit($csapatid, $csapatnev, $csapatvaros, $csapatev){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }

    $stmt = mysqli_prepare($conn, "UPDATE csapat SET nev = ?, varos = ?, alapitaseve = ? WHERE csapatid = ?");
    mysqli_stmt_bind_param($stmt, "ssii", $csapatnev, $csapatvaros, $csapatev, $csapatid);

    $success = mysqli_stmt_execute($stmt);
    if($success == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $success;
}

function csapat_torles($csapatid){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $stmt = mysqli_prepare($conn, "DELETE FROM csapat WHERE csapatid = ?");
    mysqli_stmt_bind_param($stmt, "i", $csapatid);
    $success = mysqli_stmt_execute($stmt);

    if($success == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $success;
}

function tagok_leker(){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $result = mysqli_query($conn, "SELECT csapat.nev AS csp, tag.nev AS spnev, tag.sportoloid AS id, tag.poszt AS spposzt, tag.allampolgarsag AS spnat, tag.szuletesidatum AS spszul FROM csapat, tag WHERE csapat.csapatid = tag.csapatid");

    if($result == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $result;
}

function tag_torles($spid){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $stmt = mysqli_prepare($conn, "DELETE FROM tag WHERE sportoloid = ?");
    mysqli_stmt_bind_param($stmt, "i", $spid);
    $success = mysqli_stmt_execute($stmt);

    if($success == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $success;
}

function tag_felvitel($csapatid, $spid, $nev, $poszt, $allampolg, $szuldatum){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $stmt = mysqli_prepare($conn, "INSERT INTO tag(sportoloid, nev, szuletesidatum, allampolgarsag, poszt, csapatid) VALUES (?,?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "issssi", $spid, $nev, $szuldatum, $allampolg, $poszt, $csapatid);

    $success = mysqli_stmt_execute($stmt);

    if($success == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $success;
}

function merkozesek_leker(){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $result = mysqli_query($conn, "SELECT * FROM merkozes");

    if($result == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $result;
}

function merkozes_felvitel($csp1, $csp2, $datum, $helyszin, $nyertes, $nyertespont, $vesztespont){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $stmt = mysqli_prepare($conn, "INSERT INTO merkozes(csapat1id, csapat2id, datum, helyszin, nyertes, nyertespont, vesztespont) VALUES (?,?,?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "iisssii", $csp1, $csp2, $datum,$helyszin, $nyertes, $nyertespont, $vesztespont);
    $success = mysqli_stmt_execute($stmt);

    if($success == false){
        die(mysqli_error($conn));
    }

    mysqli_close($conn);
    return $success;
}

function merkozest_leker($csp1, $csp2, $datum){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $stmt = mysqli_prepare($conn, "SELECT * FROM merkozes WHERE csapat1id = ? AND csapat2id = ? AND datum = ?");
    mysqli_stmt_bind_param($stmt, "iis", $csp1, $csp2, $datum);
    $success = mysqli_stmt_execute($stmt);

    if($success == false){
        die(mysqli_error($conn));
    }
    mysqli_stmt_bind_result($stmt, $cspid1,$cspid2, $datum, $helyszin, $nyertes, $nyertespont, $vesztespont);
    $merkozes = array();
    mysqli_stmt_fetch($stmt);
    $merkozes["csapat1id"] = $cspid1;
    $merkozes["csapat2id"] = $cspid2;
    $merkozes["datum"] = $datum;
    $merkozes["helyszin"] = $helyszin;
    $merkozes["nyertes"] = $nyertes;
    $merkozes["nyertespont"] = $nyertespont;
    $merkozes["vesztespont"] = $vesztespont;

    mysqli_close($conn);
    return $merkozes;
}

function merkozes_modosit($cspid1, $cspid2, $datum, $helyszin, $nyertes, $nyertespont, $vesztespont){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $stmt = mysqli_prepare($conn, "UPDATE merkozes SET helyszin = ?, nyertes = ?, nyertespont = ?, vesztespont = ? WHERE csapat1id = ? AND csapat2id = ? AND datum = ?");
    mysqli_stmt_bind_param($stmt, "ssiiiis", $helyszin, $nyertes, $nyertespont, $vesztespont, $cspid1, $cspid2, $datum);

    $success = mysqli_stmt_execute($stmt);
    if($success == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $success;
}

function merkozes_torles($cspid1, $cspid2, $datum){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $stmt = mysqli_prepare($conn, "DELETE FROM merkozes WHERE merkozes.csapat1id = ? AND merkozes.csapat2id = ? AND merkozes.datum = ?");
    mysqli_stmt_bind_param($stmt, "iis", $cspid1, $cspid2, $datum);
    $success = mysqli_stmt_execute($stmt);

    if($success == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $success;
}

function merkozes_kovhet(){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $result = mysqli_query($conn, "SELECT * FROM merkozes WHERE DATEDIFF(merkozes.datum, CURRENT_DATE) <= 7 AND DATEDIFF(merkozes.datum, CURRENT_DATE) >= 0");
    if($result == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $result;
}

function legregebbi_csapat(){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $result = mysqli_query($conn, "SELECT nev, alapitaseve FROM csapat WHERE alapitaseve = (SELECT MIN(alapitaseve) FROM csapat)");
    if($result == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $result;
}

function legregebbi_csapat_tagok(){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }

    $result = mysqli_query($conn, "SELECT tag.nev AS nev, tag.szuletesidatum AS szul, tag.allampolgarsag AS nat, tag.poszt AS post FROM csapat, tag WHERE csapat.csapatid = tag.csapatid AND csapat.alapitaseve = (SELECT MIN(alapitaseve) FROM csapat) ORDER BY tag.nev;");
    if($result == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $result;
}

function csapat_nat($csapatid){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }

    $success = mysqli_query($conn, "SELECT allampolgarsag, COUNT(sportoloid) AS szam FROM tag WHERE csapatid = $csapatid GROUP BY  allampolgarsag");

    if($success == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $success;
}

function legfiatalabb_tagok (){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }
    $result = mysqli_query($conn, "SELECT tag.nev AS nev, tag.poszt, tag.szuletesidatum, tag.allampolgarsag, csapat.nev AS csapatnev FROM tag, csapat WHERE tag.csapatid = csapat.csapatid ORDER BY tag.szuletesidatum DESC LIMIT 5;");
    if($result == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $result;
}

function csapat_gyozelmek(){
    if(!($conn = felhasznalo_csatlakozas())){
        return false;
    }

    $result = mysqli_query($conn, "SELECT csapat.csapatid, csapat.nev, csapat.varos, csapat.alapitaseve, COUNT(merkozes.datum) AS db FROM csapat, merkozes WHERE csapat.nev = merkozes.nyertes GROUP BY csapat.nev ORDER BY csapat.nev");
    if($result == false){
        die(mysqli_error($conn));
    }
    mysqli_close($conn);
    return $result;
}
?>
