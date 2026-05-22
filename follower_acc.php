<?php
session_start();
include("config.php");

if(!isset($_SESSION['id'])){
    header('location: login.php');
    exit;
}

if(isset($_POST['target_id']))
{
    $target_id = $_POST['target_id'];

    // GET USER
    $stmt = $conn->prepare("
        SELECT * FROM users 
        WHERE User_ID = ?
    ");

    $stmt->bind_param("i", $target_id);
    $stmt->execute();

    $user_array = $stmt->get_result();

    // GET TAGS
    $tags_stmt = $conn->prepare("
        SELECT t.Tag_Name, t.Category
        FROM user_tags ut
        JOIN tags t ON ut.Tag_ID = t.Tag_ID
        WHERE ut.User_ID = ?
        ORDER BY t.Category, t.Tag_Name
    ");

    $tags_stmt->bind_param("i", $target_id);
    $tags_stmt->execute();

    $tags_result = $tags_stmt->get_result();
}
else
{
    header("location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>GetMatch Profile</title>

<link rel="icon"
href="assets/images/event_accepted_50px.png"
type="image/icon type">

<link rel="stylesheet"
href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<link rel="stylesheet"
href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<style>

:root{
    --primary:#dc3545;
    --primary-dark:#8b0000;
    --dark:#111;
    --card:#1c1c1c;
    --border:#333;
    --text:#fff;
    --muted:#aaa;
}

/* BODY */
body{
    background:
    linear-gradient(135deg,#0f0f0f,#1a1a1a);
    min-height:100vh;
    overflow-x:hidden;
    color:white;
    font-family:'Segoe UI',sans-serif;
}

/* COVER */
.cover{
    background:
    linear-gradient(
    135deg,
    rgba(220,53,69,.9),
    rgba(0,0,0,.9)
    );

    height:260px;
    border-radius:18px 18px 0 0;
}

/* PROFILE CARD */
.profile-wrapper{
    background:#1a1a1a;
    border-radius:18px;
    overflow:hidden;
    box-shadow:
    0 15px 40px rgba(0,0,0,.5);
    border:1px solid #2e2e2e;
}

/* PROFILE TOP */
.profile-head{
    margin-top:-90px;
    padding:0 30px 30px;
}

/* IMAGE */
.profile-img{
    width:170px;
    height:170px;
    object-fit:cover;
    border-radius:50%;
    border:5px solid #1a1a1a;
    box-shadow:0 10px 30px rgba(0,0,0,.4);
}

/* USERNAME */
.username{
    font-size:2rem;
    font-weight:700;
}

.fullname{
    color:#ccc;
    margin-top:5px;
}

/* BUTTONS */
.btn-custom{
    border:none;
    padding:10px 18px;
    border-radius:10px;
    font-weight:600;
    transition:.3s;
}

.btn-follow{
    background:linear-gradient(
    135deg,
    var(--primary),
    var(--primary-dark)
    );

    color:white;
}

.btn-unfollow{
    background:#444;
    color:white;
}

.btn-custom:hover{
    transform:translateY(-2px);
}

/* STATS */
.stats{
    display:flex;
    justify-content:center;
    gap:50px;
    padding:20px;
    border-top:1px solid #2c2c2c;
    border-bottom:1px solid #2c2c2c;
    text-align:center;
}

.stat-number{
    font-size:1.4rem;
    font-weight:700;
    color:var(--primary);
}

.stat-label{
    color:#bbb;
    font-size:.9rem;
}

/* CARD */
.info-card{
    background:#222;
    border:1px solid #333;
    border-radius:14px;
    padding:20px;
    margin-bottom:20px;
}

.section-title{
    font-size:1.2rem;
    font-weight:700;
    margin-bottom:15px;
    border-left:4px solid var(--primary);
    padding-left:10px;
}

/* BADGES */
.tag-badge{
    display:inline-block;
    background:
    linear-gradient(
    135deg,
    var(--primary),
    #ff4d5d
    );

    color:white;
    padding:8px 14px;
    border-radius:20px;
    margin:5px;
    font-size:13px;
}

/* TAG CATEGORIES */
.tag-category{
    display:flex;
    flex-direction:column;
    gap:10px;
    margin-bottom:15px;
}

.tag-category-label{
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
    color:var(--primary);
    letter-spacing:0.5px;
}

.tag-category-items{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
}

/* PROFILE DETAILS */
.detail-box{
    display:flex;
    align-items:center;
    margin-bottom:15px;
    gap:15px;
}

.detail-icon{
    width:45px;
    height:45px;
    border-radius:12px;
    background:#2c2c2c;
    display:flex;
    align-items:center;
    justify-content:center;
    color:var(--primary);
    font-size:18px;
}

.detail-content small{
    display:block;
    color:#aaa;
}

.detail-content strong{
    color:white;
}

/* GALLERY */
.gallery{
    display:grid;
    grid-template-columns:
    repeat(auto-fill,minmax(220px,1fr));

    gap:15px;
}

.gallery-item{
    position:relative;
    overflow:hidden;
    border-radius:14px;
}

.gallery-item img{
    width:100%;
    height:220px;
    object-fit:cover;
    transition:.3s;
}

.gallery-item:hover img{
    transform:scale(1.05);
}

.overlay{
    position:absolute;
    inset:0;
    background:rgba(0,0,0,.5);
    opacity:0;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:20px;
    transition:.3s;
}

.gallery-item:hover .overlay{
    opacity:1;
}

/* MOBILE */
@media(max-width:768px){

    .profile-head{
        text-align:center;
    }

    .profile-img{
        width:130px;
        height:130px;
    }

    .username{
        font-size:1.5rem;
    }

    .stats{
        gap:20px;
    }

    .gallery{
        grid-template-columns:1fr;
    }
}

.follow-btn{
    min-width:140px;
    height:48px;
    font-size:15px;
    border-radius:12px;
    font-weight:700;
    box-shadow:0 8px 20px rgba(220,53,69,.25);
}

.follow-btn i{
    margin-right:6px;
}

@media(max-width:768px){

    .follow-btn{
        width:100%;
        margin-top:15px;
    }

}

</style>

</head>

<body>

<?php include("navbar.php"); ?>

<?php foreach($user_array as $array_user){ ?>

<div class="container py-5">

<div class="profile-wrapper">

    <!-- COVER -->
    <div class="cover"></div>

    <!-- PROFILE HEAD -->
    <div class="profile-head">

        <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between">

            <!-- IMAGE -->
            <div class="mr-md-4">

                <img
                src="<?php echo 'assets/images/profiles/'.$array_user['IMAGE']; ?>"
                class="profile-img">

            </div>

            <!-- INFO -->
            <div class="flex-grow-1 mt-4 mt-md-0">

                <div class="username">
                    <?php echo htmlspecialchars($array_user['USER_NAME']); ?>
                </div>

                <div class="fullname">
                    <?php echo htmlspecialchars($array_user['FULL_NAME']); ?>
                </div>

                <!-- AGE -->
                <?php
                $age = "N/A";

                if(!empty($array_user['Birthdate'])){
                    $birthDate = new DateTime($array_user['Birthdate']);
                    $today = new DateTime();
                    $age = $today->diff($birthDate)->y;
                }
                ?>

                <div class="mt-3 text-light">

                    <span class="mr-4">
                        <i class="fas fa-venus-mars text-danger"></i>
                        <?php echo $array_user['Gender'] ?: 'Not Set'; ?>
                    </span>

                    <span class="mr-4">
                        <i class="fas fa-birthday-cake text-danger"></i>
                        <?php echo $age; ?> years old
                    </span>

                    <span>
                        <i class="fas fa-calendar text-danger"></i>
                        <?php echo $array_user['Birthdate'] ?: 'No Birthdate'; ?>
                    </span>

                </div>

            </div>

            <!-- FOLLOW -->
            <!-- FOLLOW BUTTON -->
            <div class="mt-4 mt-md-0 text-md-right">

                <?php include('Check_FallowStatus.php'); ?>

                <?php if($following_status){ ?>

                    <form method="post" action="Unfollow_User.php">

                        <input
                        type="hidden"
                        value="<?php echo $array_user['User_ID']?>"
                        name="other_User_Id">

                        <button
                        type="submit"
                        name="unfollow"
                        class="btn-custom btn-unfollow follow-btn">

                            <i class="fas fa-user-minus"></i>
                            Unfollow

                        </button>

                    </form>

                <?php } else { ?>

                    <form method="post" action="fallow_user.php">

                        <input
                        type="hidden"
                        name="fallow_person"
                        value="<?php echo $array_user['User_ID'];?>">

                        <button
                        type="submit"
                        name="fallow"
                        class="btn-custom btn-follow follow-btn">

                            <i class="fas fa-user-plus"></i>
                            Follow

                        </button>

                    </form>

                <?php } ?>

            </div>

        </div>

    </div>

    <!-- STATS -->
    <div class="stats">

        <div>
            <div class="stat-number">
                <?php echo $array_user['POSTS']; ?>
            </div>
            <div class="stat-label">Posts</div>
        </div>

        <div>
            <div class="stat-number">
                <?php echo $array_user['FALLOWERS']; ?>
            </div>
            <div class="stat-label">Followers</div>
        </div>

        <div>
            <div class="stat-number">
                <?php echo $array_user['FALLOWING']; ?>
            </div>
            <div class="stat-label">Following</div>
        </div>

    </div>

    <div class="container py-4">

        <!-- ABOUT -->
        <div class="info-card">

            <div class="section-title">
                About
            </div>

            <p style="color:#ddd;">
                <?php
                echo !empty($array_user['BIO'])
                ?
                nl2br(htmlspecialchars($array_user['BIO']))
                :
                "No bio yet.";
                ?>
            </p>

        </div>

        <!-- DETAILS -->
        <div class="info-card">

            <div class="section-title">
                Profile Details
            </div>

            <div class="detail-box">

                <div class="detail-icon">
                    <i class="fas fa-envelope"></i>
                </div>

                <div class="detail-content">
                    <small>Email</small>
                    <strong>
                        <?php echo htmlspecialchars($array_user['EMAIL']); ?>
                    </strong>
                </div>

            </div>

            <div class="detail-box">

                <div class="detail-icon">
                    <i class="fab fa-facebook"></i>
                </div>

                <div class="detail-content">
                    <small>Facebook</small>
                    <strong>
                        <?php echo $array_user['FACEBOOK'] ?: 'No Facebook'; ?>
                    </strong>
                </div>

            </div>

            <div class="detail-box">

                <div class="detail-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>

                <div class="detail-content">
                    <small>WhatsApp</small>
                    <strong>
                        <?php echo $array_user['WHATSAPP'] ?: 'No WhatsApp'; ?>
                    </strong>
                </div>

            </div>

        </div>

        <!-- INTERESTS -->
        <div class="info-card">

            <div class="section-title">
                Interests
            </div>

            <?php if($tags_result->num_rows > 0){ ?>

                <?php 
                $tags_array = [];
                $tags_result->data_seek(0);
                while($tag = $tags_result->fetch_assoc()): 
                    $category = $tag['Category'] ?? 'General';
                    if (!isset($tags_array[$category])) {
                        $tags_array[$category] = [];
                    }
                    $tags_array[$category][] = $tag['Tag_Name'];
                endwhile;
                ?>

                <?php foreach($tags_array as $category => $tag_names): ?>
                    <div class="tag-category">
                        <div class="tag-category-label"><?php echo htmlspecialchars($category); ?></div>
                        <div class="tag-category-items">
                            <?php foreach($tag_names as $tag_name): ?>
                                <span class="tag-badge">
                                    #<?php echo htmlspecialchars($tag_name); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php } else { ?>

                <p class="text-muted">
                    No interests selected yet.
                </p>

            <?php } ?>

        </div>

        <!-- POSTS -->
        <div class="info-card">

            <div class="section-title">
                Recent Posts
            </div>

            <div class="gallery">

                <?php include("get_targetPosts.php"); ?>

                <?php foreach($posts as $post){ ?>

                    <div class="gallery-item">

                        <img
                        src="<?php echo './assets/images/posts/'.$post['Img_Path'];?>">

                        <div class="overlay">

                            <div>
                                ❤️ <?php echo $post['Likes']; ?>
                            </div>

                            <a
                            href="single-post.php?post_id=<?php echo $post['Post_ID'];?>"
                            target="_blank"
                            style="color:white;">

                                <i class="fas fa-comment fa-2x"></i>

                            </a>

                        </div>

                    </div>

                <?php } ?>

            </div>

        </div>

    </div>

</div>

</div>

<?php } ?>

<script>
document.getElementById("logo-img").onclick = function () {
    location.href = "home.php";
};
</script>

</body>
</html>