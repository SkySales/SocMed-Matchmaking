<?php
session_start();
include("../config.php");

if(!isset($_SESSION['id'])){
    exit;
}

$lobby_id = $_POST['lobby_id'];

// GET LOBBY
$stmt = $conn->prepare("
SELECT * FROM lobbies
WHERE Lobby_ID=?
");

$stmt->bind_param("i",$lobby_id);
$stmt->execute();

$lobby =
$stmt->get_result()
->fetch_assoc();

if(!$lobby){
    exit;
}

// ONLY HOST CAN DELETE
if($_SESSION['id'] != $lobby['Host_ID']){
    exit;
}

// DELETE MEMBERS
$members = $conn->prepare("
DELETE FROM lobby_members
WHERE Lobby_ID=?
");

$members->bind_param(
"i",
$lobby_id
);

$members->execute();

// DELETE LOBBY
$delete = $conn->prepare("
DELETE FROM lobbies
WHERE Lobby_ID=?
");

$delete->bind_param(
"i",
$lobby_id
);

$delete->execute();

echo "success";
?>