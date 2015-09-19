<?php

class Model_statistic extends Model {

    protected $user_tablename = "users";
    protected $site_tablename = 'site_keyword';
    protected $result_tablename = 'parse_result';
    protected $user_id;
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
        $ObjDb->db_close();
    }

    public function get_data() {
        
        if ($_POST['site'] == null && $_GET['keyword'] == null) {
            return 1;
        }
        $ObjDb = new connect_db();
        $ObjDb->db_connect();
        
        session_start();
        
        if ($_POST['site']) {
            $site = $_POST['site'];
            
            $seacher = $_POST['url'];
            $_SESSION['stat_seacher'] = $seacher;
            $_SESSION['site'] = $site;
            
        } else {
            $site = $_GET['site'];
            $seacher = $_GET['url'];
            $_SESSION['stat_seacher'] = $seacher;
            $_SESSION['site'] = $site;
        }

        if ($_GET['keyword'] != null) {
            $GetKeyword = $_GET['keyword'];
        }

//-------------------------получение запросов из таблицы и запись в $Data----------------
        //$site = iconv('utf-8', 'windows-1251', $site);
        $query = "SELECT keyword FROM $this->site_tablename WHERE user_id = '$this->user_id' AND site = '$site'";
        $result = mysql_query($query);
        if (!$result) {
            print(mysql_error());
            exit;
        }
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $query_data = mysql_fetch_array($result);
            $keyword = $query_data['keyword'];
            if ($keyword == null && mysql_num_rows($result) == 1) {
                $KeywordData = array();
            } else {
                //$KeywordData[] = iconv('windows-1251', 'utf-8', $keyword);
                $KeywordData[] = $keyword;
            }
        }
        //$site = iconv('windows-1251', 'utf-8', $site);
        if (is_array($KeywordData)) {
            array_unshift($KeywordData, $site);
        }
        $Data = $KeywordData;
        unset($KeywordData);

//------------------------получение результатов из базы и запись в $PositionData-----------------

        //$site = iconv('utf-8', 'windows-1251', $site);
        //$seacher = iconv('utf-8', 'windows-1251', $seacher);

        if (is_array($Data)) {
            foreach ($Data as $k => $keyword) {
                if ($k == 0)
                    continue;
                //$keyword = iconv('utf-8', 'windows-1251', $keyword);

                $query = "SELECT keyword_id FROM $this->site_tablename WHERE site = '$site' AND user_id = '$this->user_id' AND keyword = '$keyword'";
                $result = mysql_query($query);
                if (!$result) {
                    print(mysql_error());
                    exit;
                }
                $query_data = mysql_fetch_array($result);
                $keyword_id = $query_data['keyword_id'];

                $query = "SELECT accessdate, position, url, depth FROM $this->result_tablename WHERE keyword_id = '$keyword_id' AND seacher = '$seacher' ORDER BY accessdate ASC";
                $result = mysql_query($query);
                if (!$result) {
                    print(mysql_error());
                    exit;
                }
                //$keyword = iconv('windows-1251', 'utf-8', $keyword);

                for ($i = 0; $i < mysql_num_rows($result); $i++) {

                    $query_data = mysql_fetch_array($result);
                    $position = $query_data['position'];
                    $depth = $query_data['depth'];
                    $accessdate = $query_data['accessdate'];
                    $url = $query_data['url'];

                    $dateExpl = explode(" ", $accessdate);
                    $date = explode("-", $dateExpl[0]);
                    $time = explode(":", $dateExpl[1]);

                    $temp = array();
                    foreach ($date as $key => $value) {
                        if ($key == 1) {
                            $value--;
                        }
                        $temp[] = intval($value);
                    }
                    foreach ($time as $value) {
                        array_push($temp, $value);
                    }
                    //исключаем значения времени запросов не найденных при поиске

                    $dateArray[] = $temp;

                    unset($time);
                    unset($date);
                    //----------------глубина поиска------------------------
                    //    Откидывает результаты поиска с разницей времени менье часа
                    //    не доделано, не откидывает позиции откидывает только глубину
//                    $accessU = intval(strtotime($accessdate));
//                    $lastDateDepth = $dateDepth[count($dateDepth) - 1];
//
//                    if (is_array($lastDateDepth)) {
//                        foreach ($lastDateDepth as $key => $value) {
//                            if ($key == 0)
//                                $lastAccess = $value;
//                            if ($key == 1)
//                                $value++;
//                            if ($key > 0 && $key < 3)
//                                $lastAccess.="-" . $value;
//                            if ($key == 3)
//                                $lastAccess.=" " . $value;
//                            if ($key > 3 && $key <= 5)
//                                $lastAccess.=":" . $value;
//                        }
//                    }
//                    $lastU = intval(strtotime($lastAccess));
//                    $delta = 1 * 60 * 60;
//                    $diff = $accessU - $lastU;
//
//                    if (($positionDepth[count($positionDepth) - 1] != $depth && $diff > 0) || $diff > $delta) {
//                        $dateDepth[] = $temp;
//                        $positionDepth[] = $depth;
//                    }
                    //------------------------------------------------------------------------------------
                    $dateDepth[] = $temp;
                    $positionDepth[] = $depth;
                    
                    unset($temp);
                    //-------------------------------------------------------
                    if ($url != NULL) {
//                        $positionArray[] = iconv('windows-1251', 'utf-8', $position);
                        $positionArray[] = $position;
                    }
                    else
                        $positionArray[] = NULL;
                    $urlArray[] = $url;
                }
                if (!(mysql_num_rows($result) == 0)) {
                    $PositionData[] = array($keyword, $positionArray, $dateArray, $urlArray);
                }
                unset($positionArray);
                unset($dateArray);

                if (count($positionDepth) > 0) {
                    $DepthData[] = array('Глубина поиска', $positionDepth, $dateDepth);
                }
                unset($dateDepth);
                unset($positionDepth);
                unset($lastAccess);
            }
        }



        //$site = iconv('windows-1251', 'utf-8', $site);

        /* echo"<pre>";
          print_r($PositionData);
          echo"</pre>";
          exit; */

        if ($_GET['keyword']) {
            $GetKeyword = $_GET['keyword'];
            foreach ($PositionData as $k => $v) {
                if ($GetKeyword == $v[0])
                    break;
            }
            $ArrayPositionData = $PositionData;
            $PositionData = array($DepthData[$k], $ArrayPositionData[$k]);
        }
        else {
            $ArrayPositionData = $PositionData;
            $PositionData = array($DepthData[0], $ArrayPositionData[0]);
        }
        $ObjDb->db_close();
        return array('ArrayPositionData' => $ArrayPositionData, 'PositionData' => $PositionData,'site' => $site,'seacher' => $seacher);
    }

}

?>
