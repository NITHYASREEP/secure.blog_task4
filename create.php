<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
}

include 'db.php';

$message = "";

if(isset($_POST['submit'])) {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Validation
    if(empty($title) || empty($content)){

        $message = "All fields are required!";

    } else {

        // Prepared Statement
        $stmt = $conn->prepare("INSERT INTO posts(title, content) VALUES(?, ?)");

        $stmt->bind_param("ss", $title, $content);

        if($stmt->execute()){

            header("Location: index.php");

        } else {

            $message = "Post creation failed!";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>Add New Post</h2>

<form method="POST">

    <input type="text"
           name="title"
           placeholder="Enter title"
           required>

    <br><br>

    <textarea name="content"
              placeholder="Enter content"
              required></textarea>

    <br><br>

    <button type="submit" name="submit">
        Add Post
    </button>

</form>

<br>

<p><?php echo $message; ?></p>

</div>

</body>
</html>