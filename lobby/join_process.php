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

// CHECK LOBBY
$stmt = $conn->prepare("SELECT * FROM lobbies WHERE Lobby_ID=?");
$stmt->bind_param("i", $lobby_id);
$stmt->execute();
$lobby = $stmt->get_result()->fetch_assoc();

if(!$lobby){
    die("Lobby not found");
}

// CHECK PIN
if($lobby['Is_Private'] && $lobby['Lobby_Code'] != $code){
    die("Invalid PIN");
}

// CHECK IF USER ALREADY INSIDE THIS LOBBY
$check_existing = $conn->prepare("
    SELECT * FROM lobby_members 
    WHERE Lobby_ID=? AND User_ID=?
");
$check_existing->bind_param("ii", $lobby_id, $user_id);
$check_existing->execute();
$existing_member = $check_existing->get_result();

// PLAYER COUNT
$count_stmt = $conn->prepare("
    SELECT COUNT(*) as total 
    FROM lobby_members 
    WHERE Lobby_ID=?
");
$count_stmt->bind_param("i", $lobby_id);
$count_stmt->execute();

$current_players = $count_stmt
->get_result()
->fetch_assoc()['total'];

$max_players = $lobby['Max_Players'];

// IF FULL AND USER NOT INSIDE
if(
    $current_players >= $max_players &&
    $existing_member->num_rows == 0
){
    die("Lobby is already full.");
}

// REMOVE USER FROM OTHER LOBBIES
$leave = $conn->prepare("
    DELETE FROM lobby_members 
    WHERE User_ID=? AND Lobby_ID != ?
");
$leave->bind_param("ii", $user_id, $lobby_id);
$leave->execute();

// JOIN IF NOT ALREADY INSIDE
if($existing_member->num_rows == 0){

    $join = $conn->prepare("
        INSERT INTO lobby_members (Lobby_ID, User_ID)
        VALUES (?, ?)
    ");

    $join->bind_param("ii", $lobby_id, $user_id);
    $join->execute();
}

header("location: lobby_room.php?id=".$lobby_id);
exit;
?>