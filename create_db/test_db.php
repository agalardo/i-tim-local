<?php

$dbhost = 'localhost';
$dbusername = 'technoid';
$dbuserpassword = 'technoid123';

$link_id = mysql_connect($dbhost, $dbusername, $dbuserpassword);
if (!$link_id)
    die("Не удалось подключится к узлу $dbhost");
else{
    echo "Подключение успешно установлено ".$link_id;
}
?>
