<?php
// Start session
session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Get user's name from session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

// Fetch uploaded documents
$sql = "SELECT * FROM documents";
$result = $mysqli->query($sql);

// Array to store document cards HTML
$documentCards = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $documentId = $row['doc_id']; // Assuming your table has an ID field
        $documentName = $row['name'];
        // Assuming documents are stored as PDFs or Word files
        $icon = 'assets/img/pdf.png'; // Change this based on file type if needed

        // Generate HTML for document card
        $documentCard = '<div class="document-card shadow-sm m-2">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">' . $documentName . '</h6>
                                <div class="document-buttons ml-2">
                                    <a href="PHP_Connections/download_document_transparency.php?id=' . $documentId . '">
                                        <button class="Btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512" class="svgIcon">
                                                <path d="M169.4 470.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 370.8 224 64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 306.7L54.6 265.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z"></path>
                                            </svg>
                                            <span class="icon2"></span>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>';

        // Append card HTML to array
        $documentCards[] = $documentCard;
    }
}
$uploadStatus = isset($_GET['upload_status']) ? $_GET['upload_status'] : '';
if ($uploadStatus === 'failed') {
    $errorMsg = isset($_GET['error']) ? urldecode($_GET['error']) : 'Unknown error occurred.';
    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($errorMsg) . '</div>';
}
?>