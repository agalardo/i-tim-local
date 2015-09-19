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

$tablename='site_keyword';

$table_def="keyword_id SMALLINT NOT NULL AUTO_INCREMENT,";
$table_def.="user_id SMALLINT NOT NULL,";
$table_def.="site VARCHAR(50) BINARY NOT NULL,";
$table_def.="keyword VARCHAR(100) BINARY DEFAULT NULL,";
$table_def.="PRIMARY KEY (keyword_id),";
$table_def.="FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE";

//ALTER TABLE users TYPE=INNODB  без назначения такого типа внешние ключи не работают
//----раньше было FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE RESTRICT
//ALTER TABLE `parse_result` ADD FOREIGN KEY (`keyword`) REFERENCES `site_keyword`.`keyword` ON UPDATE CASCADE ON DELETE CASCADE
//ALTER TABLE `parse_result` DROP FOREIGN KEY `keyword` 

if(!mysql_query("CREATE TABLE $tablename ($table_def)  ENGINE=InnoDB;")) die($ObjDb->sql_error());

echo "Таблица $tablename была успешно создана.<br />";

# Создание таблицы parse_result(позиции по ключевым словам)
$tablename='parse_result';

$table_def="";
$table_def="result_id SMALLINT NOT NULL AUTO_INCREMENT,";
$table_def.="seacher VARCHAR(50) BINARY NOT NULL,";
$table_def.="keyword_id SMALLINT NOT NULL,";
$table_def.="url VARCHAR(100) BINARY,";
$table_def.="position MEDIUMINT(5) DEFAULT '0' NOT NULL,";
$table_def.="depth SMALLINT NOT NULL,";
$table_def.="accessdate TIMESTAMP,";
$table_def.="PRIMARY KEY (result_id),";
$table_def.="FOREIGN KEY (keyword_id) REFERENCES site_keyword(keyword_id) ON UPDATE CASCADE ON DELETE CASCADE";

if(!mysql_query("CREATE TABLE $tablename ($table_def)  ENGINE=InnoDB;")) die($ObjDb->sql_error());

//ALTER TABLE `access_log` CHANGE `userid` `user_id`

echo "Таблица $tablename была успешно создана.<br />";

$ObjDb->db_close();
?>
