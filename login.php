<?php
session_start();
include 'db.php';

$message = "";

if(isset($_POST['login'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validation
    if(empty($username) || empty($password)){

        $message = "All fields are required!";

    } else {

        // Prepared Statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");

        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        $user = $result->fetch_assoc();

        if($user && password_verify($password, $user['password'])){

            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php");

        } else {

            $message = "Invalid Username or Password!";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>Login</h2>

<form method="POST">

    <input type="text"
           name="username"
           placeholder="Username"
           required>

    <br><br>

    <input type="password"
           name="password"
           placeholder="Password"
           required>

    <br><br>

    <button type="submit" name="login">
        Login
    </button>

</form>

<br>

<p><?php echo $message; ?></p>

<a href="register.php">Register Here</a>

</div>

</body>
</html>