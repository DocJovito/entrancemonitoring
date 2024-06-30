<?php
// Set headers for allowing CORS and specifying JSON content type
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }

    exit(0);
}

// Include the database connection
include 'connection.php';

// Check if action parameter is provided
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Handle GET requests
    if ($action === 'get_by_id' && isset($_GET['RFID'])) {
        $RFID = $_GET['RFID'];

        try {
            // Query to fetch data based on RFID
            $query = "SELECT * FROM tblrfid WHERE RFID = :rfid";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':rfid', $RFID);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $userID = $row['userID'];
                $type = $row['type'];

                // Fetch details based on type (EMPLOYEE or STUDENT)
                if ($type === 'EMPLOYEE') {
                    $query_emp = "SELECT * FROM tblemployee WHERE empID = :empID";
                    $stmt_emp = $conn->prepare($query_emp);
                    $stmt_emp->bindParam(':empID', $userID);
                    $stmt_emp->execute();
                    $row_emp = $stmt_emp->fetch(PDO::FETCH_ASSOC);

                    // Prepare data to return
                    $data = array(
                        'userID' => $row_emp['empID'],
                        'lastName' => $row_emp['lastName'],
                        'firstName' => $row_emp['firstName'],
                        'middleName' => $row_emp['middleName'],
                        'position' => $row_emp['position'],
                        'department' => $row_emp['department'],
                        'bday' => $row_emp['bday'],
                        'isActive' => $row_emp['isActive'],
                        'empType' => $type, // Specify employee type
                        'image' => isset($row_emp['image']) ? $row_emp['image'] : null, // Add employee image field if available
                        'note' => $row_emp['note'],
                    );

                    // Check and add schedule ID if available
                    if (isset($row_emp['schedID'])) {
                        $data['schedID'] = $row_emp['schedID'];
                    }
                } elseif ($type === 'STUDENT') {
                    $query_stud = "SELECT * FROM tblstudent WHERE studID = :studID";
                    $stmt_stud = $conn->prepare($query_stud);
                    $stmt_stud->bindParam(':studID', $userID);
                    $stmt_stud->execute();
                    $row_stud = $stmt_stud->fetch(PDO::FETCH_ASSOC);

                    // Prepare data to return
                    $data = array(
                        'userID' => $row_stud['studID'],
                        'lastName' => $row_stud['lastName'],
                        'firstName' => $row_stud['firstName'],
                        'middleName' => $row_stud['middleName'],
                        'courseYrSec' => $row_stud['courseYrSec'],
                        'department' => $row_stud['department'],
                        'bday' => $row_stud['bday'],
                        'isActive' => $row_stud['isActive'],
                        'empType' => $type, // Specify student type
                        'image' => isset($row_stud['image']) ? $row_stud['image'] : null, // Add student image field if available
                        'note' => $row_stud['note']
                    );
                }

                // Return JSON response
                echo json_encode($data);
            } else {
                // RFID not found
                http_response_code(404);
                echo json_encode(array("message" => "RFID not found."));
            }
        } catch (PDOException $e) {
            // Error querying database
            http_response_code(500);
            echo json_encode(array("message" => "Error querying database: " . $e->getMessage()));
        }
    } elseif ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handling log creation (insertion into log table)
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            // Example insertion query (adjust as per your database schema)
            $query_insert_log = "INSERT INTO log_table (userID, action, logType) 
                                 VALUES (:userID, :action, :logType)";
            $stmt_insert_log = $conn->prepare($query_insert_log);
            $stmt_insert_log->bindParam(':userID', $data['userID']);
            $stmt_insert_log->bindParam(':action', $data['action']);
            $stmt_insert_log->bindParam(':logType', $data['logType']);
            $stmt_insert_log->execute();

            http_response_code(201);
            echo json_encode(array("message" => "Log created successfully."));
        } catch (PDOException $e) {
            // Error creating log
            http_response_code(500);
            echo json_encode(array("message" => "Error creating log: " . $e->getMessage()));
        }
    } else {
        // Invalid action or method
        http_response_code(400);
        echo json_encode(array("message" => "Invalid action or method."));
    }
} else {
    // Action parameter not provided
    http_response_code(400);
    echo json_encode(array("message" => "Action parameter not provided."));
}

// Close database connection
$conn = null;
