<?php
header("Content-Type: application/json");
include 'connection.php';

// Set headers for CORS
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
    exit(0);
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Handle search query
        if (isset($_GET['query'])) {
            $searchQuery = "%" . $_GET['query'] . "%";
            $query = "
                SELECT tblemployee.empID AS id, tblrfid.RFID, tblemployee.lastName, tblemployee.firstName, tblemployee.position, tblemployee.department, tblemployee.empType 
                FROM tblemployee 
                LEFT JOIN tblrfid ON tblrfid.userID = tblemployee.empID
                WHERE tblemployee.empID LIKE :query 
                OR tblrfid.RFID LIKE :query 
                OR tblemployee.lastName LIKE :query 
                OR tblemployee.firstName LIKE :query";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':query', $searchQuery);
            $stmt->execute();
            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($employees) {
                echo json_encode($employees);
            } else {
                echo json_encode([]);
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle timekeeping log
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['employeeID']) && isset($data['date']) && isset($data['time'])) {
            $query = "INSERT INTO tbltimekeeping (empID, date, time, logType) VALUES (:empID, :date, :time, 'manual')";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':empID', $data['employeeID']);
            $stmt->bindParam(':date', $data['date']);
            $stmt->bindParam(':time', $data['time']);
            $stmt->execute();

            echo json_encode(["message" => "TimeKeep record added successfully."]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid data provided."]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Server error: " . $e->getMessage()]);
}

$conn = null;
