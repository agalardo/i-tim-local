<?php

class Model_parser extends Model {

    protected $user_tablename = "users";
    protected $site_tablename = 'site_keyword';
    protected $count_tablename = "count_curl";
    protected $captcha_tablename = 'captcha';
    protected $result_tablename = 'parse_result';
    protected $user_id;
    protected $userpassword;
    protected $nickname;
    protected $seacher = array("google", "yandex");
    protected $delta; //--------------интервал паузы между поисками

    function __construct() {
        include_once "libraries/connect_db.class.php";
        $this->delta = 12 * 60 * 60;
        
        $this->userpassword = $_SESSION['userpassword'];
        $this->nickname = $_SESSION['nickname'];

        //$nickname = iconv('utf-8', 'windows-1251', $this->nickname);
        //$userpassword = iconv('utf-8', 'windows-1251', $this->userpassword);
        $nickname = $this->nickname;
        $userpassword = $this->userpassword;
        
        session_start();
        $ObjDb = new connect_db();
        $link_id = $ObjDb->db_connect();
        
        if (!$link_id) {
            print($ObjDb->sql_error());
            exit;
        }
        
        $query = "SELECT * FROM $this->user_tablename WHERE nickname = '$nickname' AND userpassword = '$userpassword'";
        $result = mysql_query($query);
        if (!$result) {
            print($ObjDb->sql_error());
            exit;
        }
        $query_data = mysql_fetch_assoc($result);

        $this->user_id = $query_data['user_id'];
        $ObjDb->db_close();
    }

    public function get_data() {
        //-------------------------------работа с таблицей данных--------------------------------

        $ObjDb = new connect_db();
        $link_id = $ObjDb->db_connect();
        
        if (!$link_id) {
            print($ObjDb->sql_error());
            exit;
        }

//------------------------выбор сайтов и запросов из таблицы---------------------------------

        $query = "SELECT site FROM $this->site_tablename WHERE user_id = '$this->user_id' GROUP BY site";
        $result = mysql_query($query);
        if (!$result) {
            print($ObjDb->sql_error());
            exit;
        }

        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $query_data = mysql_fetch_array($result);
            $site = $query_data['site'];
            //$Sites[] = iconv('windows-1251', 'utf-8', $site);
            $Sites[] = $site;
        }
        if (count($Sites) != 0) {
            $Sites = array_unique($Sites);
            $temp = $Sites;
            $Sites = array();
            foreach ($temp as $value) {
                $Sites[] = $value;
            }
            unset($temp);

            foreach ($Sites as $site) {
                //$site = iconv('utf-8', 'windows-1251', $site);
                $query = "SELECT keyword FROM $this->site_tablename WHERE user_id = '$this->user_id' AND site = '$site'";
                //$site = iconv('windows-1251', 'utf-8', $site);
                $temp[] = $site;
                $result = mysql_query($query);
                if (!$result) {
                    print($ObjDb->sql_error());
                    exit;
                }
                for ($i = 0; $i < mysql_num_rows($result); $i++) {
                    $query_data = mysql_fetch_array($result);
                    $keyword = $query_data['keyword'];
                    if ($keyword == null)
                        continue;
                    //$temp[] = iconv('windows-1251', 'utf-8', $keyword);
                    $temp[] = $keyword;
                }
                $Data[] = $temp;
                unset($temp);
            }
        }
        /* echo"<pre>";
          print_r($Data);
          echo"</pre>";
          exit; */
//------------------------определение $textArray---------------------------------------
        $site = $Sites[0];
        //$site = iconv('utf-8', 'windows-1251', $site);
        $query = "SELECT keyword FROM $this->site_tablename WHERE user_id = '$this->user_id' AND site = '$site'";
        $site = $Sites[0];
        $result = mysql_query($query);
        if (!$result) {
            print($ObjDb->sql_error());
            exit;
        }
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $query_data = mysql_fetch_array($result);
            $keyword = $query_data['keyword'];
            //$textArray[] = iconv('windows-1251', 'utf-8', $keyword);
            $textArray[] = $keyword;
        }
//------------------------------------------------------------------------------
////-----------------------Получаем число доступных запросов--------------------
     
        foreach ($this->seacher as $value) {

            //---------------Проверяем перерыв между поисками-------------------
            $query = "SELECT accessdate FROM $this->count_tablename WHERE user_id = '$this->user_id' AND seacher = '$value'";
            $result = mysql_query($query);
            if (!$result) {
                print($ObjDb->sql_error());
                exit;
            }
            $query_data = mysql_fetch_array($result);
            $accessdate = $query_data['accessdate'];

            $todayU = intval(date("U"));
            $accessU = intval(strtotime($accessdate));

            
            $todayDate = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
            $accessdate = date("Y-m-d H:i:s", $todayDate);

            $diff = $todayU - $accessU;
            /*
             * Если перерыв больше $delta сбрасываем счетчик и записываем новое время
             * при последующем обновлении count в $count_tablename время не обновляется до истечения времени $delta
             */
            if ($diff > $this->delta) {
                $query = "UPDATE $this->count_tablename SET count = '0', accessdate = '$accessdate' WHERE user_id = '$this->user_id' AND seacher = '$value'";
                $result = mysql_query($query);

                if (!$result) {
                    print($ObjDb->sql_error());
                    exit;
                }
            }


            $query = "SELECT count FROM $this->count_tablename WHERE user_id = '$this->user_id' AND seacher = '$value'";
            $result = mysql_query($query);
            if (!$result) {
                print($ObjDb->sql_error());
                exit;
            }
            $query_data = mysql_fetch_array($result);
            $countOfRequest[$value] = 300 - $query_data['count'];
            // 300 максимальное число запросов отведено на полдня(можно варьировать в $delta parser_curl_yandex.php etc)
        }
        
//------------------------------------------------------------------------------------

        $data = array('Sites' => $Sites, 'textArray' => $textArray, 'Data' => $Data, 'CountOfRequest' => $countOfRequest);
        $ObjDb->db_close();
        return $data;
    }

}

?>
