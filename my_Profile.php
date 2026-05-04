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
    <title>My Profile - EventsWave</title>
    <link rel="icon" href="assets/images/event_accepted_50px.png" type="image/icon type">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        :root {
        --primary: #b30f0f;
        --primary-dark: #220d0d;
        --secondary: #791b1b;
        --bg-light: #f1f5f9;
        --bg-white: #ffffff;
        --text-dark: #0f172a;
        --text-light: #64748b;
        --border: #e2e8f0;
        }

        /* GLOBAL */
        body {
        font-family: 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 100%);
        }

        /* NAVBAR FIX */
        .navbar {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: white;
        border-bottom: 1px solid var(--border);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .nav-wrapper {
        max-width: 1200px;
        margin: auto;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        }

        .brand-img {
        height: 38px;
        }

        .nav-items {
        display: flex;
        gap: 20px;
        align-items: center;
        }

        .nav-items a,
        .nav-items i {
        color: #334155;
        transition: 0.2s;
        cursor: pointer;
        }

        .nav-items i:hover,
        .nav-items a:hover {
        color: var(--primary);
        transform: scale(1.1);
        }

        /* CONTAINER */
        .container-custom {
        max-width: 1100px;
        margin: auto;
        padding: 2rem 1rem;
        }

        /* PROFILE HEADER */
        .profile-header {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid var(--border);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }

        .header-cover {
        height: 180px;
        background: linear-gradient(135deg, #941f1f, #000000);
        }

        /* PROFILE TOP */
        .profile-top {
        display: flex;
        align-items: flex-end;
        gap: 20px;
        margin-top: -60px;
        padding: 0 25px;
        flex-wrap: wrap;
        }

        /* AVATAR */
        .avatar-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        object-fit: cover;
        }

        /* INFO */
        .profile-info {
        flex: 1;
        }

        .profile-info h1 {
        font-size: 1.6rem;
        font-weight: 700;
        }

        .handle {
        color: var(--text-light);
        }

        /* BUTTONS */
        .action-buttons {
        margin-left: auto;
        display: flex;
        gap: 10px;
        }

        .btn-custom {
        border-radius: 8px;
        padding: 8px 14px;
        font-weight: 600;
        border: none;
        }

        .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        }

        .btn-secondary-custom {
        background: #f1f5f9;
        color: var(--primary);
        }

        /* STATS */
        .profile-stats {
        display: flex;
        text-align: center;
        border-top: 1px solid var(--border);
        margin-top: 20px;
        }

        .stat-item {
        flex: 1;
        padding: 12px;
        }

        .stat-count {
        font-weight: bold;
        color: var(--primary);
        }

        /* CARDS */
        .section-card {
        background: white;
        border-radius: 14px;
        padding: 20px;
        margin-top: 20px;
        border: 1px solid var(--border);
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        }

        .section-title {
        font-weight: 700;
        margin-bottom: 12px;
        border-bottom: 2px solid var(--primary);
        padding-bottom: 5px;
        }

        /* TAGS */
        .tag-badge {
        background: linear-gradient(135deg, #af1616, #6e0d0d);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        margin: 4px;
        display: inline-block;
        }

        /* POSTS */
        .posts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px,1fr));
        gap: 10px;
        }

        .post-card {
        border-radius: 10px;
        overflow: hidden;
        }

        .post-img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        }

        /* NAVBAR BASE */
        .navbar-custom {
            position: fixed;
            top: 0;
            width: 100%;
            background: #1a1a1a;
            border-bottom: 2px solid #dc3545;
            z-index: 999;
            box-shadow: 0 4px 20px rgba(220, 53, 69, 0.15);
        }

        /* CONTAINER */
        .nav-container {
            max-width: 1200px;
            margin: auto;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* LOGO */
        .logo {
            font-size: 1.4rem;
            font-weight: 700;
            color: #dc3545;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.5px;
        }

        .logo span {
            color: #ffffff;
        }

        .logo:hover {
            text-shadow: 0 0 10px rgba(220, 53, 69, 0.6);
        }

        /* MENU */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        /* LINKS */
        .nav-link {
            color: #d0d0d0;
            font-size: 1.1rem;
            padding: 8px 10px;
            border-radius: 8px;
            transition: all 0.25s ease;
            position: relative;
        }

        /* HOVER EFFECT */
        .nav-link:hover {
            color: #ffffff;
            background: rgba(220, 53, 69, 0.15);
            transform: translateY(-2px);
        }

        /* ACTIVE LINK */
        .nav-link.active {
            color: #dc3545;
        }

        /* PROFILE ICON SPECIAL */
        .nav-link.profile i {
            font-size: 1.4rem;
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .nav-container {
                padding: 10px 15px;
            }

            .logo {
                font-size: 1.2rem;
            }

            .nav-menu {
                gap: 12px;
            }

            .nav-link {
                font-size: 1rem;
                padding: 6px;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar-custom">
    <div class="nav-container">

        <!-- LOGO -->
        <div class="logo" onclick="location.href='home.php'">
            🎮 <span>GetMatch</span>
        </div>

        <!-- MENU -->
        <div class="nav-menu">
            <a href="home.php" class="nav-link active">
                <i class="fas fa-home"></i>
            </a>

            <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#search-model">
                <i class="fas fa-search"></i>
            </a>

            <a href="Events.php" class="nav-link">
                <i class="fas fa-flag"></i>
            </a>

            <a href="shorts.php" class="nav-link">
                <i class="fas fa-video"></i>
            </a>

            <a href="Event-Calander/index.php" class="nav-link">
                <i class="fas fa-calendar-alt"></i>
            </a>

            <a href="my_Profile.php" class="nav-link profile">
                <i class="fas fa-user-circle"></i>
            </a>
        </div>

    </div>
</nav>

<div class="container-custom">

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="header-cover"></div>
        <div class="profile-content">

            <div class="profile-top">
                <div class="profile-avatar">
                    <img src="<?php echo 'assets/images/profiles/' . htmlspecialchars($user['IMAGE']); ?>"
                         alt="Profile photo"
                         class="avatar-img">
                </div>

                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($user['USER_NAME']); ?></h1>
                    <div class="handle"><?php echo htmlspecialchars($user['FULL_NAME']); ?></div>
                </div>

                <div class="action-buttons">
                    <form action="edit-profile.php" style="display:contents;">
                        <button type="submit" class="btn-custom btn-primary-custom">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </form>
                    <button class="btn-custom btn-secondary-custom border rounded-circle" type="button">
                        <i class="fas fa-share"></i> 
                    </button>
                </div>
            </div>

            <!-- Stats moved outside the flex row so they always span full width -->
            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-count"><?php echo (int)($user['POSTS'] ?? 0); ?></span>
                    <span class="stat-label">Posts</span>
                </div>
                <div class="stat-item">
                    <span class="stat-count"><?php echo (int)($user['FALLOWERS'] ?? 0); ?></span>
                    <span class="stat-label">Followers</span>
                </div>
                <div class="stat-item">
                    <span class="stat-count"><?php echo (int)($user['FALLOWING'] ?? 0); ?></span>
                    <span class="stat-label">Following</span>
                </div>
            </div>

        </div>
    </div>

    <!-- About -->
    <div class="section-card">
        <h2 class="section-title"><i class="fas fa-user"></i> About</h2>
        <p class="bio-text">
            <?php echo $user['BIO'] ? nl2br(htmlspecialchars($user['BIO'])) : '<em>No bio yet.</em>'; ?>
        </p>
    </div>

    <!-- Interests -->
    <div class="section-card">
        <h2 class="section-title"><i class="fas fa-star"></i> Interests</h2>
        <div class="tags-container">
            <?php if($tags_result->num_rows > 0): ?>
                <?php while($tag = $tags_result->fetch_assoc()): ?>
                    <span class="tag-badge">
                        <i class="fas fa-hashtag"></i>
                        <?php echo htmlspecialchars($tag['Tag_Name']); ?>
                    </span>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="bio-text" style="margin:0;">No interests selected yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contact & Social -->
    <div class="section-card">
        <h2 class="section-title"><i class="fas fa-link"></i> Contact &amp; Social</h2>
        <div class="social-list">
            <div class="social-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <small style="color:var(--text-light);">Email</small>
                    <strong><?php echo htmlspecialchars($user['EMAIL']); ?></strong>
                </div>
            </div>
            <?php if(!empty($user['FACEBOOK'])): ?>
            <div class="social-item">
                <i class="fab fa-facebook"></i>
                <div>
                    <small style="color:var(--text-light);">Facebook</small>
                    <strong><?php echo htmlspecialchars($user['FACEBOOK']); ?></strong>
                </div>
            </div>
            <?php endif; ?>
            <?php if(!empty($user['WHATSAPP'])): ?>
            <div class="social-item">
                <i class="fab fa-whatsapp"></i>
                <div>
                    <small style="color:var(--text-light);">WhatsApp</small>
                    <strong><?php echo htmlspecialchars($user['WHATSAPP']); ?></strong>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Posts -->
    <div class="section-card">
        <h2 class="section-title"><i class="fas fa-images"></i> Recent Posts</h2>
        <div class="posts-grid">
            <?php
            $posts_stmt = $conn->prepare("SELECT * FROM posts WHERE User_ID = ? ORDER BY Post_ID DESC");
            $posts_stmt->bind_param("i", $user_id);
            $posts_stmt->execute();
            $posts = $posts_stmt->get_result();

            if($posts->num_rows > 0):
                while($post = $posts->fetch_assoc()):
            ?>
            <div class="post-card">
                <img src="<?php echo 'assets/images/posts/' . htmlspecialchars($post['Img_Path']); ?>"
                     alt="Post image"
                     class="post-img">
                <div class="post-overlay">
                    <i class="fas fa-heart" style="color:#ec4899;font-size:2rem;"></i>
                </div>
            </div>
            <?php
                endwhile;
            else:
            ?>
            <div class="empty-state">
                <i class="fas fa-image"></i>
                <p>No posts yet. Start sharing your events!</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
    // Guard: element may not exist on this page
    var logo = document.getElementById("logo-img");
    if(logo) logo.onclick = function(){ location.href = "home.php"; };
</script>

</body>
</html>