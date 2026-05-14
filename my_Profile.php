<?php
session_start();
include("config.php");

if(!isset($_SESSION['id'])){
    header('location: login.php');
    exit;
}

$user_id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE User_ID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$tags_stmt = $conn->prepare("
    SELECT t.Tag_Name 
    FROM user_tags ut
    JOIN tags t ON ut.Tag_ID = t.Tag_ID
    WHERE ut.User_ID = ?
");
$tags_stmt->bind_param("i", $user_id);
$tags_stmt->execute();
$tags_result = $tags_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile - GetMatch</title>

<link rel="icon" href="assets/images/event_accepted_50px.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

:root{
    --primary:#dc3545;
    --primary-dark:#a02834;
    --bg:#121212;
    --card:#1e1e1e;
    --border:#333;
    --text:#ffffff;
    --muted:#aaaaaa;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:
    radial-gradient(circle at top,#2b0d11,#121212);
    color:var(--text);
    font-family:'Segoe UI',sans-serif;
    min-height:100vh;
}

/* NAVBAR */

.navbar{
    position:fixed;
    top:0;
    width:100%;
    background:#1a1a1a;
    border-bottom:2px solid var(--primary);
    z-index:999;
    box-shadow:0 5px 20px rgba(220,53,69,.15);
}

.nav-container{
    max-width:1200px;
    margin:auto;
    padding:14px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.logo{
    font-size:1.4rem;
    font-weight:700;
    color:var(--primary);
    cursor:pointer;
}

.logo span{
    color:white;
}

.nav-menu{
    display:flex;
    gap:18px;
    align-items:center;
}

.nav-link{
    color:#d0d0d0;
    font-size:1.1rem;
    transition:.3s;
}

.nav-link:hover{
    color:white;
    transform:translateY(-2px);
}

/* CONTAINER */

.container{
    max-width:1100px;
    margin:auto;
    padding:110px 20px 40px;
}

/* PROFILE CARD */

.profile-card{
    background:rgba(30,30,30,.95);
    border:1px solid var(--border);
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,.4);
}

/* COVER */

.cover{
    height:220px;
    background:
    linear-gradient(
        135deg,
        #dc3545,
        #300404,
        #000000
    );
    position:relative;
}

.cover::after{
    content:'';
    position:absolute;
    inset:0;
    background:
    linear-gradient(
        to top,
        rgba(0,0,0,.7),
        transparent
    );
}

/* PROFILE TOP */

.profile-top{
    position:relative;
    display:flex;
    align-items:flex-end;
    gap:25px;
    padding:0 30px;
    margin-top:-70px;
    flex-wrap:wrap;
}

.avatar{
    width:140px;
    height:140px;
    border-radius:50%;
    border:5px solid #1e1e1e;
    object-fit:cover;
    background:#111;
    box-shadow:0 10px 30px rgba(0,0,0,.5);
}

.profile-info{
    flex:1;
}

.profile-info h1{
    font-size:2rem;
    margin-bottom:5px;
}

.realname{
    color:var(--muted);
    margin-bottom:15px;
}

/* USER META */

.meta{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
}

.meta-box{
    background:#2a2a2a;
    border:1px solid #444;
    border-radius:12px;
    padding:10px 14px;
    min-width:120px;
}

.meta-box small{
    display:block;
    color:var(--muted);
    font-size:12px;
    margin-bottom:4px;
}

.meta-box strong{
    color:white;
    font-size:14px;
}

/* ACTIONS */

.actions{
    margin-left:auto;
    display:flex;
    gap:10px;
}

.btn{
    border:none;
    padding:11px 18px;
    border-radius:10px;
    cursor:pointer;
    font-weight:600;
    transition:.3s;
}

.btn-primary{
    background:
    linear-gradient(
        135deg,
        var(--primary),
        var(--primary-dark)
    );
    color:white;
}

.btn-secondary{
    background:#2d2d2d;
    color:white;
}

.btn:hover{
    transform:translateY(-2px);
}

/* STATS */

.stats{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    border-top:1px solid var(--border);
    margin-top:30px;
}

.stat{
    padding:20px;
    text-align:center;
}

.stat h2{
    color:var(--primary);
    margin-bottom:5px;
}

.stat p{
    color:var(--muted);
    font-size:14px;
}

/* SECTIONS */

.section{
    margin-top:25px;
    background:var(--card);
    border:1px solid var(--border);
    border-radius:16px;
    padding:25px;
}

.section-title{
    font-size:18px;
    margin-bottom:18px;
    border-left:4px solid var(--primary);
    padding-left:10px;
}

/* BIO */

.bio{
    line-height:1.8;
    color:#ddd;
}

/* TAGS */

.tags{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}

.tag{
    background:
    linear-gradient(
        135deg,
        #dc3545,
        #6f0d18
    );
    color:white;
    padding:8px 14px;
    border-radius:30px;
    font-size:13px;
    font-weight:600;
}

/* SOCIAL */

.social-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
}

.social-box{
    background:#181818;
    border:1px solid #333;
    border-radius:12px;
    padding:15px;
    display:flex;
    align-items:center;
    gap:12px;
}

.social-box i{
    font-size:22px;
    color:var(--primary);
}

/* POSTS */

.posts-grid{
    display:grid;
    grid-template-columns:
    repeat(auto-fill,minmax(180px,1fr));
    gap:15px;
}

.post{
    position:relative;
    overflow:hidden;
    border-radius:14px;
}

.post img{
    width:100%;
    height:180px;
    object-fit:cover;
    transition:.4s;
}

.post:hover img{
    transform:scale(1.08);
}

.overlay{
    position:absolute;
    inset:0;
    background:rgba(0,0,0,.5);
    display:flex;
    justify-content:center;
    align-items:center;
    opacity:0;
    transition:.3s;
}

.post:hover .overlay{
    opacity:1;
}

/* EMPTY */

.empty{
    text-align:center;
    color:var(--muted);
    padding:30px;
}

/* MOBILE */

@media(max-width:768px){

    .profile-top{
        flex-direction:column;
        align-items:center;
        text-align:center;
    }

    .actions{
        width:100%;
        justify-content:center;
        margin:0;
    }

    .stats{
        grid-template-columns:1fr;
    }

    .meta{
        justify-content:center;
    }

    .profile-info h1{
        font-size:1.6rem;
    }

    .avatar{
        width:120px;
        height:120px;
    }

}

</style>
</head>

<body>

<!-- NAVBAR -->

<div class="navbar">

    <div class="nav-container">

        <div class="logo"
        onclick="location.href='home.php'">

            🎮 <span>GetMatch</span>

        </div>

        <div class="nav-menu">

            <a href="home.php" class="nav-link">
                <i class="fas fa-home"></i>
            </a>

            <a href="Events.php" class="nav-link">
                <i class="fas fa-flag"></i>
            </a>

            <a href="shorts.php" class="nav-link">
                <i class="fas fa-video"></i>
            </a>

            <a href="my_Profile.php" class="nav-link">
                <i class="fas fa-user-circle"></i>
            </a>

        </div>

    </div>

</div>

<div class="container">

    <!-- PROFILE -->

    <div class="profile-card">

        <div class="cover"></div>

        <div class="profile-top">

            <img
            src="<?php echo 'assets/images/profiles/' . htmlspecialchars($user['IMAGE']); ?>"
            class="avatar">

            <div class="profile-info">

                <h1>
                    <?php echo htmlspecialchars($user['USER_NAME']); ?>
                </h1>

                <div class="realname">
                    <?php echo htmlspecialchars($user['FULL_NAME']); ?>
                </div>

                <!-- AGE + GENDER -->

                <div class="meta">

                    <div class="meta-box">
                        <small>Age</small>
                        <strong>
                            <?php echo !empty($user['AGE']) ? $user['AGE'] : 'N/A'; ?>
                        </strong>
                    </div>

                    <div class="meta-box">
                        <small>Gender</small>
                        <strong>
                            <?php echo !empty($user['GENDER']) ? htmlspecialchars($user['GENDER']) : 'N/A'; ?>
                        </strong>
                    </div>

                    <div class="meta-box">
                        <small>Email</small>
                        <strong>
                            <?php echo htmlspecialchars($user['EMAIL']); ?>
                        </strong>
                    </div>

                </div>

            </div>

            <div class="actions">

                <button
                class="btn btn-primary"
                onclick="location.href='edit-profile.php'">

                    <i class="fas fa-edit"></i>
                    Edit Profile

                </button>

                <button class="btn btn-secondary">

                    <i class="fas fa-share"></i>

                </button>

            </div>

        </div>

        <!-- STATS -->

        <div class="stats">

            <div class="stat">
                <h2>
                    <?php echo (int)($user['POSTS'] ?? 0); ?>
                </h2>
                <p>Posts</p>
            </div>

            <div class="stat">
                <h2>
                    <?php echo (int)($user['FALLOWERS'] ?? 0); ?>
                </h2>
                <p>Followers</p>
            </div>

            <div class="stat">
                <h2>
                    <?php echo (int)($user['FALLOWING'] ?? 0); ?>
                </h2>
                <p>Following</p>
            </div>

        </div>

    </div>

    <!-- ABOUT -->

    <div class="section">

        <h2 class="section-title">
            <i class="fas fa-user"></i>
            About Me
        </h2>

        <div class="bio">

            <?php
            echo $user['BIO']
            ?
            nl2br(htmlspecialchars($user['BIO']))
            :
            'No bio added yet.';
            ?>

        </div>

    </div>

    <!-- INTERESTS -->

    <div class="section">

        <h2 class="section-title">
            <i class="fas fa-gamepad"></i>
            Interests
        </h2>

        <div class="tags">

        <?php if($tags_result->num_rows > 0): ?>

            <?php while($tag = $tags_result->fetch_assoc()): ?>

                <div class="tag">
                    #<?php echo htmlspecialchars($tag['Tag_Name']); ?>
                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="empty">
                No interests selected yet.
            </div>

        <?php endif; ?>

        </div>

    </div>

    <!-- CONTACT -->

    <div class="section">

        <h2 class="section-title">
            <i class="fas fa-link"></i>
            Contact & Social
        </h2>

        <div class="social-grid">

            <div class="social-box">

                <i class="fas fa-envelope"></i>

                <div>

                    <small style="color:#aaa;">
                        Email
                    </small>

                    <div>
                        <?php echo htmlspecialchars($user['EMAIL']); ?>
                    </div>

                </div>

            </div>

            <?php if(!empty($user['FACEBOOK'])): ?>

            <div class="social-box">

                <i class="fab fa-facebook"></i>

                <div>

                    <small style="color:#aaa;">
                        Facebook
                    </small>

                    <div>
                        <?php echo htmlspecialchars($user['FACEBOOK']); ?>
                    </div>

                </div>

            </div>

            <?php endif; ?>

            <?php if(!empty($user['WHATSAPP'])): ?>

            <div class="social-box">

                <i class="fab fa-whatsapp"></i>

                <div>

                    <small style="color:#aaa;">
                        WhatsApp
                    </small>

                    <div>
                        <?php echo htmlspecialchars($user['WHATSAPP']); ?>
                    </div>

                </div>

            </div>

            <?php endif; ?>

        </div>

    </div>

    <!-- POSTS -->

    <div class="section">

        <h2 class="section-title">
            <i class="fas fa-images"></i>
            Recent Posts
        </h2>

        <div class="posts-grid">

        <?php

        $posts_stmt = $conn->prepare("
            SELECT * 
            FROM posts 
            WHERE User_ID = ?
            ORDER BY Post_ID DESC
        ");

        $posts_stmt->bind_param("i", $user_id);
        $posts_stmt->execute();

        $posts = $posts_stmt->get_result();

        if($posts->num_rows > 0):

            while($post = $posts->fetch_assoc()):

        ?>

            <div class="post">

                <img
                src="<?php echo 'assets/images/posts/' . htmlspecialchars($post['Img_Path']); ?>">

                <div class="overlay">

                    <i class="fas fa-heart"
                    style="font-size:2rem;color:#ff4d6d;"></i>

                </div>

            </div>

        <?php
            endwhile;

        else:
        ?>

            <div class="empty">
                No posts yet.
            </div>

        <?php endif; ?>

        </div>

    </div>

</div>

</body>
</html>