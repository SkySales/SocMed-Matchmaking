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

if($specific_lobby_id) {
    $check = $conn->prepare("SELECT * FROM lobbies WHERE Lobby_ID = ?");
    $check->bind_param("i", $specific_lobby_id);
    $check->execute();
    $lobby = $check->get_result()->fetch_assoc();

    if($lobby && !$lobby['Is_Private']) {
        $join = $conn->prepare("INSERT IGNORE INTO lobby_members (Lobby_ID, User_ID) VALUES (?, ?)");
        $join->bind_param("ii", $specific_lobby_id, $user_id);
        $join->execute();
        header("location: lobby_room.php?id=" . $specific_lobby_id);
        exit;
    }
}

// Fetch lobbies
$result = $conn->query("SELECT * FROM lobbies ORDER BY Created_At DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GetMatch - Lobbies</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --primary: #dc3545;
    --primary-dark: #a02834;
    --bg-dark: #1a1a1a;
    --bg-card: #2d2d2d;
    --text-light: #d0d0d0;
    --border: #404040;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
    padding: 40px 20px;
    color: white;
}

/* HEADER */
.header {
    text-align: center;
    margin-bottom: 40px;
}

.header h1 {
    color: var(--primary);
    font-size: 32px;
}

.header p {
    color: var(--text-light);
}

/* BUTTONS */
.actions {
    margin-top: 15px;
}

.btn {
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    margin: 5px;
    display: inline-block;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.btn-secondary {
    border: 2px solid var(--primary);
    color: var(--primary);
}

.btn:hover {
    opacity: 0.9;
}

/* SEARCH */
.search-box {
    max-width: 500px;
    margin: 20px auto;
}

.search-box input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: #1a1a1a;
    color: white;
}

/* GRID */
.lobbies {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

/* CARD */
.card {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 12px;
    border: 1px solid var(--border);
    transition: 0.3s;
}

.card:hover {
    border-color: var(--primary);
    transform: translateY(-5px);
}

.highlight {
    border: 2px solid var(--primary);
}

/* TITLE */
.title {
    font-size: 18px;
    font-weight: 700;
}

/* BADGE */
.badge {
    margin-top: 8px;
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 20px;
    display: inline-block;
}

.public {
    background: rgba(40,167,69,0.2);
    color: #28a745;
}

.private {
    background: rgba(220,53,69,0.2);
    color: var(--primary);
}

/* PLAYER COUNT */
.count {
    margin-top: 10px;
    font-size: 13px;
    color: #aaa;
}

/* JOIN */
.join-btn {
    margin-top: 15px;
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: none;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    cursor: pointer;
}

/* PIN */
.pin input {
    width: 100%;
    margin-top: 10px;
    padding: 8px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: #1a1a1a;
    color: white;
}

/* EMPTY */
.empty {
    text-align: center;
    margin-top: 50px;
    color: var(--text-light);
}
</style>
</head>

<body>

<div class="header">
    <h1><i class="fas fa-gamepad"></i> GetMatch Lobbies</h1>
    <p>Find players and join a squad</p>

    <div class="actions">
        <a href="create_lobby.php" class="btn btn-primary">+ Create Lobby</a>
        <a href="../home.php" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="search-box">
    <input type="text" id="search" placeholder="Search lobby...">
</div>

<div class="lobbies" id="lobbyList">

<?php if($result->num_rows > 0): ?>
<?php while($row = $result->fetch_assoc()): 

// PLAYER COUNT
$count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM lobby_members WHERE Lobby_ID=?");
$count_stmt->bind_param("i", $row['Lobby_ID']);
$count_stmt->execute();
$count = $count_stmt->get_result()->fetch_assoc()['total'];

$is_highlight = ($specific_lobby_id == $row['Lobby_ID']);
?>

<div class="card <?php echo $is_highlight ? 'highlight' : ''; ?>">

    <div class="title">
        <i class="fas fa-users"></i>
        <?php echo htmlspecialchars($row['Lobby_Name']); ?>
    </div>

    <div class="badge <?php echo $row['Is_Private'] ? 'private' : 'public'; ?>">
        <?php echo $row['Is_Private'] ? 'Private' : 'Public'; ?>
    </div>

    <div class="count">
        <i class="fas fa-user"></i> <?php echo $count; ?> players
    </div>

    <?php if($row['Is_Private']): ?>
        <form action="join_process.php" method="post" class="pin">
            <input type="hidden" name="lobby_id" value="<?php echo $row['Lobby_ID']; ?>">
            <input type="password" name="code" placeholder="Enter PIN" required>
            <button class="join-btn">Join</button>
        </form>
    <?php else: ?>
        <a href="join_process.php?lobby_id=<?php echo $row['Lobby_ID']; ?>">
            <button class="join-btn">Join Lobby</button>
        </a>
    <?php endif; ?>

</div>

<?php endwhile; ?>
<?php else: ?>

<div class="empty">
    <h3>No lobbies yet</h3>
    <a href="create_lobby.php" class="btn btn-primary">Create First Lobby</a>
</div>

<?php endif; ?>

</div>

<script>
// SEARCH FILTER
document.getElementById("search").addEventListener("keyup", function() {
    let value = this.value.toLowerCase();
    let cards = document.querySelectorAll(".card");

    cards.forEach(card => {
        let text = card.innerText.toLowerCase();
        card.style.display = text.includes(value) ? "block" : "none";
    });
});
</script>

</body>
</html>