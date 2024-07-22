<?php
session_start();
include('header.php');
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM User WHERE UserId = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user_id'] = $user['UserId'];
        header("Location: home.php");
        exit();
    } else {
        echo "<p>Invalid credentials.</p>";
    }
}
?>

<main>
    <h1>Login</h1>
    <form action="login.php" method="post">
        <label for="user_id">User ID:</label>
        <input type="text" id="user_id" name="user_id" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
</main>

<?php
include('footer.php');
?>
