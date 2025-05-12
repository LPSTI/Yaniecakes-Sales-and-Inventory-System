<?php

include("../db/database.php");
logActivity($sqlc);

function logActivity($sqlc) {

    if (isset($_SESSION['U_ID'])) {
        $user_id = $_SESSION['U_ID'];

        $result = mysqli_query($sqlc, "SELECT FIRSTNAME, LASTNAME FROM users WHERE USER_ID = $user_id");

        if ($result && $row = mysqli_fetch_assoc($result)) {
            
            $fullname = $row['FIRSTNAME'] . ' ' . $row['LASTNAME'];

            mysqli_query($sqlc, "SET @current_user_id = $user_id");
            mysqli_query($sqlc, "SET @current_user_name = '" . mysqli_real_escape_string($sqlc, $fullname) . "'");
        }
    }
}