<?php  
session_start();
include('config.php');

/* ================= SECURITY ================= */
if(!isset($_SESSION['id'])){
    header("location: login.php");
    exit;
}

/* ================= TF-IDF FUNCTIONS ================= */

function clean_text($text){
    $text = strtolower($text);
    $text = preg_replace("/[^a-z0-9 ]/", "", $text);
    return array_filter(explode(" ", $text));
}

function compute_tf($words){
    $tf = array_count_values($words);
    $total = count($words);
    foreach($tf as $word => $count){
        $tf[$word] = $count / $total;
    }
    return $tf;
}

function compute_idf($documents){
    $idf = [];
    $total_docs = count($documents);

    foreach($documents as $doc){
        foreach(array_unique($doc) as $word){
            $idf[$word] = ($idf[$word] ?? 0) + 1;
        }
    }

    foreach($idf as $word => $count){
        $idf[$word] = log($total_docs / $count);
    }

    return $idf;
}

function compute_tfidf($tf, $idf){
    $tfidf = [];
    foreach($tf as $word => $value){
        if(isset($idf[$word])){
            $tfidf[$word] = $value * $idf[$word];
        }
    }
    return $tfidf;
}

/* ================= SEARCH ================= */

$search_input = $_POST['find'] ?? $_GET['find'] ?? "event";
$search_words = clean_text($search_input);

/* ================= GET DATA ================= */

$sql = "
SELECT Post_ID as ID, Caption, HashTags, Img_Path as Media, NULL as Thumb, Likes, Date_Upload, 'post' as type FROM posts
UNION
SELECT Event_ID as ID, Caption, HashTags, Event_Poster as Media, NULL as Thumb, Likes, Date_Upload, 'event' as type FROM events
UNION
SELECT Video_ID as ID, Caption, HashTags, Video_Path as Media, Thumbnail_Path as Thumb, Likes, Date_Upload, 'video' as type FROM videos
";

$result = $conn->query($sql);

$documents = [];
$data = [];

while($row = $result->fetch_assoc()){
    $words = clean_text($row['Caption']." ".$row['HashTags']);
    $documents[] = $words;
    $data[] = $row;
}

/* ================= TF-IDF ================= */

$idf = compute_idf($documents);
$scores = [];

foreach($documents as $i => $doc){
    $tf = compute_tf($doc);
    $tfidf = compute_tfidf($tf, $idf);

    $score = 0;

    foreach($search_words as $word){
        if(isset($tfidf[$word])){
            $score += $tfidf[$word];
        }
    }

    if($data[$i]['type'] == 'video'){
        $score *= 1.2;
    }

    $scores[$i] = $score;
}

arsort($scores);

$results = [];
foreach($scores as $i => $score){
    if($score > 0){
        $results[] = $data[$i];
    }
}

if(empty($results)){
    $results = $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EventsWave</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="assets/images/event_accepted_50px.png" type="image/icon type">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-+qdLaIRZfNu4cVPK/PxJJEy0B0f3Ugv8i482AKY7gwXwhaCroABd086ybrVKTa0q" crossorigin="anonymous">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>

    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="assets/css/section.css">
    
    <link rel="stylesheet" href="assets/css/posting.css">

    <link rel="stylesheet" href="assets/css/responsive.css">

    <link rel="stylesheet" href="assets/css/right_col.css">
    
    <link rel="stylesheet" href="assets/css/profile-page.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<style>

/* 🔥 NAV FIX */
body {
    padding-top: 0px;
}

@media (max-width: 768px) {
    body {
        padding-top: 10px;
        padding-bottom: 70px;
    }
}

.nav-wrapper {
    width: 90%;
    max-width: 1200px;
    margin: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-items {
    display: flex;
    align-items: center;
    gap: 18px;
}

/* 🔥 MOBILE NAV */
@media (max-width: 768px) {

    .mobile-nav {
        position: fixed;
        bottom: 0;
        top: auto;
        width: 100%;
        height: 60px;
        background: #fff;
        border-top: 1px solid #ddd;
        z-index: 999;
    }

    .mobile-nav .brand-img {
        display: none;
    }

    .mobile-nav .nav-items {
        width: 100%;
        justify-content: space-around;
    }

    .mobile-nav .icon {
        font-size: 20px;
    }
}

/* 🔥 CARDS */
.card-box{
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    margin-bottom:20px;
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

video{
    aspect-ratio: 9/16;
    object-fit: cover;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<!-- Modern Navigation Bar -->
<?php include('navbar.php'); ?>

<!-- Search Modal -->
<div class="modal fade" id="search-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form method="post" action="Results.php">
                    <input type="search" name="find" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                </form>
            </div>
        </div>
    </div>
</div>

<!-- CONTENT -->
<div class="container-fluid px-3" style="max-width:1200px; margin:auto;">

<h4>Search Results for "<b><?php echo htmlspecialchars($search_input); ?></b>"</h4>
<br>

<!-- Search Modal -->
<div class="modal fade" id="search-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                    <form method="post" action="Results.php">
                        <input type="search" name="find" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                    </form>
            </div>
        </div>
    </div>
</div>

<div class="row">

<?php foreach ($results as $item) { ?>

    <div class="col-md-4">

        <div class="card-box">

            <?php if($item['type'] == 'video'){ ?>

                <video controls 
                poster="<?php echo "assets/videos/" . ($item['Thumb'] ?: ''); ?>"
                style="width:100%; height:300px;">
                    <source src="<?php echo "assets/videos/" . $item['Media']; ?>" type="video/mp4">
                </video>

            <?php } else { ?>

                <img src="<?php echo "assets/images/posts/" . ($item['Media'] ?: 'default.png'); ?>"
                style="width:100%; height:250px; object-fit:cover;">

            <?php } ?>

            <div style="padding:15px;">
                <p><strong><?php echo $item['Caption'] ?: "No caption"; ?></strong></p>
                <p style="color:#0b5ed7;"><?php echo $item['HashTags']; ?></p>
                <small><?php echo date("M d, Y", strtotime($item['Date_Upload'])); ?></small>
            </div>

            <div style="padding:10px; border-top:1px solid #eee; display:flex; justify-content:space-between;">
                <span>👍 <?php echo $item['Likes']; ?></span>

                <?php if($item['type'] == 'post'){ ?>
                    <a href="single-post.php?post_id=<?php echo $item['ID']; ?>">View</a>
                <?php } elseif($item['type'] == 'event'){ ?>
                    <a href="Single-Event.php?post_id=<?php echo $item['ID']; ?>">Event</a>
                <?php } else { ?>
                    <a href="Single-Video.php?post_id=<?php echo $item['ID']; ?>">Watch</a>
                <?php } ?>
            </div>

        </div>

    </div>

<?php } ?>

</div>
</div>

<script>
document.getElementById("logo-img").onclick = function(){
    window.location.href = "home.php";
}
</script>

</body>
</html>