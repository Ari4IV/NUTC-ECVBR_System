<?php
$id = $_POST["id"];

include "./db_link_include.php";

$insertSql = "DELETE FROM `ecvbr_data` WHERE `ecvbr_data`.`id` = $id";

if ($connect->query($insertSql) === TRUE) {
    echo "<h3>資料刪除成功</h3>";
    } else {
    echo "資料刪除錯誤: " . $insertSql . "<br>" . $connect->error;
    }

$connect->close();
?>