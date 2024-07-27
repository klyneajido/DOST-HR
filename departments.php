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
    <link rel="stylesheet" href="assets/css/transparency.css">
    <script src="https://kit.fontawesome.com/0dcd39d035.js" crossorigin="anonymous"></script>
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
                                <a href=""><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Departments</a>
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
                                        <button class="delete-button"
                                            data-applicant-id="<?php echo $applicant['id']; ?>"
                                            onclick="confirmArchive(<?php echo $applicant['id']; ?>)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 14"
                                                class="svgIcon bin-top">
                                                <g clip-path="url(#clip0_35_24)">
                                                    <path fill="blue"
                                                        d="M20.8232 2.62734L19.9948 4.21304C19.8224 4.54309 19.4808 4.75 19.1085 4.75H4.92857C2.20246 4.75 0 6.87266 0 9.5C0 12.1273 2.20246 14.25 4.92857 14.25H64.0714C66.7975 14.25 69 12.1273 69 9.5C69 6.87266 66.7975 4.75 64.0714 4.75H49.8915C49.5192 4.75 49.1776 4.54309 49.0052 4.21305L48.1768 2.62734C47.3451 1.00938 45.6355 0 43.7719 0H25.2281C23.3645 0 21.6549 1.00938 20.8232 2.62734ZM64.0023 20.0648C64.0397 19.4882 63.5822 19 63.0044 19H5.99556C5.4178 19 4.96025 19.4882 4.99766 20.0648L8.19375 69.3203C8.44018 73.0758 11.6746 76 15.5712 76H53.4288C57.3254 76 60.5598 73.0758 60.8062 69.3203L64.0023 20.0648Z">
                                                    </path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_35_24">
                                                        <rect fill="white" height="14" width="69"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 57"
                                                class="svgIcon bin-bottom">
                                                <g clip-path="url(#clip0_35_22)">
                                                    <path fill="black"
                                                        d="M20.8232 -16.3727L19.9948 -14.787C19.8224 -14.4569 19.4808 -14.25 19.1085 -14.25H4.92857C2.20246 -14.25 0 -12.1273 0 -9.5C0 -6.8727 2.20246 -4.75 4.92857 -4.75H64.0714C66.7975 -4.75 69 -6.8727 69 -9.5C69 -12.1273 66.7975 -14.25 64.0714 -14.25H49.8915C49.5192 -14.25 49.1776 -14.4569 49.0052 -14.787L48.1768 -16.3727C47.3451 -17.9906 45.6355 -19 43.7719 -19H25.2281C23.3645 -19 21.6549 -17.9906 20.8232 -16.3727ZM64.0023 1.0648C64.0397 0.4882 63.5822 0 63.0044 0H5.99556C5.4178 0 4.96025 0.4882 4.99766 1.0648L8.19375 50.3203C8.44018 54.0758 11.6746 57 15.5712 57H53.4288C57.3254 57 60.5598 54.0758 60.8062 50.3203L64.0023 1.0648Z">
                                                    </path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_35_22">
                                                        <rect fill="white" height="57" width="69"></rect>
                                                    </clipPath>
                                                </defs>
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