<!DOCTYPE html>
<html lang="en">
<?php
include("../db/database.php");
session_start();
if (!isset($_SESSION["U_ID"]) || $_SESSION["ROLE"] !== "manager") {
    header('location: ../index.php');
} else {
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Inventory</title>
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
                        <div class="col-12">
                            <div class="col-lg-12">
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Ingredients List
                                            <div style="float: inline-end;">
                                                <a href="intbatchlist.php" class="btn btn-outline-secondary">
                                                    <span>Batch List</span>
                                                </a>
                                                <form action="" method="post">
                                                    <input name="search" type="text" class="form-control" id="live_search" autocomplete="off" placeholder="Search...">
                                                </form>
                                            </div>
                                        </h4>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>INGREDIENT</th>
                                                    <th>QUANTITY</th>
                                                    <th>UNIT</th>
                                                    <th>LAST UPDATED</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $searchResults = false;

                                                if (isset($_POST['search']) && !empty($_POST['search'])) {
                                                    $input = mysqli_real_escape_string($sqlc, $_POST['search']);
                                                    $searchql = "SELECT inventory.IN_ID, inventory.IN_NAME, inventory.IN_UNIT,
                                                                    MIN(intbatch.EXP_DATE) AS EARLIEST_EXPIRY,
                                                                    COALESCE(SUM(CASE WHEN intbatch.EXP_DATE >= CURDATE() THEN intbatch.BATCH_Q ELSE 0 END), 0) AS IN_QUANTITY,
                                                                    MAX(intbatch.DATE_ADDED) AS LAST_UPDATED
                                                                FROM inventory
                                                                LEFT JOIN intbatch ON inventory.IN_ID = intbatch.IN_ID
                                                                WHERE inventory.IN_NAME LIKE '%{$input}%' OR inventory.IN_UNIT LIKE '%{$input}%'
                                                                GROUP BY inventory.IN_ID
                                                                ORDER BY EARLIEST_EXPIRY ASC";

                                                    $query = mysqli_query($sqlc, $searchql);
                                                    $searchResults = true;
                                                } else {
                                                    $sql = "SELECT inventory.IN_ID, inventory.IN_NAME, inventory.IN_UNIT,
                                                                MIN(intbatch.EXP_DATE) AS EARLIEST_EXPIRY,
                                                                COALESCE(SUM(CASE WHEN intbatch.EXP_DATE >= CURDATE() THEN intbatch.BATCH_Q ELSE 0 END), 0) AS IN_QUANTITY,
                                                                MAX(intbatch.DATE_ADDED) AS LAST_UPDATED
                                                            FROM inventory
                                                            LEFT JOIN intbatch ON inventory.IN_ID = intbatch.IN_ID
                                                            GROUP BY inventory.IN_ID
                                                            ORDER BY EARLIEST_EXPIRY ASC";

                                                    $query = mysqli_query($sqlc, $sql);
                                                }
                                                if ($searchResults && mysqli_num_rows($query) == 0) {
                                                    echo "<h5 class ='text-danger text-center mt-3'> No Data Found</h5>";
                                                }
                                                if (mysqli_num_rows($query)): ?>
                                                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['IN_NAME']) ?></td>
                                                            <td><?= htmlspecialchars($row['IN_QUANTITY']) ?></td>
                                                            <td><?= htmlspecialchars($row['IN_UNIT']) ?></td>
                                                            <td><?= htmlspecialchars($row['LAST_UPDATED'] ?? '') ?></td>
                                                            <td>
                                                                <a href="updateing.php?INUPD=<?= $row['IN_ID'] ?>" class="btn btn-info"><i class="bi bi-pencil-square"></i></a>
                                                                <a href="delfunc.php?INDEL=<?= $row['IN_ID'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this?');"><i class="bi bi-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="8">
                                                            <center>No Ingredients</center>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    </body>

</html>
<?php } ?>