<?php
session_start();
include("../config.php");

if(!isset($_SESSION['id'])){
    header("location: ../login.php");
    exit;
}

$user_id = $_SESSION['id'];
$lobby_id = $_POST['lobby_id'] ?? $_GET['lobby_id'];
$code = $_POST['code'] ?? null;

// Check lobby
$stmt = $conn->prepare("SELECT * FROM lobbies WHERE Lobby_ID=?");
$stmt->bind_param("i", $lobby_id);
$stmt->execute();
$lobby = $stmt->get_result()->fetch_assoc();

if(!$lobby){
    die("Lobby not found");
}

// Check private PIN
if($lobby['Is_Private'] && $lobby['Lobby_Code'] != $code){
    die("Invalid PIN");
}

// 🔥 Remove user from any existing lobby first
$leave = $conn->prepare("DELETE FROM lobby_members WHERE User_ID=?");
$leave->bind_param("i", $user_id);
$leave->execute();

// 🔥 Join new lobby
$join = $conn->prepare("INSERT INTO lobby_members (Lobby_ID, User_ID) VALUES (?, ?)");
$join->bind_param("ii", $lobby_id, $user_id);
$join->execute();

header("location: lobby_room.php?id=".$lobby_id);
exit;
?>