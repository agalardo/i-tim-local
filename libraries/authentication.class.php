<?php

class authentication {

    protected $user_tablename = "users";
    protected $access_log_tablename = 'access_log';
    protected $site_tablename = 'site_keyword';
    protected $count_tablename = "count_curl";
    protected $exclude_dirs = array(); //Если директория в этом массиве то в ней не происходит авторизация
    protected $exclude_files = array(); //Если файл в этом массиве то для него не происходит авторизация
    protected $nickname;
    protected $userpassword;

    function __construct($nickname, $userpassword) {
        session_start();
        include_once "connect_db.class.php";
        $this->nickname = $nickname;
        $this->userpassword = $userpassword;
    }

    function do_authentication() {

        $filename = basename($_SERVER['SCRIPT_FILENAME']);
        $PHP_SELF = $_SERVER['PHP_SELF'];

        if (!$_SESSION['nickname']) {
            if (!isset($this->nickname)) {
                authentication::login_form();
                exit;
            } else {
                $_SESSION['userpassword'] = $this->userpassword;
                $_SESSION['nickname'] = $this->nickname;
            }
            
            $nickname = iconv('utf-8', 'windows-1251', $this->nickname);
            $userpassword = iconv('utf-8', 'windows-1251', $this->userpassword);

            $ObjDb = new connect_db();
            $link_id = $ObjDb->db_connect();

            $query = "SELECT * FROM $this->user_tablename WHERE nickname = '$nickname' AND userpassword = '$userpassword'";
            $result = mysql_query($query);
            $query_data = mysql_fetch_assoc($result);

            $user_id = $query_data['user_id'];
            $_SESSION['user_id'] = $user_id;

            if (!mysql_num_rows($result)) {
                $_SESSION['nickname'] = null;
                $_SESSION['userpassword'] = null;
                $_SESSION['user_id'] = null;
                return 1;
            } else {
                $query = "SELECT user_id FROM $this->access_log_tablename WHERE page = '$filename'
        AND user_id = '$user_id'";
                $result = mysql_query($query);
                if (!mysql_num_rows($result)) {
                    $query = "INSERT INTO $this->access_log_tablename VALUES ('$filename', '$user_id', 1, NULL)";
                } else {
                    $query = "UPDATE $this->access_log_tablename SET visitcount = visitcount + 1, accessdate = NULL 
        	WHERE page = '$filename' AND user_id = '$user_id'";
                }
                mysql_query($query);
                $num_rows = mysql_affected_rows($link_id);

                if ($num_rows != 1)
                    die(sql_error());
            }
            $ObjDb->db_close();
        }
        elseif ($_SESSION['nickname'] && $_SESSION['userpassword']) {
            $nickname = $_SESSION['nickname'];
            $filename = basename($_SERVER['SCRIPT_FILENAME']);

            $ObjDb = new connect_db($this->dbhost, $this->dbusername, $this->dbuserpassword, $this->dbname);
            $link_id = $ObjDb->db_connect();

            $nickname = iconv('utf-8', 'windows-1251', $nickname);

            $query = "select user_id from users where nickname='$nickname'";
            $result = mysql_query($query);
            $query_data = mysql_fetch_assoc($result);
            $user_id = $query_data['user_id'];

            $nickname = iconv('windows-1251', 'utf-8', $nickname);

            $query = "SELECT user_id FROM $this->access_log_tablename WHERE page = '$filename' AND user_id = '$user_id'";

            $result = mysql_query($query);

            if (!mysql_num_rows($result))
                $query = "INSERT INTO $this->access_log_tablename VALUES ('$filename', '$user_id', 1, NULL)";
            else
                $query = "UPDATE $this->access_log_tablename SET visitcount = visitcount + 1, accessdate = NULL 
        WHERE page = '$filename' AND user_id = '$user_id'";
            mysql_query($query);
            $num_rows = mysql_affected_rows($link_id);
            if ($num_rows != 1)
                die(sql_error());

            $ObjDb->db_close();
        }
    }

    function login_form() {
        $host = 'http://' . $_SERVER['HTTP_HOST'];
        header('Location:' . $host);
    }

}

