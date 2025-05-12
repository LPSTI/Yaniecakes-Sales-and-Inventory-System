<!DOCTYPE html>
<html lang="en">
<?php
include("../db/database.php");
session_start();
if (!isset($_SESSION["U_ID"])|| $_SESSION["ROLE"] !== "manager") {
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
    <title>Menu</title>
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
                        <div class="col-lg-12">
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4>Drinks n Desserts List
                                        <div style="float: inline-end;">
                                            <a href="dndbatchlist.php">
                                                <button class="btn btn-outline-secondary">
                                                    <span>Batch List</span>
                                                </button>
                                            </a>
                                        </div>
                                    </h4>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>IMAGE</th>
                                                <th>NAME</th>
                                                <th>QUANTITY</th>
                                                <th>INGREDIENTS</th>
                                                <th>CATEGORY</th>
                                                <th>PRICE</th>
                                                <th>DATE ADDED</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT dnd.*,
                                                        COALESCE((SELECT SUM(dndbatch.DB_Q) FROM dndbatch WHERE dndbatch.DND_ID = dnd.DND_ID 
                                                        AND dndbatch.EXP_DATE >= CURDATE()), 0) AS DND_QUANTITY,
                                                        GROUP_CONCAT(DISTINCT inventory.IN_NAME SEPARATOR ', ') AS INGREDIENTS
                                                    FROM dnd
                                                    LEFT JOIN recipe ON dnd.DND_ID = recipe.DND_ID
                                                    LEFT JOIN inventory ON recipe.IN_ID = inventory.IN_ID
                                                    GROUP BY dnd.DND_ID, dnd.DND_NAME";
                                                    
                                            $query = mysqli_query($sqlc, $sql);
                                            
                                            if (mysqli_num_rows($query) > 0): ?>
                                                <?php while($row = mysqli_fetch_assoc($query)): ?>
                                                    <tr>
                                                        <td>
                                                            <img src="<?= htmlspecialchars($row['DND_IMAGE'])?>" alt="" width="50" height="50">
                                                        </td>
                                                        <td><?= htmlspecialchars($row['DND_NAME'])?></td>
                                                        <td><?= htmlspecialchars($row['DND_QUANTITY'])?></td>
                                                        <td><?= htmlspecialchars($row['INGREDIENTS'])?></td>
                                                        <td><?= htmlspecialchars($row['DND_CAT'])?></td>
                                                        <td><?= htmlspecialchars($row['DND_PRICE'])?> Php</td>
                                                        <td><?= htmlspecialchars($row['DND_ADDED'])?></td>
                                                        <td>
                                                            <a href="updatednd.php?DNDUPD=<?= $row['DND_ID']?>" class="btn btn-info"><i class="bi bi-pencil-square"></i></a>
                                                            <a href="delfunc.php?DNDDEL=<?= $row['DND_ID']?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this?');"><i class="bi bi-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8">
                                                        <center>No Drinks or Desserts Found</center>
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
</body>
</html>
<?php } ?>