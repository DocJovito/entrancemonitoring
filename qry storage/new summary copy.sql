WITH RECURSIVE DateSeries AS (
    SELECT
        DATE('2024-07-01') AS date,
        CASE
            WHEN DAYOFWEEK('2024-07-01') = 1 THEN 7
            ELSE DAYOFWEEK('2024-07-01') - 1
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
        date < '2024-07-15'
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
        tblschedassign.empID IN ('ICI23-0011', 'ICI09-0028', 'ICI08-0010')
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
        empID IN ('ICI23-0011', 'ICI09-0028', 'ICI08-0010')
        AND DATE(date) BETWEEN '2024-07-01'
        AND '2024-07-15'
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
    ORDER BY
        allLogsSchedule.empID,
        date
)
select
    empid,
    sum(total_time) as total_time,
    sum(late) as total_late,
    sum(undertime) as total_undertime,
    sum(absent) as total_absent
from
    finalTable
GROUP BY
    empid
ORDER BY
    empID,
    date