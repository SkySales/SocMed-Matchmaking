<?php
session_start();
include("../config.php");

if(!isset($_GET['id'])){
    header("location: join_lobby.php");
    exit;
}

$lobby_id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT * FROM lobbies 
    WHERE Lobby_ID=?
");

$stmt->bind_param("i", $lobby_id);
$stmt->execute();

$lobby = $stmt->get_result()->fetch_assoc();

if(!$lobby){
    header("location: join_lobby.php");
    exit;
}

// PLAYER COUNT
$count_stmt = $conn->prepare("
    SELECT COUNT(*) as total 
    FROM lobby_members 
    WHERE Lobby_ID=?
");

$count_stmt->bind_param("i", $lobby_id);
$count_stmt->execute();

$total_players = $count_stmt
->get_result()
->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo htmlspecialchars($lobby['Lobby_Name']); ?></title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

:root{
    --primary:#dc3545;
    --dark:#1a1a1a;
    --card:#262626;
    --border:#3a3a3a;
    --text:#fff;
    --muted:#aaa;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:linear-gradient(135deg,#121212,#1f1f1f);
    font-family:'Segoe UI',sans-serif;
    color:white;
    padding:25px;
}

/* HEADER */

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
    flex-wrap:wrap;
    gap:15px;
}

.logo{
    font-size:28px;
    font-weight:700;
}

.logo span{
    color:var(--primary);
}

.top-actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.btn{
    border:none;
    padding:10px 18px;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
    color:white;
    background:var(--primary);
}

.btn:hover{
    transform:translateY(-2px);
}

.delete-btn{
    background:#ff4d4d;
}

/* GRID */

.grid{
    display:grid;
    grid-template-columns:320px 1fr;
    gap:20px;
}

.card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:12px;
    padding:20px;
}

/* INFO */

.section-title{
    font-size:16px;
    font-weight:700;
    margin-bottom:15px;
    border-left:4px solid var(--primary);
    padding-left:10px;
}

.info{
    margin-bottom:12px;
    display:flex;
    justify-content:space-between;
    color:#ddd;
    font-size:14px;
}

.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:700;
}

.waiting{
    background:#ffc10722;
    color:#ffc107;
}

.ingame{
    background:#28a74522;
    color:#28a745;
}

.full{
    background:#dc354522;
    color:#ff5d6c;
}

/* PLAYERS */

.player{
    background:#1a1a1a;
    border:1px solid #333;
    border-radius:10px;
    padding:12px;
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:10px;
}

.avatar{
    width:42px;
    height:42px;
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

.host{
    background:gold;
    color:black;
    padding:3px 8px;
    border-radius:20px;
    font-size:11px;
    font-weight:700;
}

.kick{
    background:#ff4d4d;
    border:none;
    color:white;
    padding:6px 10px;
    border-radius:6px;
    cursor:pointer;
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
    border:none;
    border-radius:6px;
    background:#111;
    color:white;
}

/* CHAT */

.chat-box{
    height:350px;
    overflow-y:auto;
    background:#111;
    border-radius:10px;
    padding:15px;
    margin-bottom:15px;
}

.chat-message{
    margin-bottom:12px;
    display:flex;
    flex-direction:column;
}

.chat-name{
    font-size:12px;
    color:#999;
    margin-bottom:4px;
}

.chat-bubble{
    max-width:70%;
    padding:10px 14px;
    border-radius:12px;
    word-wrap:break-word;
}

.me{
    align-items:flex-end;
}

.me .chat-bubble{
    background:var(--primary);
}

.other{
    align-items:flex-start;
}

.other .chat-bubble{
    background:#2d2d2d;
}

.chat-form{
    display:flex;
    gap:10px;
}

.chat-form input{
    flex:1;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#1a1a1a;
    color:white;
}

/* MOBILE */

@media(max-width:768px){

    body{
        padding:15px;
    }

    .grid{
        grid-template-columns:1fr;
    }

    .header{
        flex-direction:column;
        align-items:flex-start;
    }

    .top-actions{
        width:100%;
    }

    .btn{
        flex:1;
    }

    .invite-box{
        flex-direction:column;
    }

    .chat-form{
        flex-direction:column;
    }
}

</style>
</head>

<body>

<div class="header">

    <div class="logo">
        Get<span>Match</span>
    </div>

    <div class="top-actions">

        <a href="join_lobby.php">
            <button class="btn">
                <i class="fas fa-arrow-left"></i>
                Back
            </button>
        </a>

        <button class="btn" onclick="leaveLobby()">
            Leave
        </button>

        <?php if($_SESSION['id'] == $lobby['Host_ID']): ?>

        <button
        class="btn delete-btn"
        onclick="deleteLobby()">

            Delete Lobby

        </button>

        <?php endif; ?>

    </div>

</div>

<div class="grid">

<!-- LEFT -->

<div class="card">

    <div class="section-title">
        Lobby Information
    </div>

    <div class="info">
        <span>Name</span>
        <strong><?php echo $lobby['Lobby_Name']; ?></strong>
    </div>

    <div class="info">
        <span>Game</span>
        <strong><?php echo $lobby['Game_Name']; ?></strong>
    </div>


    <div class="info">
        <span>Players</span>
        <strong>
            <?php echo $total_players; ?>
            /
            <?php echo $lobby['Max_Players']; ?>
        </strong>
    </div>

    <div class="info">
        <span>Lobby</span>

        <?php if($total_players >= $lobby['Max_Players']): ?>

            <span class="badge full">
                FULL
            </span>

        <?php else: ?>

            <span style="color:#4caf50;">
                OPEN
            </span>

        <?php endif; ?>
    </div>

    <?php if(
        $lobby['Is_Private'] &&
        $_SESSION['id'] == $lobby['Host_ID']
        ): ?>

        <div class="info">

            <span>Lobby PIN</span>

            <strong style="
                color:#ffc107;
                letter-spacing:2px;
                font-size:18px;
            ">
                <?php echo $lobby['Lobby_Code']; ?>
            </strong>

        </div>

    <?php endif; ?>

    <div class="section-title" style="margin-top:25px;">
        Invite Link
    </div>

    <div class="invite-box">

        <input type="text"
        id="invite"
        readonly>

        <button class="btn"
        onclick="copyInvite()">

            Copy

        </button>

    </div>

</div>

<!-- RIGHT -->

<div>

<!-- PLAYERS -->

<div class="card">

    <div class="section-title">
        Players
        (<span id="count">0</span>)
    </div>

    <div id="players"></div>

</div>

<!-- CHAT -->

<div class="card" style="margin-top:20px;">

    <div class="section-title">
        Lobby Chat
    </div>

    <div class="chat-box" id="chat-box">

        <div style="color:#777;text-align:center;">
            Loading messages...
        </div>

    </div>

    <form id="chatForm" class="chat-form">

        <input
        type="text"
        id="message"
        placeholder="Type message..."
        autocomplete="off"
        required>

        <button class="btn">
            Send
        </button>

    </form>

</div>

</div>

</div>

<script>

const LOBBY_ID =
<?php echo $lobby_id; ?>;

const USER_ID =
<?php echo $_SESSION['id']; ?>;

const HOST_ID =
<?php echo $lobby['Host_ID']; ?>;

// INVITE
document.getElementById("invite").value =
location.origin +
"/lobby/join_lobby.php?lobby_id=" +
LOBBY_ID;

function copyInvite(){

    let copyText =
    document.getElementById("invite");

    copyText.select();

    document.execCommand("copy");

    alert("Invite copied!");
}

// PLAYERS
function loadPlayers(){

fetch(
"fetch_players.php?lobby_id="+LOBBY_ID
)

.then(res=>res.json())

.then(data=>{

    let html = "";

    data.players.forEach(p=>{

        let initial =
        p.USER_NAME.charAt(0).toUpperCase();

        html += `
        <div class="player">

            <div class="avatar">
                ${initial}
            </div>

            <div class="name">

                ${p.USER_NAME}

                ${
                    p.User_ID == HOST_ID
                    ?
                    `<span class="host">
                        HOST
                    </span>`
                    :
                    ''
                }

            </div>

            ${
                USER_ID == HOST_ID &&
                p.User_ID != USER_ID
                ?
                `<button class="kick"
                onclick="kick(${p.User_ID})">
                    Kick
                </button>`
                :
                ''
            }

        </div>
        `;
    });

    document.getElementById("players")
    .innerHTML = html;

    document.getElementById("count")
    .innerText = data.players.length;

});

}

// KICK
function kick(id){

fetch("kick_user.php",{

method:"POST",

headers:{
"Content-Type":
"application/x-www-form-urlencoded"
},

body:
`lobby_id=${LOBBY_ID}&user_id=${id}`

}).then(()=>loadPlayers());

}

// START MATCH
function startMatch(){

fetch("start_match.php",{

method:"POST",

headers:{
"Content-Type":
"application/x-www-form-urlencoded"
},

body:`lobby_id=${LOBBY_ID}`

}).then(()=>location.reload());

}

// LEAVE
function leaveLobby(){

window.location =
"leave_lobby.php?id="+LOBBY_ID;

}

// DELETE
function deleteLobby(){

    if(confirm("Delete this lobby?")){

        fetch("delete_lobby.php",{

            method:"POST",

            headers:{
                "Content-Type":
                "application/x-www-form-urlencoded"
            },

            body:`lobby_id=${LOBBY_ID}`

        }).then(()=>{

            window.location =
            "join_lobby.php";

        });

    }

}

// CHAT LOAD
function loadMessages(){

fetch("fetch_messages.php?lobby_id="+LOBBY_ID)

.then(res=>res.text())

.then(data=>{

    let box =
    document.getElementById("chat-box");

    box.innerHTML = data;

    box.scrollTop = box.scrollHeight;

});

}

// SEND MESSAGE
document
.getElementById("chatForm")

.addEventListener("submit",function(e){

e.preventDefault();

let msg =
document.getElementById("message").value;

fetch("send_message.php",{

method:"POST",

headers:{
"Content-Type":
"application/x-www-form-urlencoded"
},

body:
`lobby_id=${LOBBY_ID}&message=${encodeURIComponent(msg)}`

})

.then(()=>{

    document.getElementById("message").value="";

    loadMessages();

});

});

setInterval(loadPlayers,2000);
setInterval(loadMessages,2000);

loadPlayers();
loadMessages();

</script>

</body>
</html>