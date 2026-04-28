<?php
include("../config.php");
header('Content-Type: application/json');

$lobby_id = $_GET['lobby_id'];

// Get lobby host
$host_stmt = $conn->prepare("SELECT Host_ID FROM lobbies WHERE Lobby_ID = ?");
$host_stmt->bind_param("i", $lobby_id);
$host_stmt->execute();
$lobby = $host_stmt->get_result()->fetch_assoc();
$host_id = $lobby ? $lobby['Host_ID'] : null;

$stmt = $conn->prepare("
SELECT users.USER_NAME, users.User_ID 
FROM lobby_members
JOIN users ON users.User_ID = lobby_members.User_ID
WHERE Lobby_ID = ?
");
$stmt->bind_param("i", $lobby_id);
$stmt->execute();
$query = $stmt->get_result();

$players = [];
while($row = $query->fetch_assoc()){
    $row['is_host'] = ($row['User_ID'] == $host_id);
    $players[] = $row;
}

echo json_encode(['players' => $players]);
?>