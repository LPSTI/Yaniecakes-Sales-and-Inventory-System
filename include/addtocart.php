<?php
if (isset($_POST['atc'])) {

    if ($user_id == '') {
        header('location: login.php');
    } else {

        $did = mysqli_real_escape_string($sqlc, $_POST['DND_ID']);
        $dname = mysqli_real_escape_string($sqlc, $_POST['DND_NAME']);
        $dprice = mysqli_real_escape_string($sqlc, $_POST['DND_PRICE']);
        $dqty = mysqli_real_escape_string($sqlc, $_POST['order_qty']);
        $dimg = mysqli_real_escape_string($sqlc, $_POST['DND_IMAGE']);

        $cartchck = mysqli_query($sqlc, "SELECT * FROM cart WHERE DND_ID = '$did' AND user_id = '$user_id'");

        if (mysqli_num_rows($cartchck) > 0) {
            $errors[] = "Already in cart!";
        } else {
            $aql = "INSERT INTO cart(user_id, dnd_id, dnd_name, dnd_price, order_qty, dnd_img)
                    VALUES ('$user_id', '$did', '$dname', '$dprice', '$dqty', '$dimg')";
            mysqli_query($sqlc, $aql);
            $alert[] = "Added to cart!";
        }
    }
}
