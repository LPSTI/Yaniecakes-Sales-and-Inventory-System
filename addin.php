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
    $error = array();
    
    if(isset($_POST['submit'])) {
        
        if(isset($_POST['IN_NAME']) || isset($_POST['IN_UNIT'])) {

            $inname = mysqli_real_escape_string($sqlc, $_POST['IN_NAME']);
            $inunit = mysqli_real_escape_string($sqlc, $_POST['IN_UNIT']);

            $intcheck = mysqli_query($sqlc, "SELECT IN_NAME FROM inventory WHERE IN_NAME = '$inname'");

            if(mysqli_num_rows($intcheck) > 0 ) {
                $alert[] = "Ingredient already exists!";
            }
            else {
                $mql = "INSERT INTO inventory(IN_NAME, IN_UNIT) VALUES ('$inname', '$inunit')";
                mysqli_query($sqlc, $mql);
                $alert[] = "Added Successfully";
                header("Location: addin.php");
                exit();
            }
        }
    }

    if(isset($_POST['add'])) {
        if(isset($_POST['IN_NAME']) || isset($_POST['BATCH_Q']) || isset($_POST['EXP_DATE'])) {
    
            $innameb = mysqli_real_escape_string($sqlc, $_POST['IN_NAME']);
            $ibq = mysqli_real_escape_string($sqlc, $_POST['BATCH_Q']);
            $ed = mysqli_real_escape_string($sqlc, $_POST['EXP_DATE']);

            $batcheck = mysqli_query($sqlc, "SELECT IN_ID FROM inventory WHERE IN_NAME = '$innameb'");

            if($batcheck && mysqli_num_rows($batcheck) > 0) {
                $row = mysqli_fetch_assoc($batcheck);
                $ii = $row['IN_ID'];

                $bql = "INSERT INTO intbatch(IN_ID, BATCH_Q, EXP_DATE) VALUES ('$ii', '$ibq', '$ed')";
                mysqli_query($sqlc, $bql);
                $error[] = "Batch Added";
                header('location: addin.php');
                exit();
            }
            else {
                $error[] = "Ingredient not found!";
            }
        }
    }
    $int = mysqli_query($sqlc, "SELECT * FROM inventory");
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Add Ingredients</title>
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
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card mt-4">
                            <div class="card-header">
                                <h4>Add Ingredients</h4>
                            </div>
                            <form action="" method="post">
                            <?php if(count($alert) > 0): ?>
                            <div class="alert alert-info text-center">
                            <?php foreach($alert as $showalert){echo $showalert;}?>
                            </div>
                            <?php endif; ?>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="">Ingredient Name</label>
                                                <input type="text" placeholder="Name" name="IN_NAME" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="unit_id">Unit</label>
                                                <select name="IN_UNIT" class="form-control" required>
                                                    <option value="" selected disabled>--Select--</option>
                                                    <option value="g">g</option>
                                                    <option value="ml">ml</option>
                                                    <option value="pcs">pcs</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 col-md-3 mb-3">
                                            <div class="form-group">
                                                <label for="">Save</label><br>
                                                <input type="submit" name="submit" value="Submit" class="form-control btn btn-outline-secondary">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Cancel</label><br>
                                                <a href="addin.php" class="form-control btn btn-outline-secondary">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card mt-4">
                            <div class="card-header">
                                <h4>Add New Batch</h4>
                            </div>
                            <form action="" method="post">
                                <?php if(count($error) > 0): ?>
                                <div class="alert alert-info text-center">
                                <?php foreach($error as $showerror){echo $showerror;}?>
                                </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="">Ingredient</label>
                                                <select name="IN_NAME" class="form-control" required>
                                                    <option value="">Select</option>
                                                    <?php while($row = mysqli_fetch_assoc($int)): ?>
                                                    <option value="<?=$row['IN_NAME']; ?>">
                                                        <?php echo $row['IN_NAME']?>
                                                    </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-group">
                                                <label for="">Quantity</label>
                                                <input type="number" name="BATCH_Q" placeholder="Amount" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-5 md-2">
                                            <div class="form-group">
                                                <label for="">Expiration Date</label>
                                                <input type="date" name="EXP_DATE" placeholder="Name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-2 mb-2">
                                            <div class="form-group">
                                                <label for="">Save</label><br>
                                                <input type="submit" name="add" value="Add" class="form-control btn btn-outline-secondary">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label for="">Cancel</label><br>
                                                <a href="addin.php" class="form-control btn btn-outline-secondary">Cancel</a>
                                            </div>
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