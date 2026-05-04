<?php 
session_start();

if(isset($_SESSION['id'])){
  header('location: index.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GetMatch - Sign In</title>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --primary: #dc3545;
    --primary-dark: #a02834;
    --bg-dark: #1a1a1a;
    --card-dark: #262626;
    --text-dark: #ffffff;
    --text-light: #a1a1aa;
    --border: #3f3f46;
}

/* GLOBAL */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: radial-gradient(circle at top, #2d2d2d, #121212);
    min-height: 100vh;
}

/* WRAPPER */
.login-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

/* CARD */
.login-card {
    display: flex;
    max-width: 950px;
    width: 100%;
    border-radius: 18px;
    overflow: hidden;
    background: var(--card-dark);
    box-shadow: 0 20px 60px rgba(220,53,69,0.2);
    border: 1px solid rgba(220,53,69,0.3);
}

/* IMAGE */
.login-image {
    flex: 1;
}

.login-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* FORM */
.login-form {
    flex: 1;
    padding: 40px;
}

/* HEADER */
.brand {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 10px;
}

.subtitle {
    color: var(--text-light);
    margin-bottom: 25px;
    font-size: 0.95rem;
}

/* INPUT */
.input-group {
    position: relative;
    margin-bottom: 18px;
}

.input-group i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
}

.input-group input {
    width: 100%;
    padding: 12px 12px 12px 38px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: #111;
    color: white;
    font-size: 0.95rem;
    transition: 0.3s;
}

.input-group input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(220,53,69,0.2);
    outline: none;
}

/* BUTTON */
.btn-login {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: none;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: 0.3s;
    margin-top: 10px;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(220,53,69,0.4);
}

/* ALERT */
.alert {
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 0.9rem;
}

.alert-danger {
    background: #3d0f0f;
    color: #ff6b6b;
    border: 1px solid var(--primary);
}

/* LINKS */
.form-footer {
    margin-top: 15px;
    font-size: 0.9rem;
    color: var(--text-light);
}

.form-footer a {
    color: var(--primary);
    font-weight: 600;
}

.forgot {
    display: block;
    margin-top: 10px;
    font-size: 0.85rem;
    color: var(--text-light);
}

/* RESPONSIVE */
@media(max-width: 768px){
    .login-card {
        flex-direction: column;
    }

    .login-image {
        display: none;
    }

    .login-form {
        padding: 25px;
    }
}
</style>
</head>

<body>

<div class="login-wrapper">

<div class="login-card">

    <!-- IMAGE -->
    <div class="login-image">
        <img src="assets/images/login_request/main_img.jpg">
    </div>

    <!-- FORM -->
    <div class="login-form">

        <div class="brand">GetMatch</div>
        <div class="subtitle">Welcome back. Login to continue.</div>

        <form method="post" action="login_action.php">

        <?php if(isset($_GET['error_message'])){ ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_GET['error_message']); ?>
            </div>
        <?php } ?>

        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="email" placeholder="Email or Username" required>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button class="btn-login" type="submit" name="button">
            Login
        </button>

        <a href="Reset-Password.php" class="forgot">Forgot password?</a>

        <div class="form-footer">
            Don't have an account? <a href="create-account.php">Register</a>
        </div>

        </form>

    </div>

</div>
</div>

</body>
</html>