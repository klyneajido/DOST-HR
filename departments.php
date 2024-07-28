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
</head>
<body class="scrollbar" id="style-5">
    <?php include("logout_modal.php") ?>
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <div class="col-md-3">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href=""><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Departments</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Table section -->
            <div class="col-xl-12 col-sm-12 col-12 pb-3">
                <div class="card">
                    <div class="header_1 card-header d-flex justify-content-between">
                        <h2 class="card-titles col-lg-2">Departments</h2>
                        <div class="func col-lg-10 d-flex justify-content-center align-items-center">
                            <!-- START SEARCH -->
                            <div class="top-nav-search col-md-10">
                                <div class="">
                                    <input type="text" id="search-input" name="search" class="form-control" placeholder="Search here">
                                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                            <!-- END SEARCH -->
                            <div class="add-dept col-md-2 d-flex justify-content-end">
                                <a href="addDepartment.php">
                                      <button class="addfile-btn" data-toggle="modal" data-target="#uploadModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125"
                                            stroke="#ffffff" stroke-width="2"></path>
                                        <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#ffffff" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    ADD
                                </button>
                                </a>
                              
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <?php if (isset($_SESSION['error'])) : ?>
                            <div class="alert alert-danger text-center">
                                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['success'])) : ?>
                            <div class="alert alert-success">
                                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>
                        <table class="table custom-table no-footer text-center">
                            <thead>
                                <tr>
                                    <th data-column="name" class="sortable">Name<i class="fas"></i></th>
                                    <th data-column="location" class="sortable">Location<i class="fas"></i></th>
                                    <th data-column="action" class="sortable">Action<i class="fas"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $default_department_id = 5; // Define your default department ID
                                if (!empty($departments)) : 
                                ?>
                                <?php foreach ($departments as $department) : ?>
                                <tr data-search="<?php echo htmlspecialchars($department['name'] . ' ' . $department['location']); ?>">
                                    <td><?php echo htmlspecialchars($department['name']); ?></td>
                                    <td><?php echo htmlspecialchars($department['location']); ?></td>
                                    <td>
                                        <?php if ($department['department_id'] != $default_department_id) : ?>
                                            <button class="edit-btn">
                                                <a href="editDepartment.php?id=<?php echo $department['department_id']; ?>">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            </button>
                                            <button class="delete-btn" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo $department['department_id']; ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                <tr>
                                    <td colspan="3">No Departments.</td>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this department?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="PHP_Connections/delete_department.php" method="post">
                        <input type="hidden" name="department_id" id="delete-department-id">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/date.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script>
        // Script to pass department_id to delete modal
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var departmentId = button.data('id');
            var modal = $(this);
            modal.find('#delete-department-id').val(departmentId);
        });

        // Search functionality
        $(document).ready(function() {
            $('#search-input').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('table tbody tr').filter(function() {
                    $(this).toggle($(this).data('search').toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>
