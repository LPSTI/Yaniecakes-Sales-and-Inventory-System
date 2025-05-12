<!DOCTYPE html>
<html lang="en">
<?php
include("../db/database.php");
session_start();

if (isset($_SESSION['U_ID'])) {
    $user_id = $_SESSION['U_ID'];
} else {
    $user_id = '';
};

$alert = array();
$error = array();

$usercheck = "SELECT * FROM users WHERE USER_ID = '$user_id'";
$query = mysqli_query($sqlc, $usercheck);
$row = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {

    if (
        isset($_POST['fname']) && isset($_POST['lname']) &&
        isset($_POST['contact']) && isset($_POST['email']) &&
        isset($_POST['address'])
    ) {

        $firstname = mysqli_real_escape_string($sqlc, $_POST['fname']);
        $lastname = mysqli_real_escape_string($sqlc, $_POST['lname']);
        $contact = mysqli_real_escape_string($sqlc, $_POST['contact']);
        $email = mysqli_real_escape_string($sqlc, $_POST['email']);
        $address = mysqli_real_escape_string($sqlc, $_POST['address']);

        switch (true) {
            case (!preg_match('/^09[0-9]{9}$/', $contact)):
                $error[] = "Invalid phone number!It must start with 09 and be exactly 11 digits long.";
                break;
            case (!filter_var($email, FILTER_VALIDATE_EMAIL)):
                $error[] = "Invalid Email!";
                break;

            default:

                $checkemail = mysqli_query($sqlc, "SELECT EMAIL FROM users WHERE EMAIL - '$email'");

                switch (true) {
                    case (mysqli_num_rows($checkemail) > 0):
                        $error[] = "Email already taken!";
                        break;
                    default:
                        $upateql = "UPDATE users SET FIRSTNAME = '$firstname', LASTNAME = '$lastname', CONTACT = '$contact', EMAIL = '$email', ADDRESS = '$address' WHERE USER_ID = '$user_id'";
                        mysqli_query($sqlc, $upateql);
                        $alert[] = "Profile Successfully Updated!";
                        break;
                }
                break;
        }
    }
}

if (isset($_POST['change_pass'])) {

    $oldpass = trim($_POST['old_pass']);
    $newpass = trim($_POST['new_pass']);
    $connewpass = trim($_POST['con_new_pass']);

    if (empty($oldpass) || empty($newpass) || empty($connewpass)) {
        $error[] = "All fields are required!";
    } else {
        if ($newpass !== $connewpass) {
            $error[] = "New password and Confirm Password do not match!";
        } else {
            if (!password_verify($oldpass, $row['PASS'])) {
                $error[] = "Old password is incorrect!";
            } else {
                $hash = password_hash($newpass, PASSWORD_DEFAULT);
                $updatepsql = "UPDATE users SET PASS = '$hash' WHERE USER_ID = '$user_id'";
                mysqli_query($sqlc, $updatepsql);
                $alert[] = "Password Changed!";
            }
        }
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Profile</title>
</head>

<body>
    <div class="main">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="dashboard.php">
                    <img src="../img/leftover.png" alt="" width="28" height="28">
                </a>
                <div class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="../img/user.png" alt="" width="28" height="28">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="d-flex">
            <nav class="bg-light sidebar p-3" style=" height: auto; position: sticky;">
                <ul class="nav flex-column">
                    <li class="nav-item">Home</li>
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">Log</li>
                    <li class="nav-item">
                        <a class="nav-link" href="userlist.php">Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#menu">
                            Menu
                        </a>
                        <ul class="collapse list-unstyled ms-3" id="menu">
                            <li><a class="nav-link" href="dndlist.php">Menu List</a></li>
                            <li><a class="nav-link" href="adddnd.php">Add Menu</a></li>
                            <li><a class="nav-link" href="addcat.php">Add Category</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#ing">
                            Ingredients
                        </a>
                        <ul class="collapse list-unstyled ms-3" id="ing">
                            <li><a class="nav-link" href="inventory.php">Ingredient List</a></li>
                            <li><a class="nav-link" href="addin.php">Add Ingredient</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orderlist.php">Orders</a>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <h3>My Profile</h3>
                            <hr>
                            <?php
                            if (!empty($alert) || !empty($error)) {
                                $type = !empty($alert) ? "alert" : "error";

                                switch ($type) {
                                    case "alert":
                                        $class = "alert-success";
                                        $messages = $alert;
                                        break;
                                    case "error":
                                        $class = "alert-danger";
                                        $messages = $error;
                                        break;
                                } ?>
                                <div class="alert <?= $class ?> text-center alert-dismissible fade show" role="alert">
                                    <?php foreach ($messages as $message) {
                                        echo $message;
                                    } ?>
                                    <img class="btn btn-link" data-bs-dismiss="alert" aria-label="Close" src="img/ico/x-square.svg" alt="">
                                </div>
                            <?php } ?>
                            <div class="row mb-5 gx-5">
                                <div class="col-lg-8 mb-5 mb-0">
                                    <div class="card px-4 py-5 rounded">
                                        <form action="" method="post">
                                            <div class="row g-3">
                                                <h4 class="mb-4 mt-0">Contact detail</h4>
                                                <div class="col-md-6">
                                                    <label class="form-label">First Name *</label>
                                                    <input type="text" name="fname" class="form-control" placeholder="<?= htmlspecialchars($row['FIRSTNAME']) ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Last Name *</label>
                                                    <input type="text" name="lname" class="form-control" placeholder="<?= htmlspecialchars($row['LASTNAME']) ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Contact number *</label>
                                                    <input type="text" name="contact" class="form-control" placeholder="<?= htmlspecialchars($row['CONTACT']) ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="inputEmail4" class="form-label">Email *</label>
                                                    <input type="email" name="email" class="form-control" placeholder="<?= htmlspecialchars($row['EMAIL']) ?>" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Address *</label>
                                                    <input type="text" name="address" class="form-control" placeholder="<?= htmlspecialchars($row['ADDRESS']) ?>" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="submit" class="btn btn-primary form-control" name="update" value="Update" required>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-5 mb-0">
                                    <div class="card px-4 py-5 rounded">
                                        <form action="" method="post">
                                            <div class="row g-3">
                                                <h4 class="mb-4 mt-0">Change Password</h4>
                                                <div class="col-md-12">
                                                    <label for="">Old password *</label>
                                                    <input type="password" name="old_pass" class="form-control">
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="">New password *</label>
                                                    <input type="password" name="new_pass" class="form-control">
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="">Confirm Password *</label>
                                                    <input type="password" name="con_new_pass" class="form-control">
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <input type="submit" class="btn btn-primary form-control" name="change_pass" value="Change Password">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>