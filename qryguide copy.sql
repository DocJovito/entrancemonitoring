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
        WHEN tbltimekeeping.log_in > sd.timeStart THEN TIMEDIFF(tbltimekeeping.log_in, sd.timeStart)
        ELSE '00:00:00'
    END AS late,
    CASE
        WHEN tbltimekeeping.log_out < sd.timeEnd THEN TIMEDIFF(sd.timeEnd, tbltimekeeping.log_out)
        ELSE '00:00:00'
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
            empID = 'ICI09-0028'
            AND DATE(date) BETWEEN '2024-07-01'
            AND '2024-07-15'
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
            empID = 'ICI09-0028'
    ) as sd ON WEEKDAY(DateSeries.date) + 1 = sd.dayOfTheWeek
ORDER BY
    DateSeries.date;