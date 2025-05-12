<!DOCTYPE html>
<html lang="en">
<?php
include("../db/database.php");
session_start();
if (!isset($_SESSION["U_ID"])|| $_SESSION["ROLE"] !== "manager") {
    header('location: ../index.php');
} else {

    $dnd = "SELECT * FROM dnd WHERE DND_ID = '$_GET[DNDUPD]'";
    $query = mysqli_query($sqlc, $dnd);
    $row = mysqli_fetch_assoc($query); 

    if(isset($_POST['submit'])) {
        
        if(isset($_POST['DND_NAME']) && isset($_POST['DND_PRICE']) && isset($_POST['DND_CAT']) && isset($_POST['DND_RB'])) {
        
            $dndname = mysqli_real_escape_string($sqlc, $_POST['DND_NAME']);
            $dndprice = mysqli_real_escape_string($sqlc, $_POST['DND_PRICE']);
            $dndrb = mysqli_real_escape_string($sqlc, $_POST['DND_RB']);
            $dndcat = mysqli_real_escape_string($sqlc, $_POST['DND_CAT']);

            $mql = "UPDATE dnd SET DND_NAME = '$dndname', DND_PRICE = '$dndprice', DND_CAT = '$dndcat', DND_RB = '$dndrb' WHERE DND_ID = '$_GET[DNDUPD]'";
            mysqli_query($sqlc, $mql);
            $dndid = $_GET['DNDUPD'];
            header("Location: addrec.php?DND_ID=$dndid");
            exit();
        }
    }
    $cat = mysqli_query($sqlc, "SELECT * FROM category");
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Update Menu</title>
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
                                    <h4>Update DND</h4>
                                </div>
                                <div class="card-body">
                                    <form action="" method="post">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Dessert/Drink Name</label>
                                                    <input type="text" name="DND_NAME" value="<?= htmlspecialchars($row['DND_NAME'])?>" class="form-control" placeholder="Dessert/Drink Name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="CAT_ID">Category</label>
                                                    <select name="DND_CAT" class="form-control" required>
                                                        <option value="">Select</option>
                                                        <?php while($catrow = mysqli_fetch_assoc($cat)): ?>
                                                        <option value="<?=$catrow['CAT_NAME']; ?>">
                                                            <?php echo $catrow['CAT_NAME']?>
                                                        </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Image</label>
                                                    <input type="file" name="DND_IMAGE" class="form-control" accept="image/jpg, image/jpeg, image/png, image/webp" required>
                                                </div>
                                            </div>
                                        </div>        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Price</label>
                                                    <input type="text" name="DND_PRICE" value="<?= htmlspecialchars($row['DND_PRICE'])?>" class="form-control" placeholder="Price" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Recipe Batch Size</label>
                                                    <input type="text" name="DND_RB" value="<?= htmlspecialchars($row['DND_RB'])?>" class="form-control" placeholder="Batch Size" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Save</label><br>
                                                    <input type="submit" name="submit" value="Submit" class="form-control btn btn-outline-secondary">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Cancel</label>
                                                    <a href="dndlist.php" class="form-control btn btn-danger">Cancel</a>
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