<?php
$department = $_POST["department"];
$checkin_date = $_POST["check-in_date"];
$checkin_date_range = $_POST["check-in_date_range"];
$user_name = $_POST["user_name"];
$user_number = $_POST["user_number"];
$space = $_POST["space"];
$space_time = $_POST["space_time"];
$space_time_range = $_POST["space_time_range"];
$remark = $_POST["remark"];
$checkpoint = 1;
include "./db_link_include.php";
include "./header.php";
$insertSql = "INSERT INTO `ecvbr_data` (`department`, `check-in_date`, `check-in_date_range`, `user_name`, `user_number`, `space`, `space_time`, `space_time_range`, `remark`) VALUES ('$department', '$checkin_date', '$checkin_date_range', '$user_name', '$user_number', '$space', '$space_time', '$space_time_range', '$remark');";
if (empty($department)) {
	echo "<h3>借用單位不能為空</h3>";
	$checkpoint = 0;
}
if (empty($user_name)) {
	echo "<h3>借用人不能為空</h3>";
	$checkpoint = 0;
}
if (empty($user_number)) {
	echo "<h3>借用人電話不能為空</h3>";
	$checkpoint = 0;
}
if (empty($checkin_date)) {
	echo "<h3>借用日期不能為空</h3>";
	$checkpoint = 0;
} else {
	if (empty($checkin_date_range)) {
		echo "<h3>借用日期範圍不能為空</h3>";
		$checkpoint = 0;
	}
}
if (empty($space_time)) {
	echo "<h3>借用時間不能為空</h3>";
	$checkpoint = 0;
} else {
	if (empty($space_time_range)) {
		echo "<h3>借用時間範圍不能為空</h3>";
		$checkpoint = 0;
	}
}
if ($space == '選擇借用空間') {
	echo "<h3>未選擇借用空間</h3>";
	$checkpoint = 0;
}

function is_time_cross($beginTime1, $endTime1, $beginTime2, $endTime2) {
	$status = $beginTime2 - $beginTime1;
	if ($status > 0) {
	  	$status2 = $beginTime2 - $endTime1;
	  	if ($status2 >= 0) {
			return false;
	  	} else {
			return true;
	  	}
	} else {
	  	$status2 = $endTime2 - $beginTime1;
	  	if ($status2 > 0) {
			return true;
	  	} else {
			return false;
	  	}
	}
}

$selectSql = "SELECT * FROM ecvbr_data";
$memberData = $connect->query($selectSql);
while ($row = $memberData->fetch_assoc()) {
	if ($space == $row["space"]) {
		if (is_time_cross(strtotime($checkin_date. $space_time), strtotime($checkin_date_range. $space_time_range), strtotime($row["check-in_date"]. $row["space_time"]), strtotime($row["check-in_date_range"]. $row["space_time_range"]))) {
			echo '<h3>時間日期與'.$row["department"].'重疊</h3>';
			$checkpoint = 0;
		}
	}
}

if ($checkpoint == 1) {
	if ($connect->query($insertSql) === TRUE) {
		echo '
		<h3>資料登記成功</h3>
		<br><br>
		<table width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<th colspan="4">活 動 中 心 場 地 借 用</th>
		</tr>
		<tr>
			<th>借 用 單 位</th>
			<td>　'.$department.'</td>
			<th>借 用 人</th>
			<td>　'.$user_name.'</td>
		</tr>
		<tr>
			<th>借 用 人 電 話</th>
			<td>　'.$user_number.'</td>
			<th>借 用 日 期</th>
			<td>　'.$checkin_date.' 至 '.$checkin_date_range.'</td>
		</tr>
		<tr>
			<th>借 用 空 間</th>
			<td>　'.$space.'</td>
			<th>借 用 時 段</th>
			<td>　'.$space_time.' 至 '.$space_time_range.'</td>
		</tr>
		<tr style="height:200px">
			<th>活 動 內 容</th>
			<td colspan="4"></td>
		</tr>
		<tr>
			<th>課 指 組 承 辦 人</th>
			<td></td>
			<th>備　　註</th>
			<td>　'.$remark.'</td>
		</tr>
		</table>
		';
		
		} else {
		echo "資料登記錯誤: " . $insertSql . "<br>" . $connect->error;
		}
} else {
	echo "<h3>資料登記失敗</h3>";
}
$connect->close();
?>