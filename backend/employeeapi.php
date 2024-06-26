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
        $stmt = $conn->prepare("SELECT * FROM tblemployee");
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
            $empID = $data['empID'];
            $lastName = $data['lastName'];
            $firstName = $data['firstName'];
            $middleName = $data['middleName'];
            $position = $data['position'];
            $department = $data['department'];
            $bday = $data['bday'];
            $isActive = $data['isActive'];
            $empType = $data['empType'];
            $image = $data['image'];
            $note = $data['note'];
            $schedID = $data['schedID'];

            $stmt = $conn->prepare("INSERT INTO tblemployee (empID, lastName, firstName, middleName, position, department, bday, isActive, empType, image, note, schedID) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$empID, $lastName, $firstName, $middleName, $position, $department, $bday, $isActive, $empType, $image, $note, $schedID]);

            echo json_encode(array("message" => "Record created successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error creating record: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'update') {
        try {
            $empID = $data['empID'];
            $lastName = $data['lastName'];
            $firstName = $data['firstName'];
            $middleName = $data['middleName'];
            $position = $data['position'];
            $department = $data['department'];
            $bday = $data['bday'];
            $isActive = $data['isActive'];
            $empType = $data['empType'];
            $image = $data['image'];
            $note = $data['note'];
            $schedID = $data['schedID'];

            $stmt = $conn->prepare("UPDATE tblemployee SET empID=?, lastName=?, firstName=?, middleName=?, position=?, department=?, bday=?, isActive=?, empType=?, image=?, note=?, schedID=? WHERE empID=?");
            $stmt->execute([$empID, $lastName, $firstName, $middleName, $position, $department, $bday, $isActive, $empType, $image, $note, $schedID, $empID]);

            echo json_encode(array("message" => "Record updated successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating record: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'searchemployees') {
        try {
            $empID = $data['empID'];
            $lastName = $data['lastName'];
            $department = $data['department'];
            $empType = $data['empType'];
            // Base query
            $query = "SELECT * FROM tblemployee WHERE isActive = 1";
            $params = [];

            // Add conditions based on inputs
            if ($empID != '') {
                $empID .= " AND empID = ?";
                $params[] = $empID;
            }
            if ($lastName != '') {
                $query .= " AND lastName like %?%";
                $params[] = $lastName;
            }
            if ($department != 'All') {
                $query .= " AND department = ?";
                $params[] = $department;
            }
            if ($empType != 'All') {
                $query .= " AND empType = ?";
                $params[] = $empType;
            }

            // Order by residentid descending
            $query .= " ORDER BY empID DESC";

            // Prepare and execute the statement
            $stmt = $conn->prepare($query);
            $stmt->execute($params);

            // Fetch and return the results
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating record: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Invalid action"));
    }




    // Import multiple records
    if ($data['action'] === 'import' && isset($data['records']) && is_array($data['records'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO tblemployee (empID, lastName, firstName, middleName, position, department, bday, isActive, empType, image, note, schedID) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            foreach ($data['records'] as $record) {
                $empID = $record['empID'];
                $lastName = $record['lastName'];
                $firstName = $record['firstName'];
                $middleName = $record['middleName'];
                $position = $record['position'];
                $department = $record['department'];
                $bday = $record['bday'];
                $isActive = $record['isActive'];
                $empType = $record['empType'];
                $image = $record['image'];
                $note = $record['note'];
                $schedID = $record['schedID'];

                $stmt->execute([$empID, $lastName, $firstName, $middleName, $position, $department, $bday, $isActive, $empType, $image, $note, $schedID]);
            }

            echo json_encode(array("success" => true, "message" => "Records imported successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("success" => false, "error" => "Error importing records: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("success" => false, "error" => "Invalid data format"));
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



//https://rjprint10.com/entrancemonitoring/backend/employeeapi.php?action=get_all
//https://rjprint10.com/entrancemonitoring/backend/employeeapi.php?action=get_by_id&empid=ici09-0028