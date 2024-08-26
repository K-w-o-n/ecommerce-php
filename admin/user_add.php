<?php
session_start();

require('Database/MySQL.php');
require('Database/encap.php');

// if (empty($_SESSION['userid']) || empty($_SESSION['login']) || $_SESSION['role'] != 1) {
//     header("Location: login.php");
// }

if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['address']) || empty($_POST['password']) || strlen($_POST['password'] < 4)) {
        if (empty($_POST['name'])) {
            $nameError = 'Name cannot be null';
        }
        if (empty($_POST['email'])) {
            $emailError = 'Email cannot be null';
        }
        if (empty($_POST['phone'])) {
            $phoneError = 'Phone cannot be null';
        }
        if (empty($_POST['address'])) {
            $addressError = 'Address cannot be null';
        }
        if (empty($_POST['password'])) {
            $passswordError = 'Password cannot be null';
        }
        if (strlen($_POST['password']) < 4) {
            $passswordError = 'Password should be 4 characters at least';
        }
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if (empty($_POST['role'])) {
            $role = 0;
        } else {
            $role = 1;
        }


        $stmt = $db->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            echo "<script>alert('Email duplicated')</script>";
        } else {

            $stmt = $db->prepare("INSERT INTO users(name,email,phone,address,password,role) VALUES (:name,:email,:phone,:address,:password,:role)");

            $result = $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':address' => $address,
                ':password' => $password,
                ':role' => $role
            ]);

            if ($result) {
                echo "<script>alert('Successfully added');window.location.href='user_list.php'</script>";
            }
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/bootstrap.bundle.min.js" defer></script>

    <title>User</title>

</head>

<body>
    <div class="container-fluid p-5">
        <div class="row bg-primary p-3 text-white">
            <h4>Ecommerce</h4>
        </div>
        <div class="row gap-0 ">
            <nav class="col-2 bg-light pe-3" style="background: #0083aa;padding:0px;">
                <div class="list-group rounded-0 text-center text-lg-start">
                    <a href="dashboard.php" class="list-group-item">
                        <span>Dashboard</span>
                    </a>
                    <a href="user_list.php" class="list-group-item">
                        <span>Products</span>
                    </a>
                    <a href="index.php" class="list-group-item">
                        <span>Category</span>
                    </a>
                    <a href="user_list.php" class="list-group-item">
                        <span>Users</span>
                    </a>
                </div>
            </nav>
            <main class="col-10 bg-light p-3">
                <div class="row p-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action='user_add.php' method='post'>
                                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                                <div>
                                    <h4>Create New User</h4>
                                </div>
                                <div class="form-group mb-3">
                                    <label><b>Name</b></label>
                                    <p style="color:red"><?php echo empty($nameError) ? "" : "*" . $nameError ?></p>
                                    <input type="text" class="form-control" name='name'>
                                </div>
                                <div class="form-group mb-3">
                                    <label><b>Email</b></label>
                                    <p style="color:red"><?php echo empty($emailError) ? "" : "*" . $emailError ?></p>
                                    <input type="email" name="email" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label><b>Phone</b></label>
                                    <p style="color:red"><?php echo empty($phoneError) ? "" : "*" . $phoneError ?></p>
                                    <input type="text" name="phone" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label><b>Address</b></label>
                                    <p style="color:red"><?php echo empty($addressError) ? "" : "*" . $addressError ?></p>
                                    <input type="text" name="address" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label><b>Password</b></label>
                                    <p style="color:red"><?php echo empty($passswordError) ? "" : "*" . $passswordError ?></p>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="checkbox" name="role" value="1">
                                    <label for="vehicle3"> <b>Admin</b></label>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="user_list.php" type='button' class='btn btn-default'>Back</a>
                            </form>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>

</body>

</html>