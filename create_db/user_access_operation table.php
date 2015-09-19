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

#TIMESTAMP(14) не работает в mysql 5.5 нужно TIMESTAMP
# создание таблица пользователей users
$user_tablename = 'users';

//$nick='kwazar';
//$sex=1;
//$password='123';
//$email='kwazar32@yandex.ru';

$user_table_def = "user_id SMALLINT NOT NULL AUTO_INCREMENT,";
$user_table_def.="nickname VARCHAR(20) BINARY NOT NULL,";
$user_table_def.="sex TINYINT NOT NULL,";
$user_table_def.="userpassword VARCHAR(20) NOT NULL,";
$user_table_def.="email VARCHAR(40) NOT NULL,";
$user_table_def.="PRIMARY KEY (user_id),";
$user_table_def.="UNIQUE (nickname,email)";

if (!mysql_query("CREATE TABLE $user_tablename ($user_table_def) ENGINE=InnoDB;"))
    die($ObjDb->sql_error());

//$query = "INSERT INTO users VALUES(NULL,'$first_name','$last_name','$nick','$sex','$password','$email','$birthday')";
//$result = mysql_query($query);
//if (!$result) {
//    error_message(mysql_error());
//}

echo "Таблица $user_tablename успешно создана.<br />";

# база статистики доступа к файлам
$user_tablename = 'access_log';

$user_table_def = "";
$user_table_def = "page VARCHAR(250) NOT NULL,";
$user_table_def.="user_id SMALLINT NOT NULL,";
$user_table_def.="visitcount MEDIUMINT(5) DEFAULT '0' NOT NULL,";
$user_table_def.="accessdate TIMESTAMP,";
$user_table_def.="PRIMARY KEY (user_id, page),";
$user_table_def.="FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE";

if (!mysql_query("CREATE TABLE $user_tablename ($user_table_def) ENGINE=InnoDB;"))
    die($ObjDb->sql_error());

//ALTER TABLE `access_log` CHANGE `userid` `user_id`

echo "Таблица $user_tablename успешно создана.<br />";

# база статистики запросов
$user_tablename = 'date_operation';

$user_table_def = "";
$user_table_def = "user_id SMALLINT NOT NULL,";
$user_table_def.="operation VARCHAR(50) NOT NULL,";
$user_table_def.="date TIMESTAMP,";
$user_table_def.="PRIMARY KEY (user_id, date),";
$user_table_def.="FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE";

if (!mysql_query("CREATE TABLE $user_tablename ($user_table_def) ENGINE=InnoDB;"))
    die($ObjDb->sql_error());

echo "Таблица $user_tablename успешно создана.";

$ObjDb->db_close();
?>
