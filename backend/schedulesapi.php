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

    // Retrieve a single record by ID
    if ($_GET['action'] === 'get_by_id' && isset($_GET['empid'])) {
        $empid = $_GET['empid'];
        $stmt = $conn->prepare("SELECT * FROM tblemployee WHERE empid = ?");
        $stmt->execute([$empid]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse incoming JSON data
    $data = json_decode(file_get_contents("php://input"), true);

    // Create a new record
    if ($data['action'] === 'create') {
        try {
            $schedName = $data['schedName'];
            $schoolYear = $data['schoolYear'];
            $semester = $data['semester'];
            $effDateStart = $data['effDateStart'];
            $effDateEnd = $data['effDateEnd'];
            $description = $data['description'];
            $isDeleted = $data['isDeleted'];
            $createdBy = $data['createdBy'];
            $createdAt = $data['createdAt'];
            $updatedBy = $data['updatedBy'];
            $updatedAt = $data['updatedAt'];
            $deletedBy = $data['deletedBy'];
            $deletedAt = $data['deletedAt'];

            $stmt = $conn->prepare("INSERT INTO tblempschedule (schedName, schoolYear, semester, effDateStart, effDateEnd, description, isDeleted, createdBy, createdAt, updatedBy, updatedAt, deletedBy, deletedAt) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$schedName, $schoolYear, $semester, $effDateStart, $effDateEnd, $description, $isDeleted, $createdBy, $createdAt, $updatedBy, $updatedAt, $deletedBy, $deletedAt]);

            echo json_encode(array("message" => "Record created successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error creating record: " . $e->getMessage()));
        }
    }
    if ($data['action'] === 'update') {
        try {
            $schedID = $data['schedID']; // Include schedID for identifying which record to update
            $schedName = $data['schedName'];
            $schoolYear = $data['schoolYear'];
            $semester = $data['semester'];
            $effDateStart = $data['effDateStart'];
            $effDateEnd = $data['effDateEnd'];
            $description = $data['description'];
            $isDeleted = $data['isDeleted'];
            $createdBy = $data['createdBy'];
            $createdAt = $data['createdAt'];
            $updatedBy = $data['updatedBy'];
            $updatedAt = $data['updatedAt'];
            $deletedBy = $data['deletedBy'];
            $deletedAt = $data['deletedAt'];

            $stmt = $conn->prepare("UPDATE tblempschedule 
                                SET schedName = ?, schoolYear = ?, semester = ?, effDateStart = ?, effDateEnd = ?, description = ?, isDeleted = ?, createdBy = ?, createdAt = ?, updatedBy = ?, updatedAt = ?, deletedBy = ?, deletedAt = ?
                                WHERE schedID = ?");
            $stmt->execute([$schedName, $schoolYear, $semester, $effDateStart, $effDateEnd, $description, $isDeleted, $createdBy, $createdAt, $updatedBy, $updatedAt, $deletedBy, $deletedAt, $schedID]);

            echo json_encode(array("message" => "Record updated successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating record: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'searchsched') {
        try {
            $schedID = $data['schedID'];
            $schedName = $data['schedName'];
            $schoolYear = $data['schoolYear'];
            $semester = $data['semester'];
            // Base query
            $query = "SELECT * FROM tblempschedule WHERE isDeleted = 0";
            $params = [];

            // Add conditions based on inputs
            if ($schedID != '') {
                $query .= " AND schedID = ?";
                $params[] = $schedID;
            }
            if ($schedName != '') {
                $query .= " AND schedName like ?";
                $params[] = "%{$schedName}%";
            }
            if ($schoolYear != 'All') {
                $query .= " AND schoolYear = ?";
                $params[] = $schoolYear;
            }
            if ($semester != 'All') {
                $query .= " AND semester = ?";
                $params[] = $semester;
            }

            // Order by residentid descending
            $query .= " ORDER BY schedID DESC";

            // Prepare and execute the statement
            $stmt = $conn->prepare($query);
            $stmt->execute($params);

            // Fetch and return the results
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error fetching residents: " . $e->getMessage()));
            echo json_encode($params);
        }
    } elseif ($data['action'] === 'searchscheddetail') {
        try {
            $schedID = $data['schedID'];
            $query = "SELECT * FROM tblempscheddetails WHERE schedID = ? ORDER BY dayOfTheWeek";
            $stmt = $conn->prepare($query);
            $stmt->execute([$schedID]);

            // Fetch and return the results
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error fetching residents: " . $e->getMessage()));
            echo json_encode($params);
        }
    } else {
        echo json_encode(array("error" => "Invalid action"));
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Delete a record by ID
    if ($data['action'] === 'delete' && isset($data['id'])) {
        try {
            $id = $data['id'];
            $stmt = $conn->prepare("DELETE FROM tblperson WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(array("message" => "Record deleted successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error deleting record: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Invalid action or missing ID"));
    }
}





// Close database connection
$conn = null;
