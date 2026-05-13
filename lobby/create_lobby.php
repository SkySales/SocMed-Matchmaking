<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config.php");

if(!isset($_SESSION['id'])){
    header("location: login.php");
    exit;
}

if(isset($_POST['create'])){

    $name = trim($_POST['lobby_name']);
    $game = trim($_POST['game_name']);
    $max_players = (int)$_POST['max_players'];

    $private = isset($_POST['private']) ? 1 : 0;
    $code = $private ? rand(1000,9999) : NULL;

    // VALIDATIONS
    if($max_players < 2){
        die("Minimum players is 2");
    }

    if($max_players > 10){
        die("Maximum players is 10");
    }

    // Prevent multiple lobbies
    $check = $conn->prepare("
        SELECT * FROM lobby_members
        WHERE User_ID = ?
    ");

    $check->bind_param("i", $_SESSION['id']);
    $check->execute();

    if($check->get_result()->num_rows > 0){
        die("❌ You are already inside a lobby.");
    }

    // Create lobby
    $stmt = $conn->prepare("
        INSERT INTO lobbies
        (
            Lobby_Name,
            Host_ID,
            Is_Private,
            Lobby_Code,
            Game_Name,
            Max_Players,
            Lobby_Status
        )
        VALUES (?, ?, ?, ?, ?, ?, 'Waiting')
    ");

    $stmt->bind_param(
        "siissi",
        $name,
        $_SESSION['id'],
        $private,
        $code,
        $game,
        $max_players
    );

    $stmt->execute();

    $lobby_id = $stmt->insert_id;

    // Auto join host
    $join = $conn->prepare("
        INSERT INTO lobby_members (Lobby_ID, User_ID)
        VALUES (?, ?)
    ");

    $join->bind_param("ii", $lobby_id, $_SESSION['id']);
    $join->execute();

    header("location: lobby_room.php?id=".$lobby_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Create Lobby - GetMatch</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

:root{
    --primary:#dc3545;
    --primary-dark:#a02834;
    --bg-dark:#111111;
    --bg-card:#1d1d1d;
    --bg-input:#151515;
    --border:#333;
    --text:#fff;
    --text-light:#b8b8b8;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:
    radial-gradient(circle at top left, rgba(220,53,69,.15), transparent 30%),
    radial-gradient(circle at bottom right, rgba(220,53,69,.1), transparent 30%),
    linear-gradient(135deg,#0f0f0f,#1a1a1a);

    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
    color:white;
}

/* CARD */

.lobby-card{
    width:100%;
    max-width:500px;

    background:rgba(25,25,25,.95);

    border:1px solid rgba(220,53,69,.3);

    border-radius:18px;

    padding:35px;

    backdrop-filter:blur(12px);

    box-shadow:
    0 15px 40px rgba(0,0,0,.45),
    0 0 20px rgba(220,53,69,.12);

    animation:fadeIn .4s ease;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(25px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* HEADER */

.lobby-header{
    text-align:center;
    margin-bottom:30px;
}

.logo-icon{
    width:70px;
    height:70px;

    margin:auto;
    margin-bottom:15px;

    border-radius:18px;

    display:flex;
    justify-content:center;
    align-items:center;

    background:
    linear-gradient(135deg,var(--primary),var(--primary-dark));

    font-size:28px;

    box-shadow:
    0 10px 25px rgba(220,53,69,.35);
}

.lobby-header h1{
    font-size:30px;
    margin-bottom:8px;
}

.lobby-header p{
    color:var(--text-light);
    font-size:14px;
}

/* FORM */

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    color:#d4d4d4;
    font-size:13px;
    font-weight:600;
}

.form-control{
    width:100%;
    padding:14px;

    border-radius:10px;
    border:1px solid var(--border);

    background:var(--bg-input);

    color:white;

    font-size:14px;

    transition:.25s;
}

.form-control:focus{
    outline:none;

    border-color:var(--primary);

    box-shadow:
    0 0 0 3px rgba(220,53,69,.15);
}

/* SELECT */

select.form-control{
    cursor:pointer;
}

/* CHECKBOX */

.checkbox-group{
    display:flex;
    align-items:center;

    background:#151515;

    border:1px solid var(--border);

    padding:14px;

    border-radius:12px;

    margin-bottom:25px;

    transition:.25s;
}

.checkbox-group:hover{
    border-color:var(--primary);
}

.checkbox-group input{
    margin-right:12px;
    accent-color:var(--primary);
    transform:scale(1.1);
}

.checkbox-group label{
    color:#d8d8d8;
    font-size:14px;
    cursor:pointer;
}

/* BUTTON */

.btn-create{
    width:100%;

    border:none;

    padding:15px;

    border-radius:12px;

    background:
    linear-gradient(135deg,var(--primary),var(--primary-dark));

    color:white;

    font-size:15px;
    font-weight:700;

    cursor:pointer;

    transition:.3s;

    box-shadow:
    0 10px 20px rgba(220,53,69,.25);
}

.btn-create:hover{
    transform:translateY(-2px);

    box-shadow:
    0 14px 28px rgba(220,53,69,.35);
}

/* BACK */

.back-link{
    margin-top:18px;
    text-align:center;
}

.back-link a{
    color:var(--primary);
    text-decoration:none;
    font-size:13px;
}

.back-link a:hover{
    color:#ff5c6c;
}

/* MOBILE */

@media(max-width:600px){

    .lobby-card{
        padding:25px;
    }

    .lobby-header h1{
        font-size:24px;
    }

    .logo-icon{
        width:60px;
        height:60px;
        font-size:24px;
    }

}

</style>
</head>

<body>

<div class="lobby-card">

    <div class="lobby-header">

        <div class="logo-icon">
            <i class="fas fa-gamepad"></i>
        </div>

        <h1>Create Lobby</h1>

        <p>Build your squad and start matchmaking</p>

    </div>

    <form method="POST">

        <div class="form-group">
            <label>
                <i class="fas fa-users"></i>
                Lobby Name
            </label>

            <input
                type="text"
                name="lobby_name"
                class="form-control"
                placeholder="Ex: CF Ranked Squad"
                required
            >
        </div>

        <div class="form-group">
            <label>
                <i class="fas fa-gamepad"></i>
                Select Game
            </label>

            <select name="game_name" class="form-control" required>

                <option value="CrossFire">CrossFire</option>

                <option value="Special Force">
                    Special Force
                </option>

                <option value="Valorant">Valorant</option>

                <option value="Mobile Legends">
                    Mobile Legends
                </option>

                <option value="Call of Duty Mobile">
                    Call of Duty Mobile
                </option>

                <option value="PUBG Mobile">
                    PUBG Mobile
                </option>

                <option value="League of Legends">
                    League of Legends
                </option>

                <option value="DOTA 2">DOTA 2</option>

            </select>
        </div>

        <div class="form-group">
            <label>
                <i class="fas fa-user-friends"></i>
                Player Limit
            </label>

            <input
                type="number"
                name="max_players"
                class="form-control"
                min="2"
                max="10"
                value="5"
                required
            >
        </div>

        <div class="checkbox-group">

            <input type="checkbox" id="private" name="private">

            <label for="private">
                <i class="fas fa-lock"></i>
                Private Lobby (PIN Protected)
            </label>

        </div>

        <button type="submit" name="create" class="btn-create">

            <i class="fas fa-plus-circle"></i>

            Create Match Lobby

        </button>

    </form>

    <div class="back-link">

        <a href="join_lobby.php">

            <i class="fas fa-arrow-left"></i>

            Back to Lobby List

        </a>

    </div>

</div>

</body>
</html>