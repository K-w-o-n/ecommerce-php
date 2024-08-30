<?php
session_start();
require 'Database/MySQL.php';

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}

if ($_POST) {
  $id = $_POST['id'];
  $qty = $_POST['qty'];

  $stmt = $db->prepare("SELECT * FROM products WHERE id=".$id);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($qty > $result['quantity']) {
    echo "<script>alert('!Not enough stock');window.location.href='product_detail.php?id=$id'</script>";
  }else{
    if (isset($_SESSION['cart']['id'.$id])) {
      $_SESSION['cart']['id'.$id] += $qty;
    }else{
      $_SESSION['cart']['id'.$id] = $qty;
    }

    header("Location: index.php");
  }


}

?>