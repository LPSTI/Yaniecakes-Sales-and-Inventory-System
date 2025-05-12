<?php
include("../db/database.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    switch (true) {
        case isset ($_GET['CATDEL']):
            mysqli_query($sqlc, "DELETE FROM category WHERE CAT_ID = '$_GET[CATDEL]'");
            header("Location: addcat.php");
            exit();
            break;
        case isset ($_GET['DNDDEL']):
            mysqli_query($sqlc, "DELETE FROM dnd WHERE DND_ID = '$_GET[DNDDEL]'");
            mysqli_query($sqlc, "DELETE FROM recipe WHERE DND_ID = '$_GET[DNDDEL]'");
            mysqli_query($sqlc, "DELETE FROM dndbatch WHERE DND_ID = '$_GET[DNDDEL]'");
            header("Location: dndlist.php");
            exit();
            break;
        case isset ($_GET['INDEL']):
            mysqli_query($sqlc, "DELETE FROM inventory WHERE IN_ID = '$_GET[INDEL]'");
            mysqli_query($sqlc, "DELETE FROM intbatch WHERE IN_ID = '$_GET[INDEL]'");
            header("Location: inventory.php");
            exit();
            break;
        case isset ($_GET['IBDEL']):
            mysqli_query($sqlc, "DELETE FROM intbatch WHERE B_ID = '$_GET[IBDEL]'");
            header("Location: intbatchlist.php");
            exit();
            break;
        case isset ($_GET['RECDEL']):
            mysqli_query($sqlc, "DELETE FROM recipe WHERE RECIPE_ID = '$_GET[RECDEL]'");
            header("Location: addrec.php");
            exit();
            break;
        default:
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
    }
}
?>