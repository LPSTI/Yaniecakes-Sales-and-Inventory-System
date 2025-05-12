<!DOCTYPE html>
<html lang="en">
<?php
include("../db/database.php");
session_start();
if (!isset($_SESSION["U_ID"])|| $_SESSION["ROLE"] !== "manager") {
    header('location: ../index.php');
}
else {

    $alert = array();

    if(isset($_POST['submit'])) {
        
        if (isset($_POST['CAT_NAME'])) {

            $uql = "UPDATE category SET CAT_NAME = '$_POST[CAT_NAME]' WHERE CAT_ID = '$_GET[CATUPD]'";
            mysqli_query($sqlc, $uql);
            $alert[] = "Updated!";
        }
    }
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Update Category</title>
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
                <div class="row">
                    <div class="container-fluid">
                        <div class="col-lg-6">
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4>Update Category</h4>
                                </div>
                                <div class="card-body">
                                    <form action="" method="post">
                                        <?php if(count($alert) > 0): ?>
                                        <div class="alert alert-success text-center">
                                        <?php foreach($alert as $showalert){echo $showalert;}?>
                                        </div>
                                        <?php endif; ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <?php
                                                    $ssql = "SELECT * FROM category WHERE CAT_ID = '$_GET[CATUPD]'";
                                                    $query = mysqli_query($sqlc, $ssql);
                                                    $row = mysqli_fetch_array($query);
                                                    ?>
                                                    <label for="">Category</label>
                                                    <input type="text" name="CAT_NAME" value="<?= htmlspecialchars($row['CAT_NAME'])?>" class="form-control" placeholder="Category Name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Update</label>
                                                    <input type="submit" name="submit" class="btn btn-success" value="Save">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Abort</label>
                                                    <a href="addcat.php" class="btn btn-danger">Cancel</a>
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
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>