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

    <title>Edit Profile</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #0f0f0f;
            color: #fff;
            font-family: Segoe UI;
        }

        .card {
            background: #1a1a1a;
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
        }

        .text-primary {
            color: #ff2e2e !important;
        }

        .form-control {
            background: #121212;
            border: 1px solid #333;
            color: #fff;
        }

        .form-control:focus {
            border-color: #ff2e2e;
            box-shadow: 0 0 8px rgba(255, 46, 46, 0.4);
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff2e2e, #8b0000);
            border: none;
        }

        .btn-secondary {
            background: #333;
            border: none;
        }

        .user-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 3px solid #ff2e2e;
            object-fit: cover;
        }
    </style>
</head>

<body>

<?php include('navbar.php'); ?>

<div class="container mt-5">

    <div class="row g-4">

        <!-- LEFT PROFILE -->
        <div class="col-md-3">
            <div class="card p-3 text-center">

                <img class="user-img mb-3"
                    src="<?php echo "assets/images/profiles/" . $_SESSION['img_path']; ?>">

                <h5>
                    <?php echo $_SESSION['username'] ?? 'No Username'; ?>
                </h5>

                <p class="text-muted">
                    <?php echo $_SESSION['fullname'] ?? 'No Name'; ?>
                </p>

                <hr>

                <h6 class="text-primary">About</h6>

                <p>
                    <?php echo $_SESSION['bio'] ?? 'No bio yet'; ?>
                </p>

            </div>
        </div>

        <!-- RIGHT FORM -->
        <div class="col-md-9">
            <div class="card p-4">

                <h4 class="text-primary">Account Settings</h4>
                <hr>

                <form method="POST" action="update-profile.php">

                    <!-- PERSONAL DETAILS -->
                    <h6 class="text-primary">Personal Details</h6>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Full Name</label>
                            <input type="text" class="form-control"
                                name="full_name"
                                value="<?php echo $_SESSION['fullname'] ?? ''; ?>">
                        </div>

                        <div class="col-md-6">
                            <label>Username</label>
                            <input type="text" class="form-control"
                                name="user_name"
                                value="<?php echo $_SESSION['username'] ?? ''; ?>">
                        </div>

                        <div class="col-12">
                            <label>About You</label>
                            <textarea class="form-control" name="bio" rows="3"><?php echo $_SESSION['bio'] ?? ''; ?></textarea>
                        </div>

                    </div>

                    <!-- SOCIAL LINKS -->
                    <h6 class="text-primary mt-3">Social Links</h6>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" class="form-control"
                                name="email"
                                value="<?php echo $_SESSION['email'] ?? ''; ?>">
                        </div>

                        <div class="col-md-6">
                            <label>WhatsApp</label>
                            <input type="text" class="form-control"
                                name="wapp"
                                value="<?php echo $_SESSION['whatsapp'] ?? ''; ?>">
                        </div>

                        <div class="col-md-6">
                            <label>Facebook</label>
                            <input type="text" class="form-control"
                                name="fbook"
                                value="<?php echo $_SESSION['facebook'] ?? ''; ?>">
                        </div>

                    </div>

                    <!-- BUTTONS -->
                    <div class="d-flex gap-2 justify-content-end mt-4">

                        <button type="submit" name="submits"
                            class="btn btn-secondary px-4">
                            Cancel
                        </button>

                        <button type="submit" name="submit"
                            class="btn btn-primary px-4">
                            Update
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

</body>

</html>