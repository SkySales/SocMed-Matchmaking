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

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>GetMatch - Sign In</title>

<link rel="stylesheet"
href="https://use.fontawesome.com/releases/v5.15.2/css/all.css"/>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<style>

:root{
    --primary:#dc3545;
    --primary-dark:#a02834;
    --bg-dark:#1a1a1a;
    --card-dark:#262626;
    --text-dark:#ffffff;
    --text-light:#a1a1aa;
    --border:#3f3f46;
}

/* GLOBAL */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Inter',sans-serif;
    background:
    radial-gradient(circle at top,#2d2d2d,#121212);
    min-height:100vh;
    overflow:hidden;
}

/* ANIMATED BACKGROUND */

body::before{

    content:'';

    position:fixed;

    width:500px;
    height:500px;

    background:
    radial-gradient(
        rgba(220,53,69,.15),
        transparent
    );

    top:-200px;
    left:-100px;

    z-index:-1;

    animation:pulse 6s infinite;
}

body::after{

    content:'';

    position:fixed;

    width:400px;
    height:400px;

    background:
    radial-gradient(
        rgba(220,53,69,.12),
        transparent
    );

    bottom:-150px;
    right:-100px;

    z-index:-1;

    animation:pulse 7s infinite;
}

@keyframes pulse{

    0%{
        transform:scale(1);
        opacity:.7;
    }

    50%{
        transform:scale(1.15);
        opacity:1;
    }

    100%{
        transform:scale(1);
        opacity:.7;
    }

}

/* WRAPPER */

.login-wrapper{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:20px;
}

/* CARD */

.login-card{

    display:flex;

    max-width:950px;
    width:100%;

    border-radius:18px;
    overflow:hidden;

    background:var(--card-dark);

    box-shadow:
    0 20px 60px rgba(220,53,69,0.2);

    border:
    1px solid rgba(220,53,69,0.3);

    animation:cardEnter 1s ease;
}

@keyframes cardEnter{

    0%{
        opacity:0;
        transform:
        translateY(40px)
        scale(.95);
    }

    100%{
        opacity:1;
        transform:
        translateY(0)
        scale(1);
    }

}

/* IMAGE */

.login-image{
    flex:1;
}

.login-image img{

    width:100%;
    height:100%;

    object-fit:cover;

    animation:
    floatImage 5s ease-in-out infinite;
}

@keyframes floatImage{

    0%{
        transform:translateY(0px);
    }

    50%{
        transform:translateY(-8px);
    }

    100%{
        transform:translateY(0px);
    }

}

/* FORM */

.login-form{
    flex:1;
    padding:40px;
}

/* HEADER */

.brand{
    font-size:1.8rem;
    font-weight:700;
    color:var(--primary);
    margin-bottom:10px;
    animation:fadeUp .7s ease;
}

.subtitle{
    color:var(--text-light);
    margin-bottom:25px;
    font-size:.95rem;
    animation:fadeUp .9s ease;
}

/* INPUT */

.input-group{
    position:relative;
    margin-bottom:18px;
}

.input-group i{
    position:absolute;
    left:12px;
    top:50%;
    transform:translateY(-50%);
    color:var(--text-light);
}

.input-group input{

    width:100%;

    padding:
    12px 12px 12px 38px;

    border-radius:8px;

    border:1px solid var(--border);

    background:#111;

    color:white;

    font-size:.95rem;

    transition:.3s;
}

.input-group input:focus{

    border-color:var(--primary);

    box-shadow:
    0 0 0 2px rgba(220,53,69,0.2);

    outline:none;
}

/* ANIMATIONS */

.input-group:nth-child(1){
    animation:fadeUp 1s ease;
}

.input-group:nth-child(2){
    animation:fadeUp 1.1s ease;
}

.terms-box{
    animation:fadeUp 1.2s ease;
}

.btn-login{
    animation:fadeUp 1.3s ease;
}

.form-footer{
    animation:fadeUp 1.4s ease;
}

.originality{
    animation:fadeUp 1.5s ease;
}

@keyframes fadeUp{

    from{
        opacity:0;
        transform:translateY(15px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }

}

/* BUTTON */

.btn-login{

    width:100%;

    padding:12px;

    border-radius:8px;

    border:none;

    background:
    linear-gradient(
    135deg,
    var(--primary),
    var(--primary-dark)
    );

    color:white;

    font-weight:600;

    letter-spacing:.5px;

    transition:.3s;

    margin-top:10px;

    cursor:pointer;

    position:relative;

    overflow:hidden;
}

.btn-login::before{

    content:'';

    position:absolute;

    top:0;
    left:-100%;

    width:100%;
    height:100%;

    background:
    linear-gradient(
    90deg,
    transparent,
    rgba(255,255,255,.25),
    transparent
    );

    transition:.6s;
}

.btn-login:hover::before{
    left:100%;
}

.btn-login:hover{

    transform:translateY(-2px);

    box-shadow:
    0 10px 25px rgba(220,53,69,0.4);
}

/* LOADING */

.btn-login.loading{

    pointer-events:none;
    opacity:.8;
}

.loader{

    width:18px;
    height:18px;

    border:2px solid white;
    border-top:2px solid transparent;

    border-radius:50%;

    display:inline-block;

    animation:spin .7s linear infinite;

    vertical-align:middle;
}

@keyframes spin{

    to{
        transform:rotate(360deg);
    }

}

/* ALERT */

.alert{
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    font-size:.9rem;
}

.alert-danger{
    background:#3d0f0f;
    color:#ff6b6b;
    border:1px solid var(--primary);
}

/* LINKS */

.form-footer{
    margin-top:15px;
    font-size:.9rem;
    color:var(--text-light);
}

.form-footer a{
    color:var(--primary);
    font-weight:600;
}

.forgot{
    display:block;
    margin-top:10px;
    font-size:.85rem;
    color:var(--text-light);
    text-decoration:none;
}

/* TERMS */

.terms-box{
    display:flex;
    align-items:flex-start;
    gap:10px;
    margin-top:5px;
    margin-bottom:15px;
    font-size:13px;
    color:var(--text-light);
    line-height:1.5;
}

.terms-box input{
    margin-top:3px;
    accent-color:var(--primary);
    cursor:pointer;
}

.terms-box a{
    color:var(--primary);
    font-weight:600;
    text-decoration:none;
}

.terms-box a:hover{
    text-decoration:underline;
}

/* ORIGINALITY */

.originality{
    margin-top:30px;
    text-align:center;
    font-size:12px;
    color:#777;
    line-height:1.6;
    border-top:1px solid #333;
    padding-top:15px;
}

/* TERMS MODAL */

.terms-modal{

    position:fixed;

    inset:0;

    background:rgba(0,0,0,0.7);

    display:none;

    align-items:center;
    justify-content:center;

    z-index:999;

    padding:20px;
}

.terms-content{

    width:100%;
    max-width:600px;

    background:#1f1f1f;

    border:1px solid #444;

    border-radius:14px;

    overflow:hidden;

    animation:fadeIn .3s ease;
}

.terms-header{

    background:#111;

    padding:18px 20px;

    display:flex;

    justify-content:space-between;
    align-items:center;

    border-bottom:1px solid #333;
}

.terms-header h2{
    color:var(--primary);
}

.terms-header button{

    background:none;
    border:none;

    color:white;

    font-size:28px;

    cursor:pointer;
}

.terms-body{

    padding:20px;

    max-height:400px;

    overflow-y:auto;

    color:#ccc;

    line-height:1.8;

    font-size:14px;
}

.terms-body ul{
    padding-left:20px;
}

@keyframes fadeIn{

    from{
        opacity:0;
        transform:scale(.95);
    }

    to{
        opacity:1;
        transform:scale(1);
    }

}

/* RESPONSIVE */

@media(max-width:768px){

    .login-card{
        flex-direction:column;
    }

    .login-image{
        display:none;
    }

    .login-form{
        padding:25px;
    }

}

</style>
</head>

<body>

<div class="login-wrapper">

<div class="login-card">

    <!-- IMAGE -->

    <div class="login-image">

        <img
        src="assets/images/login_request/main_img.jpg">

    </div>

    <!-- FORM -->

    <div class="login-form">

        <div class="brand">
            GetMatch
        </div>

        <div class="subtitle">
            Welcome back. Login to continue.
        </div>

        <form
        id="loginForm"
        method="post"
        action="login_action.php">

        <?php if(isset($_GET['error_message'])){ ?>

            <div class="alert alert-danger">

                <?php
                echo htmlspecialchars(
                $_GET['error_message']
                );
                ?>

            </div>

        <?php } ?>

        <!-- EMAIL -->

        <div class="input-group">

            <i class="fas fa-user"></i>

            <input
            type="text"
            name="email"
            placeholder="Email or Username"
            required>

        </div>

        <!-- PASSWORD -->

        <div class="input-group">

            <i class="fas fa-lock"></i>

            <input
            type="password"
            name="password"
            placeholder="Password"
            required>

        </div>

        <!-- TERMS -->

        <div class="terms-box">

            <input
            type="checkbox"
            id="terms"
            required>

            <label for="terms">

                I agree to the

                <a href="#"
                onclick="openTerms()">

                    Terms & Conditions

                </a>

                of GetMatch.

            </label>

        </div>

        <!-- LOGIN -->

        <button
        class="btn-login"
        type="submit"
        name="button"
        id="loginBtn">

            Login

        </button>

        <!-- FORGOT -->

        <a href="Reset-Password.php"
        class="forgot">

            Forgot password?

        </a>

        <!-- REGISTER -->

        <div class="form-footer">

            Don't have an account?

            <a href="create-account.php">
                Register
            </a>

        </div>

        <!-- ORIGINALITY -->

        <div class="originality">

            © 2026 GetMatch Matchmaking System

            <br>

            Designed & Developed
            for Educational Purposes.

        </div>

        </form>

    </div>

</div>

</div>

<!-- TERMS MODAL -->

<div class="terms-modal"
id="termsModal">

<div class="terms-content">

<div class="terms-header">

    <h2>
        Terms & Conditions
    </h2>

    <button onclick="closeTerms()">
        &times;
    </button>

</div>

<div class="terms-body">

<p>
Welcome to GetMatch.
</p>

<br>

<p>
By using this platform,
you agree to use the system
responsibly and respectfully.
</p>

<br>

<p>
Users are prohibited from:
</p>

<ul>

<li>Harassing other players</li>

<li>Using offensive usernames or messages</li>

<li>Spamming lobbies or chats</li>

<li>Attempting to exploit or hack the platform</li>

<li>Sharing illegal or harmful content</li>

</ul>

<br>

<p>
GetMatch is developed for
educational and matchmaking
purposes only.
</p>

<br>

<p>
Accounts violating these terms
may be suspended or removed.
</p>

<br>

<p>
By continuing, you acknowledge
and agree to these terms.
</p>

</div>

</div>

</div>

<script>

/* TERMS */

function openTerms(){

    document
    .getElementById(
    "termsModal"
    )
    .style.display = "flex";

}

function closeTerms(){

    document
    .getElementById(
    "termsModal"
    )
    .style.display = "none";

}

window.onclick = function(event){

    let modal =
    document.getElementById(
    "termsModal"
    );

    if(event.target == modal){

        modal.style.display = "none";

    }

}

/* LOGIN LOADING */

document
.getElementById("loginForm")
.addEventListener("submit",function(){

    let btn =
    document.getElementById(
    "loginBtn"
    );

    btn.classList.add("loading");

    btn.innerHTML = `
        <span class="loader"></span>
        Logging in...
    `;

});

</script>

</body>
</html>