<?php
require 'check_login.php';
include('header.php');
?>

<main>
    <h1>Welcome, <?php echo $_SESSION['user_id']; ?>!</h1>
    <p>This is your home page.</p>
</main>

<?php
include('footer.php');
?>
