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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['action'])) {
        $action = $_GET['action'];

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
                    // Here i want the display web page will display RFID not found.
                    // please provide instruction like "Please contact HR or OSAS"
                    // please elaborate
                    echo json_encode(array("message" => "RFID not found. Please contact HR or OSAS to register your RFID."));
                }
            } catch (PDOException $e) {
                // Error querying database
                http_response_code(500);
                echo json_encode(array("message" => "Error querying database: " . $e->getMessage()));
            }
        } else {
            // Invalid action or parameters
            http_response_code(400);
            echo json_encode(array("message" => "Invalid action or parameters."));
        }
    } else {
        // Action parameter not provided
        http_response_code(400);
        echo json_encode(array("message" => "Action parameter not provided."));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    // error_log("Incoming request data: " . print_r($data, true));

    if (isset($data['action'])) {
        $action = $data['action'];

        // Ensure currentDate and currentTime are provided in the payload
        if (!isset($data['currentDate']) || !isset($data['currentTime'])) {
            http_response_code(400);
            echo json_encode(array("message" => "currentDate or currentTime not provided."));
            exit;
        }

        if ($action === 'create_employee_log') {
            try {
                // Example insertion query (adjust as per your database schema)
                $query_insert_log = "INSERT INTO tbltimekeeping (empID, date, time, logType) 
                                     VALUES (:empID, :currentDate, :currentTime, :logType)";
                $stmt_insert_log = $conn->prepare($query_insert_log);
                $stmt_insert_log->bindParam(':empID', $data['userID']);
                $stmt_insert_log->bindParam(':currentDate', $data['currentDate']);
                $stmt_insert_log->bindParam(':currentTime', $data['currentTime']);
                $stmt_insert_log->bindParam(':logType', $data['clientIP']);
                $stmt_insert_log->execute();

                http_response_code(201);
                echo json_encode(array("message" => "Employee log created successfully."));
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(array("message" => "Error creating employee log: " . $e->getMessage()));
            }
        } elseif ($action === 'create_student_log') {
            try {
                // Example insertion query (adjust as per your database schema)
                $query_insert_log = "INSERT INTO tblstudtimekeeping (studID, date, time, logType) 
                                     VALUES (:studID, :currentDate, :currentTime, :logType)";
                $stmt_insert_log = $conn->prepare($query_insert_log);
                $stmt_insert_log->bindParam(':studID', $data['userID']);
                $stmt_insert_log->bindParam(':currentDate', $data['currentDate']);
                $stmt_insert_log->bindParam(':currentTime', $data['currentTime']);
                $stmt_insert_log->bindParam(':logType', $data['clientIP']);
                $stmt_insert_log->execute();

                http_response_code(201);
                echo json_encode(array("message" => "Student log created successfully."));
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(array("message" => "Error creating student log: " . $e->getMessage()));
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
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}


// Close database connection
$conn = null;
