<?php
session_start();
require('../Database/MySQL.php');
require('../Database/encap.php');

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
        setcookie('search', null, -1, '/');
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
  
   


    <title>Ecommerce</title>

</head>

<body>
    <div class="container-fluid p-5">
        <div class="row bg-primary p-3 text-white">
            <div class="d-flex justify-content-between">
                <h4>Ecommerce</h4>
                <div>
                    <a href="../logout.php" type="button" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
        <div class="row gap-0 ">
            <nav class="col-2 bg-light pe-3" style="background: #0083aa;padding:0px;">
                <div class="list-group rounded-0 text-center text-lg-start">
                    <a href="../reports.php" class="list-group-item">
                        <span>Dashboard</span>
                    </a>
                    <a href="index.php" class="list-group-item">
                        <span>Products</span>
                    </a>
                    <a href="category.php" class="list-group-item">
                        <span>Categories</span>
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
                            <h4 class="me-2">Weekly Reports</h4>
                           
                        </div>
                        
                    </div>
                    <?php
                        $currentDate = date("Y-m-d");
                        $fromDate = date('Y-m-d',strtotime($currentDate . '+1 day'));
                        $toDate = date('Y-m-d',strtotime($currentDate . '-7 day'));

                        $stmt = $db->prepare("SELECT * FROM sales_orders WHERE order_date > :todate AND order_date <= :fromdate ORDER BY id DESC");
                        $stmt->execute([
                            ':todate' => $toDate, ':fromdate' => $fromDate
                        ]);
                        $result = $stmt->fetchAll();
                        // print_r($result);exit();
                     ?>

                    <table class="table table-striped table-bordered rounded-3 overflow-hidden" id="d-table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">UserId</th>
                                <th scope="col">Name</th>
                                <th scope="col">Total Amount</th>
                                <th scope="col">Order Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result) {
                                $i = 1;
                                foreach ($result as $value) { ?>

                                    <?php
                                    $userStmt = $db->prepare("SELECT * FROM users WHERE id=" . $value['user_id']);
                                    $userStmt->execute();
                                    $userResult = $userStmt->fetchAll();
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo encap($userResult[0]['name']) ?></td>
                                        <td><?php echo encap($value['total_price']) ?></td>
                                        <td><?php echo encap(date("Y-m-d",strtotime($value['order_date']))) ?></td>
                                        
                                    </tr>
                            <?php
                                    $i++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    
                </div>


            </main>

        </div>
    </div>
   
    <script>
        new DataTable('#d-table');
        $('#myTable').DataTable(); 
    </script>
    <script src="../js/bootstrap.bundle.min.js" defer></script>
   
</body>

</html>