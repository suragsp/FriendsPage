<?php
session_start();
include('header.php');
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $albumId = $_POST['album_id'];
    $stmt = $pdo->prepare("DELETE FROM Album WHERE Album_Id = ?");
    $stmt->execute([$albumId]);

    echo "Album deleted successfully!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_changes'])) {
    $albumId = $_POST['album_id'];
    $accessibility = $_POST['accessibility'];
    $stmt = $pdo->prepare("UPDATE Album SET Accessibility_Code = ? WHERE Album_Id = ?");
    $stmt->execute([$accessibility, $albumId]);

    echo "Album updated successfully!";
}

$albums = $pdo->prepare("SELECT * FROM Album WHERE Owner_Id = ?");
$albums->execute([$userId]);
$albums = $albums->fetchAll();

$accessibilities = $pdo->query("SELECT * FROM Accessibility")->fetchAll();
?>

<main>
    <h1>My Albums</h1>
    <a href="add_album.php">Create New Album</a>
    <ul>
        <?php foreach ($albums as $album) : ?>
            <li>
                <h2><?= $album['Title'] ?></h2>
                <p><?= $album['Description'] ?></p>
                <form action="my_albums.php" method="post">
                    <input type="hidden" name="album_id" value="<?= $album['Album_Id'] ?>">
                    <label for="accessibility">Accessibility:</label>
                    <select id="accessibility" name="accessibility">
                        <?php foreach ($accessibilities as $accessibility) : ?>
                            <option value="<?= $accessibility['Accessibility_Code'] ?>" <?= $album['Accessibility_Code'] == $accessibility['Accessibility_Code'] ? 'selected' : '' ?>><?= $accessibility['Description'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="save_changes">Save Changes</button>
                    <button type="submit" name="delete">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

<?php
include('footer.php');
?>
