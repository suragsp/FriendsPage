<?php
session_start();
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert new user into the database
    $stmt = $pdo->prepare("INSERT INTO User (UserId, Name, Phone, Password) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$userId, $name, $phone, $password])) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error: Could not create user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Sign Up</h1>
    </header>
    <main>
        <form action="new_user.php" method="post">
            <label for="user_id">User ID:</label>
            <input type="text" id="user_id" name="user_id" required>
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone">
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Sign Up</button>
        </form>
    </main>
</body>
</html>
