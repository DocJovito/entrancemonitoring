<?php
$uploadDir = '/home/icpmkctc/public_html/images/';

// Add CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight request
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {
    $files = $_FILES['images'];

    // Array to hold response data
    $response = [];

    foreach ($files['name'] as $key => $fileName) {
        $fileTmpName = $files['tmp_name'][$key];
        $uploadPath = $uploadDir . basename($fileName);

        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            // File uploaded successfully
            $response[] = ['success' => true, 'message' => 'Image uploaded successfully', 'file' => $fileName];
        } else {
            // Failed to upload file
            $response[] = ['success' => false, 'error' => 'Failed to upload image: ' . $fileName];
        }
    }

    // Send JSON response
    echo json_encode($response);
} else {
    // Invalid request method or no file uploaded
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
