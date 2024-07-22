<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algonquin Social Media Network</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Algonquin Social Media Network</h1>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout</a>
            <a href="my_albums.php">My Albums</a>
            <a href="my_friends.php">My Friends</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="new_user.php">Sign Up</a>
        <?php endif; ?>
    </nav>
</header>
<main>
