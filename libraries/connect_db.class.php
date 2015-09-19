<?php

//Class for sample_db

Class Connect_db {

    protected $dbname = "i_tim";
    protected $dbhost = 'localhost';
    protected $dbusername = 'root';
    protected $dbuserpassword = '';
    protected $MYSQL_ERRNO = '';
    protected $MYSQL_ERROR = '';
    protected $link_id = '';

    function __construct($dbhost=null, $dbusername=null, $dbuserpassword=null, $dbname=null) {

        if($dbhost!=null && $dbusername!=null && $dbuserpassword!=null && $dbname!=null) {
            $this->dbhost = $dbhost;
            $this->dbusername = $dbusername;
            $this->dbuserpassword = $dbuserpassword;
            $this->dbname = $dbname;
        }
    }

    function db_connect() {
        $this->link_id = mysql_connect($this->dbhost, $this->dbusername, $this->dbuserpassword);
        if (!$this->link_id) {
            $this->MYSQL_ERRNO = 0;
            $this->MYSQL_ERROR = "Не удалось подключиться к хосту $this->dbhost.";
            return 0;
        } else if (!empty($this->dbname) && !mysql_select_db($this->dbname)) {
            $this->MYSQL_ERRNO = mysql_errno();
            $this->MYSQL_ERROR = mysql_error();
            return 0;
        }
        else
            return $this->link_id;
    }

    function db_close() {
        mysql_close($this->link_id);
    }

    function sql_error() {
        if (empty($this->MYSQL_ERROR)) {
            $this->MYSQL_ERRNO = mysql_errno();
            $this->MYSQL_ERROR = mysql_error();
        }
        return "$this->MYSQL_ERRNO: $this->MYSQL_ERROR";
    }

}

?>