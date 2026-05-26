<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
}

include 'db.php';

$limit = 3;

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$start = ($page - 1) * $limit;

$search = "";

if(isset($_GET['search'])){

    $search = $_GET['search'];

    $query = "SELECT * FROM posts 
              WHERE title LIKE '%$search%' 
              OR content LIKE '%$search%'
              LIMIT $start, $limit";

    $totalQuery = "SELECT COUNT(*) as total FROM posts
                   WHERE title LIKE '%$search%' 
                   OR content LIKE '%$search%'";

} else {

    $query = "SELECT * FROM posts 
              LIMIT $start, $limit";

    $totalQuery = "SELECT COUNT(*) as total FROM posts";
}

$result = mysqli_query($conn, $query);

$totalResult = mysqli_query($conn, $totalQuery);

$totalRow = mysqli_fetch_assoc($totalResult);

$totalPosts = $totalRow['total'];

$totalPages = ceil($totalPosts / $limit);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog Posts</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<form method="GET">

    <input type="text"
           name="search"
           placeholder="Search posts..."
           value="<?php echo $search; ?>">

    <button type="submit">Search</button>

</form>

<br>

<h2>All Posts</h2>

<a href="create.php">Add New Post</a>

<br><br>

<a href="logout.php">Logout</a>

<br><br>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

    <div class="post">

        <h3><?php echo $row['title']; ?></h3>

        <p><?php echo $row['content']; ?></p>

        <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>

        <a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>

        <hr>

    </div>

<?php } ?>
<br><br>

<div class="pagination">

<?php for($i = 1; $i <= $totalPages; $i++) { ?>

    <a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>">

        <button><?php echo $i; ?></button>

    </a>

<?php } ?>

</div>
</body>
</html>