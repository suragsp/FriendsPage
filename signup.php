<?php
session_start();
include('header.php');
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];

    $stmt = $pdo->prepare("INSERT INTO User (UserId, Password, Name) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $password, $name]);

    echo "Signup successful! You can now <a href='login.php'>login</a>.";
}
?>

<main>
    <h1>Signup</h1>
    <form action="signup.php" method="post">
        <label for="user_id">User ID:</label>
        <input type="text" id="user_id" name="user_id" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <button type="submit">Signup</button>
    </form>
</main>

<?php
include('footer.php');
?>
