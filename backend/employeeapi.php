<?php
include 'connection.php';

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'get_all') {
            $stmt = $conn->prepare("SELECT * FROM tblemployee");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);
        } elseif ($_GET['action'] === 'get_by_id' && isset($_GET['empid'])) {
            try {
                $empID = $_GET['empid'];
                $query = "SELECT e.*, r.RFID FROM tblemployee e 
                          LEFT JOIN tblrfid r ON e.empID = r.userID 
                          WHERE e.empID = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$empID]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($row);
            } catch (PDOException $e) {
                echo json_encode(array("error" => "Error fetching employee: " . $e->getMessage()));
            }
        } elseif ($_GET['action'] === 'get_emp' && isset($_GET['empid'])) {
            try {
                $empID = $_GET['empid'];
                $query = "SELECT * FROM tblemployee WHERE empID = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$empID]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($row);
            } catch (PDOException $e) {
                echo json_encode(array("error" => "Error fetching employee: " . $e->getMessage()));
            }
        } else {
            echo json_encode(array("error" => "Invalid action or missing parameters"));
        }
    } else {
        echo json_encode(array("error" => "Action not specified"));
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['action'])) {
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
                $image = $empID . '.JPG';
                $note = $data['note'];
                $email = $data['email'];
                $password = $data['password'];
                $RFID = $data['RFID'];


                // Start a transaction
                $conn->beginTransaction();

                // Insert into tblemployee
                $stmtEmployee = $conn->prepare("INSERT INTO tblemployee (empID, lastName, firstName, middleName, position, department, bday, isActive, empType, image, note, email, password) 
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmtEmployee->execute([$empID, $lastName, $firstName, $middleName, $position, $department, $bday, $isActive, $empType, $image, $note, $email, $password]);

                error_log("Successfully inserted into tblemployee");

                // Insert into tblrfid
                $stmtRFID = $conn->prepare("INSERT INTO tblrfid (RFID, userID, type) 
                                            VALUES (?, ?, ?)");
                $stmtRFID->execute([$RFID, $empID, 'EMPLOYEE']);

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
                $empID = $data['empID'];
                $RFID = $data['RFID'];
                $lastName = $data['lastName'];
                $firstName = $data['firstName'];
                $middleName = $data['middleName'];
                $position = $data['position'];
                $department = $data['department'];
                $bday = $data['bday'];
                $isActive = $data['isActive'];
                $empType = $data['empType'];
                $image = $data['image'];
                $email = $data['email'];
                $password = $data['password'];
                $note = $data['note'];

                // Start a transaction
                $conn->beginTransaction();

                // Update tblemployee table
                $stmt = $conn->prepare("UPDATE tblemployee SET empID=?, lastName=?, firstName=?, middleName=?, position=?, department=?, bday=?, isActive=?, empType=?, image=?, note=?, email=?, password=? WHERE empID=?");
                $stmt->execute([$empID, $lastName, $firstName, $middleName, $position, $department, $bday, $isActive, $empType, $image, $note, $email, $password, $empID]);

                // Update tblrfid table
                $stmtRFID = $conn->prepare("UPDATE tblrfid SET RFID=?, userID=? WHERE userID=?");
                $stmtRFID->execute([$RFID, $empID, $empID]);

                // Commit the transaction
                $conn->commit();

                echo json_encode(array("message" => "Record updated successfully"));
            } catch (PDOException $e) {
                // Rollback the transaction in case of an error
                $conn->rollBack();
                echo json_encode(array("error" => "Error updating record: " . $e->getMessage()));
            }
        } elseif ($data['action'] === 'search_employees') {
            try {
                $empID = $data['empID'];
                $lastName = $data['lastName'];
                $department = $data['department'];
                $empType = $data['empType'];

                // Base query
                $query = "SELECT r.ID, r.RFID, e.empID, e.lastName, e.firstName, e.middleName, e.position, e.department, e.empType, e.image
                          FROM tblrfid r 
                          INNER JOIN tblemployee e ON r.userID = e.empID 
                          WHERE e.isActive = 1";
                $params = [];

                // Add conditions based on inputs
                if ($empID != '') {
                    $query .= " AND e.empID = ?";
                    $params[] = $empID;
                }
                if ($lastName != '') {
                    $query .= " AND e.lastName LIKE ?";
                    $params[] = "%{$lastName}%";
                }
                if ($department != 'All') {
                    $query .= " AND e.department = ?";
                    $params[] = $department;
                }
                if ($empType != 'All') {
                    $query .= " AND e.empType = ?";
                    $params[] = $empType;
                }

                // Order by empID descending
                $query .= " ORDER BY e.empID DESC";

                // Prepare and execute the statement
                $stmt = $conn->prepare($query);
                $stmt->execute($params);

                // Fetch and return the results
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($rows);
            } catch (PDOException $e) {
                echo json_encode(array("error" => "Error fetching employees: " . $e->getMessage()));
            }
        } elseif ($data['action'] === 'search_employees2') {
            try {
                $searchTerm = $data['searchTerm'];
                $query = "SELECT e.*, r.RFID FROM tblemployee e 
                          LEFT JOIN tblrfid r ON e.empID = r.userID 
                          WHERE (e.empID LIKE ? OR e.lastName LIKE ? OR e.firstName LIKE ?)";
                $stmt = $conn->prepare($query);
                $stmt->execute(["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"]);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($rows);
            } catch (PDOException $e) {
                echo json_encode(array("error" => "Error searching employees: " . $e->getMessage()));
            }
        } elseif ($data['action'] === 'search_employee') {
            try {
                $empID = $data['empID'];

                $query = "SELECT * FROM tblemployee WHERE empID = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$empID]);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($rows);
            } catch (PDOException $e) {
                echo json_encode(array("error" => "Error searching employee: " . $e->getMessage()));
            }
        } elseif ($data['action'] === 'import' && isset($data['records']) && is_array($data['records'])) {
            try {
                $checkStmt = $conn->prepare("SELECT COUNT(*) FROM tblemployee WHERE empID = ?");
                $insertEmployeeStmt = $conn->prepare("INSERT INTO tblemployee (empID, lastName, firstName, middleName, position, department, bday, isActive, empType, image, note) 
                                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insertRFIDStmt = $conn->prepare("INSERT INTO tblrfid (RFID, userID, type) VALUES (?, ?, 'EMPLOYEE')");

                $successCount = 0;
                $existingCount = 0;

                foreach ($data['records'] as $record) {
                    $empID = $record[0];
                    $lastname = $record[1];
                    $firstname = $record[2];
                    $middlename = $record[3] ?? '';  // Use empty string if not provided
                    $position = $record[4] ?? '';  // Use empty string if not provided
                    $department = $record[5] ?? '';  // Use empty string if not provided
                    $bday = $record[6] ?? null;  // Use null if not provided
                    $empType = $record[7] ?? '';  // Use empty string if not provided
                    $note = $record[8] ?? '';  // Use empty string if not provided
                    $rfid = $record[9] ?? '';  // Use empty string if not provided
                    $type = "EMPLOYEE";
                    $isActive = 1;
                    $image = $empID . ".JPG";

                    // Check if $lastname is not null or empty
                    if ($lastname != null && $lastname != "") {
                        // Check if the record already exists
                        $checkStmt->execute([$empID]);
                        $recordExists = $checkStmt->fetchColumn();

                        if ($recordExists == 0) {
                            // Insert the employee record if it does not exist
                            $insertEmployeeStmt->execute([$empID, $lastname, $firstname, $middlename, $position, $department, $bday, $isActive, $empType, $image, $note]);
                            // Insert the RFID record
                            $insertRFIDStmt->execute([$rfid, $empID]);
                            $successCount++;
                        } else {
                            $existingCount++;
                        }
                    }
                }

                echo json_encode(array(
                    "success" => true,
                    "message" => "Records imported successfully",
                    "inserted" => $successCount,
                    "existing" => $existingCount
                ));
            } catch (PDOException $e) {
                echo json_encode(array("success" => false, "error" => "Error importing records: " . $e->getMessage()));
            }
        } else {
            echo json_encode(array("error" => "Invalid action"));
        }
    } else {
        echo json_encode(array("error" => "Action not specified"));
    }
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['action']) && $data['action'] === 'delete' && isset($data['empID'])) {
        try {
            $empID = $data['empID'];

            // Start a transaction
            $conn->beginTransaction();

            // Delete from tblrfid
            $stmtRFID = $conn->prepare("DELETE FROM tblrfid WHERE userID = ?");
            $stmtRFID->execute([$empID]);

            // Delete from tblemployee
            $stmtEmployee = $conn->prepare("DELETE FROM tblemployee WHERE empID = ?");
            $stmtEmployee->execute([$empID]);

            // Commit the transaction
            $conn->commit();

            echo json_encode(array("message" => "Record deleted successfully"));
        } catch (PDOException $e) {
            // Rollback the transaction in case of an error
            $conn->rollBack();
            echo json_encode(array("error" => "Error deleting record: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Action not specified or missing parameters"));
    }
}
