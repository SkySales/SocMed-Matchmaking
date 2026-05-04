<?php
session_start();
include("../config.php");

if(!isset($_SESSION['id'])){
    header("location: login.php");
    exit;
}

if(isset($_POST['create'])){
    $name = $_POST['lobby_name'];
    $private = isset($_POST['private']) ? 1 : 0;
    $code = $private ? rand(1000,9999) : NULL;

    // OPTIONAL: prevent user from being in multiple lobbies
    $check = $conn->prepare("SELECT * FROM lobby_members WHERE User_ID=?");
    $check->bind_param("i", $_SESSION['id']);
    $check->execute();
    $existing = $check->get_result();

    if($existing->num_rows > 0){
        die("❌ You are already in a lobby. Leave it first.");
    }

    $stmt = $conn->prepare("INSERT INTO lobbies (Lobby_Name, Host_ID, Is_Private, Lobby_Code) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $name, $_SESSION['id'], $private, $code);
    $stmt->execute();

    $lobby_id = $stmt->insert_id;

    // auto join host (NO DUPLICATE)
    $join = $conn->prepare("INSERT INTO lobby_members (Lobby_ID, User_ID) VALUES (?, ?)");
    $join->bind_param("ii", $lobby_id, $_SESSION['id']);
    $join->execute();

    header("location: lobby_room.php?id=".$lobby_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Lobby - GetMatch</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --primary: #dc3545;
    --primary-dark: #a02834;
    --bg-dark: #1a1a1a;
    --bg-card: #2d2d2d;
    --border: #404040;
    --text: #ffffff;
    --text-light: #bfbfbf;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* CARD */
.lobby-card {
    background: var(--bg-card);
    border: 2px solid var(--primary);
    border-radius: 14px;
    padding: 40px 30px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 15px 40px rgba(220,53,69,0.2);
    animation: fadeIn 0.4s ease;
}

@keyframes fadeIn {
    from {opacity:0; transform: translateY(20px);}
    to {opacity:1; transform: translateY(0);}
}

/* HEADER */
.lobby-header {
    text-align: center;
    margin-bottom: 25px;
}

.lobby-header h1 {
    color: var(--primary);
    font-size: 26px;
}

.lobby-header p {
    color: var(--text-light);
    font-size: 14px;
}

/* INPUT */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    color: var(--text-light);
    font-size: 13px;
    margin-bottom: 6px;
    display: block;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: #1a1a1a;
    color: var(--text);
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary);
}

/* CHECKBOX */
.checkbox-group {
    display: flex;
    align-items: center;
    background: #1a1a1a;
    border: 1px solid var(--border);
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    margin-bottom: 25px;
}

.checkbox-group input {
    margin-right: 10px;
    accent-color: var(--primary);
}

.checkbox-group label {
    color: var(--text-light);
    font-size: 14px;
}

/* BUTTON */
.btn-create {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(220,53,69,0.4);
}

/* BACK */
.back-link {
    text-align: center;
    margin-top: 15px;
}

.back-link a {
    color: var(--primary);
    text-decoration: none;
    font-size: 13px;
}

.back-link a:hover {
    color: #ff4d5e;
}

/* MOBILE RESPONSIVE */
@media(max-width:480px){
    body{
        padding:15px;
    }

    .lobby-card{
        padding:25px 20px;
        max-width:100%;
    }

    .lobby-header h1{
        font-size:22px;
    }

    .lobby-header p{
        font-size:13px;
    }

    .form-group input{
        padding:11px;
        font-size:14px;
    }

    .form-group label{
        font-size:12px;
    }

    .checkbox-group{
        padding:10px;
        font-size:13px;
    }

    .btn-create{
        padding:11px;
        font-size:14px;
    }

    .back-link a{
        font-size:12px;
    }
}
</style>
</head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<body>

<div class="lobby-card">

    <div class="lobby-header">
        <h1><i class="fas fa-users-cog"></i> Create Lobby</h1>
        <p>Setup your squad and invite players</p>
    </div>

    <form method="post">

        <div class="form-group">
            <label>Lobby Name</label>
            <input type="text" name="lobby_name" placeholder="Enter lobby name..." required>
        </div>

        <div class="checkbox-group">
            <input type="checkbox" id="private" name="private">
            <label for="private">
                <i class="fas fa-lock"></i> Private Lobby (PIN protected)
            </label>
        </div>

        <button type="submit" name="create" class="btn-create">
            <i class="fas fa-plus"></i> Create Lobby
        </button>

    </form>

    <div class="back-link">
        <a href="join_lobby.php">
            <i class="fas fa-arrow-left"></i> Back to Lobby List
        </a>
    </div>

</div>

</body>
</html>