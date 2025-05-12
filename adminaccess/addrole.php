<!DOCTYPE html>
<html lang="en">
<?php
include("../db/database.php");
session_start();

if (!isset($_SESSION["U_ID"]) || $_SESSION["ROLE"] !== "admin") {
    header('location: ../login.php');
} else {

$alert = array();

if (isset($_POST['submit'])) {
    if (empty($_POST['role_name'])) {
        $alert[] = "Field Required";
    } else {

        $rolename = mysqli_real_escape_string($sqlc, $_POST['role_name']);

        $rolecheck = mysqli_query($sqlc, "SELECT role_name FROM roles WHERE role_name = '$rolename'");
        if (mysqli_num_rows($rolecheck) > 0) {
            $alert[] = "Role already exist!";
        } else {
            $mql = "INSERT INTO roles(role_name) VALUES ('$rolename')";
            mysqli_query($sqlc, $mql);
            $alert[] = "Added Successfully";
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
    <title>role</title>
</head>
<body>
    <div class="main"><nav class="navbar navbar-expand-lg navbar-light bg-light">
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
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h4>Add Role or Unit</h4>
                                    </div>
                                    <form action="" method="post">
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
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="">Role</label>
                                                    <input type="text" name="role_name" class="form-control" required>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="">Submit</label>
                                                    <input type="submit" class="btn btn-success form-control" name="submit" value="Save" onsubmit="return confirm('Are you sure you want to add this?');">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="">Cancel</label>
                                                    <a href="addrole.php" class="btn btn-danger form-control">Cancel</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h4>role List</h4>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>role</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM roles ORDER BY role_id DESC";
                                                $query = mysqli_query($sqlc, $sql);

                                                if (mysqli_num_rows($query)): ?>
                                                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['role_name']) ?></td>
                                                            <td>
                                                                <a href="delfunc.php?ROLDEL=<?= $row['role_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this?');"><i class="bi bi-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="8">
                                                            <center>No Categories Listed</center>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
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
<?php } ?>