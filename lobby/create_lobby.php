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

    $stmt = $conn->prepare("INSERT INTO lobbies (Lobby_Name, Host_ID, Is_Private, Lobby_Code) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $name, $_SESSION['id'], $private, $code);
    $stmt->execute();

    $lobby_id = $stmt->insert_id;

    // auto join host
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
    <title>Create Lobby</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container-lobby {
            width: 100%;
            max-width: 500px;
        }

        .lobby-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .lobby-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .lobby-header h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .lobby-header p {
            color: #999;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 25px;
        }

        .checkbox-group:hover {
            background: #f0f1f5;
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .checkbox-group label {
            margin: 0 0 0 12px;
            flex: 1;
            cursor: pointer;
            margin-bottom: 0;
            font-weight: 500;
        }

        .btn-create {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-create:active {
            transform: translateY(0);
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container-lobby">
        <div class="lobby-card">
            <div class="lobby-header">
                <h1>
                    <i class="fas fa-users-cog"></i>
                    Create Lobby
                </h1>
                <p>Set up a new game lobby and invite players</p>
            </div>

            <form method="post">
                <div class="form-group">
                    <label for="lobby_name">
                        <i class="fas fa-tag"></i> Lobby Name
                    </label>
                    <input type="text" id="lobby_name" name="lobby_name" placeholder="Enter lobby name" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="private" name="private">
                    <label for="private">
                        <i class="fas fa-lock"></i> Private Lobby (with PIN)
                    </label>
                </div>

                <button type="submit" name="create" class="btn-create">
                    <i class="fas fa-plus-circle"></i>
                    Create Lobby
                </button>
            </form>

            <div class="back-link">
                <a href="join_lobby.php">
                    <i class="fas fa-arrow-left"></i> Back to Lobbies
                </a>
            </div>
        </div>
    </div>
</body>
</html>