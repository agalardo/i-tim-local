<?php

class PassEmailSaver {

    protected $user_tablename = "users";
    protected $user_id;
    protected $email;
    protected $pass0;
    protected $pass1;
    protected $pass2;
    protected $pass;
    
    function __construct() {
        session_start();
        if(strip_tags($_GET['email'])){
            $this->email = strip_tags($_GET['email']);
        }
        if(strip_tags($_GET['pass0'])){
           $this->pass0 = strip_tags($_GET['pass0']);
        }
        if(strip_tags($_GET['pass1'])){
           $this->pass1 = strip_tags($_GET['pass1']);
        }
        if(strip_tags($_GET['pass2'])){
           $this->pass2 = strip_tags($_GET['pass2']);
        }
        if(strip_tags($_GET['pass'])){
           $this->pass = strip_tags($_GET['pass']);
        }
        $this->user_id = $_SESSION['user_id'];
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

    function save_pass() {
        
        if($this->pass1 == null && $this->pass2 == null){
            return;
        }
        if (strlen($this->pass1) < 5) {
            self::error_message("Короткий пароль!");
        }
        /* if(empty($password1))
          {error_message("Введите пароль снова для проверки!");
          } */
        if (strlen($this->pass2) < 5) {
            self::error_message("Короткий пароль!");
        }
        /* if(empty($password2))
          {error_message("Введите пароль снова для проверки!");
          } */
        
        if ($this->pass1 != $this->pass2) {
            self::error_message("Пароли не совпадают!");
        }
        if ($this->pass != $_SESSION['password']) {
            self::error_message("Ключевой пароль не верен!");
        }

        $ObjDb = new connect_db();
        $ObjDb->db_connect();
        
        $query = "SELECT userpassword FROM $this->user_tablename WHERE user_id = '$this->user_id'";
        $result = mysql_query($query);
        if (!$result) {
            Model_register::error_message(mysql_error());
        }
        $query_data = mysql_fetch_array($result);
        $userpassword = $query_data['userpassword'];
      
         if ($this->pass0 != $userpassword) {
            self::error_message("Пароль не верен!");
        }
               
        $query = "START TRANSACTION;";
        $result = mysql_query($query);
        if (!$result) {
            self::error_message(mysql_error());
        }

        $query = "UPDATE $this->user_tablename SET userpassword = '$this->pass1' WHERE user_id = '$this->user_id'";
        
        $result = mysql_query($query);
        if (!$result) {
            self::error_message(mysql_error());
        }
        
        $query = "COMMIT;";
        $result = mysql_query($query);
        if (!$result) {
            self::error_message(mysql_error());
        }

        $ObjDb->db_close();

        $_SESSION['userpassword'] = $this->pass1;
        echo'Пароль успешно изменен.';
    }
    
    function save_email() {
        
        if($this->email == null){
            return;
        }
        if (empty($this->email)) {
            self::error_message("Введите свой e-mail-адрес!");
        }
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) == false) {
            self::error_message("Неверный формат e-mail-адреса!");
        }
        if ($this->pass != $_SESSION['password']) {
            self::error_message("Ключевой пароль не верен!");
        }
        $ObjDb = new connect_db();
        $ObjDb->db_connect();
        
        if (self::in_use_email($this->email)) {
            self::error_message("E-mail $this->email уже используется. Пожалуйста, выберите другой.");
        }
        
        $query = "START TRANSACTION;";
        $result = mysql_query($query);
        if (!$result) {
            self::error_message(mysql_error());
        }

        $query = "UPDATE $this->user_tablename SET email = '$this->email' WHERE user_id = '$this->user_id'";
        
        $result = mysql_query($query);
        if (!$result) {
            self::error_message(mysql_error());
        }
        
        $query = "COMMIT;";
        $result = mysql_query($query);
        if (!$result) {
            self::error_message(mysql_error());
        }

        $ObjDb->db_close();

        echo'Email успешно изменен.';
    }
    
    function error_message($msg) {
        $str = "Error: $msg";
        echo $str;
        exit;
    }
}
include_once "../../libraries/authentication.class.php";
$authentication = new authentication();
$auth = $authentication->do_authentication();
if($auth == 1) die("Аутентификация не пройдена!");
        
$obj = new PassEmailSaver();
$obj->save_pass();

$obj->save_email();

?>
