<?php
    session_start();
    include("../config.php");

    if(!isset($_SESSION['id'])){
        exit;
    }

    $user_id = $_SESSION['id'];

    $lobby_id = $_POST['lobby_id'];
    $message = trim($_POST['message']);

    if($message == ""){
        exit;
    }

    $stmt = $conn->prepare("
    INSERT INTO lobby_messages
    (Lobby_ID, User_ID, Message)
    VALUES (?, ?, ?)
    ");

    $stmt->bind_param(
    "iis",
    $lobby_id,
    $user_id,
    $message
    );

    $stmt->execute();
?>