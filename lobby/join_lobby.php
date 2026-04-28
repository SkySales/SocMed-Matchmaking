<?php
session_start();
include("../config.php");

// Check if there's a specific lobby_id from invite link
$specific_lobby_id = isset($_GET['lobby_id']) ? $_GET['lobby_id'] : null;

if($specific_lobby_id) {
    // Verify the lobby exists
    $check = $conn->prepare("SELECT * FROM lobbies WHERE Lobby_ID = ?");
    $check->bind_param("i", $specific_lobby_id);
    $check->execute();
    $lobby = $check->get_result()->fetch_assoc();
    
    if($lobby) {
        // Auto join if public lobby, or show join form if private
        if(!$lobby['Is_Private']) {
            // Direct join for public lobby
            $join = $conn->prepare("INSERT IGNORE INTO lobby_members (Lobby_ID, User_ID) VALUES (?, ?)");
            $join->bind_param("ii", $specific_lobby_id, $_SESSION['id']);
            $join->execute();
            header("location: lobby_room.php?id=" . $specific_lobby_id);
            exit;
        }
    }
}

$result = $conn->query("SELECT * FROM lobbies ORDER BY Created_At DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Lobby</title>
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
            padding: 40px 20px;
        }

        .container-lobbies {
            max-width: 900px;
            margin: 0 auto;
        }

        .header-section {
            text-align: center;
            color: white;
            margin-bottom: 50px;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-section h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .header-section p {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-primary {
            background: white;
            color: #667eea;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #667eea;
        }

        .lobbies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 40px;
        }

        .lobby-item {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .lobby-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .lobby-title {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .lobby-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            background: #f0f1f5;
            border-radius: 20px;
            font-size: 12px;
            color: #666;
            font-weight: 600;
        }

        .lobby-badge.private {
            background: #ffe6e6;
            color: #d32f2f;
        }

        .lobby-badge.public {
            background: #e6f7ff;
            color: #1976d2;
        }

        .pin-form {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .pin-form input {
            flex: 1;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .pin-form input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-join {
            padding: 12px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex: 1;
        }

        .btn-join:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: white;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .empty-state p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .lobby-item.highlighted {
            border: 3px solid #667eea;
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
            transform: scale(1.02);
        }

        .highlight-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container-lobbies">
        <div class="header-section">
            <h1>
                <i class="fas fa-gamepad"></i> Game Lobbies
            </h1>
            <p>Join an existing lobby or create your own</p>
            <div class="action-buttons">
                <a href="create_lobby.php" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Create New Lobby
                </a>
                <a href="../home.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back Home
                </a>
            </div>
        </div>

        <div class="lobbies-grid">
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): 
                    $is_highlighted = $specific_lobby_id && $specific_lobby_id == $row['Lobby_ID'];
                ?>
                    <div class="lobby-item <?php echo $is_highlighted ? 'highlighted' : ''; ?>">
                        <?php if($is_highlighted): ?>
                            <div class="highlight-badge">
                                <i class="fas fa-star"></i> Invited Lobby
                            </div>
                        <?php endif; ?>
                        
                        <div class="lobby-title">
                            <i class="fas fa-door-open"></i>
                            <?php echo htmlspecialchars($row['Lobby_Name']); ?>
                        </div>

                        <div class="lobby-badge <?php echo $row['Is_Private'] ? 'private' : 'public'; ?>">
                            <i class="fas <?php echo $row['Is_Private'] ? 'fa-lock' : 'fa-unlock'; ?>"></i>
                            <?php echo $row['Is_Private'] ? 'Private' : 'Public'; ?>
                        </div>

                        <?php if($row['Is_Private']): ?>
                            <form action="join_process.php" method="post" class="pin-form">
                                <input type="hidden" name="lobby_id" value="<?php echo $row['Lobby_ID']; ?>">
                                <input type="password" name="code" placeholder="Enter PIN" required>
                                <button type="submit" class="btn-join">
                                    <i class="fas fa-sign-in-alt"></i> Join
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="join_process.php?lobby_id=<?php echo $row['Lobby_ID']; ?>" class="btn-join" style="text-decoration: none; display: flex; margin-top: 15px;">
                                <i class="fas fa-sign-in-alt"></i> Join Lobby
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1/-1;">
                    <i class="fas fa-inbox"></i>
                    <p>No lobbies available</p>
                    <a href="create_lobby.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Create First Lobby
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>