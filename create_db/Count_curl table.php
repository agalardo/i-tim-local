<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<?php 
include_once "../libraries/connect_db.class.php";



$ObjDb = new connect_db();
$link_id = $ObjDb->db_connect();
if(!$link_id){
    die($ObjDb->sql_error());
}

# Создание таблицы 'count_curl'(число обращений скрипта к поисковику)
$tablename='count_curl';

$table_def="user_id SMALLINT NOT NULL,";
$table_def.="count SMALLINT NOT NULL,";
$table_def.="seacher VARCHAR(50) BINARY NOT NULL,";
$table_def.="accessdate DATETIME NOT NULL,";
$table_def.="PRIMARY KEY (user_id, seacher),";
$table_def.="FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE";

if(!mysql_query("CREATE TABLE $tablename ($table_def)  ENGINE=InnoDB;")) die($ObjDb->sql_error());

echo "Таблица $tablename была успешно создана.<br />";

$ObjDb->db_close();
?>
