<?php

class Model_register extends Model {

    protected $user_tablename = "users";
    protected $count_tablename = "count_curl";
    protected $seacher = array('yandex', 'google');

    function in_use_nickname($nickname) {
        $query = "SELECT nickname FROM $this->user_tablename WHERE nickname='$nickname'";
        $result = mysql_query($query);
        if (!mysql_num_rows($result)) {
            return 0;
        } else {
            return 1;
        }
    }

    function in_use_email($email) {
        global $user_tablename;
        $query = "SELECT email FROM $this->user_tablename WHERE email='$email'";
        $result = mysql_query($query);
        if (!mysql_num_rows($result)) {
            return 0;
        } else {
            return 1;
        }
    }

    function create_account() {
        $nick = strip_tags($_POST['nick']);
        $day = strip_tags($_POST['date0']);
        $month = strip_tags($_POST['date1']);
        $year = strip_tags($_POST['date2']);

        $password1 = strip_tags($_POST['password1']);
        $password2 = strip_tags($_POST['password2']);
        $sex = strip_tags($_POST['sex']);
        $email = strip_tags($_POST['email']);
        $password = strip_tags($_POST['password']);

        if (empty($nick)) {
            self::error_message("Введите свой ник!");
        }
        if (strlen($password1) < 5) {
            self::error_message("Короткий пароль!");
        }
        /* if(empty($password1))
          {error_message("Введите пароль снова для проверки!");
          } */
        if (strlen($password2) < 5) {
            self::error_message("Короткий пароль!");
        }
        /* if(empty($password2))
          {error_message("Введите пароль снова для проверки!");
          } */
        if (empty($email)) {
            self::error_message("Введите свой e-mail-адрес!");
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            self::error_message("Неверный формат e-mail-адреса!");
        }
        if ($sex == null) {
            self::error_message("Укажите ваш пол!");
        }
        if ($password1 != $password2) {
            self::error_message("Пароли не совпадают!");
        }
        if ($password != $_SESSION['password']) {
            self::error_message("Ключевой пароль не верен!");
        }

        $ObjDb = new connect_db();
        $ObjDb->db_connect();
        if (Model_register::in_use_nickname($nick)) {
            Model_register::error_message("Ник $nick уже используется. Пожалуйста, выберите другой.");
        }
        if (Model_register::in_use_email($email)) {
            Model_register::error_message("E-mail $email уже используется. Пожалуйста, выберите другой.");
        }

        $nick = iconv('utf-8', 'windows-1251', $nick);
        $password1 = iconv('utf-8', 'windows-1251', $password1);
        $email = iconv('utf-8', 'windows-1251', $email);

        $query = "START TRANSACTION;";
        $result = mysql_query($query);
        if (!$result) {
            Model_register::error_message(mysql_error());
        }

        $query = "INSERT INTO $this->user_tablename VALUES(NULL,'$nick','$sex','$password1','$email')";

        $result = mysql_query($query);
        if (!$result) {
            Model_register::error_message(mysql_error());
        }

        $query = "SELECT user_id FROM $this->user_tablename WHERE nickname = '$nick' AND userpassword = '$password1'";
        $result = mysql_query($query);
        if (!$result) {
            Model_register::error_message(mysql_error());
        }
        $query_data = mysql_fetch_array($result);
        $user_id = $query_data['user_id'];

        foreach ($this->seacher as $value) {
            $query = "INSERT INTO $this->count_tablename VALUES('$user_id','0','$value',CURRENT_TIMESTAMP)";
            $result = mysql_query($query);
            if (!$result) {
                Model_register::error_message(mysql_error());
            }
        }

        $query = "COMMIT;";
        $result = mysql_query($query);
        if (!$result) {
            Model_register::error_message(mysql_error());
        }

        $ObjDb->db_close();

        $nick = iconv('windows-1251', 'utf-8', $nick);
        $password1 = iconv('windows-1251', 'utf-8', $password1);

        $_SESSION['user_id'] = $user_id;
        $_SESSION['nickname'] = $nick;
        $_SESSION['userpassword'] = $password1;


        $_SESSION["redirect"] = 1;
    }

    function error_message($msg) {
        $head = '<html><head></head><body>';
        $str = "<script>alert(\"Error: $msg\");history.go(-1);</script>";
        $footer = '</body></html>';
        echo $head . $str . $footer;
        exit;
    }

    public function get_data() {
        session_start();
        include_once "libraries/connect_db.class.php";

        if ($_SESSION["redirect"] == 1) {
            switch ($_POST['action']) {
                case"register":
                    Model_register::create_account();
                    $_SESSION["redirect"] = 0;
                    return 1;
                    break;
                default:
                    $host = 'http://' . $_SERVER['HTTP_HOST'];
                    header('Location:' . $host);
                    break;
            }
        } elseif ($_SESSION["redirect"] == 0) {
            return 1;
        }
    }

}

?>
