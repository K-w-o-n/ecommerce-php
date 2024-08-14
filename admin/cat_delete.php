<?php

require('Database/MySQL.php');

$stmt = $db->prepare("DELETE FROM categories WHERE id=".$_GET['id']);

$stmt->execute();

header("Location: category.php");