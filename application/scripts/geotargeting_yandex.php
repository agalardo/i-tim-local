<?php

header('Content-Type: text/html; charset=UTF-8');
?>
<?php
session_start();
$user_id = $_SESSION['user_id'];

$url = 'yandex.ru';

//$temp = explode('/',$cookiePath);
//unset($temp[count($temp)-1]);
//$temp = implode('/', $temp);
//$cookiePath =$temp."/cookie";
//unset($temp);
//$captcha_tablename = 'captcha';
//$seacher = "yandex";
//require_once "../../libraries/connect_db.class.php";
//$ObjDb = new connect_db();
//$link_id = $ObjDb->db_connect();
//-----------------Функция для выполнения запроса------------------------
function curl($url_str, $url) {

    global $endUrl, $user_id;
    
//    $cookieFile = "cookies/cookie$user_id.txt";
    $cookieFile = "./cookie$user_id.txt";
    $host = "Host: " . $url;

    $curl = curl_init($url_str);

    $header = array($host,'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 YaBrowser/15.2.2214.3645 Safari/537.36',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language: ru,en;q=0.8',
        'Accept-Encoding: gzip, deflate, sdch',
        'Connection: keep-alive',
        'Cache-Control: max-age=0');
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_HEADER, 0); //При установке этого параметра в ненулевое значение результат будет включать полученные заголовки.
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);//отключение корневого агенства сертификата 2014 google выдал ошибку: ssl certificate problem  
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);   // переходит по редиректам	
    curl_setopt($curl, CURLOPT_ENCODING, "gzip, deflate");
    curl_setopt($curl, CURLOPT_AUTOREFERER, true); //TRUE для автоматической установки поля Referer: в запросах, перенаправленных заголовком Location: 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //При установке этого параметра в ненулевое значение CURL будет возвращать результат, а не выводить его.
    curl_setopt($curl, CURLOPT_COOKIESESSION, 0); //TRUE to mark this as a new cookie "session". It will force libcurl to ignore all cookies it is about to load that are "session cookies" from the previous session.

    curl_setopt($curl, CURLINFO_HEADER_OUT, true);

    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookieFile);

    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);

    if (!$handle = fopen($cookieFile, 'a')) {
        echo "Не могу открыть файл ($cookieFile)";
    }
    fclose($handle);
    
    $response = curl_exec($curl);

    //отсылаемый http заголовок
//    echo'<pre>';
//    print_r(curl_getinfo($curl, CURLINFO_HEADER_OUT));
//    echo'</pre>';
    
    //последний урл при редиректах
    $endUrl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);


    if (!$response) {
        $error = curl_error($curl) . '(' . curl_errno($curl) . ')';
        echo $error;
        die("Поисковая система $url недоступна");
    } else {
        print($response);
    }
    curl_close($curl);

    return $response;
}

//----------------------Функция анализатор---------------------------
function parser($url, $url_str) {
    global $captcha_tablename, $seacher;
    $response = curl($url_str, $url);

    //подключаем регулярные выражения поиска сайтов и навигации
    require('yandex_regexp.php');

    if (count($matches[0]) == 0) {

        preg_match_all('/<input class="form__key" type="hidden" name="key" value="([^>]+?)"\/>/i', $response, $matches);  //Value of Input.key
        $key = $matches[1][0];
        preg_match_all('/<input class="form__retpath" type="hidden" name="retpath" value="([^>]+?)"\/>/i', $response, $matches);  //Value of Input.retpath
        $retpath = urlencode($matches[1][0]);
        preg_match_all('/<form class="form__inner" method="get" action="([^>]+?)">/i', $response, $matches);  //Value of form.action
        $action = $matches[1][0];
        preg_match_all('/<img class="image form__captcha" [^>]+? src="([^>]+?)"[^>]+?>/i', $response, $matches);  //Value of imgSrc
        $imgSrc = $matches[1][0];

//        echo $action;
//        echo"<br/>";

        /*
         * При попытке обновить капчу function reload по урл $endUrl говорит что такой страницы не существует
         * обновляется при повторении запроса по $url_str
         */
//        echo"<br/>";
//        echo $imgSrc;
        if ($key && $retpath) {// т.е страница с капчей
            $htmlCaptcha = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "
<html>
    <head>
        <title>Геотаргетинг</title>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>" . '
        <link rel="stylesheet" type="text/css" href="../../css/geotargeting.css" media="all"/>
        <script type="text/javascript" src="../../js/geotargeting.js"></script>
        <script type="text/javascript" src="../../js/jquery-1.7.1.js"></script>' .
                    "</head>
    <body><div id='result'>
                <form action='sendCaptcha.php' method='Get'>
            <img src='$imgSrc'/><div id='reload' onClick='reloadYandex(" . '"' . $url_str . '"' . ");'>reload</div><br/>
                <input type='text' id='rep' name='rep'/><br/>                
                <input type='hidden' id='action' name='action' value='$action'/>
                    <input type='hidden' id='key' name='key' value='$key'/>
                        <input type='hidden' id='retpath' name='retpath' value='$retpath'/>
                            <input type = 'hidden' id = 'user_id' name = 'user_id' value = '$user_id'/>
                            <input value='Отправить' id='submit2' type='submit' onClick='SubmitCaptchaYandex(" . '"' . $url_str . '"' . ");return false;'/><br/>
                
            </form></div>" . '                    
            </body></html>';
            print_r($htmlCaptcha);
//            print($response);

            exit;
        } else {
            return 0;
        }
    }
    return $response;
}

//-----------------Проверяем не заблокирован ли поиск Яндекс----------------
//$query = "SELECT bool, accessdate FROM $captcha_tablename  WHERE seacher='$seacher'";
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
//$deltaC = 2 * 60 * 60;
//
//if ($captcha == 1 && $diffC < $deltaC) {
//
//    $PauseC = gmdate('H:i:s', abs($deltaC - $diffC));
//    echo"Работа сервиса приостановлена на $PauseC из-за блокировки Yandex";
//    exit;
//} elseif ($captcha == 1 && $diffC >= $deltaC) {
//
//    $query = "UPDATE $captcha_tablename SET bool = '0'";
//    $result = mysql_query($query);
//
//    if (!$result) {
//        error_message(mysql_error());
//    }
//}
//-------------------------------------------------------------------------------

$value = "кафе";
$str_query = $value;
$url_str = "http://" . $url . "/yandsearch?text=" . $str_query;
print_r(parser($url, $url_str));
//$ObjDb->db_close();
?>