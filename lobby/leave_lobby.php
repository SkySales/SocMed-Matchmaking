<?php
session_start();
include("../config.php");

$lobby_id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM lobby_members WHERE Lobby_ID=? AND User_ID=?");
$stmt->bind_param("ii", $lobby_id, $_SESSION['id']);
$stmt->execute();

header("location: join_lobby.php");