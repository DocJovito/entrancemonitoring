<?php
include 'connection.php';

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['action'])) {
        if ($data['action'] === 'login') {
            try {
                $userName = $data['userName'];
                $password = $data['password'];

                // Prepare the SQL statement
                $stmt = $conn->prepare("SELECT * FROM tbluser WHERE userName = ? AND password = ?");
                $stmt->execute([$userName, $password]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    // Login successful
                    echo json_encode(array("success" => true, "message" => "Login successful", "user" => $user));
                } else {
                    // Login failed
                    echo json_encode(array("success" => false, "message" => "Invalid username or password"));
                }
            } catch (PDOException $e) {
                echo json_encode(array("error" => "Error during login: " . $e->getMessage()));
            }
        } elseif ($data['action'] === 'register') {
            try {
                $userName = $data['userName'];
                $password = $data['password'];
                $email = $data['email'];
                $lastName = $data['lastName'];
                $firstName = $data['firstName'];
                $userLevel = $data['userLevel'];
                $notes = $data['notes'];

                // Check if username or email already exists
                $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM tbluser WHERE userName = ? OR email = ?");
                $stmtCheck->execute([$userName, $email]);
                if ($stmtCheck->fetchColumn() > 0) {
                    echo json_encode(array("success" => false, "message" => "Username or email already exists"));
                    exit;
                }

                // Insert new user
                $stmt = $conn->prepare("INSERT INTO tbluser (userName, password, email, lastName, firstName, userLevel, notes) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$userName, $password, $email, $lastName, $firstName, $userLevel, $notes]);

                echo json_encode(array("success" => true, "message" => "Registration successful"));
            } catch (PDOException $e) {
                echo json_encode(array("error" => "Error during registration: " . $e->getMessage()));
            }
        } else {
            echo json_encode(array("error" => "Invalid action"));
        }
    } else {
        echo json_encode(array("error" => "Action not specified"));
    }
} else {
    echo json_encode(array("error" => "Invalid request method"));
}
