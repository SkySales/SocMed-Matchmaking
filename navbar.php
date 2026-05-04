<!-- Modern Bootstrap Navbar -->
<style>
    :root {
        --primary: #e72e2e;
        --primary-dark: #0b152c;
        --secondary: #8b5cf6;
        --accent: #ec4899;
        --bg-light: #f8fafc;
        --bg-white: #ffffff;
        --text-dark: #0f172a;
        --text-light: #64748b;
        --border: #e2e8f0;
    }

    /* Custom Navbar Styling */
    .navbar-modern {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.95) 100%);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 1rem 0;
        position: sticky;
        top: 0;
        z-index: 1030;
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .navbar-brand img {
        max-height: 40px;
        margin-right: 0.5rem;
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .search-box input {
        border: 1px solid var(--border);
        border-radius: 25px;
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        background: var(--bg-light);
        font-size: 0.95rem;
        transition: all 0.3s ease;
        width: 100%;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        background: var(--bg-white);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        pointer-events: none;
    }

    /* Nav Icons */
    .nav-link-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: var(--text-dark);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        text-decoration: none !important;
    }

    .nav-link-icon:hover {
        background: var(--bg-light);
        color: var(--primary);
        transform: scale(1.1);
    }

    .nav-link-icon i {
        font-size: 1.1rem;
    }

    /* Dropdown */
    .dropdown-menu {
        border: none;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        padding: 0.5rem 0;
    }

    .dropdown-header {
        padding: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .dropdown-item {
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background: var(--bg-light);
        color: var(--primary);
        padding-left: 1.25rem;
    }

    .dropdown-item i {
        width: 20px;
        text-align: center;
        margin-right: 0.5rem;
    }

    /* User Avatar */
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .user-avatar:hover {
        border-color: var(--primary);
        transform: scale(1.05);
    }

    /* Create Button */
    .btn-create {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        color: white;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-create:active {
        transform: translateY(0);
    }

    /* Mobile Navbar */
    @media (max-width: 992px) {
        .search-box {
            display: none;
        }

        .navbar-modern {
            padding: 0.75rem 0;
        }
    }

    @media (max-width: 768px) {
        body {
            padding-bottom: 85px;
        }

        .navbar-modern {
            position: fixed;
            bottom: 0;
            top: auto;
            width: 100%;
            padding: 0.5rem 0;
            border-bottom: none;
            border-top: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            height: auto;
        }

        .navbar-collapse {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 85px;
            background: var(--bg-white);
            padding: 1rem;
            flex-direction: column;
            display: none !important;
            overflow-y: auto;
        }

        .navbar-collapse.show {
            display: flex !important;
        }

        .navbar-brand {
            display: none;
        }

        .navbar-toggler {
            display: none;
        }

        .navbar-nav {
            display: flex;
            justify-content: space-around;
            width: 100%;
            flex-direction: row;
        }

        .nav-item {
            text-align: center;
        }

        .nav-link-text {
            font-size: 0.65rem;
            display: block;
            margin-top: 0.25rem;
            color: var(--text-light);
        }

        .nav-link-icon:hover .nav-link-text {
            color: var(--primary);
        }

        .nav-link-icon {
            width: 50px;
            height: 50px;
        }

        .btn-create {
            display: none;
        }

        .navbar-divider {
            display: none;
        }
    }

    /* Modal Styling */
    dialog#createModal {
        border: none;
        border-radius: 12px;
        max-width: 500px;
        width: 90%;
        padding: 0;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    dialog#createModal::backdrop {
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease;
    }

    dialog#createModal[open] {
        animation: slideIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from {
            transform: translate(-50%, -55%);
            opacity: 0;
        }
        to {
            transform: translate(-50%, -50%);
            opacity: 1;
        }
    }

    dialog .modal-content {
        border-radius: 12px;
    }

    dialog .modal-header {
        border-bottom: 1px solid var(--border);
        padding: 1.5rem;
    }

    dialog .modal-body {
        padding: 1.5rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    dialog .btn-close {
        background: transparent;
        border: none;
        cursor: pointer;
        color: var(--text-dark);
        font-size: 1.5rem;
        padding: 0;
        width: auto;
        height: auto;
    }

    dialog .btn-close:hover {
        color: var(--primary);
    }

    .modal-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--bg-light);
        border-radius: 8px;
        text-decoration: none !important;
        color: var(--text-dark) !important;
        transition: all 0.3s ease;
    }

    .modal-item:hover {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white !important;
        transform: translateX(5px);
    }

    .modal-item i {
        font-size: 1.5rem;
        width: 25px;
        text-align: center;
    }

    .modal-item-content span:first-child {
        font-weight: 600;
        display: block;
    }

    .modal-item-content span:last-child {
        font-size: 0.8rem;
        color: inherit;
        opacity: 0.8;
        display: block;
    }

    /* MOBILE NAVBAR */
.mobile-navbar {
    display: none;
}

@media (max-width: 768px) {

    body {
        padding-bottom: 80px;
    }

    /* Hide desktop navbar */
    .navbar-modern {
        display: none !important;
    }

    .mobile-navbar {
        display: flex;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 75px;
        background: rgba(255,255,255,0.98);
        border-top: 1px solid var(--border);
        justify-content: space-around;
        align-items: center;
        z-index: 1000;
        box-shadow: 0 -4px 10px rgba(0,0,0,0.05);
    }

    .mobile-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        color: var(--text-light);
        text-decoration: none;
        background: none;
        border: none;
    }

    .mobile-item i {
        font-size: 1.4rem;
        margin-bottom: 3px;
    }

    .mobile-item.active,
    .mobile-item:hover {
        color: var(--primary);
    }
}
/* MOBILE TOP SEARCH */
.mobile-top-search {
    display: none;
}

@media (max-width: 768px) {

    /* SHOW SEARCH BAR */
    .mobile-top-search {
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        padding: 10px;
        background: rgba(255,255,255,0.98);
        border-bottom: 1px solid var(--border);
        z-index: 1000;
    }

    .mobile-search-box {
        position: relative;
    }

    .mobile-search-box input {
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 8px 12px 8px 35px;
        background: var(--bg-light);
        font-size: 0.9rem;
    }

    .mobile-search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
    }

    /* PUSH CONTENT DOWN (important) */
    body {
        padding-top: 70px;
        padding-bottom: 80px;
    }

    /* HIDE DESKTOP NAVBAR */
    .navbar-modern {
        display: none !important;
    }
}
</style>

<!-- Bootstrap Navbar -->
<nav class="navbar navbar-expand-lg navbar-modern">
    <div class="container-fluid">
        <!-- Brand/Logo -->
        <a class="navbar-brand" href="home.php">
            <i class="fas fa-rocket"></i> GetMatch
        </a>

        <!-- Toggle Button (Mobile) -->
        <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Desktop Search -->
            <div class="search-box mx-auto d-none d-lg-flex">
                <i class="fas fa-search"></i>
                <input type="text" id="navbar-search" placeholder="Search people, events..." onkeypress="if(event.key=='Enter') searchNavbar()">
            </div>

            <!-- Nav Items -->
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <!-- Home -->
                <li class="nav-item">
                    <a class="nav-link-icon" href="home.php" title="Home">
                        <i class="fas fa-home"></i>
                        <span class="nav-link-text d-lg-none">Home</span>
                    </a>
                </li>

                <!-- Events -->
                <li class="nav-item">
                    <a class="nav-link-icon" href="Events.php" title="Events">
                        <i class="fas fa-flag"></i>
                        <span class="nav-link-text d-lg-none">Events</span>
                    </a>
                </li>

                <!-- Lobby -->
                <li class="nav-item">
                    <a class="nav-link-icon" href="lobby/lobby_room.php" title="Events">
                        <i class="fa-solid fa-champagne-glasses"></i>
                        <span class="nav-link-text d-lg-none">Lobby</span>
                    </a>
                </li>

                <!-- Videos -->
                <li class="nav-item">
                    <a class="nav-link-icon" href="shorts.php" title="Videos">
                        <i class="fas fa-video"></i>
                        <span class="nav-link-text d-lg-none">Videos</span>
                    </a>
                </li>

                <!-- Create Button (Desktop) -->
                <li class="nav-item d-none d-lg-inline-block">
                    <button class="btn-create" data-bs-toggle="modal" data-bs-target="#createModal" title="Create Post">
                        <i class="fas fa-plus"></i> Create
                    </button>
                </li>

                <!-- Create Icon (Mobile) -->
                <li class="nav-item d-lg-none">
                    <a class="nav-link-icon" href="#" onclick="event.preventDefault(); document.getElementById('createModal').showModal()" title="Create">
                        <i class="fas fa-plus-circle"></i>
                        <span class="nav-link-text">Create</span>
                    </a>
                </li>

                <!-- Divider -->
                <li class="nav-item navbar-divider d-none d-lg-inline-block" style="width: 1px; height: 20px; background: var(--border); margin: 0 0.5rem;"></li>

                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown">
                    <img src="<?php echo isset($_SESSION['img_path']) ? 'assets/images/profiles/'.$_SESSION['img_path'] : 'assets/images/default.png'; ?>" alt="Profile" class="user-avatar" id="navbarProfileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarProfileDropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?php echo isset($_SESSION['img_path']) ? 'assets/images/profiles/'.$_SESSION['img_path'] : 'assets/images/default.png'; ?>" alt="Profile" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover;">
                                <div>
                                    <strong><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?></strong><br>
                                    <small class="text-muted">Active now</small>
                                </div>
                            </div>
                        </div>
                        <a class="dropdown-item" href="my_Profile.php">
                            <i class="fas fa-user-circle"></i> My Profile
                        </a>
                        <a class="dropdown-item" href="edit-profile.php">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                        <a class="dropdown-item" href="Event-Upload.php">
                            <i class="fas fa-calendar-plus"></i> Post Event
                        </a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item" href="Event-Calander/index.php">
                            <i class="fas fa-calendar"></i> Calendar
                        </a>
                        <a class="dropdown-item" href="#settings">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item text-danger" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Log Out
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- MOBILE NAVBAR -->
<div class="mobile-navbar">
    <a href="home.php" class="mobile-item">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>

    <a href="Events.php" class="mobile-item">
        <i class="fas fa-flag"></i>
        <span>Events</span>
    </a>

    <button class="mobile-item" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="fas fa-plus-circle"></i>
        <span>Create</span>
    </button>

    <a href="shorts.php" class="mobile-item">
        <i class="fas fa-video"></i>
        <span>Videos</span>
    </a>

    <a href="my_Profile.php" class="mobile-item">
        <i class="fas fa-user"></i>
        <span>Profile</span>
    </a>
</div>

<!-- MOBILE TOP SEARCH -->
<div class="mobile-top-search">
    <div class="mobile-search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="mobile-search" placeholder="Search..." 
               onkeypress="if(event.key==='Enter') searchMobile()">
    </div>
</div>

<!-- Create Post Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="modal-title" style="font-size: 1.5rem; font-weight: 600;">✨ Create & Share</h3>
                <button type="button" class="btn-close" aria-label="Close" onclick="document.getElementById('createModal').close()"></button>
            </div>
            <div class="modal-body">
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="edit-profile.php" class="modal-item">
                        <i class="fas fa-edit" style="color: var(--primary);"></i>
                        <div class="modal-item-content">
                            <span>Edit Profile</span>
                            <span>Update your information</span>
                        </div>
                    </a>
                    <a href="Event-Upload.php" class="modal-item">
                        <i class="fas fa-calendar-check" style="color: var(--secondary);"></i>
                        <div class="modal-item-content">
                            <span>Post About Event</span>
                            <span>Share an upcoming event</span>
                        </div>
                    </a>
                    <a href="post-uploader.php" class="modal-item">
                        <i class="fas fa-pen" style="color: var(--accent);"></i>
                        <div class="modal-item-content">
                            <span>Create New Post</span>
                            <span>Share your thoughts</span>
                        </div>
                    </a>
                    <a href="video_upload.php" class="modal-item">
                        <i class="fas fa-video" style="color: #10b981;"></i>
                        <div class="modal-item-content">
                            <span>Publish Short Video</span>
                            <span>Upload a video</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Search functionality
    function searchNavbar() {
        const searchTerm = document.getElementById('navbar-search').value;
        if (searchTerm.trim()) {
            window.location.href = 'Results.php?find=' + encodeURIComponent(searchTerm);
        }
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('createModal');
            if (modal && modal.open) {
                modal.close();
            }
        }
    });

    // Close modal when clicking on backdrop
    const modal = document.getElementById('createModal');
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.close();
        }
    });

    // Close modal when clicking a create option
    document.querySelectorAll('.modal-body a').forEach(link => {
        link.addEventListener('click', function() {
            modal.close();
        });
    });

    // Update active nav item based on current page
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.pathname.split('/').pop() || 'home.php';
        document.querySelectorAll('.nav-link-icon').forEach(link => {
            const href = link.getAttribute('href');
            if (href && href.includes(currentPage.split('.')[0])) {
                link.style.color = 'var(--primary)';
                link.style.background = 'var(--bg-light)';
            }
        });

        // Close navbar collapse on mobile after clicking a link
        const navbarCollapse = document.querySelector('.navbar-collapse');
        if (navbarCollapse && window.innerWidth <= 768) {
            document.querySelectorAll('.nav-link-icon').forEach(link => {
                link.addEventListener('click', function() {
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse, { toggle: false });
                    bsCollapse.hide();
                });
            });
        }
    });
</script>

<script>
function searchMobile() {
    const searchTerm = document.getElementById('mobile-search').value;
    if (searchTerm.trim()) {
        window.location.href = 'Results.php?find=' + encodeURIComponent(searchTerm);
    }
}
</script>
