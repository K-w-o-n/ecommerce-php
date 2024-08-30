<?php
session_start();
require('Database/MySQL.php');

if (empty($_SESSION['userid']) && empty($_SESSION['login']) && $_SESSION['role'] != 1) {
    header('location: login.php');
}




$stmt = $db->prepare("SELECT *  FROM users");
$result = $stmt->execute();
$users = $stmt->fetchAll();
$userCount = count($users);

$stmtblog = $db->prepare("SELECT *  FROM products");
$resultblog = $stmtblog->execute();
$products = $stmtblog->fetchAll();
$productsCount = count($products);

$stmtblog = $db->prepare("SELECT *  FROM categories");
$resultblog = $stmtblog->execute();
$categories = $stmtblog->fetchAll();
$categoriesCount = count($categories);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/bootstrap.bundle.min.js" defer></script>

    <title>Blog</title>

</head>

<body>
    <div class="container-fluid p-5">
        <div class="row bg-primary p-3 text-white">
            <div class="d-flex justify-content-between">
                <h4>Kwon blogs</h4>
                <div>
                    <a href="logout.php" type="button" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
        <div class="row gap-0 ">
            <nav class="col-2 bg-light pe-3" style="background: #0083aa;padding:0px;">
                <div class="list-group rounded-0 text-center text-lg-start">
                    <a href="dashboard.php" class="list-group-item">
                        <span>Dashboard</span>
                    </a>
                    <a href="user_list.php" class="list-group-item">
                        <span>Users</span>
                    </a>
                    <a href="index.php" class="list-group-item">
                        <span>Products</span>
                    </a>
                    <a href="category.php" class="list-group-item">
                        <span>Categories</span>
                    </a>
                </div>
            </nav>
            <main class="col-10 bg-light p-3">
                <div class="container-fluid" style="height: 600px;">
                    <div class="d-flex justify-content-between bg-primary text-white p-2">
                        <div class="d-flex">
                            <h4 class="me-2">Ecommerce</h4>
                        </div>
                        
                    </div>

                    <div class="row flex-column flex-lg-row p-3">
                        <div class="col text-center">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h3 class="card-title h2">Weely Reports</h3>
                                    <a href="reports/weekly_report.php"> Weekly Reports</a>
                                </div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h3 class="card-title h2">Monthy Reports</h3>
                                    <a href="reports/monthly_report.php"> Monthy Reports</a>
                                </div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h3 class="card-title h2">Royal Users</h3>
                                    <a href="reports/royal_users.php">Royal users</a>
                                </div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h3 class="card-title h2">Best Seller Items</h3>
                                    <a href="reports/best_seller_item.php">Best Seller Item</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        </main>
    </div>
    </div>

</body>

</html>