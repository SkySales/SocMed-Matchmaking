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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>EventsWave - Select Interests</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-login-form.min.css">

    <style>
        .tag-box {
            border: 2px solid #ddd;
            padding: 10px 18px;
            border-radius: 30px;
            cursor: pointer;
            margin: 5px;
            display: inline-block;
            transition: 0.3s;
            font-size: 14px;
        }

        .tag-box:hover {
            border-color: #0d6efd;
        }

        .tag-box.active {
            background: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }
    </style>

</head>

<body>

<section class="vh-100" style="background-image: url('assets/images/login_request/cover.png');">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-10">
                <div class="card" style="border-radius: 1rem;">
                    <div class="row g-0">
                        <!-- LEFT IMAGE -->
                        <div class="col-md-6 col-lg-5 d-none d-md-block">
                            <img src="assets/images/login_request/signup_img.jpg"
                                 class="img-fluid"
                                 style="border-radius: 1rem 0 0 1rem;" />
                        </div>
                        <!-- RIGHT CONTENT -->
                        <div class="col-md-6 col-lg-7 d-flex align-items-center">
                            <div class="card-body p-4 p-lg-5 text-black text-center">
                                <form method="POST" action="save-tags.php">
                                    <div class="d-flex justify-content-center">
                                        <!-- <img class="mb-4" src="assets/images/login_request/small_logo.png" height="45"> -->
                                    </div>
                                    <h6 class="mb-3 text-muted"><b>Select Your Games</b></h6>
                                    <p class="text-muted">Choose what you play 👇</p>
                                    <!-- TAGS -->
                                    <div class="mb-4" style="max-height: 250px; overflow-y: auto;">
                                        <?php while ($row = $result->fetch_assoc()) { ?>
                                            <div class="tag-box" onclick="toggleTag(this)">
                                                <input type="checkbox" name="tags[]" value="<?php echo $row['Tag_ID']; ?>" hidden>
                                                <?php echo $row['Tag_Name']; ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <button class="btn btn-dark btn-lg w-100" type="submit">
                                        CONTINUE
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function toggleTag(el) {
        el.classList.toggle("active");
        let checkbox = el.querySelector("input");
        checkbox.checked = !checkbox.checked;
    }
</script>

</body>
</html>