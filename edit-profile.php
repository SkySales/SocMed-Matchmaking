<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Profile - GetMatch</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

:root{
    --primary:#dc3545;
    --primary-dark:#8b0000;
    --bg:#0f0f0f;
    --card:#1a1a1a;
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
    radial-gradient(circle at top,#2b0d11,#0f0f0f);
    color:white;
    font-family:'Segoe UI',sans-serif;
    min-height:100vh;
}

/* CONTAINER */

.main-container{
    padding-top:90px;
    padding-bottom:40px;
}

/* CARD */

.card-custom{
    background:rgba(26,26,26,.95);
    border:1px solid var(--border);
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 10px 35px rgba(0,0,0,.5);
}

/* LEFT SIDE */

.profile-side{
    padding:30px 20px;
    text-align:center;
    background:
    linear-gradient(
        180deg,
        #1f1f1f,
        #151515
    );
    height:100%;
}

/* PROFILE IMAGE */

.profile-wrapper{
    position:relative;
    width:140px;
    margin:auto;
}

.user-img{
    width:140px;
    height:140px;
    border-radius:50%;
    border:4px solid var(--primary);
    object-fit:cover;
    box-shadow:0 10px 30px rgba(220,53,69,.4);
}

/* CAMERA BUTTON */

.camera-btn{
    position:absolute;
    bottom:5px;
    right:5px;
    background:var(--primary);
    width:38px;
    height:38px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    border:3px solid #111;
    transition:.3s;
}

.camera-btn:hover{
    transform:scale(1.1);
}

/* USER INFO */

.username{
    font-size:1.4rem;
    font-weight:700;
    margin-top:18px;
}

.fullname{
    color:var(--muted);
    margin-bottom:20px;
}

.bio-box{
    margin-top:20px;
    background:#111;
    padding:15px;
    border-radius:12px;
    border:1px solid #333;
}

.bio-box h6{
    color:var(--primary);
    margin-bottom:10px;
}

/* FORM */

.form-side{
    padding:35px;
}

.section-title{
    color:var(--primary);
    font-weight:700;
    margin-bottom:18px;
    border-left:4px solid var(--primary);
    padding-left:10px;
}

/* INPUTS */

.form-label{
    color:#ddd;
    font-size:14px;
    margin-bottom:6px;
}

.form-control,
.form-select{
    background:#121212;
    border:1px solid #333;
    color:white;
    border-radius:10px;
    padding:12px;
}

.form-control:focus,
.form-select:focus{
    background:#121212;
    color:white;
    border-color:var(--primary);
    box-shadow:0 0 10px rgba(220,53,69,.3);
}

textarea{
    resize:none;
}

/* BUTTONS */

.btn-custom{
    padding:12px 24px;
    border:none;
    border-radius:10px;
    font-weight:600;
    transition:.3s;
}

.btn-save{
    background:
    linear-gradient(
        135deg,
        var(--primary),
        var(--primary-dark)
    );
    color:white;
}

.btn-cancel{
    background:#2d2d2d;
    color:white;
}

.btn-custom:hover{
    transform:translateY(-2px);
}

/* FILE INPUT */

input[type=file]{
    display:none;
}

/* STATS */

.quick-stats{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:12px;
    margin-top:25px;
}

.stat-box{
    background:#111;
    border:1px solid #333;
    padding:15px;
    border-radius:12px;
}

.stat-box h5{
    color:var(--primary);
    margin-bottom:5px;
}

/* RESPONSIVE */

@media(max-width:768px){

    .form-side{
        padding:25px;
    }

    .quick-stats{
        grid-template-columns:1fr;
    }

}

</style>
</head>

<body>

<?php include('navbar.php'); ?>

<div class="container main-container">

<div class="row g-4">

    <!-- LEFT PROFILE -->

    <div class="col-lg-4">

        <div class="card-custom profile-side">

            <!-- PROFILE IMAGE -->

            <div class="profile-wrapper">

                <img
                class="user-img"
                id="previewImage"
                src="<?php echo "assets/images/profiles/" . $_SESSION['img_path']; ?>">

                <label for="profileInput" class="camera-btn">

                    <i class="fas fa-camera"></i>

                </label>

            </div>

            <h4 class="username">
                <?php echo $_SESSION['username'] ?? 'Username'; ?>
            </h4>

            <p class="fullname">
                <?php echo $_SESSION['fullname'] ?? 'Full Name'; ?>
            </p>

            <!-- BIO -->

            <div class="bio-box">

                <h6>
                    <i class="fas fa-user"></i>
                    About Me
                </h6>

                <p style="font-size:14px;color:#ccc;">

                    <?php
                    echo !empty($_SESSION['bio'])
                    ?
                    $_SESSION['bio']
                    :
                    'No bio yet.';
                    ?>

                </p>

            </div>

            <!-- QUICK STATS -->

            <div class="quick-stats">

                <div class="stat-box">

                    <h5>
                        <?php echo $_SESSION['posts'] ?? 0; ?>
                    </h5>

                    <small>Posts</small>

                </div>

                <div class="stat-box">

                    <h5>
                        <?php echo $_SESSION['followers'] ?? 0; ?>
                    </h5>

                    <small>Followers</small>

                </div>

                <div class="stat-box">

                    <h5>
                        <?php echo $_SESSION['following'] ?? 0; ?>
                    </h5>

                    <small>Following</small>

                </div>

            </div>

        </div>

    </div>

    <!-- RIGHT FORM -->

    <div class="col-lg-8">

        <div class="card-custom form-side">

            <h3 style="margin-bottom:25px;">

                <i class="fas fa-user-cog"></i>
                Edit Profile

            </h3>

            
            <form method="POST" action="update-profile.php" enctype="multipart/form-data">
                <!-- HIDDEN FILE -->

                <input
                type="file"
                id="profileInput"
                name="profile_img"
                accept="image/*">

                <!-- PERSONAL -->

                <h5 class="section-title">
                    Personal Information
                </h5>

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Full Name
                        </label>

                        <input
                        type="text"
                        class="form-control"
                        name="full_name"
                        value="<?php echo $_SESSION['fullname'] ?? ''; ?>"
                        required>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Username
                        </label>

                        <input
                        type="text"
                        class="form-control"
                        name="user_name"
                        value="<?php echo $_SESSION['username'] ?? ''; ?>"
                        required>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Age
                        </label>

                        <input
                        type="number"
                        class="form-control"
                        name="age"
                        min="13"
                        max="100"
                        value="<?php echo $_SESSION['age'] ?? ''; ?>"
                        required>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Gender
                        </label>

                        <select
                        class="form-select"
                        name="gender"
                        required>

                            <option value="">
                                Select Gender
                            </option>

                            <option value="Male"
                            <?php
                            if(($_SESSION['gender'] ?? '') == 'Male')
                            echo 'selected';
                            ?>>
                                Male
                            </option>

                            <option value="Female"
                            <?php
                            if(($_SESSION['gender'] ?? '') == 'Female')
                            echo 'selected';
                            ?>>
                                Female
                            </option>

                            <option value="Other"
                            <?php
                            if(($_SESSION['gender'] ?? '') == 'Other')
                            echo 'selected';
                            ?>>
                                Other
                            </option>

                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Birthdate
                        </label>

                        <input
                        type="date"
                        class="form-control"
                        name="birthdate"
                        value="<?php echo $_SESSION['birthdate'] ?? ''; ?>">

                    </div>

                    <div class="col-12">

                        <label class="form-label">
                            Bio
                        </label>

                        <textarea
                        class="form-control"
                        name="bio"
                        rows="4"
                        maxlength="250"
                        placeholder="Tell something about yourself..."><?php echo $_SESSION['bio'] ?? ''; ?></textarea>

                    </div>

                </div>

                <!-- CONTACT -->

                <h5 class="section-title mt-4">
                    Contact & Social
                </h5>

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                        type="email"
                        class="form-control"
                        name="email"
                        value="<?php echo $_SESSION['email'] ?? ''; ?>"
                        required>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            WhatsApp
                        </label>

                        <input
                        type="text"
                        class="form-control"
                        name="wapp"
                        value="<?php echo $_SESSION['whatsapp'] ?? ''; ?>">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Facebook
                        </label>

                        <input
                        type="text"
                        class="form-control"
                        name="fbook"
                        value="<?php echo $_SESSION['facebook'] ?? ''; ?>">

                    </div>

                </div>

                <!-- SECURITY -->

                <h5 class="section-title mt-4">
                    Security
                </h5>

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            New Password
                        </label>

                        <input
                        type="password"
                        class="form-control"
                        name="new_password"
                        placeholder="Leave blank if unchanged">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Confirm Password
                        </label>

                        <input
                        type="password"
                        class="form-control"
                        name="confirm_password"
                        placeholder="Confirm new password">

                    </div>

                </div>

                <!-- BUTTONS -->

                <div class="d-flex gap-3 justify-content-end mt-5">

                    <a
                    href="my_Profile.php"
                    class="btn btn-custom btn-cancel">

                        Cancel

                    </a>

                    <button
                    type="submit"
                    name="submit"
                    class="btn btn-custom btn-save">

                        <i class="fas fa-save"></i>
                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</div>

<script>

/* IMAGE PREVIEW */

document
.getElementById("profileInput")
.addEventListener("change", function(e){

    const file = e.target.files[0];

    if(file){

        const reader = new FileReader();

        reader.onload = function(event){

            document
            .getElementById("previewImage")
            .src = event.target.result;

        };

        reader.readAsDataURL(file);

    }

});

</script>

</body>
</html>