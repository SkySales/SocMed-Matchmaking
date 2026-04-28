<?php
session_start();
include("../config.php");
header('Content-Type: application/json');

if(!isset($_SESSION['id']) || !isset($_POST['lobby_id'])){
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$lobby_id = $_POST['lobby_id'];
$current_user = $_SESSION['id'];

// Verify the current user is the host
$stmt = $conn->prepare("SELECT Host_ID FROM lobbies WHERE Lobby_ID = ?");
$stmt->bind_param("i", $lobby_id);
$stmt->execute();
$lobby = $stmt->get_result()->fetch_assoc();

if(!$lobby || $lobby['Host_ID'] != $current_user){
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Only host can delete the lobby']);
    exit;
}

// Delete all lobby members first
$delete_members = $conn->prepare("DELETE FROM lobby_members WHERE Lobby_ID = ?");
$delete_members->bind_param("i", $lobby_id);
$delete_members->execute();

// Delete the lobby
$delete_lobby = $conn->prepare("DELETE FROM lobbies WHERE Lobby_ID = ?");
$delete_lobby->bind_param("i", $lobby_id);

if($delete_lobby->execute()){
    echo json_encode(['success' => true, 'message' => 'Lobby deleted successfully']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error deleting lobby']);
}
?>
