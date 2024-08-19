<?php
session_start();
require('Database/MySQL.php');
require('Database/encap.php');

if ($_POST) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute([
        ':email' => $email
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // password_verify($password,$user['password'])

    if ($user) {
        if ($user['password'] == $password) {
            $_SESSION['userid'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['login'] = time();
            $_SESSION['role'] = 1;

            header("location: index.php");
        }
    }

    echo "<script>alert('Incorrect credentials')</script>";
}


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
    <div class="wrap">
        <div class="container d-flex justify-content-center m-auto">
            <div class="col-12 col-md-6 p-5 shadow-lg rounded">
                <h3 class='text-center mt-3 text-dark mb-5'>Admin | <span class="text-muted h-5">Login</span></h3>
                <form action="login.php" method='post'>
                    <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                    <div class='mb-3'>
                        <label for="">Email</label>
                        <input type="text" name="email" class='form-control'>
                    </div>
                    <div class='mb-3'>
                        <label for="">Password</label>
                        <input type="password" name="password" class='form-control'>
                    </div>
                    <button class='btn btn-primary w-100 fs'>Log in</button>
                </form>
                <p class='text-center text-muted mt-3'>If u don't have account register <a href="register.php">here!</a></p>
            </div>
        </div>
    </div>
</body>

</html>

<!-- password_verify($password,$user['password']) -->