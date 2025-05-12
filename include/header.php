<?php require_once("db/database.php"); ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <a class="navbar-brand" href="index.php">
        <img src="img/cherry.png" width="30" height="30" class="d-inline-block align-top" alt="">
        <img src="img/YNCSBanner.png" width="180" height="30" class="d-inline-block align-top" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon justify-content-around"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <?php if (isset($_SESSION["U_ID"])): ?>
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="menu.php">Menu</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="orders.php">Orders</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="cart.php">
                        <img src="img/cart.png" alt="" width="28" height="28">
                    </a>
                </li>
            </ul>
            <div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <img src="img/user.png" alt="" width="28" height="28">
                </a>
                <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
        <?php else: ?>
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="menu.php">Menu</a>
                </li>
            </ul>
            <div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <img src="img/user.png" alt="" width="28" height="28">
                </a>
                <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="login.php">Login</a></li>
                    <li><a class="dropdown-item" href="signup.php">Signup</a></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</nav>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>