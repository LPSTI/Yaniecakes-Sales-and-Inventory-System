<!DOCTYPE html>
<html lang="en">
<?php
include("../db/database.php");
session_start();
if (!isset($_SESSION["U_ID"])|| $_SESSION["ROLE"] !== "manager") {
    header('location: ../index.php');
}
else {
    if(!isset($_GET['DND_ID'])) {
        header("Location: adddnd.php");
        exit();
    }

    $alert = array();
    $did = mysqli_real_escape_string($sqlc, $_GET['DND_ID']);
    $dql = mysqli_query($sqlc, "SELECT DND_NAME FROM dnd WHERE DND_ID = '$did'");
    $drow = mysqli_fetch_assoc($dql);
    $dname = $drow['DND_NAME'];

    $iql = mysqli_query($sqlc, "SELECT IN_ID, IN_NAME FROM inventory");

    if(isset($_POST['add'])) {

        if(isset($_POST['IN_ID']) || isset($_POST['R_QUANTITY']) || isset($_POST['R_UNIT'])) {

            $inid = intval($_POST['IN_ID']);
            $inq = floatval($_POST['R_QUANTITY']);
            $runit = mysqli_real_escape_string($sqlc, $_POST['R_UNIT']);

            $recipecheck = mysqli_query($sqlc, "SELECT DND_ID, IN_ID FROM recipe WHERE IN_ID = '$inid' AND DND_ID = '$did'");

            if($recipecheck && mysqli_num_rows($recipecheck) > 0 ) {
                $alert[] = "Ingredient already exists in the recipe!";
            }
            else {

            $query = "INSERT INTO recipe (DND_ID, IN_ID, R_QUANTITY, R_UNIT) VALUES ('$did', '$inid', '$inq', '$runit')";
            mysqli_query($sqlc, $query);
            $alert[] = "Ingredient added successfully";
            }
        }
    }

    if(isset($_POST['finish'])) {
        header("Location: dndlist.php");
        exit();
    }
    $int = mysqli_query($sqlc, "SELECT * FROM inventory");
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Add Recipe</title>
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
                                    <h4>Add Recipe for <?php echo $dname; ?></h4>
                                </div>
                                <form action="" method="post">
                                    <?php if(count($alert) > 0): ?>
                                    <div class="alert alert-warning text-center">
                                    <?php foreach($alert as $showalert){echo $showalert;}?>
                                    </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="IN_ID">Select Ingredients</label>
                                                    <select name="IN_ID" class="form-control custom-select">
                                                        <option value="">Choose</option>
                                                        <?php while($row = mysqli_fetch_assoc($int)): ?>
                                                            <option value="<?=$row['IN_ID']; ?>">
                                                                <?php echo htmlspecialchars($row['IN_NAME'])?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="R_QUANTITY">Amount</label>
                                                    <input type="number" placeholder="0" name="R_QUANTITY" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3"><div class="form-group">
                                                    <label for="unit_id">Unit</label>
                                                    <select name="R_UNIT" class="form-control">
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="g">g</option>
                                                        <option value="ml">ml</option>
                                                        <option value="pcs">pcs</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="">Add</label>
                                                    <input type="submit" name="add" class="form-control" value="Add" onclick="return confirm('Are you sure you want to add this?');">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Finish</label>
                                                    <input type="submit" name="finish" class="form-control" value="Submit" onclick="return confirm('Are you sure you want to finish adding ingredients?');">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="container-fluid">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Added Recipe</h4>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped-columns">
                                        <thead>
                                            <tr>
                                                <th>Menu</th>
                                                <th>Ingredient</th>
                                                <th>Amount</th>
                                                <th>Unit</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT recipe.*, inventory.IN_NAME, dnd.DND_NAME
                                                    FROM recipe
                                                    JOIN inventory ON inventory.IN_ID = recipe.IN_ID
                                                    JOIN dnd ON dnd.DND_ID = recipe.DND_ID
                                                    WHERE recipe.DND_ID = '$did'";
                                            
                                            $query = mysqli_query($sqlc, $sql);

                                            if (mysqli_num_rows($query)): ?>
                                                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['DND_NAME'])?></td>
                                                <td><?= htmlspecialchars($row['IN_NAME'])?></td>
                                                <td><?= htmlspecialchars($row['R_QUANTITY'])?></td>
                                                <td><?= htmlspecialchars($row['R_UNIT'])?></td>
                                                <td>
                                                    <a href="delfunc.php?RECDEL=<?= $row['RECIPE_ID']?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this?');"><i class="bi bi-trash"></i></a>
                                                </td>
                                            </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8">
                                                        <center>No Ingredients Listed</center>
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