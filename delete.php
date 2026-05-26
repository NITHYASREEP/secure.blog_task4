<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
}

include 'db.php';

// Only admin can delete
if($_SESSION['role'] != "admin"){

    die("Access Denied!");
}

$id = $_GET['id'];

// Prepared Statement
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");

$stmt->bind_param("i", $id);

if($stmt->execute()){

    header("Location: index.php");

} else {

    echo "Delete Failed!";
}

$stmt->close();
?>