<!DOCTYPE html>
<html lang="en">
<?php
include("../db/database.php");
session_start();
if (!isset($_SESSION["U_ID"]) || $_SESSION["ROLE"] !== "manager") {
    header('location: ../index.php');
} else {

    if (isset($_POST['confirm_payment'])) {

        $oid = mysqli_real_escape_string($sqlc, $_POST['order_id']);
        $updateps = "UPDATE orders SET payment_status = 'Confirmed' WHERE order_id = '$oid'";
        mysqli_query($sqlc, $updateps);
        header('location: orderlist.php');
        exit();
    }

    if (isset($_POST['update_order_status'])) {

        $oid = mysqli_real_escape_string($sqlc, $_POST['order_id']);

        switch ($_POST['order_status']) {
            case 'Order Delivered':
                $newstatus = "UPDATE orders SET order_status = 'Order Complete' WHERE order_id = '$oid'";
                mysqli_query($sqlc, $newstatus);
                header('location: orderlist.php');
                exit();
                break;
            case 'On the Way':
                $newstatus = "UPDATE orders SET order_status = 'Order Delivered' WHERE order_id = '$oid'";
                mysqli_query($sqlc, $newstatus);
                header('location: orderlist.php');
                exit();
                break;
            case 'Processing':
                $newstatus = "UPDATE orders SET order_status = 'On the Way' WHERE order_id = '$oid'";
                mysqli_query($sqlc, $newstatus);
                header('location: orderlist.php');
                exit();
                break;
            case 'Pending':
                $newstatus = "UPDATE orders SET order_status = 'Processing' WHERE order_id = '$oid'";
                mysqli_query($sqlc, $newstatus);
                header('location: orderlist.php');
                exit();
                break;
        }
    }
?>
<?php } ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Orders</title>
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
            <nav class="bg-light sidebar p-3" style="height: auto; position: sticky;">
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
            <div class="container-fluid overflow-x-hidden">
                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4>Order List</h4>
                                    <form action="" method="post">
                                        <input name="search" type="text" class="form-control" id="live_search" autocomplete="off" placeholder="Search...">
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Check Status</th>
                                                <th>Order Status</th>
                                                <th>Customer</th>
                                                <th>Order</th>
                                                <th>Total Price</th>
                                                <th>Order Placed</th>
                                                <th>Order Method</th>
                                                <th>Pickup or Delivery</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $searchResults = false;

                                            if (isset($_POST['search']) && !empty($_POST['search'])) {
                                                $input = mysqli_real_escape_string($sqlc, $_POST['search']);
                                                $searchql = "SELECT * 
                                                            FROM orders 
                                                            WHERE order_status LIKE '%{$input}%' OR method LIKE '%{$input}%' OR order_placed LIKE '%{$input}%' OR user_name LIKE '%{$input}%'
                                                            ORDER BY order_placed DESC";

                                                $query = mysqli_query($sqlc, $searchql);
                                                $searchResults = true;
                                            } else {
                                                $sql = "SELECT * FROM orders ORDER BY order_placed DESC";

                                                $query = mysqli_query($sqlc, $sql);
                                            }
                                            if ($searchResults && mysqli_num_rows($query) == 0) {
                                                echo "<h5 class ='text-danger text-center mt-3'> No Data Found</h5>";
                                            }

                                            if (mysqli_num_rows($query) > 0): ?>
                                                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                                    <tr>
                                                        <td>
                                                            <form action="" method="post">
                                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $row['order_id'] ?>">
                                                                    Check Status
                                                                </button>
                                                                <div class="modal fade" id="staticBackdrop<?= $row['order_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Order Status</h1>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="p-3 m-0">
                                                                                    <div class="row">
                                                                                        <div class="col">
                                                                                            <label class="font-weight-bold">Customer Name: </label>
                                                                                            <p class="text-capitalize"><?= htmlspecialchars($row['user_name']) ?></p>
                                                                                        </div>
                                                                                        <div class="col">
                                                                                            <label class="font-weight-bold">Order Placed: </label>
                                                                                            <p><?= htmlspecialchars($row['order_placed']) ?></p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col">
                                                                                            <label class="font-weight-bold">Contact: </label>
                                                                                            <p><?= htmlspecialchars($row['contact']) ?></p>
                                                                                        </div>
                                                                                        <div class="col">
                                                                                            <label class="font-weight-bold">Order List: </label>
                                                                                            <p><?= htmlspecialchars($row['order_list']) ?></p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col">
                                                                                            <label class="font-weight-bold">Email: </label>
                                                                                            <p><?= htmlspecialchars($row['email']) ?></p>
                                                                                        </div>
                                                                                        <div class="col">
                                                                                            <label class="font-weight-bold">Total Price: </label>
                                                                                            <p><?= htmlspecialchars($row['total_price']) ?> ₱</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col">
                                                                                            <label class="font-weight-bold">Payment Method: </label>
                                                                                            <p><?= htmlspecialchars($row['method']) ?></p>
                                                                                        </div>
                                                                                        <div class="col">
                                                                                            <label class="font-weight-bold">Address: </label>
                                                                                            <p><?= htmlspecialchars($row['address']) ?></p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col">
                                                                                            <label class="font-weight-bold">Payment Status: </label>
                                                                                            <p>
                                                                                            <form action="" method="post">
                                                                                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['order_id']) ?>">
                                                                                                <button type="submit" name="confirm_payment" class="btn <?= htmlspecialchars($row['payment_status'] == 'Confirmed' ? 'btn-success disabled' : 'btn-warning') ?> mt-auto"
                                                                                                    <?= htmlspecialchars($row['payment_status'] == 'Confirmed' ? 'disabled' : '') ?> onclick="return confirm('Are you sure you want to continue?');">
                                                                                                    <?= htmlspecialchars($row['order_status'] == 'Confirmed' ? 'Payment Confirmed' : 'Confirm Payment') ?>
                                                                                                </button>
                                                                                            </form>
                                                                                            </p>
                                                                                        </div>
                                                                                        <div class="col">
                                                                                            <label class="font-weight-bold">Order Status: </label>
                                                                                            <form action="" method="post">
                                                                                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['order_id']) ?>">
                                                                                                <?php
                                                                                                switch ($row['order_status']) {
                                                                                                    case 'Order Complete':
                                                                                                        $btnlbl = 'Order Complete';
                                                                                                        $btnclass = 'btn btn-success';
                                                                                                        $disable = true;
                                                                                                        break;
                                                                                                    case 'Order Delivered':
                                                                                                        $btnlbl = 'Complete Order';
                                                                                                        $btnclass = 'btn btn-secondary';
                                                                                                        $disable = false;
                                                                                                        break;
                                                                                                    case 'On the Way':
                                                                                                        $btnlbl = 'Order Delivered';
                                                                                                        $btnclass = 'btn btn-dark';
                                                                                                        $disable = false;
                                                                                                        break;
                                                                                                    case 'Processing':
                                                                                                        $btnlbl = 'Deliver Order';
                                                                                                        $btnclass = 'btn btn-primary';
                                                                                                        $disable = false;
                                                                                                        break;
                                                                                                    default:
                                                                                                        if ($row['payment_status'] == 'Confirmed') {
                                                                                                            $btnlbl = 'Process Order';
                                                                                                            $btnclass = 'btn btn-info';
                                                                                                            $disable = false;
                                                                                                        } else {
                                                                                                            $btnlbl = 'Waiting Payment';
                                                                                                            $btnclass = 'btn btn-danger';
                                                                                                            $disable = true;
                                                                                                        }
                                                                                                        break;
                                                                                                }
                                                                                                ?>
                                                                                                <button type="submit" name="update_order_status" class="btn 
                                                                                                        <?= $btnclass ?> mt-auto" <?= $disable ? 'disabled' : '' ?> onclick="return confirm('Are you sure you want to continue?');">
                                                                                                    <?= $btnlbl ?>
                                                                                                </button>
                                                                                                <input type="hidden" name="order_status" value="<?= htmlspecialchars($row['order_status']) ?>">
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </td>
                                                        <td><?= htmlspecialchars($row['order_status']) ?></td>
                                                        <td class="text-capitalize"><?= htmlspecialchars($row['user_name']) ?></td>
                                                        <td><?= nl2br(htmlspecialchars($row['order_list'])) ?></td>
                                                        <td><?= htmlspecialchars($row['total_price']) ?> ₱</td>
                                                        <td><?= htmlspecialchars($row['order_placed']) ?></td>
                                                        <td><?= htmlspecialchars($row['method'])?></td>
                                                        <td><?= htmlspecialchars($row['pickordel'])?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                                <tr>
                                                <?php else: ?>
                                                    <td colspan=11>
                                                        <center>No Orders Found</center>
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