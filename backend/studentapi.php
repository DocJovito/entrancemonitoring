
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
        $studid = $_GET['studid'];
        $stmt = $conn->prepare("SELECT * FROM tblstudent WHERE studid = ?");
        $stmt->execute([$studid]);
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

            $stmt = $conn->prepare("INSERT INTO tblstudent (studID, lastName, firstName, middleName, courseYrSec, department, bday, isActive, image, note, parentID) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$studID, $lastName, $firstName, $middleName, $courseYrSec, $department, $bday, $isActive, $image, $note, $parentID]);

            echo json_encode(array("message" => "Record created successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error creating record: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'update') {
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

            $stmt = $conn->prepare("UPDATE tblstudent SET studID=?, lastName=?, firstName=?, middleName=?, courseYrSec=?, department=?, bday=?, isActive=?, image=?, note=?, parentID=? WHERE studID=?");
            $stmt->execute([$studID, $lastName, $firstName, $middleName, $courseYrSec, $department, $bday, $isActive, $image, $note, $parentID, $studID]);

            echo json_encode(array("message" => "Record updated successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating record: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'search_students') {
        try {
            $studID = $data['studID'];
            $lastName = $data['lastName'];
            $department = $data['department'];
            // Base query
            $query = "SELECT * FROM tblstudent WHERE isActive = 1";
            $params = [];

            // Add conditions based on inputs
            if ($studID != '') {
                $query .= " AND studID = ?";
                $params[] = $studID;
            }
            if ($lastName != '') {
                $query .= " AND lastName like ?";
                $params[] = "%{$lastName}%";
            }
            if ($department != 'All') {
                $query .= " AND department = ?";
                $params[] = $department;
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
    if ($data['action'] === 'delete' && isset($data['id'])) {
        try {
            $id = $data['id'];
            $stmt = $conn->prepare("DELETE FROM tblstudent WHERE studid = ?");
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
?>
