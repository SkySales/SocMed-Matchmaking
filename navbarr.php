<style>
                    /* NAVBAR BASE */
        .navbar-custom {
            position: fixed;
            top: 0;
            width: 100%;
            background: #1a1a1a;
            border-bottom: 2px solid #dc3545;
            z-index: 999;
            box-shadow: 0 4px 20px rgba(220, 53, 69, 0.15);
        }

        /* CONTAINER */
        .nav-container {
            max-width: 1200px;
            margin: auto;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* LOGO */
        .logo {
            font-size: 1.4rem;
            font-weight: 700;
            color: #dc3545;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.5px;
        }

        .logo span {
            color: #ffffff;
        }

        .logo:hover {
            text-shadow: 0 0 10px rgba(220, 53, 69, 0.6);
        }

        /* MENU */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        /* LINKS */
        .nav-link {
            color: #d0d0d0;
            font-size: 1.1rem;
            padding: 8px 10px;
            border-radius: 8px;
            transition: all 0.25s ease;
            position: relative;
        }

        /* HOVER EFFECT */
        .nav-link:hover {
            color: #ffffff;
            background: rgba(220, 53, 69, 0.15);
            transform: translateY(-2px);
        }

        /* ACTIVE LINK */
        .nav-link.active {
            color: #dc3545;
        }

        /* PROFILE ICON SPECIAL */
        .nav-link.profile i {
            font-size: 1.4rem;
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .nav-container {
                padding: 10px 15px;
            }

            .logo {
                font-size: 1.2rem;
            }

            .nav-menu {
                gap: 12px;
            }

            .nav-link {
                font-size: 1rem;
                padding: 6px;
            }
        }
</style>

<nav class="navbar-custom">
    <div class="nav-container">

        <!-- LOGO -->
        <div class="logo" onclick="location.href='home.php'">
            🎮 <span>GetMatch</span>
        </div>

        <!-- MENU -->
        <div class="nav-menu">
            <a href="home.php" class="nav-link active">
                <i class="fas fa-home"></i>
            </a>

            <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#search-model">
                <i class="fas fa-search"></i>
            </a>

            <a href="Events.php" class="nav-link">
                <i class="fas fa-flag"></i>
            </a>

            <a href="shorts.php" class="nav-link">
                <i class="fas fa-video"></i>
            </a>

            <a href="my_Profile.php" class="nav-link profile">
                <i class="fas fa-user-circle"></i>
            </a>
        </div>

    </div>
</nav>