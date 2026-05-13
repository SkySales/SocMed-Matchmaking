<?php
session_start();
include("../config.php");

$lobby_id = $_GET['lobby_id'];

$stmt = $conn->prepare("
SELECT lobby_messages.*,
users.USER_NAME
FROM lobby_messages

INNER JOIN users
ON users.User_ID = lobby_messages.User_ID

WHERE Lobby_ID=?

ORDER BY Message_ID ASC
");

$stmt->bind_param("i", $lobby_id);

$stmt->execute();

$result = $stmt->get_result();

$current_user = $_SESSION['id'];

while($row = $result->fetch_assoc()):

$isMe =
$row['User_ID'] == $current_user;
?>

<div class="chat-message <?php echo $isMe ? 'me' : 'other'; ?>">

    <div class="chat-name">
        <?php echo htmlspecialchars($row['USER_NAME']); ?>
    </div>

    <div class="chat-bubble">
        <?php echo nl2br(htmlspecialchars($row['Message'])); ?>
    </div>

</div>

<?php endwhile; ?>