WITH RECURSIVE DateSeries AS (
    SELECT
        DATE(?) AS date,
        CASE
            WHEN DAYOFWEEK(?) = 1 THEN 7
            ELSE DAYOFWEEK(?) - 1
        END AS dayOfTheWeek
    UNION
    ALL
    SELECT
        date + INTERVAL 1 DAY,
        CASE
            WHEN DAYOFWEEK(date + INTERVAL 1 DAY) = 1 THEN 7
            ELSE DAYOFWEEK(date + INTERVAL 1 DAY) - 1
        END
    FROM
        DateSeries
    WHERE
        date < ?
),
DetailedSchedule AS (
    SELECT
        tblschedassign.schedid,
        tblschedassign.empID,
        tblempscheddetails.dayOfTheWeek,
        tblempscheddetails.timeStart,
        tblempscheddetails.timeEnd
    FROM
        tblschedassign
        LEFT JOIN tblempscheddetails ON tblschedassign.schedID = tblempscheddetails.schedID
    WHERE
        tblschedassign.empID IN ($ in)
    ORDER BY
        tblschedassign.empID,
        tblempscheddetails.dayOfTheWeek
),
EmployeeLogs AS (
    SELECT
        empID,
        DATE(date) AS date,
        MIN(time) AS log_in,
        MAX(time) AS log_out
    FROM
        tbltimekeeping
    WHERE
        empID IN ($ in)
        AND DATE(date) BETWEEN ?
        AND ?
    GROUP BY
        empID,
        DATE(date)
),
allLogsSchedule AS (
    SELECT
        DateSeries.date as date,
        DateSeries.dayOfTheWeek,
        DetailedSchedule.empID as empID,
        DetailedSchedule.timeStart,
        DetailedSchedule.timeEnd
    FROM
        DateSeries
        RIGHT JOIN DetailedSchedule ON DateSeries.dayOfTheWeek = DetailedSchedule.dayOfTheWeek
    ORDER BY
        DateSeries.date
),
finalTable AS (
    SELECT
        allLogsSchedule.date as date,
        allLogsSchedule.dayOfTheWeek,
        allLogsSchedule.empID as empID,
        allLogsSchedule.timeStart,
        allLogsSchedule.timeEnd,
        EmployeeLogs.log_in,
        EmployeeLogs.log_out,
        TIMEDIFF(EmployeeLogs.log_out, EmployeeLogs.log_in) AS total_time,
        CASE
            WHEN allLogsSchedule.timeStart IS NOT NULL
            AND EmployeeLogs.log_in > allLogsSchedule.timeStart THEN TIMEDIFF(EmployeeLogs.log_in, allLogsSchedule.timeStart)
            ELSE NULL
        END AS late,
        CASE
            WHEN allLogsSchedule.timeEnd IS NOT NULL
            AND EmployeeLogs.log_out < allLogsSchedule.timeEnd THEN TIMEDIFF(allLogsSchedule.timeEnd, EmployeeLogs.log_out)
            ELSE NULL
        END AS undertime,
        CASE
            WHEN allLogsSchedule.timeStart IS NOT NULL
            AND EmployeeLogs.log_in IS NULL THEN 1
            ELSE 0
        END AS absent
    FROM
        allLogsSchedule
        LEFT JOIN EmployeeLogs ON allLogsSchedule.date = EmployeeLogs.date
        AND allLogsSchedule.empID = EmployeeLogs.empID
    WHERE
        allLogsSchedule.date IS NOT NULL
    ORDER BY
        allLogsSchedule.empID,
        date
),
Summary AS (
    select
        empid,
        sum(TIME_TO_SEC(total_time)) as total_seconds,
        sum(TIME_TO_SEC(late)) as total_late_seconds,
        sum(TIME_TO_SEC(undertime)) as total_undertime_seconds,
        sum(absent) as total_absent
    from
        finalTable
    GROUP BY
        empid
    ORDER BY
        empID,
        date
)
SELECT
    Summary.empID,
    CONCAT(
        lastName,
        ', ',
        firstName,
        ' ',
        middleName
    ) AS fullName,
    SEC_TO_TIME(total_seconds) AS total_time,
    SEC_TO_TIME(total_late_seconds) AS total_late,
    SEC_TO_TIME(total_undertime_seconds) AS total_undertime,
    total_absent
FROM
    Summary
    LEFT JOIN tblemployee ON Summary.empID = tblemployee.empID;