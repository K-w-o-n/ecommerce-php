<?php
session_start();
require('Database/MySQL.php');
require('Database/encap.php');

if (empty($_SESSION['userid']) && empty($_SESSION['login'])) {
    header('location: index.php');
}

if ($_SESSION['role'] != 1) {
    header("Location: login.php");
}


if (!empty($_POST['search'])) {
    setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
} else {
    if (empty($_GET['pageno'])) {
        unset($_COOKIE['search']);
        setcookie('search', null, '-1', '/');
    }
}

if (!empty($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

$numOfrecs = 3;
$offset = ($pageno - 1) * $numOfrecs;

$stmt = $db->prepare("SELECT * FROM sales_orders ORDER BY id DESC");
$stmt->execute();
$rawResult = $stmt->fetchAll();

$total_pages = ceil(count($rawResult) / $numOfrecs);

$stmt = $db->prepare("SELECT * FROM sales_orders ORDER BY id DESC LIMIT $offset,$numOfrecs");
$stmt->execute();
$result = $stmt->fetchAll();






?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="../js/bootstrap.bundle.min.js" defer></script>
    <title>Ecommerce</title>


</head>

<body>
    <div class="container-fluid p-5">
        <div class="row bg-primary p-3 text-white">
            <div class="d-flex justify-content-between">
                <h4>Ecommerce</h4>
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
                    <a href="category.php" class="list-group-item">
                        <span>Categories</span>
                    </a>
                    <a href="index.php" class="list-group-item">
                        <span>Products</span>
                    </a>
                    <a href="user_list.php" class="list-group-item">
                        <span>Users</span>
                    </a>
                    <a href="order_list.php" class="list-group-item">
                        <span>Orders</span>
                    </a>
                </div>
            </nav>
            <main class="col-10 bg-light p-3">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between bg-primary text-white p-2">
                        <div class="d-flex">
                            <h4 class="me-2">Ecommerce- <span class="text-white-50">orders</span></h4>
                        </div>

                    </div>
                    <table class="table table-striped table-bordered rounded-3 overflow-hidden">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">name</th>
                                <th scope="col">Price</th>
                                <th>Actions</th>
                                <th scope="col">Created_at</th>
                            </tr>
                        </thead>
                        <?php

                        if ($result) {
                            //$i = 1;
                            foreach ($result as $value) { ?>
                            
                                <?php
                                
                                $userStmt = $db->prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                                $userStmt->execute();
                                $userResult = $userStmt->fetchAll();
                               
                                ?>
                               
                                <tbody>
                                    <tr>
                                        <td><?= $value['id'] ?></td>
                                        <td><?php echo encap($userResult[0]['name']) ?></td>
                                        <td><?php echo encap($value['total_price']) ?></td>
                                        <td>
                                            <a href="order_detail.php?id=<?php echo $value['id'] ?>" class="btn btn-outline-primary" type='button'>view</a>
                                        </td>
                                        <td><?php echo encap(date('Y-m-d',strtotime($value['order_date']))) ?></td>
                                    </tr>
                                </tbody>

                        <?php

                                //$i++;
                            }
                        }


                        ?>
                    </table>
                    <div class="d-flex align-items-center justify-content-center">
                        <nav aria-label="Page navigation example" style="float:right">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                                <li class="page-item <?php if ($pageno <= 1) {
                                                            echo 'disabled';
                                                        } ?>">
                                    <a class="page-link" href="<?php if ($pageno <= 1) {
                                                                    echo '#';
                                                                } else {
                                                                    echo "?pageno=" . ($pageno - 1);
                                                                } ?>">Previous</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                                <li class="page-item <?php if ($pageno >= $total_pages) {
                                                            echo 'disabled';
                                                        } ?>">
                                    <a class="page-link" href="<?php if ($pageno >= $total_pages) {
                                                                    echo '#';
                                                                } else {
                                                                    echo "?pageno=" . ($pageno + 1);
                                                                } ?>">Next</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                            </ul>
                        </nav>
                    </div>


                </div>


            </main>

        </div>
    </div>

</body>

</html>