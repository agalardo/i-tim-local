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

# Создание таблицы 'activity'(признак работы скрипта поиска)
$tablename = 'activity';

$table_def = "bool TINYINT NOT NULL,";
$table_def.="seacher VARCHAR(50) BINARY NOT NULL";

$query = "START TRANSACTION;";
$result = mysql_query($query);
if (!$result) {
    die($ObjDb->sql_error());
}

if (!mysql_query("CREATE TABLE $tablename ($table_def)  ENGINE=InnoDB;"))
    die($ObjDb->sql_error());

$seacher = array('yandex', 'google');

foreach ($seacher as $value) {
    $query = "INSERT INTO $tablename VALUES(0,'$value')";
    $result = mysql_query($query);
    if (!$result) {
        die($ObjDb->sql_error());
    }
}

echo "Таблица $tablename была успешно создана.<br />";

# Создание таблицы 'captcha'(признак работы скрипта поиска)
$tablename = 'captcha';

$table_def = "";
$table_def = "bool TINYINT NOT NULL,";
$table_def.="seacher VARCHAR(50) BINARY NOT NULL,";
$table_def.="accessdate TIMESTAMP";

if (!mysql_query("CREATE TABLE $tablename ($table_def)  ENGINE=InnoDB;"))
    die($ObjDb->sql_error());

foreach ($seacher as $value) {
    $query = "INSERT INTO $tablename VALUES(0,'$value', NULL)";
    $result = mysql_query($query);
    if (!$result) {
        die($ObjDb->sql_error());
    }
}

$query = "COMMIT;";
$result = mysql_query($query);
if (!$result) {
    die($ObjDb->sql_error());
}

echo "Таблица $tablename была успешно создана.<br />";
?>
