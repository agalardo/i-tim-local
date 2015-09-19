<?php

class Model_user extends Model {

    protected $user_tablename = "users";
    protected $site_tablename = 'site_keyword';
    protected $result_tablename = 'parse_result';
    protected $user_id;
    protected $email;
    protected $userpassword;
    protected $nickname;

    function __construct() {
        include_once "libraries/connect_db.class.php";

        $this->userpassword = $_SESSION['userpassword'];
        $this->nickname = $_SESSION['nickname'];

        $nickname = $this->nickname;
        $userpassword = $this->userpassword;

        $ObjDb = new connect_db();
        $link_id = $ObjDb->db_connect();
        $query = "SELECT * FROM $this->user_tablename WHERE nickname = '$nickname' AND userpassword = '$userpassword'";
        $result = mysql_query($query);
        $query_data = mysql_fetch_assoc($result);

        $this->user_id = $query_data['user_id'];
        $this->email = $query_data['email'];
        $ObjDb->db_close();
    }

    public function get_data() {
        
        return array('user_id' => $this->user_id, 'email' => $this->email, 'userpassword' => $this->userpassword);
    }

}

?>
