<?php
header("Content-Type: application/json");
include 'db_connection.php'; // Ensure you have a proper database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data['action'] === 'login') {
        $username = $data['username'];
        $password = $data['password'];

        $query = "SELECT * FROM tbluser WHERE userName = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'user' => [
                    'userID' => $user['userID'],
                    'username' => $user['userName'],
                    'email' => $user['email'],
                    'firstName' => $user['firstName'],
                    'lastName' => $user['lastName'],
                    'userLevel' => $user['userLevel'],
                    'notes' => $user['notes']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid username or password']);
        }

        $stmt->close();
        $conn->close();
    }
}
?>
