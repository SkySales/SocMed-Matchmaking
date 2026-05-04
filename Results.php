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
$filter = $_GET['filter'] ?? 'all'; // Filter: all, user, post, event, video

/* ================= GET DATA ================= */

$sql = "
SELECT Post_ID as ID, Caption, HashTags, Img_Path as Media, NULL as Thumb, Likes, Date_Upload, 'post' as type, NULL as IMAGE, NULL as USER_NAME, NULL as FULL_NAME, NULL as BIO FROM posts
UNION
SELECT Event_ID as ID, Caption, HashTags, Event_Poster as Media, NULL as Thumb, Likes, Date_Upload, 'event' as type, NULL as IMAGE, NULL as USER_NAME, NULL as FULL_NAME, NULL as BIO FROM events
UNION
SELECT Video_ID as ID, Caption, HashTags, Video_Path as Media, Thumbnail_Path as Thumb, Likes, Date_Upload, 'video' as type, NULL as IMAGE, NULL as USER_NAME, NULL as FULL_NAME, NULL as BIO FROM videos
UNION
SELECT User_ID as ID, BIO as Caption, CONCAT(USER_NAME, ' ', FULL_NAME) as HashTags, IMAGE as Media, NULL as Thumb, FALLOWERS as Likes, NOW() as Date_Upload, 'user' as type, IMAGE, USER_NAME, FULL_NAME, BIO FROM users
";

$result = $conn->query($sql);

$documents = [];
$data = [];

while($row = $result->fetch_assoc()){
    if($row['type'] == 'user'){
        $words = clean_text($row['USER_NAME']." ".$row['FULL_NAME']." ".$row['BIO']);
    } else {
        $words = clean_text($row['Caption']." ".$row['HashTags']);
    }
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
    } elseif($data[$i]['type'] == 'user'){
        $score *= 1.3; // Boost user results slightly
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
    <title>GetMatch</title>
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
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    padding-top: 0px;
    background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    min-height: 100vh;
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

/* 🔥 HEADER */
.search-header {
    margin-bottom: 40px;
}

.search-header h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 8px;
    letter-spacing: -0.5px;
}

.search-header .search-term {
    color: #3b82f6;
    font-weight: 600;
}

.search-meta {
    color: #64748b;
    font-size: 0.95rem;
}

/* 🔥 FILTER TABS */
.filter-tabs {
    margin-bottom: 35px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-btn {
    padding: 10px 20px;
    border: 2px solid #e2e8f0;
    background: white;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #64748b;
    font-size: 0.95rem;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.filter-btn:hover {
    border-color: #3b82f6;
    background: #f0f4f8;
    color: #3b82f6;
    transform: translateY(-2px);
}

.filter-btn.active {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

/* 🔥 CARDS */
.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    margin-top: 20px;
}

.card-box {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.card-box:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    border-color: #cbd5e1;
}

/* USER CARD */
.user-card {
    text-align: center;
    padding: 28px 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.user-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 16px;
    border: 4px solid #f0f4f8;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.user-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 4px;
}

.user-handle {
    color: #3b82f6;
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 12px;
}

.user-bio {
    color: #64748b;
    font-size: 0.85rem;
    line-height: 1.5;
    margin-bottom: 16px;
    flex-grow: 1;
}

.user-footer {
    padding-top: 16px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.followers-badge {
    font-size: 0.85rem;
    font-weight: 600;
    color: #3b82f6;
}

/* CONTENT CARD */
.content-media {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: #f0f4f8;
}

video.content-media {
    aspect-ratio: 9/16;
}

.content-info {
    padding: 18px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.content-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 8px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.content-tags {
    color: #3b82f6;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.content-date {
    color: #94a3b8;
    font-size: 0.8rem;
    margin-top: auto;
    margin-bottom: 12px;
}

.content-footer {
    padding-top: 12px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
}

.likes-badge {
    color: #64748b;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.action-link {
    color: #3b82f6;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.action-link:hover {
    color: #2563eb;
    text-decoration: none;
}

/* BUTTONS */
.btn-sm {
    padding: 6px 14px;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    color: white;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
}

.btn-primary:active {
    transform: translateY(0);
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}

.empty-state-icon {
    font-size: 3rem;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state-text {
    font-size: 1.05rem;
    font-weight: 500;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .search-header h2 {
        font-size: 1.5rem;
    }

    .results-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 16px;
    }
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
<div class="container-fluid px-3" style="max-width:1200px; margin:auto; padding-top: 30px;">

<div class="search-header">
    <h2>Search Results</h2>
    <p class="search-meta">
        Showing results for <span class="search-term">"<?php echo htmlspecialchars($search_input); ?>"</span>
    </p>
</div>

<!-- FILTER TABS -->
<div class="filter-tabs">
    <a href="Results.php?find=<?php echo urlencode($search_input); ?>&filter=all" 
       class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">
        <i class="fas fa-th"></i> All Results
    </a>
    <a href="Results.php?find=<?php echo urlencode($search_input); ?>&filter=user" 
       class="filter-btn <?php echo $filter == 'user' ? 'active' : ''; ?>">
        <i class="fas fa-users"></i> People
    </a>
    <a href="Results.php?find=<?php echo urlencode($search_input); ?>&filter=post" 
       class="filter-btn <?php echo $filter == 'post' ? 'active' : ''; ?>">
        <i class="fas fa-image"></i> Posts
    </a>
    <a href="Results.php?find=<?php echo urlencode($search_input); ?>&filter=event" 
       class="filter-btn <?php echo $filter == 'event' ? 'active' : ''; ?>">
        <i class="fas fa-calendar"></i> Events
    </a>
    <a href="Results.php?find=<?php echo urlencode($search_input); ?>&filter=video" 
       class="filter-btn <?php echo $filter == 'video' ? 'active' : ''; ?>">
        <i class="fas fa-video"></i> Videos
    </a>
</div>

<!-- RESULTS GRID -->
<div class="results-grid">

<?php foreach ($results as $item) { ?>

    <?php 
        // Skip items that don't match the filter
        if($filter != 'all' && $item['type'] != $filter) continue;
    ?>

    <?php if($item['type'] == 'user'){ ?>

        <!-- USER CARD -->
        <div class="card-box user-card">
            <img src="<?php echo 'assets/images/profiles/' . htmlspecialchars($item['Media'] ? $item['Media'] : 'default.png'); ?>"
                 class="user-avatar">
            
            <h3 class="user-name"><?php echo htmlspecialchars($item['FULL_NAME']); ?></h3>
            <p class="user-handle">@<?php echo htmlspecialchars($item['USER_NAME']); ?></p>
            <p class="user-bio"><?php echo htmlspecialchars($item['BIO'] ? $item['BIO'] : 'No bio yet'); ?></p>
            
            <div class="user-footer">
                <span class="followers-badge"><i class="fas fa-users"></i> <?php echo $item['Likes']; ?></span>
                <form method="POST" action="follower_acc.php" style="display:inline; margin:0;">
                    <input type="hidden" name="target_id" value="<?php echo $item['ID']; ?>">
                    <button type="submit" class="btn btn-sm btn-primary">View Profile</button>
                </form>
            </div>
        </div>

    <?php } else { ?>

        <!-- CONTENT CARD (POST/EVENT/VIDEO) -->
        <div class="card-box">
            <?php if($item['type'] == 'video'){ ?>
                <video class="content-media" controls 
                       poster="<?php echo 'assets/videos/' . ($item['Thumb'] ? $item['Thumb'] : ''); ?>">
                    <source src="<?php echo 'assets/videos/' . $item['Media']; ?>" type="video/mp4">
                </video>
            <?php } else { ?>
                <img src="<?php echo 'assets/images/posts/' . ($item['Media'] ? $item['Media'] : 'default.png'); ?>"
                     class="content-media">
            <?php } ?>

            <div class="content-info">
                <h4 class="content-title"><?php echo htmlspecialchars($item['Caption'] ? $item['Caption'] : 'No caption'); ?></h4>
                <p class="content-tags"><?php echo htmlspecialchars($item['HashTags'] ? $item['HashTags'] : ''); ?></p>
                <p class="content-date">
                    <i class="fas fa-calendar-alt"></i>
                    <?php echo date("M d, Y", strtotime($item['Date_Upload'])); ?>
                </p>
                
                <div class="content-footer">
                    <span class="likes-badge">
                        <i class="fas fa-heart"></i> <?php echo $item['Likes']; ?>
                    </span>
                    <?php if($item['type'] == 'post'){ ?>
                        <a href="single-post.php?post_id=<?php echo $item['ID']; ?>" class="action-link">View Post</a>
                    <?php } elseif($item['type'] == 'event'){ ?>
                        <a href="Single-Event.php?post_id=<?php echo $item['ID']; ?>" class="action-link">View Event</a>
                    <?php } else { ?>
                        <a href="Single-Video.php?post_id=<?php echo $item['ID']; ?>" class="action-link">Watch Video</a>
                    <?php } ?>
                </div>
            </div>
        </div>

    <?php } ?>

<?php } ?>

<?php 
    // Check if any results were displayed
    $has_results = false;
    foreach ($results as $item) {
        if($filter == 'all' || $item['type'] == $filter) {
            $has_results = true;
            break;
        }
    }
    if(!$has_results) {
        echo '<div class="empty-state">';
        echo '<div class="empty-state-icon"><i class="fas fa-search"></i></div>';
        echo '<p class="empty-state-text">No ' . htmlspecialchars($filter) . ' results found for "' . htmlspecialchars($search_input) . '"</p>';
        echo '</div>';
    }
?>

</div>
</div>

<script>
document.getElementById("logo-img").onclick = function(){
    window.location.href = "home.php";
}
</script>

</body>
</html>