<?php
include 'db.php';

$message = "";

if(isset($_POST['register'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = "user";

    // Validation
    if(empty($username) || empty($password)){

        $message = "All fields are required!";

    } elseif(strlen($password) < 6){

        $message = "Password must be at least 6 characters!";

    } else {

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepared Statement
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");

        $stmt->bind_param("sss", $username, $hashedPassword, $role);

        if($stmt->execute()){

            $message = "Registration Successful!";

        } else {

            $message = "Registration Failed!";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <h2>Register</h2>

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

        <button type="submit" name="register">
            Register
        </button>

    </form>

    <br>

    <p><?php echo $message; ?></p>

</div>

</body>
</html>