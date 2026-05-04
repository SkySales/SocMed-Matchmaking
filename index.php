<!DOCTYPE html>
<html lang="en">

<head>
<title>GetMatch - Gaming Matchmaking Platform</title>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="icon" href="assets/images/event_accepted_50px.png">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css">

<style>
:root {
    --primary: #dc3545;
    --primary-dark: #a02834;
    --bg-dark: #0f0f0f;
    --card-dark: #1c1c1c;
    --text-light: #a1a1aa;
}

/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: radial-gradient(circle at top, #2a2a2a, #0f0f0f);
    color: white;
    min-height: 100vh;
}

/* HERO SECTION */
.hero {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 20px;
}

/* CARD */
.hero-card {
    background: var(--card-dark);
    border-radius: 18px;
    padding: 50px 35px;
    text-align: center;
    max-width: 520px;
    width: 100%;
    border: 1px solid rgba(220,53,69,0.3);
    box-shadow: 0 25px 60px rgba(220,53,69,0.2);
    animation: fadeUp 0.8s ease;
}

/* TITLE */
.hero-title {
    font-size: 2.8rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 10px;
}

/* SUBTITLE */
.hero-subtitle {
    font-size: 1.1rem;
    margin-bottom: 20px;
}

/* DESCRIPTION */
.hero-desc {
    color: var(--text-light);
    font-size: 0.95rem;
    margin-bottom: 30px;
    line-height: 1.5;
}

/* BUTTON */
.hero-btn {
    display: inline-block;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    color: white;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    transition: 0.3s;
    box-shadow: 0 8px 25px rgba(220,53,69,0.3);
}

.hero-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(220,53,69,0.5);
}

/* ICONS ROW */
.features {
    display: flex;
    justify-content: space-between;
    margin-top: 35px;
    gap: 10px;
}

.feature {
    flex: 1;
    font-size: 0.8rem;
    color: var(--text-light);
}

.feature i {
    display: block;
    font-size: 1.4rem;
    margin-bottom: 6px;
    color: var(--primary);
}

/* ANIMATION */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(25px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* MOBILE */
@media(max-width: 480px){
    .hero-title {
        font-size: 2rem;
    }

    .hero-card {
        padding: 35px 20px;
    }

    .features {
        flex-direction: column;
        gap: 15px;
    }
}
</style>
</head>

<body>

<section class="hero">

<div class="hero-card">

    <div class="hero-title">🎮 GetMatch</div>

    <div class="hero-subtitle">
        Find Your Perfect Gaming Squad
    </div>

    <div class="hero-desc">
        Connect with gamers worldwide, build your dream team, and dominate every match together.  
        No more random teammates, only real synergy.
    </div>

    <a href="login.php" class="hero-btn">
        Get Started <i class="fas fa-arrow-right"></i>
    </a>

    <!-- FEATURES -->
    <div class="features">
        <div class="feature">
            <i class="fas fa-users"></i>
            Team Matchmaking
        </div>
        <div class="feature">
            <i class="fas fa-comments"></i>
            Live Lobbies
        </div>
        <div class="feature">
            <i class="fas fa-trophy"></i>
            Competitive Play
        </div>
    </div>

</div>

</section>

</body>
</html>