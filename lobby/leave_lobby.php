<?php
session_start();
include("../config.php");

if(!isset($_SESSION['id'])){
    header("location: ../login.php");
    exit;
}

$user_id = $_SESSION['id'];
$lobby_id = $_GET['id'];

// REMOVE PLAYER
$leave = $conn->prepare("
DELETE FROM lobby_members
WHERE Lobby_ID=? AND User_ID=?
");

$leave->bind_param(
"ii",
$lobby_id,
$user_id
);

$leave->execute();

// CHECK IF LOBBY EMPTY
$count = $conn->prepare("
SELECT COUNT(*) as total
FROM lobby_members
WHERE Lobby_ID=?
");

$count->bind_param("i",$lobby_id);
$count->execute();

$total =
$count->get_result()
->fetch_assoc()['total'];

// AUTO DELETE EMPTY LOBBY
if($total <= 0){

    $delete = $conn->prepare("
    DELETE FROM lobbies
    WHERE Lobby_ID=?
    ");

    $delete->bind_param(
    "i",
    $lobby_id
    );

    $delete->execute();
}

header("location: join_lobby.php");
exit;
?>