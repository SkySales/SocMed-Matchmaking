<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GetMatch - Sign Up</title>

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
    overflow-x: hidden;
}

/* BACKGROUND ANIMATION */
body::before{
    content:'';
    position:fixed;
    width:500px;
    height:500px;
    background:rgba(220,53,69,0.15);
    border-radius:50%;
    top:-150px;
    left:-150px;
    filter:blur(120px);
    animation: float 8s ease-in-out infinite;
}

body::after{
    content:'';
    position:fixed;
    width:400px;
    height:400px;
    background:rgba(220,53,69,0.1);
    border-radius:50%;
    bottom:-150px;
    right:-150px;
    filter:blur(120px);
    animation: float2 10s ease-in-out infinite;
}

@keyframes float{
    0%{transform:translateY(0);}
    50%{transform:translateY(30px);}
    100%{transform:translateY(0);}
}

@keyframes float2{
    0%{transform:translateY(0);}
    50%{transform:translateY(-30px);}
    100%{transform:translateY(0);}
}

/* CONTAINER */
.signup-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

/* CARD */
.signup-card {
    display: flex;
    max-width: 1000px;
    width: 100%;
    border-radius: 18px;
    overflow: hidden;
    background: var(--card-dark);
    box-shadow: 0 20px 60px rgba(220,53,69,0.2);
    border: 1px solid rgba(220,53,69,0.3);
    animation: fadeIn 0.8s ease;
    position:relative;
    z-index:10;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(30px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* LEFT IMAGE */
.signup-image {
    flex: 1;
    position: relative;
}

.signup-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.signup-image::after{
    content:'GETMATCH';
    position:absolute;
    bottom:30px;
    left:30px;
    font-size:32px;
    font-weight:800;
    color:white;
    letter-spacing:2px;
    text-shadow:0 0 20px rgba(0,0,0,0.6);
}

/* RIGHT FORM */
.signup-form {
    flex: 1;
    padding: 40px;
}

/* HEADER */
.brand {
    font-size: 1.9rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 10px;
}

.subtitle {
    color: var(--text-light);
    margin-bottom: 25px;
    font-size: 0.95rem;
}

/* INPUT GROUP */
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

.input-group input,
.input-group select {
    width: 100%;
    padding: 12px 12px 12px 38px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: #111;
    color: white;
    font-size: 0.95rem;
    transition: 0.3s;
}

.input-group input:focus,
.input-group select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(220,53,69,0.2);
    outline: none;
}

/* PASSWORD STRENGTH */
.password-strength{
    margin-top:-10px;
    margin-bottom:15px;
    font-size:13px;
    color:#ffc107;
}

/* TERMS */
.terms{
    display:flex;
    align-items:flex-start;
    gap:10px;
    margin-top:10px;
    margin-bottom:20px;
    color:var(--text-light);
    font-size:13px;
}

.terms input{
    margin-top:3px;
    accent-color:var(--primary);
}

.terms a{
    color:var(--primary);
    text-decoration:none;
}

/* BUTTON */
.btn-signup {
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
    cursor:pointer;
    position:relative;
}

.btn-signup:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(220,53,69,0.4);
}

/* LOADING */
.btn-loading{
    pointer-events:none;
    opacity:0.8;
}

.spinner{
    border:3px solid rgba(255,255,255,0.2);
    border-top:3px solid #fff;
    border-radius:50%;
    width:18px;
    height:18px;
    animation:spin 1s linear infinite;
    display:inline-block;
    vertical-align:middle;
}

@keyframes spin{
    to{
        transform:rotate(360deg);
    }
}

/* ALERTS */
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

.alert-success {
    background: #0f3d0f;
    color: #51cf66;
    border: 1px solid #51cf66;
}

/* FOOTER */
.form-footer {
    margin-top: 20px;
    text-align: center;
    color: var(--text-light);
    font-size: 0.9rem;
}

.form-footer a {
    color: var(--primary);
    font-weight: 600;
}

.copyright{
    margin-top:20px;
    text-align:center;
    font-size:12px;
    color:#777;
}

/* RESPONSIVE */
@media(max-width: 768px){

    .signup-card {
        flex-direction: column;
    }

    .signup-image {
        display: none;
    }

    .signup-form {
        padding: 25px;
    }

}
</style>
</head>

<body>

<div class="signup-wrapper">

<div class="signup-card">

    <!-- IMAGE -->
    <div class="signup-image">
        <img src="assets/images/login_request/main_img.jpg">
    </div>

    <!-- FORM -->
    <div class="signup-form">

        <div class="brand">GetMatch</div>
        <div class="subtitle">
            Create your account and start matching with players.
        </div>

        <form method="post" action="signup_process.php" id="signupForm">

        <?php if(isset($_GET['error_message'])){ ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_GET['error_message']); ?>
            </div>
        <?php } ?>

        <?php if(isset($_GET['sucess_message'])){ ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['sucess_message']); ?>
            </div>
        <?php } ?>

        <!-- USERNAME -->
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input 
            type="text"
            name="username"
            placeholder="Username"
            minlength="3"
            maxlength="20"
            required>
        </div>

        <!-- EMAIL -->
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input 
            type="email"
            name="email"
            placeholder="Email address"
            required>
        </div>

        <!-- BIRTHDATE -->
        <div class="input-group">
            <i class="fas fa-calendar"></i>
            <input 
            type="date"
            name="birthdate"
            required>
        </div>

        <!-- GENDER -->
        <div class="input-group">
            <i class="fas fa-venus-mars"></i>

            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Prefer not to say">
                    Prefer not to say
                </option>
            </select>
        </div>

        <!-- PASSWORD -->
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input 
            type="password"
            id="password"
            name="password"
            placeholder="Password"
            required>
        </div>

        <div class="password-strength" id="strength">
            Password must be at least 8 characters.
        </div>

        <!-- CONFIRM PASSWORD -->
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input 
            type="password"
            name="confirm_password"
            placeholder="Confirm password"
            required>
        </div>

        <!-- TERMS -->
        <div class="terms">

            <input 
            type="checkbox"
            required>

            <div>
                I agree to the 
                <a href="#" onclick="showTerms()">
                    Terms & Conditions
                </a>
                and confirm that my information is valid.
            </div>

        </div>

        <!-- BUTTON -->
        <button class="btn-signup"
        type="submit"
        name="signup_btn"
        id="signupBtn">

            Create Account

        </button>

        <!-- FOOTER -->
        <div class="form-footer">
            Already have an account?
            <a href="login.php">Sign In</a>
        </div>

        <div class="copyright">
            © 2026 GetMatch Matchmaking System
        </div>

        </form>

    </div>

</div>
</div>

<script>

/* PASSWORD STRENGTH */

const password =
document.getElementById("password");

const strength =
document.getElementById("strength");

password.addEventListener("keyup", ()=>{

    let val = password.value;

    if(val.length < 8){

        strength.innerHTML =
        "❌ Weak password";

        strength.style.color = "#ff6b6b";

    }else if(
        /[A-Z]/.test(val) &&
        /[0-9]/.test(val)
    ){

        strength.innerHTML =
        "✅ Strong password";

        strength.style.color = "#51cf66";

    }else{

        strength.innerHTML =
        "⚠ Medium password";

        strength.style.color = "#ffc107";
    }

});

/* TERMS */

function showTerms(){

alert(`
GETMATCH TERMS & CONDITIONS

• Respect all users
• No cheating or toxic behavior
• No fake accounts
• Users must be 13+
• Lobby abuse may result in suspension

By creating an account,
you agree to our platform rules.
`);

}

/* LOADING ANIMATION */

document
.getElementById("signupForm")
.addEventListener("submit", function(){

    const btn =
    document.getElementById("signupBtn");

    btn.classList.add("btn-loading");

    btn.innerHTML =
    `<span class="spinner"></span> Creating Account...`;

});

</script>

</body>
</html>