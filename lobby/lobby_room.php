<?php
session_start();
include("../config.php");

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("location: join_lobby.php");
    exit;
}

$lobby_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM lobbies WHERE Lobby_ID=?");
$stmt->bind_param("i", $lobby_id);
$stmt->execute();
$lobby = $stmt->get_result()->fetch_assoc();

if(!$lobby){
    header("location: join_lobby.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($lobby['Lobby_Name']); ?> - Lobby</title>
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

        .lobby-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .lobby-header {
            color: white;
            margin-bottom: 40px;
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

        .lobby-header h1 {
            font-size: 36px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .lobby-info {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .lobby-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 15px;
            border-radius: 25px;
            font-size: 14px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 40px;
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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

        .card h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .lobby-details {
            margin-bottom: 25px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #999;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
            font-size: 16px;
            font-family: 'Courier New', monospace;
        }

        .btn-leave {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff6b6b 0%, #d32f2f 100%);
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

        .btn-leave:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(211, 47, 47, 0.4);
        }

        .player-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .player-item {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 16px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            border-left: 4px solid #667eea;
        }

        .player-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .player-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .player-name {
            flex: 1;
            color: #333;
            font-weight: 600;
        }

        .player-status {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #4caf50;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
        }

        .empty-players {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-players i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 25px;
            border: 2px solid white;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-back:hover {
            background: white;
            color: #667eea;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #999;
        }

        .loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .invite-section {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid #667eea;
        }

        .invite-label {
            color: #667eea;
            font-weight: 600;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }

        .invite-link-container {
            display: flex;
            gap: 10px;
        }

        .invite-link-input {
            flex: 1;
            padding: 12px;
            border: 2px solid #667eea;
            border-radius: 8px;
            background: white;
            color: #333;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            readonly;
        }

        .btn-copy {
            padding: 12px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-copy:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }

        .buttons-container {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .buttons-container button {
            flex: 1;
            padding: 15px;
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

        .btn-delete {
            background: linear-gradient(135deg, #ff6b6b 0%, #d32f2f 100%);
            color: white;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(211, 47, 47, 0.4);
        }

        .btn-kick {
            padding: 8px 12px;
            background: #ff6b6b;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-kick:hover {
            background: #d32f2f;
            transform: scale(1.05);
        }

        .host-badge {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #333;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .player-controls {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #4caf50;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            animation: slideInUp 0.3s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: slideInUp 0.3s ease-out;
            max-width: 400px;
            z-index: 1000;
        }

        .notification.error {
            border-left: 4px solid #d32f2f;
        }

        .notification.warning {
            border-left: 4px solid #ff9800;
        }

        .notification.info {
            border-left: 4px solid #667eea;
        }

        .notification-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notification-message {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="lobby-container">
        <div class="lobby-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1>
                        <i class="fas fa-door-open"></i>
                        <?php echo htmlspecialchars($lobby['Lobby_Name']); ?>
                    </h1>
                </div>
                <div class="header-actions">
                    <a href="join_lobby.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="card">
                <h2>
                    <i class="fas fa-info-circle"></i> Lobby Details
                </h2>
                <div class="lobby-details">
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-shield-alt"></i> Privacy
                        </span>
                        <span class="detail-value">
                            <?php echo $lobby['Is_Private'] ? '🔒 Private' : '🔓 Public'; ?>
                        </span>
                    </div>
                    <?php if($lobby['Is_Private']): ?>
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="fas fa-key"></i> PIN
                            </span>
                            <span class="detail-value">
                                <?php echo str_pad($lobby['Lobby_Code'], 4, '0', STR_PAD_LEFT); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-clock"></i> Created
                        </span>
                        <span class="detail-value">
                            <?php echo isset($lobby['Created_At']) ? date('M d, Y', strtotime($lobby['Created_At'])) : 'Today'; ?>
                        </span>
                    </div>
                </div>

                <div class="invite-section">
                    <div class="invite-label">
                        <i class="fas fa-link"></i> Invite Link
                    </div>
                    <div class="invite-link-container">
                        <input type="text" id="inviteLink" class="invite-link-input" readonly>
                        <button class="btn-copy" onclick="copyInviteLink()">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>

                <div class="buttons-container" id="hostActions" style="display: none;">
                    <button onclick="deleteLobby()" class="btn-delete">
                        <i class="fas fa-trash-alt"></i> Delete Lobby
                    </button>
                </div>

                <button onclick="leaveLobby()" class="btn-leave" id="leaveBtn">
                    <i class="fas fa-sign-out-alt"></i> Leave Lobby
                </button>
            </div>

            <div class="card">
                <h2>
                    <i class="fas fa-users"></i> Players (<span id="player-count">0</span>)
                </h2>
                <div id="players" class="player-list">
                    <div class="loading">
                        <i class="fas fa-spinner fa-spin"></i> Loading players...
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
const CURRENT_USER_ID = <?php echo $_SESSION['id']; ?>;
const LOBBY_ID = <?php echo $lobby_id; ?>;
const LOBBY_HOST_ID = <?php echo $lobby['Host_ID']; ?>;

// Initialize on page load
window.addEventListener('DOMContentLoaded', function(){
    initializeInviteLink();
    
    // Show delete button if user is host
    if(CURRENT_USER_ID === LOBBY_HOST_ID) {
        document.getElementById('hostActions').style.display = 'flex';
    }
    
    // Check membership every 2 seconds
    setInterval(checkMembership, 2000);
});

function initializeInviteLink(){
    const protocol = window.location.protocol;
    const host = window.location.host;
    const inviteUrl = `${protocol}//${host}/lobby/join_lobby.php?lobby_id=${LOBBY_ID}`;
    document.getElementById('inviteLink').value = inviteUrl;
}

function copyInviteLink(){
    const inviteInput = document.getElementById('inviteLink');
    inviteInput.select();
    document.execCommand('copy');
    
    // Show toast notification
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = '<i class="fas fa-check"></i> Invite link copied!';
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 2000);
}

function showNotification(title, message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    let icon = '';
    if(type === 'error') icon = '<i class="fas fa-exclamation-circle"></i>';
    else if(type === 'warning') icon = '<i class="fas fa-exclamation-triangle"></i>';
    else if(type === 'info') icon = '<i class="fas fa-info-circle"></i>';
    
    notification.innerHTML = `
        <div class="notification-title">${icon} ${title}</div>
        <div class="notification-message">${message}</div>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 5000);
}

function checkMembership() {
    fetch(`check_membership.php?lobby_id=${LOBBY_ID}`)
    .then(res => res.json())
    .then(data => {
        if(!data.in_lobby) {
            if(data.reason === 'kicked') {
                showNotification('Kicked', 'You have been kicked from the lobby!', 'error');
                setTimeout(() => {
                    window.location = 'join_lobby.php';
                }, 2000);
            } else if(data.reason === 'deleted') {
                showNotification('Lobby Deleted', 'The lobby has been deleted by the host!', 'warning');
                setTimeout(() => {
                    window.location = 'join_lobby.php';
                }, 2000);
            }
        }
    })
    .catch(err => console.error('Error checking membership:', err));
}

function loadPlayers(){
    fetch("fetch_players.php?lobby_id=" + LOBBY_ID)
    .then(res=>res.json())
    .then(data=>{
        let playersHtml = '';
        if(data.players && data.players.length > 0) {
            data.players.forEach(player => {
                const initial = player.USER_NAME.charAt(0).toUpperCase();
                const isHost = player.is_host;
                const isCurrentUser = player.User_ID === CURRENT_USER_ID;
                const canKick = CURRENT_USER_ID === LOBBY_HOST_ID && !isHost && !isCurrentUser;
                
                playersHtml += `
                    <div class="player-item">
                        <div class="player-avatar">${initial}</div>
                        <div style="flex: 1;">
                            <div class="player-name" style="display: flex; align-items: center; gap: 8px;">
                                ${player.USER_NAME}
                                ${isHost ? '<span class="host-badge"><i class="fas fa-crown"></i> Host</span>' : ''}
                                ${isCurrentUser ? '<span style="font-size: 11px; color: #667eea; font-weight: 600;">(You)</span>' : ''}
                            </div>
                        </div>
                        <div class="player-controls">
                            <div class="player-status"></div>
                            ${canKick ? `<button class="btn-kick" onclick="kickUser(${player.User_ID}, '${player.USER_NAME}')"><i class="fas fa-times"></i> Kick</button>` : ''}
                        </div>
                    </div>
                `;
            });
        } else {
            playersHtml = '<div class="empty-players"><i class="fas fa-users"></i><p>No players in this lobby</p></div>';
        }
        document.getElementById("players").innerHTML = playersHtml;
        document.getElementById("player-count").textContent = data.players ? data.players.length : 0;
    })
    .catch(err => {
        console.error('Error loading players:', err);
        document.getElementById("players").innerHTML = '<div class="empty-players"><i class="fas fa-exclamation-triangle"></i><p>Error loading players</p></div>';
    });
}

function kickUser(userId, userName){
    if(confirm(`Are you sure you want to kick ${userName} from the lobby?`)){
        fetch('kick_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `lobby_id=${LOBBY_ID}&user_id=${userId}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                showNotification('Kicked', `${userName} has been kicked!`, 'info');
                loadPlayers();
            } else {
                showNotification('Error', data.message, 'error');
            }
        })
        .catch(err => {
            console.error('Error kicking user:', err);
            showNotification('Error', 'Failed to kick user', 'error');
        });
    }
}

function deleteLobby(){
    if(confirm('Are you sure you want to delete this lobby? All members will be removed.')){
        fetch('delete_lobby.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `lobby_id=${LOBBY_ID}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                showNotification('Success', 'Lobby deleted! Redirecting...', 'info');
                setTimeout(() => {
                    window.location = 'join_lobby.php';
                }, 2000);
            } else {
                showNotification('Error', data.message, 'error');
            }
        })
        .catch(err => {
            console.error('Error deleting lobby:', err);
            showNotification('Error', 'Failed to delete lobby', 'error');
        });
    }
}

// refresh every 3 sec
setInterval(loadPlayers, 3000);
loadPlayers();

function leaveLobby(){
    if(confirm("Are you sure you want to leave this lobby?")){
        window.location = "leave_lobby.php?id=" + LOBBY_ID;
    }
}
</script>

</body>
</html>