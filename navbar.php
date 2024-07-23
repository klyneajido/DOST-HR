<div class="header">

    <div class="header-left">
        <div class="logo-wrapper align-self-center">
            <a href="index.php" class="logo">
                <img src="assets/img/DOST.png" alt="Logo">
            </a>

        </div>

        <a href="index.php" class="logo logo-small">
            <img src="assets/img/dost_logo.png" alt="Logo" width="30" height="30">
        </a>
        <a href="javascript:void(0);" id="toggle_btn">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>
    </div>

    <a class="mobile_btn" id="mobile_btn">
        <i class="fas fa-bars"></i>
    </a>
    <ul class="nav user-menu">
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                <span class="user-img">
                    <img src="<?php echo htmlspecialchars($profile_image_path); ?>" alt="Avatar"
                        style="border-radius: 50%; width: 45px; height: 45px;">
                    <span class="status online"></span>
                </span>
                <span><?php echo htmlspecialchars($user_name); ?></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="profile.php"><i data-feather="user" class="mr-1"></i> Profile</a>
                <a class="dropdown-item" href="#" id="logoutLink"><i data-feather="log-out" class="mr-1"></i> Logout</a>
            </div>
            <script>
            document.getElementById('logoutLink').addEventListener('click', function(event) {
                event.preventDefault();
                $('#logoutModal').modal('show');
            });

            document.getElementById('confirmLogout').addEventListener('click', function() {
                window.location.href = 'PHP_Connections/logout.php';
            });
            </script>
        </li>
        </li>

    </ul>
    <div class="dropdown mobile-user-menu show">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right ">
            <a class="dropdown-item" href="profile.php">My Profile</a>
            <a class="dropdown-item" href="settings.html">Settings</a>
            <a class="dropdown-item" href="PHP_Connections/logout.php">Logout</a>
        </div>
    </div>

</div>
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div class="sidebar-contents">
            <div id="sidebar-menu" class="sidebar-menu">
                <div class="mobile-show">
                    <div class="offcanvas-menu">
                        <div class="user-info align-center bg-theme text-center">
                            <span class="lnr lnr-cross  text-white" id="mobile_btn_close">X</span>
                            <a href="javascript:void(0)" class="d-block menu-style text-white">
                                <div class="user-avatar d-inline-block mr-3">
                                    <img src="<?php echo htmlspecialchars($profile_image_path); ?>" alt="user avatar"
                                        class="rounded-circle" width="50">
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <ul>
                    <li>
                        <a href="index.php"><img src="assets/img/home.svg" alt="sidebar_img">
                            <span>Dashboard</span></a>
                    </li>
                    <li class="">
                        <a href="applicants.php"><img src="assets/img/employee.svg" alt="sidebar_img"><span>
                                Applicants</span></a>
                    </li>
                    <li>
                        <a href="viewJob.php"><img src="assets/img/company.svg" alt="sidebar_img"> <span>
                                Jobs</span></a>
                    </li>
                    <li>
                        <a href="announcements.php"><img src="assets/img/manage.svg" alt="sidebar_img">
                            <span>Announcements</span></a>
                    </li>
                    <li>
                        <a href="transparency.php"><img src="assets/img/employee.svg" alt="sidebar_img"><span>
                                Transparency</span></a>
                    </li>
                    <li>
                        <a href="archive.php"><img src="assets/img/report.svg" alt="sidebar_img">
                            <span>Archive</span></a>
                    </li>
                    <li>
                        <a href="history.php"><img src="assets/img/review.svg" alt="sidebar_img">
                            <span>History</span></a>
                    </li>
                    <li>
                        <a href="profile.php"><img src="assets/img/profile.svg" alt="sidebar_img">
                            <span>Profile</span></a>
                    </li>

                </ul>
                <ul class="logout">
                    <li>
                        <a href="#" id="sidebarLogoutLink"><img src="assets/img/logout.svg" alt="sidebar_img"><span>Log
                                out</span></a>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('sidebarLogoutLink').addEventListener('click', function(event) {
    event.preventDefault();
    $('#logoutModal').modal('show');
});

document.getElementById('confirmLogout').addEventListener('click', function() {
    window.location.href = 'PHP_Connections/logout.php';
});
</script>