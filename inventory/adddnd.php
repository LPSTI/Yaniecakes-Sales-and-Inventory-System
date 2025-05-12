<!DOCTYPE html>
<html lang="en">
<?php
include("../db/database.php");
session_start();
if (!isset($_SESSION["U_ID"])|| $_SESSION["ROLE"] !== "manager") {
    header('location: ../index.php');
}
else {

    $error = array();
    $alert = array();
    if(isset($_POST['submit'])) {

        if(isset($_POST['DND_NAME']) && isset($_POST['DND_PRICE']) && isset($_POST['DND_CAT']) && isset($_POST['DND_RB'])) {
        
            $dndname = mysqli_real_escape_string($sqlc, $_POST['DND_NAME']);
            $dndprice = mysqli_real_escape_string($sqlc, $_POST['DND_PRICE']);
            $dndrb = mysqli_real_escape_string($sqlc, $_POST['DND_RB']);
            $dndcat = mysqli_real_escape_string($sqlc, $_POST['DND_CAT']);

            $dndcheck = mysqli_query($sqlc, "SELECT DND_NAME FROM dnd WHERE DND_NAME = '$dndname'");

            if($dndcheck && mysqli_num_rows($dndcheck) > 0 ) {
                $alert[] = "Menu already exists!";
            }
            else {
                
                if(isset($_FILES['DND_IMAGE']) && $_FILES['DND_IMAGE']['error'] === 0) {
                    
                    $loc = "foodimg/";
                    $imgname = basename($_FILES["DND_IMAGE"]["name"]);
                    $imgfile = $loc . $imgname;
                    $imgext = strtolower(pathinfo($imgfile, PATHINFO_EXTENSION));
                    $allowedext = ['jpg', 'jpeg', 'png', 'webp'];

                    if(in_array($imgext,$allowedext)) {

                        if(move_uploaded_file($_FILES["DND_IMAGE"]["tmp_name"], $imgfile)) {
                            
                            $mql = "INSERT INTO dnd(DND_NAME, DND_PRICE, DND_CAT, DND_RB, DND_IMAGE) 
                            VALUES ('$dndname', '$dndprice', '$dndcat', '$dndrb', '$imgfile')";
                            mysqli_query($sqlc, $mql);
                            $dndid = mysqli_insert_id($sqlc);
                            header("Location: addrec.php?DND_ID=$dndid");
                            exit();
                        }
                        else {
                            $alert[] = "Image upload Failed";
                        }
                    }
                    else {
                        $alert[] = "Invalid file type!";
                    }
                }
                else {
                    $alert[] = "Please select an Image";
                }
            }
        }
    }

    if(isset($_POST['add'])) {

        if(!empty($_POST['DND_NAME']) && !empty($_POST['DB_Q']) && !empty($_POST['EXP_DATE'])) {

            $dname = mysqli_real_escape_string($sqlc, $_POST['DND_NAME']);
            $dbq = intval($_POST['DB_Q']);
            $ed = $_POST['EXP_DATE'];

            $dndcheck = mysqli_query($sqlc, "SELECT DND_ID, DND_RB FROM dnd WHERE DND_NAME = '$dname'");

            if($dndcheck && mysqli_num_rows($dndcheck) > 0) {

                $dndrow = mysqli_fetch_assoc($dndcheck);
                $dndid = $dndrow['DND_ID'];
                $recipebatch = intval($dndrow['DND_RB']);

                $recipecheck = mysqli_query($sqlc,"SELECT recipe.IN_ID, recipe.R_QUANTITY, recipe.R_UNIT, inventory.IN_NAME, inventory.IN_UNIT
                                                   FROM recipe
                                                   JOIN inventory ON recipe.IN_ID = inventory.IN_ID
                                                   WHERE recipe.DND_ID = '$dndid'");
                $stock_low = false;
                $ingredients = array();

                while ($reciperow = mysqli_fetch_assoc($recipecheck)) {
                    $ingid = $reciperow['IN_ID'];
                    $ingname = $reciperow['IN_NAME'];
                    $ingquantity = $reciperow['R_QUANTITY'];

                    if ($recipebatch == 0 || $recipebatch == NULL) {
                        $deduct = 0;
                    }
                    else {
                        $deduct = ($ingquantity / $recipebatch) * $dbq;
                    }

                    $ingbatchcheck = mysqli_query($sqlc, "SELECT SUM(BATCH_Q) AS TOTAL_STOCK FROM intbatch 
                                                        WHERE IN_ID = '$ingid' AND BATCH_Q > 0 AND EXP_DATE >= CURDATE()");

                    $batchrow = mysqli_fetch_assoc($ingbatchcheck);
                    $available_stock = floatval($batchrow['TOTAL_STOCK']);

                    if ($deduct > $available_stock) {
                        $stock_low = true;
                        $error[] = "Available stocks are low for $ingname.";
                        break;
                    }

                    $ingredients[] = array('ingid' => $ingid, 'ingname' => $ingname, 'deduct' => $deduct);

                }

                if ($stock_low) {
                    $error[] = " Not Enough Ingredients in Stock";
                }
                else {
                    $inserbatch = "INSERT INTO dndbatch (DND_ID, DB_Q, EXP_DATE) VALUES ('$dndid', '$dbq', '$ed')";
                    mysqli_query($sqlc, $inserbatch);

                    foreach ($ingredients as $ing) {
                        $ingid = $ing['ingid'];
                        $ingname = $ing['ingname'];
                        $deduct = $ing['deduct'];
                        
                        $deductcheck = mysqli_query($sqlc, "SELECT B_ID, BATCH_Q FROM intbatch WHERE IN_ID = '$ingid' 
                                                            AND BATCH_Q > 0 AND EXP_DATE >= CURDATE() ORDER BY DATE_ADDED ASC");

                        $remaining = $deduct;
                        
                        while ($batchrow = mysqli_fetch_assoc($deductcheck)) {
                            $ingbatchid = $batchrow['B_ID'];
                            $ingbatchquantity = $batchrow['BATCH_Q'];

                            if ($remaining <= 0) {
                                break;
                            }

                            if ($ingbatchquantity >= $remaining) {
                                $nextbatchquantity = $ingbatchquantity - $remaining;
                                $updatebatch = "UPDATE intbatch SET BATCH_Q = $nextbatchquantity WHERE B_ID = '$ingbatchid'";
                                mysqli_query($sqlc, $updatebatch);
                                $remaining = 0;
                            }
                            else {
                                $remaining -= $ingbatchquantity;
                                $nextupdatebatch = "UPDATE intbatch SET BATCH_Q = 0 WHERE B_ID = '$ingbatchid'";
                                mysqli_query($sqlc, $nextupdatebatch);
                            }
                        }
                    }
                    $error[] = "Batch Added Successfully, Ingredients Deduction Complete";
                }
            }
            else {
                $error[] = "Drink/Dessert does not Exist!";
            }
        }
    }
    $cat = mysqli_query($sqlc, "SELECT * FROM category")
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Add Menu</title>
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
                                    <h4>Add Drinks & Desserts</h4>
                                </div>
                                <form action="" method="post" enctype="multipart/form-data">
                                <?php if(count($alert) > 0): ?>
                                <div class="alert alert-info text-center">
                                <?php foreach($alert as $showalert){echo $showalert;}?>
                                </div>
                                <?php endif; ?>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Drink or Dessert Name</label>
                                                    <input type="text" placeholder="Name" name="DND_NAME" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="CAT_ID">Category</label>
                                                    <select name="DND_CAT" class="form-control" required>
                                                        <option value="">Select</option>
                                                        <?php while($row = mysqli_fetch_assoc($cat)): ?>
                                                        <option value="<?=$row['CAT_NAME']; ?>">
                                                            <?php echo $row['CAT_NAME']?>
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
                                                    <input type="number" placeholder="₱" name="DND_PRICE" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Recipe Batch</label>
                                                    <input type="number" placeholder="Per Batch" name="DND_RB" class="form-control" required>
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
                                                    <label for="">Cancel</label><br>
                                                    <a href="adddnd.php" class="form-control btn btn-outline-secondary">Cancel</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="container-fluid">
                        <div class="col-lg-12">
                            <div class="card">
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
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Drink or Dessert Name</label>
                                                    <input type="text" name="DND_NAME" placeholder="Name" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Quantity</label>
                                                    <input type="number" name="DB_Q" placeholder="Amount" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Expiration Date</label>
                                                    <input type="date" name="EXP_DATE" placeholder="Name" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="">Save</label><br>
                                                    <input type="submit" name="add" value="Add" class="form-control btn btn-outline-secondary" onclick="return confirm('Are you sure you want to add new Batch?');">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Cancel</label><br>
                                                    <a href="adddnd.php" class="form-control btn btn-outline-secondary">Cancel</a>
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>