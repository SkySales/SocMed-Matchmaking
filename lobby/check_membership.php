<?php
session_start();
include("../config.php");
header('Content-Type: application/json');

if(!isset($_SESSION['id']) || !isset($_GET['lobby_id'])){
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$lobby_id = $_GET['lobby_id'];
$user_id = $_SESSION['id'];

// Check if user is still in lobby
$stmt = $conn->prepare("SELECT * FROM lobby_members WHERE Lobby_ID = ? AND User_ID = ?");
$stmt->bind_param("ii", $lobby_id, $user_id);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();

if($member){
    // Also check if lobby still exists
    $lobby_check = $conn->prepare("SELECT Lobby_ID FROM lobbies WHERE Lobby_ID = ?");
    $lobby_check->bind_param("i", $lobby_id);
    $lobby_check->execute();
    $lobby = $lobby_check->get_result()->fetch_assoc();
    
    if($lobby){
        echo json_encode(['success' => true, 'in_lobby' => true, 'message' => 'User is in lobby']);
    } else {
        echo json_encode(['success' => true, 'in_lobby' => false, 'reason' => 'deleted', 'message' => 'Lobby has been deleted']);
    }
} else {
    echo json_encode(['success' => true, 'in_lobby' => false, 'reason' => 'kicked', 'message' => 'You have been kicked from the lobby']);
}
?>
