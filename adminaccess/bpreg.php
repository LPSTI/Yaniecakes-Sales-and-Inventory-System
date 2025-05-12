<!DOCTYPE html>
<html lang="en">
<?php
include("../db/database.php");
session_start();
if (!isset($_SESSION["U_ID"]) || $_SESSION["ROLE"] !== "admin") {
    header('location: ../login.php');
} else {
    if (isset($_POST['submit'])) {

        if (!empty($_POST['Username']) && !empty($_POST['Password']) &&
            !empty($_POST['Firstname']) && !empty($_POST['Lastname']) &&
            !empty($_POST['Email']) && !empty($_POST['Number']) &&
            !empty($_POST['Role']) && !empty($_POST['Address'])) 
            {

            $user = mysqli_real_escape_string($sqlc, $_POST['Username']);
            $pass = trim($_POST['Password']);
            $fname = mysqli_real_escape_string($sqlc, $_POST['Firstname']);
            $lname = mysqli_real_escape_string($sqlc, $_POST['Lastname']);
            $email = mysqli_real_escape_string($sqlc, $_POST['Email']);
            $number = mysqli_real_escape_string($sqlc, $_POST['Number']);
            $role = mysqli_real_escape_string($sqlc, $_POST['Role']);
            $address = mysqli_real_escape_string($sqlc, $_POST['Address']);

            $dbcheck = mysqli_query($sqlc, "SELECT * FROM users WHERE USERNAME = '$user' OR EMAIL = '$email'");

            if (mysqli_num_rows($dbcheck) > 0) {
                $alert[] = "Account Already Exist!";
            } else {
                $hash = password_hash($pass, PASSWORD_DEFAULT);

                $addtodb = "INSERT INTO users (USERNAME, PASS, FIRSTNAME, LASTNAME, EMAIL, CONTACT, ROLE, ADDRESS)
                    VALUES ('$user', '$hash', '$fname', '$lname', '$email', '$number', '$role', '$address')";

                mysqli_query($sqlc, $addtodb);
                $alert[] = "Registration Successful";
            }
        }
    }
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Register - BP</title>
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
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#acc">
                            Accounts
                        </a>
                        <ul class="collapse list-unstyled ms-3" id="acc">
                            <li><a class="nav-link" href="accesslist.php">Users List</a></li>
                            <li><a class="nav-link" href="bpreg.php">Register Access</a></li>
                            <li><a class="nav-link" href="addrole.php">Add Role</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-8 col-md-8 col-sm-6 mb-3">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4>Register Partner Access</h4>
                            </div>
                            <form action="" method="post">
                                <?php if (count($alert) > 0): ?>
                                    <div class="alert alert-info text-center">
                                        <?php foreach ($alert as $showalert) {echo $showalert;} ?>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Username *</label>
                                            <input type="text" class="form-control" name="Username" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Password *</label>
                                            <input type="password" class="form-control" name="Password" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Firstname *</label>
                                            <input type="text" class="form-control" name="Firstname" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Lastname *</label>
                                            <input type="text" class="form-control" name="Lastname" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Email *</label>
                                            <input type="email" class="form-control" name="Email" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Number *</label>
                                            <input type="number" class="form-control" name="Number" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Role *</label>
                                            <select name="Role" class="form-control" required>
                                                <option value="" selected disabled>Select</option>
                                                <option value="admin">admin</option>
                                                <option value="manager">manager</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Address *</label>
                                            <input type="text" class="form-control" name="Address" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="">Submit</label>
                                            <input type="submit" class="form-control" name="submit" value="Register">
                                        </div>
                                    </div>
                                </div>
                            </form>
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
<?php } ?>