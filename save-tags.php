<?php
session_start();
include('config.php');

if(!isset($_SESSION['id'])){
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

if(isset($_POST['tags'])){
    foreach($_POST['tags'] as $tag_id){
        $stmt = $conn->prepare("INSERT INTO user_tags (User_ID, Tag_ID) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $tag_id);
        $stmt->execute();
    }
}

// mark as tagged
$conn->query("UPDATE users SET is_tagged = 1 WHERE User_ID = $user_id");

// redirect after saving
header("location: home.php");
exit;