<?php 
include("PHP_Connections/checkUser.php"); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>HRMO Admin</title>
    <link rel="shortcut icon" href="assets/img/dost_logo.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/announcement.css">
    <style>
        .password-cover {
            -webkit-text-security: disc;
        }
    </style>
</head>

<body class="scrollbar" id="style-5">
    <?php include("logout_modal.php") ?>
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href=""><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Accounts</a>
                        </li>
                        <li class="breadcrumb-item active">Admin</li>
                    </ul>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Admin Accounts</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Authority</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT admin_id, name, username, email, password, profile_image, authority FROM admins";
                                $result = $mysqli->query($query);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['authority']) . "</td>";
                                        echo "<td>
                                                <a href='editAccount.php?id=" . $row['admin_id'] . "' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i></a>
                                                <a href='deleteAdmin.php?id=" . $row['admin_id'] . "' class='btn btn-sm btn-danger'><i class='fas fa-trash-alt'></i></a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>No accounts found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pop-up notification -->
                <?php if (!empty($success_message)) : ?>
                    <script>
                        alert('<?php echo addslashes($success_message); ?>');
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="assets/js/date.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/announcements.js"></script>
    <script>
        function togglePassword(adminId) {
            var passwordField = document.getElementById('password-' + adminId);
            if (passwordField.classList.contains('password-cover')) {
                passwordField.classList.remove('password-cover');
            } else {
                passwordField.classList.add('password-cover');
            }
        }
    </script>
</body>

</html>