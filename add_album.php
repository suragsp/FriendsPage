<?php
session_start();
require 'check_login.php';
include('header.php');
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $accessibility = $_POST['accessibility'];
    $ownerId = $_SESSION['user_id'];
    $dateUpdated = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO Album (Title, Description, Date_Updated, Owner_Id, Accessibility_Code) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $dateUpdated, $ownerId, $accessibility]);

    echo "Album created successfully!";
}

$accessibilities = $pdo->query("SELECT * FROM Accessibility")->fetchAll();
?>

<main>
    <h1>Create a New Album</h1>
    <form action="add_album.php" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea><br>
        <label for="accessibility">Accessibility:</label>
        <select id="accessibility" name="accessibility">
            <?php foreach ($accessibilities as $accessibility) : ?>
                <option value="<?= $accessibility['Accessibility_Code'] ?>"><?= $accessibility['Description'] ?></option>
            <?php endforeach; ?>
        </select><br>
        <button type="submit">Create Album</button>
    </form>
</main>

<?php
include('footer.php');
?>
