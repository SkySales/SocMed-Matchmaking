<?php
session_start();
include("../config.php");

$lobby_id = $_POST['lobby_id'] ?? $_GET['lobby_id'];
$code = $_POST['code'] ?? null;

// check lobby
$stmt = $conn->prepare("SELECT * FROM lobbies WHERE Lobby_ID=?");
$stmt->bind_param("i",$lobby_id);
$stmt->execute();
$lobby = $stmt->get_result()->fetch_assoc();

if($lobby['Is_Private'] && $lobby['Lobby_Code'] != $code){
    die("Invalid PIN");
}

// join
$join = $conn->prepare("INSERT INTO lobby_members (Lobby_ID, User_ID) VALUES (?, ?)");
$join->bind_param("ii", $lobby_id, $_SESSION['id']);
$join->execute();

header("location: lobby_room.php?id=".$lobby_id);
?>