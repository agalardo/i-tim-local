<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<?php

include_once "../libraries/connect_db.class.php";

$ObjDb = new connect_db();
$link_id = $ObjDb->db_connect();
if (!$link_id) {
    die($ObjDb->sql_error());
}

$user_tablename = "users";
$count_tablename = "count_curl";
$seacher = array('yandex', 'google');

$query = "SELECT user_id FROM $user_tablename";
$result = mysql_query($query);
if (!$result) {
    die($ObjDb->sql_error());
}


for ($i = 0; $i < mysql_num_rows($result); $i++) {
    
    $query_data = mysql_fetch_array($result);
    $user_id = $query_data['user_id'];

    foreach ($seacher as $value) {
        $query = "INSERT INTO $count_tablename VALUES('$user_id','0' ,'$value', CURRENT_TIMESTAMP)";
        $result2 = mysql_query($query);
        if (!$result2) {
            die($ObjDb->sql_error());
        }
    }
}
?>
