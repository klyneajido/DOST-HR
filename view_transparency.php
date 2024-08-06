<?php include("PHP_Connections/fetch_transparency_docs.php") ?>
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
    <link rel="stylesheet" href="assets/css/transparency.css">
</head>

<body class="scrollbar" id="style-5">
    <?php include("modal_logout.php") ?>
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <div class="col-md-3 ">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href=""><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Legal</a>
                            </li>
                            <li class="breadcrumb-item active">Documents</li>
                        </ul>
                    </div>
                    <!-- Search Bar -->
                    <div class="col-md-7 ">
                        <input type="text" class="form-control" id="searchBar" placeholder="Search documents...">
                    </div>
                    <div class="col-md-2 d-flex justify-content-end ">
                        <button class="addfile-btn" data-toggle="modal" data-target="#uploadModal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke="#fffffff" stroke-width="2"></path>
                                <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#fffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            ADD FILE
                        </button>
                    </div>
                </div>
                <?php
            // Place the error message display here
            if ($uploadStatus === 'failed') {
                echo '<div class="alert alert-danger mt-3 mb-3" role="alert">' . htmlspecialchars($errorMsg) . '</div>';
            }
?>
                <div class="container text-center"></div>
            </div>
            <h3 class="d-flex justify-content-center my-3">Documents</h3>
            <div class="display-documents pb-4">
                <div class="container-fluid">
                    <?php if (empty($documentCards)): ?>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <p>No documents uploaded.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="row" id="documentList">
                            <?php
                foreach ($documentCards as $card) {
                    echo '<div class="col-md-6 document-item">' . $card . '</div>';
                }
                        ?>
                        </div>
                    <?php endif; ?>
                    <div class="row" id="noDocumentsFound" style="display: none;">
                        <div class="col-md-12 text-center">
                            <p>No documents found.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for File Upload -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="PHP_Connections/upload_document.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="form-group col-md-12">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="document">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary col-md-12 mb-4">Upload</button>
                    </form>
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
