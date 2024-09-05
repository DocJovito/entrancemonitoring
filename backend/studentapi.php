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
        $stmt = $conn->prepare("SELECT * FROM tblstudent");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
    }

    // Retrieve a single record by ID
    if ($_GET['action'] === 'get_by_id' && isset($_GET['studid'])) {
        try {
            $studid = $_GET['studid'];
            $query = "SELECT s.*, r.RFID FROM tblstudent s 
                      LEFT JOIN tblrfid r ON s.studID = r.userID 
                      WHERE s.studID = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$studid]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($row);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error fetching student: " . $e->getMessage()));
        }
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse incoming JSON data
    $data = json_decode(file_get_contents("php://input"), true);

    // Create a new record
    if ($data['action'] === 'create') {
        try {
            $studID = $data['studID'];
            $lastName = $data['lastName'];
            $firstName = $data['firstName'];
            $middleName = $data['middleName'];
            $courseYrSec = $data['courseYrSec'];
            $department = $data['department'];
            $bday = $data['bday'];
            $isActive = $data['isActive'];
            $image = $data['image'];
            $note = $data['note'];
            $parentID = $data['parentID'];
            $RFID = $data['RFID'];

            // Start a transaction
            $conn->beginTransaction();

            $stmt = $conn->prepare("INSERT INTO tblstudent (studID, lastName, firstName, middleName, courseYrSec, department, bday, isActive, image, note, parentID) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$studID, $lastName, $firstName, $middleName, $courseYrSec, $department, $bday, $isActive, $image, $note, $parentID]);

            error_log("Successfully inserted into tblstudent");

            // Insert into tblrfid
            $stmtRFID = $conn->prepare("INSERT INTO tblrfid (RFID, userID, type) 
                                            VALUES (?, ?, ?)");
            $stmtRFID->execute([$RFID, $studID, 'STUDENT']);

            error_log("Successfully inserted into tblrfid");

            // Commit the transaction
            $conn->commit();

            echo json_encode(array("message" => "Record created successfully"));

            error_log("Record created successfully");
        } catch (PDOException $e) {
            // Rollback the transaction in case of an error
            $conn->rollBack();
            echo json_encode(array("error" => "Error creating record: " . $e->getMessage()));
            error_log("Error creating record: " . $e->getMessage());
        }
    } elseif ($data['action'] === 'update') {
        try {
            $studID = $data['studID'];
            $RFID = $data['RFID'];
            $lastName = $data['lastName'];
            $firstName = $data['firstName'];
            $middleName = $data['middleName'];
            $courseYrSec = $data['courseYrSec'];
            $department = $data['department'];
            $bday = $data['bday'];
            $isActive = $data['isActive'];
            $image = $data['image'];
            $note = $data['note'];
            $parentID = $data['parentID'];

            // Start a transaction
            $conn->beginTransaction();

            $stmt = $conn->prepare("UPDATE tblstudent SET studID=?, lastName=?, firstName=?, middleName=?, courseYrSec=?, department=?, bday=?, isActive=?, image=?, note=?, parentID=? WHERE studID=?");
            $stmt->execute([$studID, $lastName, $firstName, $middleName, $courseYrSec, $department, $bday, $isActive, $image, $note, $parentID, $studID]);

            // Update tblrfid table
            $stmtRFID = $conn->prepare("UPDATE tblrfid SET RFID=?, userID=? WHERE userID=?");
            $stmtRFID->execute([$RFID, $studID, $studID]);

            // Commit the transaction
            $conn->commit();

            echo json_encode(array("message" => "Record updated successfully"));
        } catch (PDOException $e) {
            // Rollback the transaction in case of an error
            $conn->rollBack();
            echo json_encode(array("error" => "Error updating record: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'search_students') {
        try {
            $studID = $data['studID'];
            $lastName = $data['lastName'];
            $department = $data['department'];
            $courseYrSec = $data['courseYrSec'];
            // Base query
            $query = "SELECT * FROM tblstudent WHERE isActive = 1";
            $query = "SELECT r.ID, s.image, r.RFID, s.studID, s.lastName, s.firstName, s.middleName, s.courseYrSec, s.department, s.isActive AS isActive 
                    FROM tblstudent AS s 
                    LEFT JOIN tblrfid AS r ON s.studID = r.userID 
                    WHERE r.type = 'STUDENT'";
            $params = [];

            // Add conditions based on inputs
            if ($studID != '') {
                $query .= " AND s.studID = ?";
                $params[] = $studID;
            }
            if ($lastName != '') {
                $query .= " AND s.lastName like ?";
                $params[] = "%{$lastName}%";
            }
            if ($department != 'All') {
                $query .= " AND s.department = ?";
                $params[] = $department;
            }
            if ($courseYrSec != 'All') {
                $query .= " AND s.courseYrSec = ?";
                $params[] = $courseYrSec;
            }

            // Order by studid descending
            $query .= " ORDER BY studID DESC";

            // Prepare and execute the statement
            $stmt = $conn->prepare($query);
            $stmt->execute($params);

            // Fetch and return the results
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error fetching students: " . $e->getMessage()));
            echo json_encode($params);
        }
    } else {
        echo json_encode(array("error" => "Invalid action"));
    }
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Delete a record by ID
    if ($data['action'] === 'delete' && isset($data['studID'])) {
        try {
            $id = $data['studID'];

            // Start a transaction
            $conn->beginTransaction();

            // Delete from tblrfid
            $stmtRFID = $conn->prepare("DELETE FROM tblrfid WHERE userID = ?");
            $stmtRFID->execute([$id]);

            $stmt = $conn->prepare("DELETE FROM tblstudent WHERE studid = ?");
            $stmt->execute([$id]);

            // Commit the transaction
            $conn->commit();

            echo json_encode(array("message" => "Record deleted successfully"));
        } catch (PDOException $e) {
            // Rollback the transaction in case of an error
            $conn->rollBack();
            echo json_encode(array("error" => "Error deleting record: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Invalid action or missing ID"));
    }
}

// Close database connection
$conn = null;
