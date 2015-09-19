<?php

class Model_main extends Model {

    protected $user_tablename = "users";
    protected $access_log_tablename = 'access_log';

    public function get_data() {

        session_start();
        require_once 'libraries/connect_db.class.php';

        if ($_POST['exit'] == 'Выход') {
            $_SESSION['nickname'] = null;
            $_SESSION['userpassword'] = null;
            $_SESSION['user_id'] = null;
            session_destroy();
            unset($_POST);
        }
        if ($_SESSION['nickname'] && $_SESSION['userpassword']) {

            $nickname = $_SESSION['nickname'];
            $filename = basename($_SERVER['SCRIPT_FILENAME']);

            $ObjDb = new connect_db();
            $link_id = $ObjDb->db_connect();
            
            $nickname = iconv('utf-8', 'windows-1251', $nickname);
            $query = "select user_id from $this->user_tablename where nickname='$nickname'";
            
            $result = mysql_query($query);
            $query_data = mysql_fetch_assoc($result);
            $user_id = $query_data['user_id'];
            
            $query = "SELECT user_id FROM $this->access_log_tablename WHERE page = '$filename' AND user_id = '$user_id'";
            
            $result = mysql_query($query);

            if (!mysql_num_rows($result))
                $query = "INSERT INTO $this->access_log_tablename VALUES ('$filename', '$user_id', 1, NULL)";
            else
                $query = "UPDATE $this->access_log_tablename SET visitcount = visitcount + 1, accessdate = NULL WHERE page = '$filename' AND user_id = '$user_id'";
            $result = mysql_query($query);
            $num_rows = mysql_affected_rows($link_id);
            if ($num_rows != 1)
                die($ObjDb->sql_error());

            $ObjDb->db_close();
        }
    }
}

?>
