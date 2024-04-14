<?php
include 'connection.php';

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve all records
    if ($_GET['action'] === 'get_all') {
        $stmt = $conn->prepare("SELECT * FROM tblempschedule");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
    }
    // Retrieve record by schedID
    elseif ($_GET['action'] === 'get_by_id' && isset($_GET['schedID'])) {
        $schedID = $_GET['schedID'];
        $stmt = $conn->prepare("SELECT * FROM tblempschedule WHERE schedID = :schedID");
        $stmt->bindParam(':schedID', $schedID, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }
}



// Close database connection
$conn = null;



//https://rjprint10.com/entrancemonitoring/backend/employeeapi.php?action=get_all
//https://rjprint10.com/entrancemonitoring/backend/employeeapi.php?action=get_by_id&empid=ici09-0028