<?php
session_start();
include("../config.php");

if(!isset($_SESSION['id']) || !isset($_POST['lobby_id']) || !isset($_POST['user_id'])){
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$lobby_id = $_POST['lobby_id'];
$user_to_kick = $_POST['user_id'];
$current_user = $_SESSION['id'];

// Verify the current user is the host
$stmt = $conn->prepare("SELECT Host_ID FROM lobbies WHERE Lobby_ID = ?");
$stmt->bind_param("i", $lobby_id);
$stmt->execute();
$lobby = $stmt->get_result()->fetch_assoc();

if(!$lobby || $lobby['Host_ID'] != $current_user){
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Only host can kick users']);
    exit;
}

// Prevent kicking the host
if($user_to_kick == $lobby['Host_ID']){
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Cannot kick the host']);
    exit;
}

// Remove the user from lobby
$delete = $conn->prepare("DELETE FROM lobby_members WHERE Lobby_ID = ? AND User_ID = ?");
$delete->bind_param("ii", $lobby_id, $user_to_kick);

if($delete->execute()){
    echo json_encode(['success' => true, 'message' => 'User kicked successfully']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error kicking user']);
}
?>
