<?php
session_start();

require('Database/MySQL.php');
require('Database/encap.php');

if(empty($_SESSION['userid']) && empty($_SESSION['login'])) {
    header('Location: /admin/login.php');
}

if($_SESSION['role'] != 1) {
    header('Location: /admin/login.php');
}

if($_POST) {
    if(empty($_POST['name']) || empty($_POST['description'])) {

        if(empty($_POST['name'])) {
            $nameError = 'Category name is required';
        }

        if(empty($_POST['description'])) {
            $descError = 'Category description is required';
        }
    } else {

        $name = $_POST['name'];
        $description = $_POST['description'];
        $id = $_POST['id'];

        $stmt = $db->prepare('UPDATE categories SET name=:name ,description=:description WHERE id=:id');

        $result = $stmt->execute(
            [
                ':name' => $name,
                ':description' => $description,
                ':id' => $id
            ]
        );

        if($result) {
            echo "<script>alert('Category updated');window.location.href='category.php';</script>";
        }
    }
}


$stmt = $db->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);

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

    <title>category</title>

</head>

<body>
    <div class="container-fluid p-5">
        <div class="row bg-primary p-3 text-white">
            <h4>Kwon blogs</h4>
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
                            <form action='cat_edit.php' method='post' >
                            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                             <input type="hidden" name="id" value="<?= encap($result[0]['id'])?>">
                                <div><h4>Create New Category</h4></div>
                                <div class="form-group mb-3">
                                    
                                    <label>Name</label><p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                                    <input type="text" class="form-control" name='name' value="<?= encap($result[0]['name'])?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Description</label><p style="color:red"><?php echo empty($descError) ? '' : '*'.$descError; ?></p>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name='description'>
                                    <?= encap($result[0]['description'])?>
                                    </textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="category.php" type='button' class='btn btn-default'>Back</a>
                            </form>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>

</body>

</html>