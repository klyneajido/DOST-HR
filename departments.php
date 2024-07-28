<?php include("PHP_Connections/fetch_departments.php")?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>HRMO Admin - Departments</title>
    <link rel="shortcut icon" href="assets/img/dost_logo.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/departments.css">
    <script src="https://kit.fontawesome.com/0dcd39d035.js" crossorigin="anonymous"></script>
    <style>

    </style>
</head>

<body class="scrollbar" id="style-5">
    <?php include("logout_modal.php") ?>
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <div class="col-md-3 ">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href=""><img src="assets/img/dash.png" class="mr-2"
                                        alt="breadcrumb" />Departments</a>
                            </li>

                        </ul>
                    </div>
                    <!-- Search Bar -->
                    <div class="col-md-7 ">
                        <input type="text" class="form-control" id="searchBar" placeholder="Search documents...">
                    </div>
                    <div class="col-md-2 d-flex justify-content-end ">
                        <button class="addfile-btn" data-toggle="modal" data-target="#uploadModal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125"
                                    stroke="#fffffff" stroke-width="2"></path>
                                <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#fffffff" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            ADD
                        </button>
                    </div>
                </div>
            </div>
            <!-- Table section -->
            <div class="col-xl-12 col-sm-12 col-12 pb-3">
                <div class="card ">
                    <div class="header_1 card-header  d-flex justify-content-between">
                        <h2 class="card-titles col-lg-6">Departments</h2>
                        <!-- START SEARCH -->
                        <div class="top-nav-search  col-lg-6 ">
                            <div class="">
                                <form id="search-form" method="GET" action="applicants.php">
                                    <input type="text" id="search-input" name="search" class="form-control"
                                        placeholder="Search here"
                                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    <input type="hidden" name="applicants_page" value="<?php echo $page; ?>">
                                    <input type="hidden" name="rows_per_page" value="<?php echo $rows_per_page; ?>">
                                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                                </form>
                            </div>

                        </div>
                        <!-- END SEARCH -->
                    </div>
                    <div class="table-responsive">
                        <table class="table custom-table no-footer text-center">
                            <thead>
                                <tr>
                                    <th data-column="job_title" class="sortable">Name<i class="fas"></i></th>
                                    <th data-column="position_or_unit" class="sortable">Location<i class="fas"></i>
                                    </th>
                                    <th data-column="job_title" class="sortable">Action<i class="fas"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($departments)) : ?>
                                <?php foreach ($departments as $department) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($department['name']); ?></td>
                                    <td><?php echo htmlspecialchars($department['location']); ?></td>
                                    <td>
                                        <button class="edit-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                                height="24">
                                                <path fill="none" d="M0 0h24v24H0z"></path>
                                                <path fill="currentColor"
                                                    d="M20 17h2v2H2v-2h2v-7a8 8 0 1 1 16 0v7zm-2 0v-7a6 6 0 1 0-12 0v7h12zm-9 4h6v2H9v-2z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button class="delete-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                                height="24">
                                                <path fill="none" d="M0 0h24v24H0z"></path>
                                                <path fill="currentColor"
                                                    d="M20 17h2v2H2v-2h2v-7a8 8 0 1 1 16 0v7zm-2 0v-7a6 6 0 1 0-12 0v7h12zm-9 4h6v2H9v-2z">
                                                </path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                <tr>
                                    <td colspan="18">No Departments.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination and rows per page controls -->

                </div>
            </div>
        </div>
    </div>

    <script>
    // Display selected file name in the custom file input
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("customFile").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    // Filter documents based on search input
    document.getElementById('searchBar').addEventListener('keyup', function() {
        var searchValue = this.value.toLowerCase();
        var documentItems = document.querySelectorAll('.document-item');
        var noDocumentsFound = true;

        documentItems.forEach(function(item) {
            var itemName = item.textContent.toLowerCase();
            if (itemName.includes(searchValue)) {
                item.style.display = 'block';
                noDocumentsFound = false;
            } else {
                item.style.display = 'none';
            }
        });

        if (noDocumentsFound) {
            document.getElementById('noDocumentsFound').style.display = 'block';
        } else {
            document.getElementById('noDocumentsFound').style.display = 'none';
        }
    });
    </script>

    <script src="assets/js/date.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="assets/js/script.js"></script>

</body>

</html>