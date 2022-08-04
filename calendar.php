<?php
include "./db_link_include.php";
$date = strtotime($_POST["calendar_date"]);
if ($date == '') $date = time();
$day = date('d', $date);
$month = date('m', $date);
$year = date('Y', $date);

$first_day = mktime(0, 0, 0, $month, 1, $year);
$title = date('m', $first_day);
$day_of_week = date('D', $first_day);
switch($day_of_week) {
    case "Sun": $blank = 0; break; 
    case "Mon": $blank = 1; break; 
    case "Tue": $blank = 2; break; 
    case "Wed": $blank = 3; break; 
    case "Thu": $blank = 4; break; 
    case "Fri": $blank = 5; break; 
    case "Sat": $blank = 6; break; 
}

$days_in_month = cal_days_in_month(0, $month, $year);

echo "<table border=1 width=100%>";
echo "<tr height=50px><th colspan=7>$year 年 $title 月</th></tr>";
echo "<tr height=40px><th width=42>日</th><th width=42>一</th><th width=42>二</th><th width=42>三</th><th width=42>四</th><th width=42>五</th><th width=42>六</th></tr>";

$day_count = 1;
echo "<tr>";
while ( $blank > 0 ) {
    echo "<td></td>";
    $blank = $blank-1;
    $day_count++;
}

$day_num = 1;
while ( $day_num <= $days_in_month ) {
    $selectSql = "SELECT * FROM ecvbr_data";
    $memberData = $connect->query($selectSql);
    $result = '';
    while ($row = $memberData->fetch_assoc()) {
        $timestamp = strtotime($row["check-in_date"]);
        $data_day = date('d', $timestamp);
        $data_year = date('Y', $timestamp);
        $data_month = date('m', $timestamp);
        if ($data_year == $year) {
            if ($data_month == $month) {
                if ($data_day == $day_num) {
                    $result = $result.'<br> '.$row['department'].' '.$row['space'].'<br>('.$row['space_time'].' ~ '.$row['space_time_range'].')';
                }
            }
        }        
    }
    echo "<td valign='top' height='111px'>$day_num$result</td>";
    $day_num++;
    $day_count++;
    if ($day_count > 7) {
        echo "</tr><tr>";
        $day_count = 1;
    }
}

while ( $day_count >1 && $day_count <=7 ) {
    echo "<td></td>";
    $day_count++;
}

echo "</tr></table>";