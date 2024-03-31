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

    if ($_GET['action'] === 'get_by_id' && isset($_GET['empid'])) {
        $empid = $_GET['empid'];
        $stmt = $conn->prepare("    e.empid,
        e.lastname,
        e.firstname,
        e.position,
        e.department,
        tk.date,
        MIN(tk.time) AS time_in,
        MAX(tk.time) AS time_out
        FROM 
        tblemployee e
        INNER JOIN 
        tbltimekeeping tk ON e.empid = tk.empid
        WHERE 
        tk.date BETWEEN '2023-01-01' AND '2024-04-30'
        AND e.empid LIKE '%%' 
        AND  e.lastname LIKE '%p%'
        GROUP BY 
        e.empid,
        e.lastname,
        e.firstname,
        e.position,
        e.department,
        tk.date;");
        $stmt->execute([$empid]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }
}


// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse incoming JSON data
    $data = json_decode(file_get_contents("php://input"), true);
    if ($data['action'] === 'alllogs') {
        try {


            // Get parameters from JSON data
            $start_date = $data['start_date'];
            $end_date = $data['end_date'];
            $empid_pattern = '%' . $data['empid_pattern'] . '%';
            $lastname_pattern = '%' . $data['lastname_pattern'] . '%';
            $department = '%' . $data['department'] . '%';


            // Assuming $conn is your PDO connection
            $stmt = $conn->prepare("
            SELECT 
                e.empid,
                e.lastname,
                e.firstname,
                e.position,
                e.department,
                tk.date,
                MIN(tk.time) AS time_in,
                MAX(tk.time) AS time_out,
                TIMEDIFF(MAX(tk.time), MIN(tk.time)) AS total_hours
            FROM 
                tblemployee e
            INNER JOIN 
                tbltimekeeping tk ON e.empid = tk.empid
            WHERE 
                tk.date BETWEEN ? AND ?
                AND e.empid LIKE ?
                AND e.lastname LIKE ?
                AND e.department LIKE ?  
            GROUP BY 
                e.empid,
                e.lastname,
                e.firstname,
                e.position,
                e.department,
                tk.date;
        ");

            // Bind parameters and execute query
            $stmt->execute([$start_date, $end_date, $empid_pattern, $lastname_pattern, $department]);

            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error executing query: " . $e->getMessage()));
        }
    }
}


// Close database connection
$conn = null;



//https://rjprint10.com/entrancemonitoring/backend/employeeapi.php?action=get_all
//https://rjprint10.com/entrancemonitoring/backend/employeeapi.php?action=get_by_id&empid=ici09-0028