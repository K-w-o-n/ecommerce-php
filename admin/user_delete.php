<?php


require('Database/MySQL.php');

$stmt = $db->prepare("DELETE FROM users WHERE id=".$_GET['id']);

$stmt->execute();

header("Location: user_list.php");