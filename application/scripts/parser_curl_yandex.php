<?php

if ($_GET['submit'] == null) {
    echo"<h2>Задайте параметры поиска!</h2>";
    exit;
}
date_default_timezone_set ("Etc/GMT-3");
//Etc/GMT-3 = UTC+3 задаем временную зону. PHP выдает на 1 час больше. В mysql верно.

$time_start = time();
set_time_limit(360);
$delay[] = intval($_GET['time']);
$delay[] = intval($_GET['randomTime']);

$shuffle = 1; //Перемешиваем запросы
$url = $_GET['url'];
$temp = explode(".", $url);
$seacher = $temp[0];
unset($temp);
$depth = $_GET['depth'];
$user_id = $_GET['user_id'];
$depth = ceil($depth / 10);
$depthNF = $depth; //Глубина поиска не обнаруженых или выпадающих результатов. Поиск снизу
$countRequest = 0; //Число обращений к поисковой системе
$countOfRequest = 0; //Число запросов за 12 часовой период(из базы)
$searchRequire = 0; //-------------0 поиск осуществлялся в ближайшие 12 часов - вывести сообщение
$iterationBool = 0; //------------- функция iteration() не выполнялась - пауза между сайтами не нужна
$searchBool = 0; //----------------решение о поиске
$endUrl = ''; //---------------последний url при редиректах
$maxCountOfRequest = 300; //-----------максимальное число обращений к поисковику(ограничение)
$delta = 1 * 1 * 60; //--------------интервал паузы между поисками
$deltaC = 2 * 60 * 60; //--------------интервал паузы после появления капчи
$deltaDb = 3 * 30 * 24 * 60 * 60; //---------срок хранения записей статистики 3 месяца
$k = 7; // запрос к поисковой системе раз в 7 секунд 
$resultMain = array('time' => 0, 'captcha' => 0); //Массив заполняемый результатами Main()



require_once('ya_parser_curl.php');
require_once('function.php');
require_once('../../libraries/idna_convert.class/idna_convert 0.8.0.1/idna_convert.class.php');

//-------------------------------работа с таблицей данных--------------------------------
require_once "../../libraries/connect_db.class.php";

$site_tablename = 'site_keyword';
$result_tablename = 'parse_result';
$count_tablename = 'count_curl';
$user_tablename = "users";
$activity_tablename = "activity";
$captcha_tablename = 'captcha';

$ObjDb = new connect_db();
$link_id = $ObjDb->db_connect();

//-------------------------------------------------------------------------------
//Прооверяем время простоя и решаем доступен ли поиск
//
//$query = "SELECT count, accessdate FROM $count_tablename WHERE seacher = '$seacher' ORDER BY accessdate DESC";
//$result = mysql_query($query);
//if (!$result) {
//    error_message(mysql_error());    
//}
////берем первую запись
//$query_data = mysql_fetch_array($result);
//$accessdate = $query_data['accessdate'];
//$count = $query_data['count'];
//
////отменено зимнее время
//$todayU = intval(date("U")) + (!date('I')) * 60 * 60;
//$accessU = intval(strtotime($accessdate));
//
//$diffAvail = $todayU - $accessU;
//$deltaAvail = $k * $count;
//
//if ($deltaAvail > $diffAvail) {
//    die("3");
//}
//-------------------------------------------------------------------------------
//------------------------Получаем из $_GET['data'] данные для запросов-----------------
$Data_text = $_GET['data'];

//------------------------json_decode работает только с UTF-8----------------------------
$Data = json_decode($Data_text, true);

//if (!is_array($Data)) {
//    $Data_text = iconv('windows-1251', 'utf-8', $Data_text);
//    $Data = json_decode($Data_text, true);
//}

//-------------------------получение запросов из таблицы и запись в $Data----------------
foreach ($Data as $SiteKeyword) {
    $site = $SiteKeyword[0];
    $k = count($SiteKeyword) - 1;
    if ($k == 0)
        continue;
//    $site = iconv('utf-8', 'windows-1251', $site);
    $query = "SELECT keyword FROM $site_tablename WHERE user_id = '$user_id' AND site = '$site'";
    $result = mysql_query($query);
    if (!$result) {
        error_message(mysql_error());
    }
    for ($i = 0; $i < mysql_num_rows($result); $i++) {
        $query_data = mysql_fetch_array($result);
        $keyword = $query_data['keyword'];
        if ($keyword == null && mysql_num_rows($result) == 1) {
            $KeywordData = array();
        } else {
//            $KeywordData[] = iconv('windows-1251', 'utf-8', $keyword);
            $KeywordData[] = $keyword;
        }
    }
//    $site = iconv('windows-1251', 'utf-8', $site);
    array_unshift($KeywordData, $site);
    $temp[] = $KeywordData;

    unset($KeywordData);
}
$Data = $temp;
unset($temp);
//---------Проверяем количество сохраненных запросов в базе----------------------------------------------
$temp=array(0,0);//счетчик запросов
foreach ($Data as $value) {
    foreach ($value as $key => $val) {
        if ($key == 0) {
            $temp[0]++;
        } else {
            $temp[1]++;
        }
    }
}
if($temp[1]==0){
    die("Список сохраненных сайтов или запросов пуст!");
}

unset($temp);
//--------------------------------------------------------------------------------------------
//--------------------проверяем countRequest--------------------------------------------------
# обнуляем счетчик если перерыв больше delta

$query = "SELECT count FROM $count_tablename WHERE user_id = '$user_id' AND seacher = '$seacher'";
$result = mysql_query($query);
if (!$result) {
    error_message(mysql_error());
}
$query_data = mysql_fetch_array($result);
$countOfRequest = $query_data['count'];

$query = "SELECT accessdate FROM $count_tablename WHERE user_id = '$user_id' AND seacher = '$seacher'";
$result = mysql_query($query);
if (!$result) {
    error_message(mysql_error());
}
$query_data = mysql_fetch_array($result);
$accessdate = $query_data['accessdate'];

//$todayU = intval(date("U")) + (!date('I')) * 60 * 60;
$todayU = intval(date("U"));
$accessU = intval(strtotime($accessdate));

// в россии нет зимнего времени? 2015.06
//if (!date('I'))
//    $winter = 1;
//else
//    $winter = 0;
//$todayDate = mktime(date("H") + $winter, date("i"), date("s"), date("m"), date("d"), date("Y"));
$todayDate = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
$accessdate = date("Y-m-d H:i:s", $todayDate);

$diff = $todayU - $accessU;

if ($diff > $delta) {
    $countOfRequest = 0;
    $query = "UPDATE $count_tablename SET count = '0', accessdate = '$accessdate' WHERE user_id = '$user_id' AND seacher = '$seacher'";
    $result = mysql_query($query);

    if (!$result) {
        error_message(mysql_error());
    }
}
if ($countOfRequest >= $maxCountOfRequest) {
    $diff = $delta - $diff;
    $houer = floor($diff / (60 * 60));
    $min = floor($diff / 60) - $houer * 60;
    $sec = $diff - $houer * 60 * 60 - $min * 60;
    $time = $houer . ":" . $min . ":" . $sec;
    die($time);
}
//----------------------------------------------------------------------
//-----------------Проверяем не запущен ли скрипт поиска----------------
$query = "SELECT bool FROM $activity_tablename WHERE seacher = '$seacher'";
$result = mysql_query($query);
if (!$result) {
    error_message(mysql_error());
}
$query_data = mysql_fetch_array($result);
$activity = $query_data['bool'];
if ($activity == 1) {
    die("В данный момент доступ к поиску ограничен");
}
//-----------------Проверяем не заблокирован ли поиск Yandex----------------
//$query = "SELECT bool, accessdate FROM $captcha_tablename WHERE seacher = '$seacher'";
//$result = mysql_query($query);
//if (!$result) {
//    error_message(mysql_error());
//}
//$query_data = mysql_fetch_array($result);
//$captcha = $query_data['bool'];
//$captchadate = $query_data['accessdate'];
//
//$todayCU = intval(date("U")) + (!date('I')) * 60 * 60;
//$accessCU = intval(strtotime($captchadate));
//$diffC = $todayCU - $accessCU;
//
//if ($captcha == 1 && $diffC < $deltaC) {
//
//    $PauseC = gmdate('H:i:s', abs($deltaC - $diffC));
//    die("Работа сервиса приостановлена на $PauseC из-за блокировки Yandex");    
//} elseif ($captcha == 1 && $diffC >= $deltaC) {
//
//    $query = "UPDATE $captcha_tablename SET bool = '0' WHERE seacher = '$seacher'";
//    $result = mysql_query($query);
//
//    if (!$result) {
//        error_message(mysql_error());
//    }
//}
//---------------------Говорим что Начинаем парсинг---------------------
$query = "UPDATE $activity_tablename SET bool = '1' WHERE seacher = '$seacher'";
$result = mysql_query($query);

if (!$result) {
    error_message(mysql_error());
}

//-------------------Обход массива с данными $Data и выполнение запросов----------------

if ($shuffle == 1) {
    shuffle($Data); //мешаем элементы
}

for ($i = 0; $i < count($Data); $i++) {
    $value = $Data[$i];
    $site = $value[0];

    $keywords = array_slice($value, 1); //Выбирает срез массива
    if (count($keywords) == 0)
        continue; //Случай задания сайта без ключевых слов

    if ($shuffle == 1) {
        shuffle($keywords); //мешаем запросы
    }
    if ($i != 0 && $iterationBool == 1) {
        sleep($delay[0] + rand(0, $delay[1])); //задержка для всех сайтов, кроме певого 		
    } else {
        $time_1 = $time_start;
    }
    $iterationBool = 0;

    $resultMain = Main($depth, $url, $keywords, $site, $time_1, $delay);

    $time_2 = $resultMain['time'];

    /*
     * Расчеты времени работы скрипта заморожены со времени проекта Technoid/diplom
     */
    $time_1 = $time_2;
}

//---------------------Говорим что закончили парсинг---------------------
//$query = "UPDATE $activity_tablename SET bool = '0' WHERE seacher = '$seacher'";
//$result = mysql_query($query);
//
//if (!$result) {
//    error_message(mysql_error());
//}

//$searchRequire == 0 поиск осуществлялся в ближайшие 12 часов вывести сообщение
if ($searchRequire == 1)
    echo 1;
else
    echo 2;

//-------------------------Сохраняем значение числа запросов в базу-------------
//$query = "UPDATE $count_tablename SET count = '$countOfRequest' WHERE user_id = '$user_id' AND seacher = '$seacher'";
//$result = mysql_query($query);
//
//if (!$result)
//    error_message(mysql_error());
//----------------------------Чистка статистика, удаление записей страше 3 месяцев--------------------
// echo'<pre>';
//    print_r($Data);
//    echo'</pre>';

for ($i = 0; $i < count($Data); $i++) {
    $value = $Data[$i];
    $site = $value[0];


    $keywords = array_slice($value, 1); //Выбирает срез массива
    if (count($keywords) == 0)
        continue; //Случай задания сайта без ключевых слов

    foreach ($keywords as $keyword) {

//        $keyword = iconv('utf-8', 'windows-1251', $keyword);
//        $site = iconv('utf-8', 'windows-1251', $site);

        $query = "SELECT keyword_id FROM $site_tablename WHERE site = '$site' AND user_id = '$user_id' AND keyword = '$keyword'";

//        $site = iconv('windows-1251', 'utf-8', $site);

        $result = mysql_query($query);
        if (!$result) {
            error_message(mysql_error());
        }
        $query_data = mysql_fetch_array($result);
        $keyword_id = $query_data['keyword_id'];

        $query = "SELECT result_id, accessdate FROM $result_tablename WHERE keyword_id = '$keyword_id' AND seacher = '$url' ORDER BY accessdate ASC";

        $result = mysql_query($query);
        if (!$result) {
            error_message(mysql_error());
        }

        for ($j = 1; $j < mysql_num_rows($result); $j++) {

            $query_data = mysql_fetch_array($result);
            $accessdate = $query_data['accessdate'];
            $result_id = $query_data['result_id'];

            $accessU = intval(strtotime($accessdate));

            $diff = $todayU - $accessU;

            if ($diff > $deltaDb) {
                $query = "DELETE FROM $result_tablename WHERE result_id = '$result_id'";

                $result_d = mysql_query($query);
                if (!$result_d) {
                    error_message(mysql_error());
                }
            }
            else
                break;
        }
    }
}

//------------------------------------------------------------------------------------------	
//echo"Общее время выполнения<br />";
//time_execution($time_start,$time_2);
//echo"<br/>Количество просмотренных страниц: $countOfRequest<br/>";
//echo "</br>".$countRequest."query";
?>