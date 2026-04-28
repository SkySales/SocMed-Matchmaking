<?php
session_start();
// require 'init.php';
include('config.php');

session_regenerate_id(true);

if(!isset($_SESSION['id']))
{
  header('location: login.php');
  exit;
}

$user_id = $_SESSION['id'];

/* ✅ CHECK IF USER ALREADY SELECTED TAGS */
$stmt = $conn->prepare("SELECT is_tagged FROM users WHERE User_ID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if($user['is_tagged'] == 0){
    header("location: select-tags.php");
    exit;
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EventsWave</title>

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
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, var(--bg-light) 0%, #f1f5f9 100%);
            color: var(--text-dark);
        }

        .main {
            padding-top: 80px;
            padding-bottom: 80px;
        }

        .wrapper {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
            padding: 1rem;
        }

        /* POST CARD */
        .post {
            background: var(--bg-white);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
        }

        .post:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .post .info {
            padding: 1rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border);
        }

        .post .user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .profile-pic {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .username {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .post-img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .post:hover .post-img {
            transform: scale(1.02);
        }

        .post-content {
            padding: 1rem;
        }

        .reactions-wrapper {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .reactions-wrapper form {
            margin: 0;
        }

        .reactions-wrapper button {
            background: none !important;
            border: none !important;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .reactions-wrapper button:hover {
            transform: scale(1.2);
        }

        .reactions-wrapper a {
            text-decoration: none;
            color: var(--text-dark);
            transition: transform 0.2s ease;
        }

        .reactions-wrapper a:hover {
            transform: scale(1.2);
            color: var(--accent);
        }

        .reactions {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        .description {
            margin-bottom: 0.75rem;
            line-height: 1.6;
            color: var(--text-dark);
        }

        .description span {
            font-weight: 600;
        }

        .post-time {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-bottom: 0.25rem;
        }

        /* RIGHT COLUMN */
        .right-col {
            position: sticky;
            top: 80px;
            height: fit-content;
        }

        .style-wrapper {
            background: var(--bg-white);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .suggestion_card {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .suggestion_card img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .suggestion_card p {
            margin: 0;
            font-size: 0.9rem;
        }

        .suggestion_card .username {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .sub-text {
            color: var(--text-light);
            font-size: 0.8rem;
        }

        .fallow-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            white-space: nowrap;
            transition: all 0.3s ease;
            margin-left: auto;
        }

        .fallow-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .suggesting {
            font-weight: 700;
            font-size: 1rem;
            margin: 1.5rem 0 1rem;
            color: var(--text-dark);
        }

        .profile-cards {
            background: var(--bg-white);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .profile-cards:hover {
            box-shadow: var(--shadow-lg);
        }

        .profile-pics {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
        }

        .profile-pics img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
        }

        .card {
            background: var(--bg-white) !important;
            border: none !important;
            box-shadow: var(--shadow-lg) !important;
            border-radius: 12px !important;
            overflow: hidden;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 1rem;
        }

        .card-title {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .card-text {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        /* PAGINATION */
        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }

        .page-link {
            color: var(--primary);
            border-color: var(--border);
        }

        .page-link:hover {
            background-color: var(--bg-light);
            color: var(--primary-dark);
        }

        .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* MODAL */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            border-bottom: 1px solid var(--border);
            background: var(--bg-light);
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .wrapper {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .right-col {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .main {
                padding-top: 70px;
                padding-bottom: 70px;
            }

            .post-img {
                height: 300px;
            }

            .wrapper {
                padding: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .wrapper {
                grid-template-columns: 1fr;
            }

            .post-img {
                height: 250px;
            }

            .reactions-wrapper {
                gap: 1rem;
            }

            .fallow-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>

<!-- Modern Navigation Bar -->
<?php include('navbar.php'); ?>



<!-- New Section -->


<section class="main">
    <div class="wrapper">

        <!-- Left Column - Feed -->
        <div class="left-col" id="left-col">

            <!-- Wrapper for posting -->

            <?php include('get_latest_posts.php'); ?>

            <?php

            include('get_dataById.php');

            foreach($posts as $post)
            {
                $data = get_UserData($post['User_ID']);

                $profile_img = $data[2];

                $profile_name = $data[0];

                ?>

            <div class="post" id="post">

                <div class="info">

                    <div class="user">

                        <div class="profile-pic"><img src="<?php echo "assets/images/profiles/". $profile_img; ?>"></div>

                        <p class="username"><?php echo $profile_name;?></p>

                    </div>

                </div>

                <img src="<?php echo "assets/images/posts/". $post['Img_Path']; ?>" class="post-img">

                    <div id="post_info_data">

                        <div class="post-content">

                            <div class="reactions-wrapper">

                                <?php include('check_like_status.php');?>

                                <?php if($reaction_status){?>

                                    <form>
                                        <input type="hidden" value="<?php echo $post['Post_ID'];?>" name="post_ids" id="post_ids">
                                        <button style="background: none; border: none;" type="submit" name="reaction">
                                            <i style="color: #fb3958;" class="icon fas fa-heart fa-lg" onclick="return unlike(<?php echo $post['Post_ID'];?>);"></i>
                                        </button>
                                    </form>

                                <?php } else{?>

                                    <form>
                                        <input type="hidden" value="<?php echo $post['Post_ID'];?>" name="post_id" id="post_id">
                                        <button style="background: none; border: none;" type="submit" name="reaction">
                                            <i style="color: #22262A;" class="icon fas fa-heart fa-lg" onclick="return like(<?php echo $post['Post_ID'];?>);"></i>
                                        </button>
                                    </form>

                                <?php }?>

                                <a href="single-post.php?post_id= <?php echo $post["Post_ID"];?>" style="color: #22262A;"><i class="icon fas fa-comment fa-lg"></i></a>

                            </div>

                            <p class="reactions" id="<?php echo 'reactions_'.$post['Post_ID'];?>"><?php echo $post['Likes'];?> Reactions</p>

                            <p class="description">
                                <span><?php echo $profile_name;?> Says :<br></span>

                                <?php echo $post['Caption'];?>
                            </p>

                            <p class="post-time"><?php echo date("M,Y,d", strtotime($post['Date_Upload']));?></p>

                            <p class="post-time" style="color: #0b5ed7"><?php echo $post['HashTags'];?></p>

                        </div>
                    </div>
            </div>

            <?php } ?>

            <!-- Modal For Post Options-->
            <div class="modal fade" id="post-model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Post Options</h5>
                        </div>
                        <div class="modal-body">

                            <i class="fa-solid fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#exampleModal2" data-bs-whatever="@mdo"></i><a href="" style="color: black; text-decoration: none;">Edit Post</a><br><br>

                            <i class="fa-solid fa-trash" data-bs-toggle="modal" data-bs-target="#delete_model" data-bs-whatever="@mdo"></i><a href="" style="color: black; text-decoration: none;">Delete Post</a>
                        </div>
                    </div>
                </div>
            </div>

            <!--Pagination bar-->
            <nav aria-label="Page navigation example" class="mx-auto mt-3">

            <ul class="pagination">
                        
                <li class="page-item <?php if($page_no<=1){echo 'disabled';}?>">
                             
                    <a class="page-link" href="<?php if($page_no<=1){echo'#';}else{ echo '?page_no='. ($page_no-1); }?>">Previous</a>
                        
                        </li>
                            <li class="page-item"><a class="page-link" href="?page_no=1">1</a></li>
                        
                            <li class="page-item"><a class="page-link" href="?page_no=2">2</a></li>
                        
                            <li class="page-item"><a class="page-link" href="?page_no=3">3</a></li>
                       <?php if($page_no >= 3){?>
                        
                        <li class="page-item"><a class="page-link" href="#">...</a></li>
                        
                        <li class="page-item"><a class="page-link" href="<?php echo "?page_no=". $page_no;?>"></a></li>
                        
                        <?php } ?>
                        
                        <li class="page-item <?php if($page_no>= $total_number_pages){echo 'disabled';}?>">
                        
                    <a class="page-link" href="<?php if($page_no>=$total_number_pages){echo "#";}else{ echo "?page_no=".($page_no+1);}?>">Next</a>
                        
                </li>
            </ul>
            </nav>
        
        </div>

        <!-- Design for right column -->

        <div class="right-col">

    <!-- PROFILE CARD -->
    <div class="style-wrapper" style="background: var(--bg-white);">
        <div class="suggestion_card">
            <img src="<?php echo "assets/images/profiles/".$_SESSION['img_path'];?>" alt="Profile">
            <div>
                <p class="username"><?php echo htmlspecialchars($_SESSION['username']);?></p>
                <p class="sub-text"><?php echo htmlspecialchars($_SESSION['fullname']);?></p>
            </div>
            <a href="logout.php" class="fallow-btn" style="font-size: 0.8rem;">LOG OUT</a>
        </div>
    </div>

    <!-- NORMAL SUGGESTIONS -->
    <p class="suggesting">🌟 Recommended For You</p>

    <?php
    include("get_suggestions.php");
    include("matchmaking.php");

    // ✅ RUN MATCHMAKING HERE
    $matches = get_match_suggestions($_SESSION['id']);
    ?>

    <?php foreach($matches as $suggestion){ ?>
        <?php if($suggestion['User_ID'] != $_SESSION['id']){ ?>

            <div class="profile-cards" id="suggestion_<?php echo $suggestion['User_ID'];?>">

                <!-- LEFT SIDE -->
                <div style="display:flex; align-items:center; gap:0.75rem; flex:1; min-width:0;">

                    <form id="suggestion_form<?php echo $suggestion['User_ID'];?>" method="post" action="follower_acc.php" style="margin:0;">
                        <input type="hidden" value="<?php echo $suggestion['User_ID']?>" name="target_id">

                        <div class="profile-pics">
                            <img src="<?php echo "assets/images/profiles/".$suggestion['IMAGE'];?>"
                            style="cursor:pointer;"
                            onclick="document.getElementById('suggestion_form<?php echo $suggestion['User_ID'];?>').submit();">
                        </div>
                    </form>

                    <div style="display:flex; flex-direction:column; justify-content:center; min-width:0; flex:1;">
                        <?php $new_string = mb_strimwidth($suggestion['FULL_NAME'], 0, 15, ".."); ?>

                        <span style="font-size:0.9rem; font-weight:bold; line-height:1.2; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            <?php echo htmlspecialchars($suggestion['USER_NAME']);?>
                        </span>

                        <span style="font-size:0.8rem; color:var(--text-light); line-height:1.2; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            <?php echo htmlspecialchars($new_string);?>
                        </span>

                        <span style="font-size:0.75rem; color:var(--primary); font-weight:600; margin-top:0.25rem;">
                            ⭐ <?php echo round($suggestion['score'], 2); ?>% match
                        </span>
                    </div>

                </div>

                <?php
                    $check_follow = $conn->prepare("SELECT * FROM fallowing WHERE User_ID = ? AND Other_user_id = ?");
                    $check_follow->bind_param("ii", $_SESSION['id'], $suggestion['User_ID']);
                    $check_follow->execute();
                    $is_following = $check_follow->get_result()->num_rows > 0;
                ?>

                <!-- RIGHT SIDE -->
                <form method="POST" action="fallow_user.php" style="margin:0;">
                    <input type="hidden" name="fallow_person" value="<?php echo $suggestion['User_ID'];?>">

                    <button type="submit" name="fallow"
                        style="
                            font-size:0.75rem;
                            padding:0.4rem 0.8rem;
                            border:none;
                            border-radius:6px;
                            background: <?php echo $is_following ? '#6c757d' : 'var(--primary)'; ?>;
                            color:white;
                            font-weight:600;
                            cursor:pointer;
                            white-space:nowrap;
                            transition: all 0.2s;
                        "
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                            <?php echo $is_following ? '✓ Following' : '+ Follow'; ?>
                    </button>
                </form>

            </div>

        <?php } ?>
    <?php } ?>

    <!-- UPCOMING EVENTS -->
    <?php

    $SQL = "SELECT * FROM events ORDER BY Event_ID DESC LIMIT 1;";
    $result = $conn->query($SQL);

    $Event_Caption = '';
    $Event_Date = '';
    $Poster = '';
    $Event_ID = '';

    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $Event_Caption = $row["Caption"];
        $Event_Date = $row["Event_Date"];
        $Event_ID = $row["Event_ID"];
        $Poster = $row["Event_Poster"];
    }
    ?>

    <p class="suggesting" style="margin-top: 2rem;">📅 Upcoming Events</p>

    <div class="card" style="width: 100%;">

        <img src="<?php echo "assets/images/posts/".$Poster; ?>"
        class="card-img-top" alt="Event">

        <div class="card-body">

            <h6 class="card-title">
                <i class="fas fa-calendar-alt" style="margin-right:0.5rem;"></i>
                <?php echo !empty($Event_Date) ? date("M d, Y", strtotime($Event_Date)) : 'Date : N/A'; ?>
            </h6>

            <p class="card-text">
                <?php echo !empty($Event_Caption) ? htmlspecialchars(substr($Event_Caption, 0, 100)) . (strlen($Event_Caption) > 100 ? '...' : '') : 'No event available'; ?>
            </p>

            <a href="Single-Event.php?post_id=<?php echo $Event_ID;?>"
               class="fallow-btn" style="font-size: 0.85rem;">
               <i class="fas fa-arrow-right" style="margin-right:0.5rem;"></i>Learn More
            </a>
        </div>
    </div>

</div>

    </div>

</section>


</body>

<script type="text/javascript">

    function like(post_id){

        $.ajax({
            type:"post",
            url:"like.php",
            data:
                {
                    'post_id' :post_id,
                },
            cache:false,
            success: function (html)
            {
                $('#left-col').load(document.URL +  ' #left-col');
            }
        });
        return false;
    }

    function unlike(post_id){

        $.ajax({
            type:"post",
            url:"unlike.php",
            data:
                {
                    'post_id' :post_id,
                },
            cache:false,
            success: function (html)
            {
                $('#left-col').load(document.URL +  ' #left-col');
            }
        });
        return false;
    }

    $(document).bind("contextmenu",function(e)
    {
        return false;
    });

</script>

<!-- <script type="text/javascript">
    document.getElementById("logo-img").onclick = function ()
    {
        location.href = "home.php";
    };
</script> -->

</html>