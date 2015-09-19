<?php

header('Content-Type: text/html; charset=UTF-8');
?>
<?php

$dbname = 'i_tim';
$dbhost = 'localhost';
$dbusername = 'root';
$dbuserpassword = '';

$link_id = mysql_connect($dbhost, $dbusername, $dbuserpassword);

if(!$link_id)die("Не удалось подключиться к хосту $dbhost");

if (!mysql_query("CREATE DATABASE $dbname")){
    die("Не удалось создать базу $dbname");
}

echo "База данных $dbname успешно создана.<br>";

mysql_close($link_id);
?>
