<?php
session_start();
include('config.php');

if(!isset($_SESSION['id'])){
    header("location: login.php");
    exit;
}

$result = $conn->query("SELECT * FROM tags");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>GetMatch - Select Interests</title>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
:root {
    --primary: #dc3545;
    --primary-dark: #a02834;
    --bg-dark: #0f0f0f;
    --card-dark: #1c1c1c;
    --text-light: #a1a1aa;
    --border: #2a2a2a;
}

/* RESET */
* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family: 'Inter', sans-serif;
    background: radial-gradient(circle at top, #2a2a2a, #0f0f0f);
    color: white;
}

/* CENTER */
.wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

/* CARD */
.card {
    background: var(--card-dark);
    border-radius: 18px;
    padding: 40px 30px;
    max-width: 600px;
    width: 100%;
    border: 1px solid rgba(220,53,69,0.3);
    box-shadow: 0 20px 50px rgba(220,53,69,0.15);
}

/* HEADER */
.title {
    font-size: 1.6rem;
    font-weight: 600;
    text-align: center;
}

.subtitle {
    text-align: center;
    color: var(--text-light);
    margin-bottom: 20px;
}

/* TAG AREA */
.tag-container {
    max-height: 260px;
    overflow-y: auto;
    padding: 10px;
    border-radius: 10px;
    background: #121212;
    margin-bottom: 20px;
}

/* TAG */
.tag {
    display: inline-block;
    padding: 10px 18px;
    margin: 6px;
    border-radius: 25px;
    border: 1px solid var(--border);
    cursor: pointer;
    transition: 0.25s;
    font-size: 0.9rem;
    color: var(--text-light);
    background: #1a1a1a;
}

.tag:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
}

/* ACTIVE TAG */
.tag.active {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border-color: var(--primary);
    box-shadow: 0 5px 15px rgba(220,53,69,0.4);
}

/* COUNTER */
.counter {
    font-size: 0.85rem;
    color: var(--text-light);
    text-align: right;
    margin-bottom: 10px;
}

/* BUTTON */
.btn-main {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: none;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    font-weight: 600;
    transition: 0.3s;
}

.btn-main:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(220,53,69,0.4);
}

/* SKIP */
.skip {
    text-align: center;
    margin-top: 10px;
}

.skip a {
    color: var(--text-light);
    font-size: 0.85rem;
    text-decoration: none;
}

.skip a:hover {
    color: var(--primary);
}

/* SCROLLBAR */
.tag-container::-webkit-scrollbar {
    width: 6px;
}
.tag-container::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 10px;
}
</style>

</head>

<body>

<div class="wrapper">

<div class="card">

<form method="POST" action="save-tags.php">

<div class="title">🎮 Choose Your Interests</div>
<div class="subtitle">Pick what games or roles you enjoy</div>

<div class="counter">
    Selected: <span id="count">0</span>
</div>

<div class="tag-container">

<?php while ($row = $result->fetch_assoc()) { ?>
    <div class="tag" onclick="toggleTag(this)">
        <input type="checkbox" name="tags[]" value="<?php echo $row['Tag_ID']; ?>" hidden>
        <?php echo $row['Tag_Name']; ?>
    </div>
<?php } ?>

</div>

<button class="btn-main" type="submit">
    Continue <i class="fas fa-arrow-right"></i>
</button>

<div class="skip">
    <a href="home.php">Skip for now</a>
</div>

</form>

</div>

</div>

<script>
function toggleTag(el){
    el.classList.toggle("active");

    let checkbox = el.querySelector("input");
    checkbox.checked = !checkbox.checked;

    updateCount();
}

function updateCount(){
    let selected = document.querySelectorAll(".tag input:checked").length;
    document.getElementById("count").innerText = selected;
}
</script>

</body>
</html>