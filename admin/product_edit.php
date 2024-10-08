<?php

session_start();
require('Database/MySQL.php');
require('Database/encap.php');


// if (empty($_SESSION['userid']) || empty($_SESSION['login']) || $_SESSION['role'] != 1) {
//     header('location: login.php');
// }

if ($_POST) {
    if (
        empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category'])
        || empty($_POST['price']) || empty($_POST['quantity']) || empty($_FILES['image'])
    ) {

        if (empty($_POST['name'])) {
            $nameError = 'Name is required';
        }
        if (empty($_POST['description'])) {
            $descError = 'Description is required';
        }
        if (empty($_POST['category'])) {
            $catError = 'Category is required';
        }

        if (empty($_POST['quantity'])) {
            $qtyError = 'Quantity is required';
        } elseif (is_numeric($_POST['quantity'] != 1)) {
            $qtyError = 'Quantity should be integer value';
        }
        if (empty($_POST['price'])) {
            $priceError = 'Price is required';
        } elseif (is_numeric($_POST['price'] != 1)) {
            $priceError = 'Price should be integer value';
        }

        if (empty($_FILES['image'])) {
            $imageError = 'Image is required';
        }
    } else {
        if (is_numeric($_POST['quantity'] != 1)) {
            $qtyError = 'Quantity should be integer value';
        }
        if (is_numeric($_POST['price']) != 1) {
            $priceError = 'Price should be integer value';
        }

        if ($_FILES['image']['name'] != null) {

            $file = 'images/' . ($_FILES['image']['name']);
            $imgType = pathinfo($file, PATHINFO_EXTENSION);

            if ($imgType != 'jpeg' && $imgType != 'jpg' && $imgType != 'png') {
                echo "<script>alert('Image should be jpeg,jpg or png')</script>";
            } else {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $category = $_POST['category'];
                $price = $_POST['price'];
                $quantity = $_POST['quantity'];
                $image = $_FILES['image']['name'];
                $id = $_POST['id'];



                move_uploaded_file($_FILES['image']['tmp_name'], $file);

                $stmt = $db->prepare("UPDATE products SET name=:name, description=:description, category_id=:category, price=:price,
                    quantity=:quantity, image=:image WHERE id=:id");

                $result = $stmt->execute([
                    ':name' => $name,
                    ':description' => $description,
                    ':category' => $category,
                    ':price' => $price,
                    ':quantity' => $quantity,
                    ':image' => $image,
                    ';id' => $id
                ]);

                if ($result) {
                    echo "<script>alert('Successfully updated');window.location.href='index.php'</script>";
                }
            }
        } else {
            $name = $_POST['name'];
                $description = $_POST['description'];
                $category = $_POST['category'];
                $price = $_POST['price'];
                $quantity = $_POST['quantity'];
                $id = $_POST['id'];


                $stmt = $db->prepare("UPDATE products SET name=:name, description=:description, category_id=:category, price=:price,
                    quantity=:quantity WHERE id=:id");

                $result = $stmt->execute([
                    ':name' => $name,
                    ':description' => $description,
                    ':category' => $category,
                    ':price' => $price,
                    ':quantity' => $quantity,
                    ':id' => $id
                ]);

                if ($result) {
                    echo "<script>alert('Successfully updated');window.location.href='index.php'</script>";
                }
        }
    }
}

$stmt = $db->prepare("SELECT * FROM products WHERE id=" . $_GET['id']);
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
                    <a href="user_list.php" class="list-group-item">
                        <span>Users</span>
                    </a>
                </div>
            </nav>
            <main class="col-10 bg-light p-3">
                <div class="row p-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">

                                <div>
                                    <h4>Create New Product</h4>
                                </div>
                                <input type="hidden" name="id" value="<?= $result[0]['id'] ?>">
                                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                                <div class="form-group mb-3">
                                    <label for="">Name</label>
                                    <p style="color:red"><?php echo empty($nameError) ? '' : '*' . $nameError; ?></p>
                                    <input type="text" class="form-control" name="name" value="<?= $result[0]['name'] ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Description</label>
                                    <p style="color:red"><?php echo empty($descError) ? '' : '*' . $descError; ?></p>
                                    <textarea class="form-control" name="description" rows="8" cols="80"><?= $result[0]['description'] ?></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <?php
                                    $catStmt = $db->prepare("SELECT * FROM categories");
                                    $catStmt->execute();
                                    $catResult = $catStmt->fetchAll();
                                    ?>
                                    <label for="">Category</label>
                                    <p style="color:red"><?php echo empty($catError) ? '' : '*' . $catError; ?></p>
                                    <select class="form-select" class="" name="category">
                                        <option value="">SELECT CATEGORY</option>
                                        <?php foreach ($catResult as $value) { ?>
                                            <?php if ($value['id'] == $result[0]['id']) : ?>
                                                <option value="<?php echo $value['id'] ?>" selected><?php echo $value['name'] ?></option>
                                            <?php else: ?>
                                                <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                            <?php endif ?>

                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Quantity</label>
                                    <p style="color:red"><?php echo empty($qtyError) ? '' : '*' . $qtyError; ?></p>
                                    <input type="number" class="form-control" name="quantity" value="<?= $result[0]['quantity'] ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Price</label>
                                    <p style="color:red"><?php echo empty($priceError) ? '' : '*' . $priceError; ?></p>
                                    <input type="number" class="form-control" name="price" value="<?= $result[0]['price'] ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Image</label>
                                    <p style="color:red"><?php echo empty($imageError) ? '' : '*' . $imageError; ?></p>
                                    <input type="file" name="image" value=""><br><br>
                                    <img class="img-fluid pad" src="images/<?= $result[0]['image'] ?>" style="height: 150px !important;">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success" name="" value="SUBMIT">
                                    <a href="index.php" class="btn btn-warning">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>

</body>

</html>