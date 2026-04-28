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
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #8b5cf6;
            --accent: #ec4899;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --text-dark: #0f172a;
            --text-light: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0,0,0,.1);
            --shadow-lg: 0 20px 25px -5px rgba(0,0,0,.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, var(--bg-light) 0%, #f1f5f9 100%);
            color: var(--text-dark);
            line-height: 1.6;
            padding-top: 0px;
            padding-bottom: 70px;
            min-height: 100vh;
        }

        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* ── Profile Header ── */
        .profile-header {
            background: var(--bg-white);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            margin-bottom: 2rem;
            border: 1px solid var(--border);
        }

        .header-cover {
            height: 200px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            background-size: cover;
            background-position: center;
        }

        .profile-content { padding: 0 2rem 2rem; }

        /*
         * FIX: replaced CSS Grid with Flexbox so the avatar, info, and buttons
         * wrap naturally on narrow screens without the columns collapsing into
         * each other. align-items: flex-end keeps everything bottom-aligned
         * against the cover image pull-up.
         */
        .profile-top {
            display: flex;
            align-items: flex-end;
            gap: 1.5rem;
            margin-top: -56px; /* pulls content up over the cover */
            position: relative;
            z-index: 10;
            flex-wrap: wrap;
        }

        /* Avatar */
        .profile-avatar { flex-shrink: 0; }

        .avatar-img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--bg-white);
            box-shadow: var(--shadow-lg);
            display: block;
            transition: transform .3s ease;
        }

        .avatar-img:hover { transform: scale(1.05); }

        /*
         * FIX: added padding-top so name/handle text isn't hidden behind
         * the avatar when the row wraps on small screens.
         */
        .profile-info {
            flex: 1;
            min-width: 0; /* prevents text overflow pushing layout */
            padding-top: 60px;
        }

        .profile-info h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
            letter-spacing: -.5px;
            /* FIX: truncate long usernames instead of breaking layout */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-info .handle {
            color: var(--text-light);
            font-size: 1rem;
            font-weight: 500;
        }

        /*
         * FIX: action buttons pushed to the right with margin-left:auto and
         * aligned to flex-end so they sit at the bottom of the header row
         * without fighting the stats section.
         */
        .action-buttons {
            margin-left: auto;
            padding-top: 60px;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        /* Stats ── moved BELOW the flex row so it always spans full width */
        .profile-stats {
            display: flex;
            gap: 0;
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border);
        }

        .stat-item {
            flex: 1;
            text-align: center;
            padding: 0.5rem 0;
        }

        /* dividers between stats */
        .stat-item + .stat-item { border-left: 1px solid var(--border); }

        .stat-count {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary);
            display: block;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* Buttons */
        .btn-custom {
            padding: .65rem 1.25rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all .25s ease;
            font-size: .9rem;
            display: inline-flex;
            align-items: center;
            gap: .45rem;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59,130,246,.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59,130,246,.4);
        }

        .btn-secondary-custom {
            background: var(--bg-light);
            color: var(--primary);
            border: 1.5px solid var(--primary);
        }

        .btn-secondary-custom:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        /* Section cards */
        .section-card {
            background: var(--bg-white);
            border-radius: 16px;
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            transition: box-shadow .3s ease;
        }

        .section-card:hover { box-shadow: 0 25px 30px -5px rgba(0,0,0,.12); }

        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            padding-bottom: .85rem;
            border-bottom: 2px solid var(--primary);
            color: var(--text-dark);
        }

        .section-title i { color: var(--primary); font-size: 1.2rem; }

        .bio-text {
            color: var(--text-light);
            line-height: 1.8;
            font-size: .98rem;
            word-break: break-word;
        }

        /* Tags */
        .tags-container { display: flex; flex-wrap: wrap; gap: .6rem; }

        .tag-badge {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: .4rem .9rem;
            border-radius: 20px;
            font-size: .82rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: transform .25s ease, box-shadow .25s ease;
            box-shadow: 0 2px 8px rgba(59,130,246,.2);
        }

        .tag-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(59,130,246,.3);
        }

        /* Social list */
        .social-list { display: flex; flex-direction: column; gap: .75rem; }

        .social-item {
            display: flex;
            align-items: center;
            gap: .9rem;
            padding: 1rem 1.1rem;
            background: var(--bg-light);
            border-radius: 10px;
            border: 1px solid transparent;
            transition: all .25s ease;
        }

        .social-item:hover {
            background: white;
            border-color: var(--border);
            box-shadow: 0 4px 12px rgba(59,130,246,.08);
            transform: translateX(3px);
        }

        .social-item i { font-size: 1.1rem; color: var(--primary); width: 26px; text-align: center; }

        /* Posts grid */
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }

        .post-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            background: var(--bg-light);
            aspect-ratio: 1;
            cursor: pointer;
            transition: box-shadow .25s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,.08);
        }

        .post-card:hover { box-shadow: 0 8px 16px rgba(0,0,0,.14); }

        .post-img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s ease; }
        .post-card:hover .post-img { transform: scale(1.07); }

        .post-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,.45);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .25s ease;
            backdrop-filter: blur(2px);
        }

        .post-card:hover .post-overlay { opacity: 1; }

        .empty-state { text-align: center; padding: 2.5rem; color: var(--text-light); grid-column: 1 / -1; }
        .empty-state i { font-size: 3rem; color: var(--primary); margin-bottom: .75rem; opacity: .3; display: block; }
        .empty-state p { font-size: 1rem; font-weight: 500; }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            .container-custom { padding: 1rem; }

            .header-cover { height: 130px; }

            /*
             * FIX: on mobile the whole profile-top stacks vertically and
             * centres. Avatar sits in its own row, info + buttons below.
             */
            .profile-top {
                flex-direction: column;
                align-items: center;
                text-align: center;
                margin-top: -50px;
                gap: .75rem;
            }

            .profile-info { padding-top: 0; }
            .profile-info h1 { font-size: 1.3rem; white-space: normal; }

            .action-buttons {
                margin-left: 0;
                padding-top: 0;
                justify-content: center;
                width: 100%;
            }

            .btn-custom { flex: 1; justify-content: center; }

            .avatar-img { width: 100px; height: 100px; }

            .profile-stats { gap: 0; }

            .posts-grid { grid-template-columns: repeat(2, 1fr); gap: .6rem; }

            .section-card { padding: 1.25rem; }
        }

        @media (max-width: 380px) {
            .stat-count { font-size: 1.1rem; }
            .stat-label { font-size: .7rem; }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
  <nav class="navbar mobile-nav">

      <div class="nav-wrapper">
          <img src="assets/images/black_logo.png" class="brand-img" id="logo-img" style="cursor: pointer">

          <div class="nav-items">

              <a href="home.php" style="text-decoration: none; color: #1c1f23"><i class="icon fas fa-home fa-lg"></i></a>

              <i class="icon fas fa-search fa-lg" data-bs-toggle="modal" data-bs-target="#search-model"></i>
              <a href="Events.php" style="text-decoration: none; color: #1c1f23"><i class="icon fas fa-flag fa-lg"></i></a>
              <a href="shorts.php" style="text-decoration: none; color: #1c1f23"><i class="icon fas fa-video fa-lg"></i></a>
              <a href="Event-Calander/index.php" style="text-decoration: none; color: #1c1f23"><i class="icon fas fa-calendar-alt fa-lg"></i></a>
              <div class="icon user-profile">
                  <a href="my_Profile.php" ><i class="fas fa-user-circle fa-lg"></i></a>
              </div>

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