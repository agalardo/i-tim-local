<?php

class Model_services extends Model {

    protected $user_tablename = "users";
    protected $site_tablename = 'site_keyword';
    protected $count_tablename = "count_curl";
    protected $user_id;
    protected $userpassword;
    protected $nickname;
    protected $seacher = array('google.ru','yandex.ru','google.com');

    function __construct() {
        include_once "libraries/connect_db.class.php";

        $this->userpassword = $_SESSION['userpassword'];
        $this->nickname = $_SESSION['nickname'];
       
        //$nickname = iconv('utf-8', 'windows-1251', $this->nickname);
        //$userpassword = iconv('utf-8', 'windows-1251', $this->userpassword);
        $nickname = $this->nickname;
        $userpassword = $this->userpassword;

        $ObjDb = new connect_db();
        $link_id = $ObjDb->db_connect();
        $query = "SELECT * FROM $this->user_tablename WHERE nickname = '$nickname' AND userpassword = '$userpassword'";
        $result = mysql_query($query);
        $query_data = mysql_fetch_assoc($result);

        $this->user_id = $query_data['user_id'];
        $ObjDb->db_close();
    }

    public function get_data() {
        $ObjDb = new connect_db();
        $ObjDb->db_connect();

        $query = "SELECT site FROM $this->site_tablename WHERE user_id = '$this->user_id' GROUP BY site";

        $result = mysql_query($query);
        if (!$result) {
            error_message(mysql_error());
        }

        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $query_data = mysql_fetch_array($result);
            $site = $query_data['site'];
            //$SiteArray[] = iconv('windows-1251', 'utf-8', $site);
            $SiteArray[] = $site;
        }
        if (count($SiteArray) != 0) {
            $SiteArray = array_unique($SiteArray);
            $temp = $SiteArray;
            $SiteArray = array();
            foreach ($temp as $value) {
                $SiteArray[] = $value;
            }
            unset($temp);
        }



        //----------------------поиск сайтов с неуказаными запросами в таблице
        if (count($SiteArray) != 0) {
            foreach ($SiteArray as $site) {
                //$site = iconv('utf-8', 'windows-1251', $site);
                $query = "SELECT keyword FROM $this->site_tablename WHERE user_id = '$this->user_id' AND site = '$site'";

                $result = mysql_query($query);
                if (!$result) {
                    error_message(mysql_error());
                }
                for ($i = 0; $i < mysql_num_rows($result); $i++) {
                    $query_data = mysql_fetch_array($result);
                    $keyword = $query_data['keyword'];
                    if ($keyword == null) {
                        $keywordBool[] = 0;
                        break;
                    } else {
                        $keywordBool[] = 1;
                        $bool = 1;
                        break;
                    }
                }
            }
        }
        $data = array('sitearray' => $SiteArray, 'bool' => $bool, 'keywordbool' => $keywordBool, 'seacher'=>$this->seacher);
        $ObjDb->db_close();
        return $data;
    }

}

?>
