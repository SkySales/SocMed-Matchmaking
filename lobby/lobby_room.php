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

<style>
:root {
    --primary:#dc3545;
    --primary-dark:#a02834;
    --bg:#1a1a1a;
    --card:#2d2d2d;
    --border:#444;
    --text:#ffffff;
    --muted:#b0b0b0;
}

/* BASE */
body{
    font-family:'Segoe UI', sans-serif;
    background:linear-gradient(135deg,#1a1a1a,#2d2d2d);
    color:var(--text);
    margin:0;
    padding:30px;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.header h1{
    font-size:26px;
    font-weight:700;
}

.btn{
    background:var(--primary);
    border:none;
    color:white;
    padding:10px 18px;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
    transition:.3s;
}

.btn:hover{
    background:var(--primary-dark);
    transform:translateY(-2px);
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns: 320px 1fr;
    gap:20px;
}

/* CARD */
.card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:12px;
    padding:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.25);
}

/* SECTION TITLE */
.title{
    font-weight:700;
    margin-bottom:15px;
    font-size:16px;
    border-bottom:2px solid var(--primary);
    padding-bottom:8px;
}

/* INFO */
.info-item{
    display:flex;
    justify-content:space-between;
    margin-bottom:10px;
    font-size:14px;
}

/* INVITE */
.invite-box{
    display:flex;
    gap:10px;
    margin-top:15px;
}

.invite-box input{
    flex:1;
    padding:10px;
    border-radius:6px;
    border:none;
    background:#111;
    color:white;
}

/* PLAYERS */
.player{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px;
    border-radius:10px;
    background:#1a1a1a;
    border:1px solid #333;
    margin-bottom:10px;
    transition:.3s;
}

.player:hover{
    border-color:var(--primary);
    transform:translateX(3px);
}

.avatar{
    width:40px;
    height:40px;
    border-radius:50%;
    background:var(--primary);
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
}

.name{
    flex:1;
}

.status{
    font-size:12px;
    color:#4caf50;
}

.host{
    background:gold;
    color:black;
    font-size:11px;
    padding:3px 8px;
    border-radius:12px;
}

.kick{
    background:#ff4d4d;
    padding:6px 10px;
    border-radius:6px;
    font-size:12px;
}

.empty{
    text-align:center;
    padding:30px;
    color:var(--muted);
}

/* FOOTER BUTTONS */
.actions{
    margin-top:20px;
    display:flex;
    gap:10px;
}

/* MOBILE RESPONSIVE */
@media(max-width:768px){
    body{
        padding:15px;
    }

    .header{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .header h1{
        font-size:20px;
    }

    .grid{
        grid-template-columns:1fr;
    }

    .card{
        padding:15px;
    }

    .title{
        font-size:14px;
    }

    .info-item{
        font-size:13px;
    }

    .player{
        flex-wrap:wrap;
        gap:8px;
    }

    .avatar{
        width:35px;
        height:35px;
        font-size:14px;
    }

    .name{
        flex:0 0 100%;
    }

    .btn{
        padding:8px 14px;
        font-size:13px;
        flex:1;
    }

    .invite-box{
        flex-direction:column;
    }

    .invite-box input{
        width:100%;
    }

    .invite-box button{
        width:100%;
    }

    .actions{
        flex-direction:column;
    }

    .actions button{
        width:100%;
    }
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h1><i class="fas fa-door-open"></i> <?php echo htmlspecialchars($lobby['Lobby_Name']); ?></h1>
    <a href="join_lobby.php" class="btn"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="grid">

<!-- LEFT PANEL -->
<div class="card">

<div class="title">Lobby Info</div>

<div class="info-item">
    <span>Type</span>
    <strong><?php echo $lobby['Is_Private'] ? 'Private 🔒' : 'Public 🔓'; ?></strong>
</div>

<?php if($lobby['Is_Private']): ?>
<div class="info-item">
    <span>PIN</span>
    <strong><?php echo $lobby['Lobby_Code']; ?></strong>
</div>
<?php endif; ?>

<div class="info-item">
    <span>Created</span>
    <strong><?php echo date("M d", strtotime($lobby['Created_At'] ?? "now")); ?></strong>
</div>

<!-- INVITE -->
<div class="title" style="margin-top:20px;">Invite</div>

<div class="invite-box">
    <input type="text" id="link" readonly>
    <button class="btn" onclick="copy()">Copy</button>
</div>

<div class="actions">
    <button class="btn" onclick="leaveLobby()">
        <i class="fas fa-sign-out-alt"></i> Leave
    </button>

    <?php if($_SESSION['id'] == $lobby['Host_ID']): ?>
    <button class="btn" style="background:#ff4d4d" onclick="deleteLobby()">
        Delete
    </button>
    <?php endif; ?>
</div>

</div>

<!-- RIGHT PANEL -->
<div class="card">

<div class="title">
    Players (<span id="count">0</span>)
</div>

<div id="players"></div>

</div>

</div>

<script>
const LOBBY_ID = <?php echo $lobby_id ?>;
const USER_ID = <?php echo $_SESSION['id'] ?>;
const HOST_ID = <?php echo $lobby['Host_ID'] ?>;

/* invite */
document.getElementById("link").value =
location.origin + "/lobby/join_lobby.php?lobby_id=" + LOBBY_ID;

function copy(){
    let i = document.getElementById("link");
    i.select();
    document.execCommand("copy");
}

/* players */
function loadPlayers(){
fetch("fetch_players.php?lobby_id="+LOBBY_ID)
.then(res=>res.json())
.then(data=>{
    let html="";

    if(data.players.length===0){
        html = `<div class="empty">No players yet</div>`;
    }

    data.players.forEach(p=>{
        let initial = p.USER_NAME.charAt(0).toUpperCase();

        html += `
        <div class="player">
            <div class="avatar">${initial}</div>

            <div class="name">
                ${p.USER_NAME}
                <div class="status">🟢 Online</div>
            </div>

            ${p.User_ID==HOST_ID ? '<span class="host">HOST</span>' : ''}

            ${USER_ID==HOST_ID && p.User_ID!=USER_ID ? 
                `<button class="kick" onclick="kick(${p.User_ID})">Kick</button>`:''}
        </div>`;
    });

    document.getElementById("players").innerHTML = html;
    document.getElementById("count").innerText = data.players.length;
});
}

function kick(id){
fetch("kick_user.php",{
method:"POST",
headers:{"Content-Type":"application/x-www-form-urlencoded"},
body:`lobby_id=${LOBBY_ID}&user_id=${id}`
}).then(()=>loadPlayers());
}

function deleteLobby(){
if(confirm("Delete lobby?")){
fetch("delete_lobby.php",{
method:"POST",
headers:{"Content-Type":"application/x-www-form-urlencoded"},
body:`lobby_id=${LOBBY_ID}`
}).then(()=>location="join_lobby.php");
}
}

function leaveLobby(){
window.location="leave_lobby.php?id="+LOBBY_ID;
}

setInterval(loadPlayers,2000);
loadPlayers();
</script>

</body>
</html>