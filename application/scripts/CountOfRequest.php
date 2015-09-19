<?php

require_once "../../libraries/connect_db.class.php";

session_start();
$user_id = $_SESSION['user_id'];

$count_tablename = "count_curl";
$seacher = array("google", "yandex");

////Получаем число доступных запросов
$ObjDb = new connect_db();
$ObjDb->db_connect();

foreach ($seacher as $value) {
    $query = "SELECT count FROM $count_tablename WHERE user_id = '$user_id' AND seacher = '$value'";
    $result = mysql_query($query);
    if (!$result) {
        error_message(mysql_error());
    }
    $query_data = mysql_fetch_array($result);
    if ($value == 'yandex') {
        $countOfRequest[$value] = 300 - $query_data['count'];
    } else {
        $countOfRequest[$value] = 300 - $query_data['count'];
    }
}
$jsonTextCountOfRequest = json_encode($countOfRequest);

$ObjDb->db_close();

echo $jsonTextCountOfRequest;
?>
