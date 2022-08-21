<?php
include "./db_link_include.php";
include "./header.php";
$selectSql = "SELECT * FROM ecvbr_data";
$memberData = $connect->query($selectSql);

if ($memberData->num_rows > 0) {
    echo'
        <form action="./index.html">
            <input type="submit" value="返回登記系統" />
        </form>
        <form action="./calendar.php" method="post" target="_blank">
        <input type="date" name="calendar_date">
        <input type="submit" value="行事曆產生器" />
        </form>
        <input id="myInput" onkeydown="myFunction()" onkeyup="myFunction()" style="font-size:20px" type="text" placeholder="搜尋..."/>
        <table id="myTable" width="100%" cellpadding="0" cellspacing="0" border="1"><tbody>
        <caption>活動中心場地借用查詢結果</caption>
        <tr>
            <th>借用單位</th>
            <th>借用人</th>
            <th>借用人電話</th>
            <th>借用日期</th>
            <th>借用時段</th>
            <th>借用空間</th>
            <th>備註</th>
            <th>管理</th>
        </tr>
        ';
    while ($row = $memberData->fetch_assoc()) {
        echo'
            <tr>
                <td>'.$row["department"].'</td>
                <td>'.$row["user_name"].'</td>
                <td>'.$row["user_number"].'</td>
                <td>'.$row["check-in_date"].'~'.$row["check-in_date_range"].'</td>
                <td>'.$row["space_time"].'~'.$row["space_time_range"].'</td>
                <td>'.$row["space"].'</td>
                <td>'.$row["remark"].'</td>
				<td align="center">
				    <form action="./delete.php" method="post" target="_blank" onsubmit="setTimeout(function () { window.location.reload(); }, 10)">
                    <input type="hidden" name="id" value="'.$row["id"].'">
                    <input type="submit" value="刪除"></form>
				</td>
            </tr>
            ';
    }
} else {
    echo '查無資料!!';
}
echo '</tbody></table>';
$connect->close();
echo '<script src="javascript.js"></script>';
?>