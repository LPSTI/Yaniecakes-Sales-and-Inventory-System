<!DOCTYPE html>
<html lang="en">
<?php
require_once("../db/database.php");
session_start();
if (!isset($_SESSION["U_ID"]) || $_SESSION["ROLE"] !== "manager") {
    header('location: ../index.php');
}
else {
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Dashboard</title>
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
            <nav class="bg-light sidebar p-3" style=" height: auto; position: relative;">
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
                <div class="col-lg-12">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>Dashboard</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mt-1">
                                    <div class="card p-3">
                                        <div class="card-group">
                                            <div>
                                                <h2><i class="bi bi-people"></i>
                                                <?php 
                                                $data = "SELECT * FROM users WHERE ROLE = 'customer'"; 
                                                $datacount = mysqli_query($sqlc, $data);  
                                                $count = mysqli_num_rows($datacount); 
                                                echo $count; 
                                                ?>
                                                </h2>
                                                <p>Total Customers</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-1">
                                    <div class="card p-3">
                                        <div class="card-group">
                                            <div>
                                                <h2><i class="bi bi-clipboard"></i>
                                                <?php 
                                                $data = "SELECT * FROM dnd"; 
                                                $datacount = mysqli_query($sqlc, $data);  
                                                $count = mysqli_num_rows($datacount); 
                                                echo $count; 
                                                ?>
                                                </h2>
                                                <p>Total Menu</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-1">
                                    <div class="card p-3">
                                        <div class="card-group">
                                            <div>
                                                <h2><i class="bi bi-cash-stack"></i>
                                                <?php 
                                                $data = "SELECT SUM(total_price) AS total_profit FROM orders WHERE order_status = 'Order Complete'";
                                                $datacount = mysqli_query($sqlc, $data);
                                                $count = mysqli_fetch_assoc($datacount);
                                                $totalCompleted = $count['total_profit'];
                                                echo number_format($totalCompleted, 2) . " ₱";
                                                ?>
                                                </h2>
                                                <p>Total Profit</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4 mt-1">
                                    <div class="card p-3">
                                        <div class="card-group">
                                            <div>
                                                <h2><i class="bi bi-cash"></i>
                                                <?php 
                                                $data = "SELECT * FROM orders WHERE payment_status = 'Waiting Payment'"; 
                                                $datacount = mysqli_query($sqlc, $data);
                                                $count = mysqli_num_rows($datacount); 
                                                echo $count; 
                                                ?>
                                                </h2>
                                                <p>Waiting Payment</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-1">
                                    <div class="card p-3">
                                        <div class="card-group">
                                            <div>
                                                <h2><i class="bi bi-hourglass-split"></i>
                                                <?php
                                                $data = "SELECT * FROM orders WHERE order_status = 'Pending'";
                                                $datacount = mysqli_query($sqlc, $data);
                                                $count = mysqli_num_rows($datacount);
                                                echo $count;
                                                ?>
                                                </h2>
                                                <p>Pending Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-1">
                                    <div class="card p-3">
                                        <div class="card-group">
                                            <div>
                                                <h2><i class="bi bi-cart"></i>
                                                <?php 
                                                $data = "SELECT * FROM orders"; 
                                                $datacount = mysqli_query($sqlc, $data);  
                                                $count = mysqli_num_rows($datacount); 
                                                echo $count; 
                                                ?>
                                                </h2>
                                                <p>Total Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4 mt-1">
                                    <div class="card p-3">
                                        <div class="card-group">
                                            <div>
                                                <h2><i class="bi bi-arrow-clockwise"></i>

                                                <?php 
                                                $data = "SELECT * FROM orders WHERE order_status IN ('Processing', 'On the Way')"; 
                                                $datacount = mysqli_query($sqlc, $data);
                                                $count = mysqli_num_rows($datacount); 
                                                echo $count; 
                                                ?>
                                                </h2>
                                                <p>Processing Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-1">
                                    <div class="card p-3">
                                        <div class="card-group">
                                            <div>
                                                <h2><i class="bi bi-cart-check"></i>
                                                <?php 
                                                $data = "SELECT * FROM orders WHERE order_status ='Order Delivered'"; 
                                                $datacount = mysqli_query($sqlc, $data);  
                                                $count = mysqli_num_rows($datacount); 
                                                echo $count; 
                                                ?>
                                                </h2>
                                                <p>Delivered Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-1">
                                    <div class="card p-3">
                                        <div class="card-group">
                                            <div>
                                                <h2><i class="bi bi-check-circle"></i>
                                                <?php 
                                                $data = "SELECT * FROM orders WHERE order_status = 'Order Complete'"; 
                                                $datacount = mysqli_query($sqlc, $data);  
                                                $count = mysqli_num_rows($datacount); 
                                                echo $count; 
                                                ?>
                                                </h2>
                                                <p>Complete Orders</p>
                                            </div>
                                        </div>
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