WITH RECURSIVE DateSeries AS (
    SELECT
        DATE('2024-07-01') AS date
    UNION
    ALL
    SELECT
        date + INTERVAL 1 DAY
    FROM
        DateSeries
    WHERE
        date < '2024-07-15'
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
            WHEN sd.timeStart IS NOT NULL
            AND tbltimekeeping.log_in > sd.timeStart THEN TIMEDIFF(tbltimekeeping.log_in, sd.timeStart)
            ELSE NULL
        END AS late,
        CASE
            WHEN sd.timeEnd IS NOT NULL
            AND tbltimekeeping.log_out < sd.timeEnd THEN TIMEDIFF(sd.timeEnd, tbltimekeeping.log_out)
            ELSE NULL
        END AS undertime,
        CASE
            WHEN sd.timeStart IS NOT NULL
            AND tbltimekeeping.log_in IS NULL THEN 1
            ELSE 0
        END AS absent
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
                empID IN ('ICI23-0011', 'ICI09-0028', 'ICI08-0010')
                AND DATE(date) BETWEEN '2024-07-01'
                AND '2024-07-15'
            GROUP BY
                empID,
                DATE(date)
        ) AS ON DateSeries.date = tbltimekeeping.date
        LEFT JOIN tblemployee ON tbltimekeeping.empID = tblemployee.empID
        LEFT JOIN (
            SELECT
                sa.empID,
                es.schedID,
                dayOfTheWeek,
                timeStart,
                timeEnd
            FROM
                tblschedassign sa
                JOIN tblempscheddetails es ON sa.schedID = es.schedID
            WHERE
                sa.empID IN ('ICI23-0011', 'ICI09-0028', 'ICI08-0010')
        ) AS sd ON tbltimekeeping.empID = sd.empID
        AND WEEKDAY(DateSeries.date) + 1 = sd.dayOfTheWeek
),
Summary AS (
    SELECT
        empID,
        SUM(TIME_TO_SEC(total_time)) AS total_seconds,
        SUM(TIME_TO_SEC(late)) AS total_late_seconds,
        SUM(TIME_TO_SEC(undertime)) AS total_undertime_seconds,
        SUM(absent) AS total_absent
    FROM
        DetailedRecords
    GROUP BY
        empID
)
SELECT
    empID,
    SEC_TO_TIME(total_seconds) AS total_time,
    SEC_TO_TIME(total_late_seconds) AS total_late,
    SEC_TO_TIME(total_undertime_seconds) AS total_undertime,
    total_absent
FROM
    Summary;