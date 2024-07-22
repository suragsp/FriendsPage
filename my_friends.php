<?php
session_start();
include('header.php');
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Handle friend request acceptance
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept'])) {
    $friendRequesterId = $_POST['friend_requester_id'];
    $stmt = $pdo->prepare("UPDATE Friendship SET Status = 'accepted' WHERE Friend_RequesterId = ? AND Friend_RequesteeId = ?");
    $stmt->execute([$friendRequesterId, $userId]);

    echo "Friend request accepted!";
}

// Handle friend request rejection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reject'])) {
    $friendRequesterId = $_POST['friend_requester_id'];
    $stmt = $pdo->prepare("DELETE FROM Friendship WHERE Friend_RequesterId = ? AND Friend_RequesteeId = ?");
    $stmt->execute([$friendRequesterId, $userId]);

    echo "Friend request rejected!";
}

// Handle sending a friend request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_request'])) {
    $friendRequesteeId = $_POST['friend_requestee_id'];
    
    // Check if the user exists before inserting
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM User WHERE UserId = ?");
    $stmt->execute([$friendRequesteeId]);
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        $stmt = $pdo->prepare("INSERT INTO Friendship (Friend_RequesterId, Friend_RequesteeId, Status) VALUES (?, ?, 'pending')");
        $stmt->execute([$userId, $friendRequesteeId]);

        echo "Friend request sent!";
    } else {
        echo "User ID $friendRequesteeId does not exist.";
    }
}

// Fetch friend requests
$friendRequests = $pdo->prepare("SELECT User.UserId, User.Name FROM User JOIN Friendship ON User.UserId = Friendship.Friend_RequesterId WHERE Friendship.Friend_RequesteeId = ? AND Friendship.Status = 'pending'");
$friendRequests->execute([$userId]);
$friendRequests = $friendRequests->fetchAll();

// Fetch accepted friends
$acceptedFriends = $pdo->prepare("SELECT User.UserId, User.Name FROM User JOIN Friendship ON User.UserId = Friendship.Friend_RequesterId WHERE Friendship.Friend_RequesteeId = ? AND Friendship.Status = 'accepted' UNION SELECT User.UserId, User.Name FROM User JOIN Friendship ON User.UserId = Friendship.Friend_RequesteeId WHERE Friendship.Friend_RequesterId = ? AND Friendship.Status = 'accepted'");
$acceptedFriends->execute([$userId, $userId]);
$acceptedFriends = $acceptedFriends->fetchAll();
?>

<main>
    <h1>My Friends</h1>

    <h2>Send Friend Request</h2>
    <form action="my_friends.php" method="post">
        <label for="friend_requestee_id">Friend User ID:</label>
        <input type="text" id="friend_requestee_id" name="friend_requestee_id" required><br>
        <button type="submit" name="send_request">Send Request</button>
    </form>

    <h2>Friend Requests</h2>
    <ul>
        <?php foreach ($friendRequests as $request) : ?>
            <li>
                <?= htmlspecialchars($request['Name']) ?>
                <form action="my_friends.php" method="post" style="display:inline;">
                    <input type="hidden" name="friend_requester_id" value="<?= htmlspecialchars($request['UserId']) ?>">
                    <button type="submit" name="accept">Accept</button>
                    <button type="submit" name="reject">Reject</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>My Friends</h2>
    <ul>
        <?php foreach ($acceptedFriends as $friend) : ?>
            <li><?= htmlspecialchars($friend['Name']) ?></li>
        <?php endforeach; ?>
    </ul>
</main>

<?php
include('footer.php');
?>
