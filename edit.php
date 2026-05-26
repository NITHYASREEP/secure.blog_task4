<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
}

include 'db.php';

$message = "";

// Only admin can edit
if($_SESSION['role'] != "admin"){

    die("Access Denied!");
}

$id = $_GET['id'];

// Fetch post safely
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

if(isset($_POST['update'])) {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Validation
    if(empty($title) || empty($content)){

        $message = "All fields are required!";

    } else {

        // Prepared statement for update
        $update_stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");

        $update_stmt->bind_param("ssi", $title, $content, $id);

        if($update_stmt->execute()){

            header("Location: index.php");

        } else {

            $message = "Update failed!";
        }

        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>Edit Post</h2>

<form method="POST">

    <input type="text"
           name="title"
           value="<?php echo $row['title']; ?>"
           required>

    <br><br>

    <textarea name="content"
              required><?php echo $row['content']; ?></textarea>

    <br><br>

    <button type="submit" name="update">
        Update Post
    </button>

</form>

<br>

<p><?php echo $message; ?></p>

</div>

</body>
</html>