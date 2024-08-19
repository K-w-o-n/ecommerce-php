<?php
session_start();

require('Database/MySQL.php');
require('Database/encap.php');

if (empty($_SESSION['userid']) && empty($_SESSION['login'])) {
    header('Location: /admin/login.php');
  }
  if ($_SESSION['role'] != 1) {
    header('Location: /admin/login.php');
  }
  
  if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['email'])|| empty($_POST['phone']) || empty($_POST['address'])) {
      if (empty($_POST['name'])) {
        $nameError = 'Name cannot be null';
      }
      if (empty($_POST['phone'])) {
        $phoneError = 'Phone is required';
      }
      if (empty($_POST['address'])) {
        $addressError = 'Address is required';
      }
      if (empty($_POST['email'])) {
        $emailError = 'Email cannot be null';
      }
    }elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
      $passwordError = 'Password should be 4 characters at least';
    }else{
      $id = $_POST['id'];
      $name = $_POST['name'];
      $email = $_POST['email'];
      $phone = $_POST['phone'];
      $address = $_POST['address'];
      $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
  
      if (empty($_POST['role'])) {
        $role = 0;
      }else{
        $role = 1;
      }
  
      $stmt = $db->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
      $stmt->execute(array(':email'=>$email,':id'=>$id));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
      if ($user) {
        echo "<script>alert('Email duplicated')</script>";
      }else{
        if ($password != null) {
          $stmt = $db->prepare("UPDATE users SET name='$name',email='$email',password='$password',phone='$phone',address='$address',role='$role' WHERE id='$id'");
        }else{
          $stmt = $db->prepare("UPDATE users SET name='$name',email='$email',phone='$phone',address='$address',role='$role' WHERE id='$id'");
        }
        $result = $stmt->execute();
        if ($result) {
          echo "<script>alert('Successfully Updated');window.location.href='user_list.php';</script>";
        }
      }
    }
  }
  
  $stmt = $db->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
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
    <script src="js/bootstrap.bundle.min.js" defer></script>

    <title>user</title>

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

                </div>
            </nav>
            <main class="col-10 bg-light p-3">
                <div class="row p-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action='' method='post'>
                                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                                <input type="hidden" name="id" value="<?= encap($result[0]['id'])?>">
                                <div>
                                    <h4>Edit user</h4>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Name</label>
                                    <p style="color:red"><?php echo empty($nameError) ? "" : "*" . $nameError ?></p>
                                    <input type="text" class="form-control" name='name' value="<?= $result[0]['name']?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Email</label>
                                    <p style="color:red"><?php echo empty($emailError) ? "" : "*" . $emailError ?></p>
                                    <input type="email" name="email" class="form-control" value="<?=$result[0]['email']?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Phone</label>
                                    <p style="color:red"><?php echo empty($phoneError) ? "" : "*" . $phoneError ?></p>
                                    <input type="text" name="phone" class="form-control" value="<?=$result[0]['phone']?>">
                                </div>
                                
                                
                                <div class="form-group mb-3">
                                    <input type="checkbox" name="role" value="<?php echo $result[0]['role'] == 1 ? 'checked':''?>">
                                    <label for="vehicle3"></label>
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
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