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
            $stmt->execute([$start_date, $end_date, $lastname_pattern, $department]);

            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error executing query: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'timecard') {
        try {
            $empID = $data['empID'];
            $start_date = $data['start_date'];
            $end_date = $data['end_date'];

            $stmt = $conn->prepare("
       WITH RECURSIVE DateSeries AS (
    SELECT
        DATE(?) AS date
    UNION
    ALL
    SELECT
        date + INTERVAL 1 DAY
    FROM
        DateSeries
    WHERE
        date < ?
)
SELECT
    DateSeries.date AS date,
    tbltimekeeping.empID,
    tblemployee.lastName,
    tblemployee.firstName,
    DATE_FORMAT(DateSeries.date, '%W') AS day_of_week,
    DATE_FORMAT(tbltimekeeping.log_in, '%r') AS log_in,
    DATE_FORMAT(tbltimekeeping.log_out, '%r') AS log_out,
    TIMEDIFF(tbltimekeeping.log_out, tbltimekeeping.log_in) AS total_time,
    WEEKDAY(DateSeries.date) + 1 AS day_of_week_numeric,
    DATE_FORMAT(sd.timeStart, '%r') AS timeStart,
    DATE_FORMAT(sd.timeEnd, '%r') AS timeEnd,
    CASE
        WHEN sd.timeStart IS NOT NULL AND tbltimekeeping.log_in > sd.timeStart THEN TIMEDIFF(tbltimekeeping.log_in, sd.timeStart)
        ELSE ''
    END AS late,
    CASE
        WHEN sd.timeEnd IS NOT NULL AND tbltimekeeping.log_out < sd.timeEnd THEN TIMEDIFF(sd.timeEnd, tbltimekeeping.log_out)
        ELSE ''
    END AS undertime
FROM
    DateSeries
    LEFT JOIN (
        SELECT
            empID,
            DATE(date) AS date,
            MIN(time) AS log_in,
            MAX(time) AS log_out
        FROM
            tbltimekeeping
        WHERE
            empID = ?
            AND DATE(date) BETWEEN ?
            AND ?
        GROUP BY
            empID,
            DATE(date)
    ) AS tbltimekeeping ON DateSeries.date = tbltimekeeping.date
    LEFT JOIN tblemployee ON tbltimekeeping.empID = tblemployee.empID
    LEFT JOIN (
        SELECT
            es.schedID,
            dayOfTheWeek,
            timeStart,
            timeEnd
        FROM
            `tblschedassign` sa
            JOIN tblempscheddetails es ON sa.schedID = es.schedID
        WHERE
            empID = ?
    ) as sd ON WEEKDAY(DateSeries.date) + 1 = sd.dayOfTheWeek
ORDER BY
    DateSeries.date;
          ");

            $stmt->execute([$start_date, $end_date, $empID, $start_date, $end_date, $empID]);

            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error executing query: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'timecardsummary') {
        try {
            $empID = $data['empID'];
            $start_date = $data['start_date'];
            $end_date = $data['end_date'];

            $stmt = $conn->prepare("
 WITH RECURSIVE DateSeries AS (
    SELECT
        DATE(?) AS date
    UNION
    ALL
    SELECT
        date + INTERVAL 1 DAY
    FROM
        DateSeries
    WHERE
        date < ?
),
DetailedRecords AS (
    SELECT
        DateSeries.date AS date,
        tbltimekeeping.empID,
        tblemployee.lastName,
        tblemployee.firstName,
        DATE_FORMAT(DateSeries.date, '%W') AS day_of_week,
        DATE_FORMAT(tbltimekeeping.log_in, '%r') AS log_in,
        DATE_FORMAT(tbltimekeeping.log_out, '%r') AS log_out,
        TIMEDIFF(tbltimekeeping.log_out, tbltimekeeping.log_in) AS total_time,
        WEEKDAY(DateSeries.date) + 1 AS day_of_week_numeric,
        DATE_FORMAT(sd.timeStart, '%r') AS timeStart,
        DATE_FORMAT(sd.timeEnd, '%r') AS timeEnd,
        CASE
            WHEN sd.timeStart IS NOT NULL AND tbltimekeeping.log_in > sd.timeStart THEN TIMEDIFF(tbltimekeeping.log_in, sd.timeStart)
            ELSE NULL
        END AS late,
        CASE
            WHEN sd.timeEnd IS NOT NULL AND tbltimekeeping.log_out < sd.timeEnd THEN TIMEDIFF(sd.timeEnd, tbltimekeeping.log_out)
            ELSE NULL
        END AS undertime
    FROM
        DateSeries
        LEFT JOIN (
            SELECT
                empID,
                DATE(date) AS date,
                MIN(time) AS log_in,
                MAX(time) AS log_out
            FROM
                tbltimekeeping
            WHERE
                empID = ?
                AND DATE(date) BETWEEN ?
                AND ?
            GROUP BY
                empID,
                DATE(date)
        ) AS tbltimekeeping ON DateSeries.date = tbltimekeeping.date
        LEFT JOIN tblemployee ON tbltimekeeping.empID = tblemployee.empID
        LEFT JOIN (
            SELECT
                es.schedID,
                dayOfTheWeek,
                timeStart,
                timeEnd
            FROM
                `tblschedassign` sa
                JOIN tblempscheddetails es ON sa.schedID = es.schedID
            WHERE
                empID = ?
        ) AS sd ON WEEKDAY(DateSeries.date) + 1 = sd.dayOfTheWeek
),
Summary AS (
    SELECT
        SUM(TIME_TO_SEC(total_time)) AS total_seconds,
        SUM(TIME_TO_SEC(late)) AS total_late_seconds,
        SUM(TIME_TO_SEC(undertime)) AS total_undertime_seconds
    FROM
        DetailedRecords
)
SELECT
    SEC_TO_TIME(total_seconds) AS total_time,
    SEC_TO_TIME(total_late_seconds) AS total_late,
    SEC_TO_TIME(total_undertime_seconds) AS total_undertime
FROM
    Summary;


          ");

            $stmt->execute([$start_date, $end_date, $empID, $start_date, $end_date, $empID]);


            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($row);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error executing query: " . $e->getMessage()));
        }
    } else {
        echo ("API ERROR timecard summary");
    }
}


// Close database connection
$conn = null;



//https://rjprint10.com/entrancemonitoring/backend/employeeapi.php?action=get_all
//https://rjprint10.com/entrancemonitoring/backend/employeeapi.php?action=get_by_id&empid=ici09-0028