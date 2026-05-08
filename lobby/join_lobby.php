<?php
session_start();
include("../config.php");

if(!isset($_SESSION['id'])){
    header("location: ../login.php");
    exit;
}

$user_id = $_SESSION['id'];

// Invite lobby check
$specific_lobby_id = isset($_GET['lobby_id']) ? $_GET['lobby_id'] : null;

if($specific_lobby_id){

    $check = $conn->prepare("
        SELECT * FROM lobbies
        WHERE Lobby_ID = ?
    ");

    $check->bind_param("i", $specific_lobby_id);
    $check->execute();

    $lobby = $check->get_result()->fetch_assoc();

    if($lobby){

        // Count players
        $countStmt = $conn->prepare("
            SELECT COUNT(*) as total
            FROM lobby_members
            WHERE Lobby_ID = ?
        ");

        $countStmt->bind_param("i", $specific_lobby_id);
        $countStmt->execute();

        $count = $countStmt
            ->get_result()
            ->fetch_assoc()['total'];

        // Auto set FULL
        if($count >= $lobby['Max_Players']){

            $update = $conn->prepare("
                UPDATE lobbies
                SET Lobby_Status='Full'
                WHERE Lobby_ID=?
            ");

            $update->bind_param("i", $specific_lobby_id);
            $update->execute();

        } else {

            if($lobby['Lobby_Status'] == 'Full'){

                $update = $conn->prepare("
                    UPDATE lobbies
                    SET Lobby_Status='Waiting'
                    WHERE Lobby_ID=?
                ");

                $update->bind_param("i", $specific_lobby_id);
                $update->execute();
            }
        }

        // AUTO JOIN PUBLIC
        if(
            !$lobby['Is_Private']
            &&
            $count < $lobby['Max_Players']
            &&
            $lobby['Lobby_Status'] != 'In-Game'
        ){

            // SINGLE LOBBY ONLY
            $existing = $conn->prepare("
                SELECT * FROM lobby_members
                WHERE User_ID=?
            ");

            $existing->bind_param("i", $user_id);
            $existing->execute();

            if($existing->get_result()->num_rows <= 0){

                $join = $conn->prepare("
                    INSERT IGNORE INTO lobby_members
                    (Lobby_ID, User_ID)
                    VALUES (?, ?)
                ");

                $join->bind_param(
                    "ii",
                    $specific_lobby_id,
                    $user_id
                );

                $join->execute();

                header("location: lobby_room.php?id=".$specific_lobby_id);
                exit;
            }
        }
    }
}

// FETCH LOBBIES
$result = $conn->query("
    SELECT *
    FROM lobbies
    ORDER BY Created_At DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>GetMatch - Matchmaking</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

:root{
    --primary:#dc3545;
    --primary-dark:#a02834;

    --bg:#111111;
    --card:#1d1d1d;
    --card2:#252525;

    --border:#343434;

    --text:#ffffff;
    --text-light:#b9b9b9;

    --green:#28c76f;
    --yellow:#ff9f43;
    --red:#ea5455;
    --blue:#00cfe8;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    font-family:'Segoe UI',sans-serif;

    background:
    radial-gradient(circle at top left,
    rgba(220,53,69,.12),
    transparent 25%),

    radial-gradient(circle at bottom right,
    rgba(220,53,69,.08),
    transparent 25%),

    linear-gradient(135deg,#0f0f0f,#181818);

    min-height:100vh;

    color:white;

    padding:30px 20px;
}

/* HEADER */

.header{
    text-align:center;
    margin-bottom:35px;
}

.logo{
    width:80px;
    height:80px;

    border-radius:22px;

    margin:auto;
    margin-bottom:18px;

    display:flex;
    justify-content:center;
    align-items:center;

    background:
    linear-gradient(135deg,var(--primary),
    var(--primary-dark));

    font-size:32px;

    box-shadow:
    0 10px 30px rgba(220,53,69,.35);
}

.header h1{
    font-size:38px;
    margin-bottom:8px;
}

.header p{
    color:var(--text-light);
    font-size:15px;
}

/* BUTTONS */

.actions{
    margin-top:20px;

    display:flex;
    gap:12px;

    justify-content:center;
    flex-wrap:wrap;
}

.btn{
    padding:12px 22px;

    border-radius:12px;

    text-decoration:none;

    font-weight:700;

    transition:.25s;
}

.btn-primary{

    background:
    linear-gradient(135deg,
    var(--primary),
    var(--primary-dark));

    color:white;

    box-shadow:
    0 8px 20px rgba(220,53,69,.25);
}

.btn-primary:hover{
    transform:translateY(-2px);
}

.btn-secondary{

    border:1px solid var(--border);

    background:#1a1a1a;

    color:#d5d5d5;
}

.btn-secondary:hover{
    border-color:var(--primary);
    color:white;
}

/* SEARCH */

.search-box{
    max-width:600px;
    margin:25px auto;
}

.search-box input{

    width:100%;

    padding:15px;

    border-radius:14px;

    border:1px solid var(--border);

    background:#1a1a1a;

    color:white;

    font-size:14px;
}

.search-box input:focus{
    outline:none;
    border-color:var(--primary);
}

/* GRID */

.lobbies{

    display:grid;

    grid-template-columns:
    repeat(auto-fill,minmax(320px,1fr));

    gap:22px;
}

/* CARD */

.card{

    background:
    linear-gradient(145deg,#1c1c1c,#242424);

    border:1px solid var(--border);

    border-radius:18px;

    padding:22px;

    transition:.25s;

    position:relative;

    overflow:hidden;
}

.card:hover{
    transform:translateY(-4px);

    border-color:rgba(220,53,69,.5);

    box-shadow:
    0 12px 25px rgba(0,0,0,.35);
}

.highlight{
    border:2px solid var(--primary);
}

/* TOP */

.top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:10px;
}

/* TITLE */

.title{
    font-size:19px;
    font-weight:700;
    margin-bottom:5px;
}

/* GAME */

.game{
    color:#ffb3bb;
    font-size:13px;
}

/* STATUS */

.status{

    padding:6px 12px;

    border-radius:30px;

    font-size:11px;

    font-weight:700;

    text-transform:uppercase;
}

.waiting{
    background:rgba(40,199,111,.15);
    color:var(--green);
}

.ingame{
    background:rgba(255,159,67,.15);
    color:var(--yellow);
}

.full{
    background:rgba(234,84,85,.15);
    color:var(--red);
}

/* INFO */

.info-row{
    margin-top:18px;

    display:flex;
    justify-content:space-between;

    color:#cfcfcf;

    font-size:13px;
}

/* BADGES */

.badges{
    margin-top:16px;

    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.badge{

    padding:7px 12px;

    border-radius:30px;

    font-size:11px;

    font-weight:700;
}

.public{
    background:rgba(40,199,111,.12);
    color:var(--green);
}

.private{
    background:rgba(220,53,69,.12);
    color:var(--primary);
}

/* JOIN */

.join-btn{

    margin-top:18px;

    width:100%;

    border:none;

    padding:13px;

    border-radius:12px;

    font-weight:700;

    cursor:pointer;

    transition:.25s;

    color:white;

    background:
    linear-gradient(135deg,
    var(--primary),
    var(--primary-dark));
}

.join-btn:hover{
    transform:translateY(-2px);
}

.join-btn:disabled{
    background:#3a3a3a;
    cursor:not-allowed;
    opacity:.7;
}

/* PIN */

.pin input{

    width:100%;

    margin-top:15px;

    padding:12px;

    border-radius:10px;

    border:1px solid var(--border);

    background:#111;

    color:white;
}

/* EMPTY */

.empty{
    text-align:center;
    margin-top:60px;
    color:#c8c8c8;
}

/* MOBILE */

@media(max-width:600px){

    .header h1{
        font-size:28px;
    }

    .logo{
        width:65px;
        height:65px;
        font-size:26px;
    }

    .lobbies{
        grid-template-columns:1fr;
    }

}

</style>
</head>

<body>

<div class="header">

    <div class="logo">
        <i class="fas fa-gamepad"></i>
    </div>

    <h1>GetMatch Matchmaking</h1>

    <p>Find squads, join players, dominate the game.</p>

    <div class="actions">

        <a href="create_lobby.php"
        class="btn btn-primary">

            <i class="fas fa-plus-circle"></i>
            Create Lobby

        </a>

        <a href="../home.php"
        class="btn btn-secondary">

            <i class="fas fa-arrow-left"></i>
            Back

        </a>

    </div>

</div>

<div class="search-box">

    <input
    type="text"
    id="search"

    placeholder="Search game, lobby, squad..."
    >

</div>

<div class="lobbies" id="lobbyList">

<?php if($result->num_rows > 0): ?>

<?php while($row = $result->fetch_assoc()):

$count_stmt = $conn->prepare("
    SELECT COUNT(*) as total
    FROM lobby_members
    WHERE Lobby_ID=?
");

$count_stmt->bind_param("i", $row['Lobby_ID']);
$count_stmt->execute();

$count =
$count_stmt->get_result()
->fetch_assoc()['total'];


// AUTO FULL STATUS
if($count >= $row['Max_Players']){

    $conn->query("
        UPDATE lobbies
        SET Lobby_Status='Full'
        WHERE Lobby_ID=".$row['Lobby_ID']
    );

    $row['Lobby_Status'] = 'Full';

} else {

    if($row['Lobby_Status'] == 'Full'){

        $conn->query("
            UPDATE lobbies
            SET Lobby_Status='Waiting'
            WHERE Lobby_ID=".$row['Lobby_ID']
        );

        $row['Lobby_Status'] = 'Waiting';
    }
}

$isFull = $row['Lobby_Status'] == 'Full';
$isInGame = $row['Lobby_Status'] == 'In-Game';

?>

<div class="card">

    <div class="top">

        <div>

            <div class="title">
                <?php echo htmlspecialchars($row['Lobby_Name']); ?>
            </div>

            <div class="game">
                <i class="fas fa-gamepad"></i>
                <?php echo htmlspecialchars($row['Game_Name']); ?>
            </div>

        </div>

        <div class="status
        <?php

        if($row['Lobby_Status']=='Waiting')
            echo 'waiting';

        else if($row['Lobby_Status']=='In-Game')
            echo 'ingame';

        else
            echo 'full';

        ?>">

        <?php echo $row['Lobby_Status']; ?>

        </div>

    </div>

    <div class="badges">

        <div class="badge
        <?php echo $row['Is_Private']
        ? 'private'
        : 'public'; ?>">

            <i class="fas
            <?php echo $row['Is_Private']
            ? 'fa-lock'
            : 'fa-earth-asia'; ?>"></i>

            <?php echo $row['Is_Private']
            ? 'Private'
            : 'Public'; ?>

        </div>

    </div>

    <div class="info-row">

        <div>
            <i class="fas fa-users"></i>
            <?php echo $count; ?>/<?php echo $row['Max_Players']; ?>
        </div>

        <div>
            <i class="fas fa-clock"></i>
            <?php echo date("M d", strtotime($row['Created_At'])); ?>
        </div>

    </div>

    <?php if($row['Is_Private']): ?>

        <form action="join_process.php"
        method="POST"
        class="pin">

            <input
            type="hidden"
            name="lobby_id"
            value="<?php echo $row['Lobby_ID']; ?>">

            <input
            type="password"
            name="code"
            placeholder="Enter Lobby PIN"
            required

            <?php
            if($isFull || $isInGame)
                echo 'disabled';
            ?>
            >

            <button
            class="join-btn"

            <?php
            if($isFull || $isInGame)
                echo 'disabled';
            ?>>

            <?php

            if($isFull)
                echo 'Lobby Full';

            else if($isInGame)
                echo 'Match Started';

            else
                echo 'Join Lobby';

            ?>

            </button>

        </form>

    <?php else: ?>

        <a href="
        <?php

        if(!$isFull && !$isInGame)
            echo 'join_process.php?lobby_id='.$row['Lobby_ID'];
        else
            echo '#';

        ?>
        ">

            <button
            class="join-btn"

            <?php
            if($isFull || $isInGame)
                echo 'disabled';
            ?>>

            <?php

            if($isFull)
                echo 'Lobby Full';

            else if($isInGame)
                echo 'Match Started';

            else
                echo 'Join Lobby';

            ?>

            </button>

        </a>

    <?php endif; ?>

</div>

<?php endwhile; ?>

<?php else: ?>

<div class="empty">

    <h2>No lobbies found</h2>

    <br>

    <a href="create_lobby.php"
    class="btn btn-primary">

        Create First Lobby

    </a>

</div>

<?php endif; ?>

</div>

<script>

document
.getElementById("search")
.addEventListener("keyup", function(){

    let value =
    this.value.toLowerCase();

    let cards =
    document.querySelectorAll(".card");

    cards.forEach(card=>{

        let text =
        card.innerText.toLowerCase();

        card.style.display =
        text.includes(value)
        ? "block"
        : "none";
    });

});

</script>

</body>
</html>