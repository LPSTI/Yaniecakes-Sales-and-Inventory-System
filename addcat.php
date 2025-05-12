<!DOCTYPE html>
<html lang="en">
<?php
include("../db/database.php");
session_start();

if (!isset($_SESSION["U_ID"]) || $_SESSION["ROLE"] !== "manager") {
    header('location: ../index.php');
} else {

    $alert = array();

    if (isset($_POST['submit'])) {
        if (empty($_POST['CAT_NAME'])) {
            $alert[] = "Field Required";
        } else {

            $catname = mysqli_real_escape_string($sqlc, $_POST['CAT_NAME']);

            $catcheck = mysqli_query($sqlc, "SELECT CAT_NAME FROM category WHERE CAT_NAME = '$catname'");
            if (mysqli_num_rows($catcheck) > 0) {
                $alert[] = "Category already exist!";
            } else {
                $mql = "INSERT INTO category(CAT_NAME) VALUES ('$catname')";
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
    <title>Category</title>
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
                    <div class="col-sm-3">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4>Add Category or Unit</h4>
                            </div>
                            <form action="" method="post">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="category">Category</label>
                                                <input type="text" name="CAT_NAME" class="form-control" required>
                                                <input type="submit" class="btn btn-primary form-control mt-2" name="submit" value="Save">
                                                <a href="addcat.php" class="btn btn-secondary form-control mt-2">Cancel</a>
                                            </div>
                                            <?php if(count($alert) > 0): ?>
                                            <div class="alert alert-danger text-center">
                                            <?php foreach($alert as $showalert){echo $showalert;}?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4>Category List</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT * FROM category ORDER BY CAT_ID DESC";
                                        $query = mysqli_query($sqlc, $sql); 
                                        
                                        if(mysqli_num_rows($query)): ?>
                                            <?php while($row = mysqli_fetch_assoc($query)): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['CAT_NAME'])?></td>
                                                    <td><?= htmlspecialchars($row['CAT_ADDED'])?></td>
                                                    <td>
                                                        <a href="updatecat.php?CATUPD=<?= $row['CAT_ID']?>" class="btn btn-info"><i class="bi bi-pencil-square"></i></a>
                                                        <a href="delfunc.php?CATDEL=<?= $row['CAT_ID']?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this?');"><i class="bi bi-trash"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">No Categories Listed</td>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>