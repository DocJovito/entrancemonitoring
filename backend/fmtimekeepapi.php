<?php
header("Content-Type: application/json");
include 'connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Handle search query
        if (isset($_GET['query'])) {
            $searchQuery = "%" . $_GET['query'] . "%";
            $query = "
                SELECT empID, lastName, firstName, position, department, empType 
                FROM tblemployee 
                WHERE empID LIKE :query OR lastName LIKE :query OR firstName LIKE :query";   //OR RFID LIKE :query  nasa tblrfid

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':query', $searchQuery);

            if ($stmt->execute()) {
                $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($employees ? $employees : []);
            } else {
                throw new Exception("Error executing query");
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

            if ($stmt->execute()) {
                echo json_encode(["message" => "TimeKeep record added successfully."]);
            } else {
                throw new Exception("Error executing timekeeping query");
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid data provided."]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Server error: " . $e->getMessage()]);
}

$conn = null;
