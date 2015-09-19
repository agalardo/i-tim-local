<?php

header('Content-Type: text/html; charset=UTF-8');
?>
<?php

session_start();
$user_id = $_SESSION['user_id'];

$img_captcha = "images/captcha" . $user_id . ".gif";
$url = 'google.ru';

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

function save_image($url_str, $url, $filename) {

    $response = curl($url_str, $url);


    // Вначале давайте убедимся, что файл существует и доступен для записи.
//    if (is_writable($filename)) {
    // В нашем примере мы открываем $filename в режиме "записать файл".

    if (!$handle = fopen($filename, 'w')) {
        echo "Не могу открыть файл ($filename)";
        exit;
    }

    // Записываем $somecontent в наш открытый файл.
    if (fwrite($handle, $response) === FALSE) {
        echo "Не могу произвести запись в файл ($filename)";
        exit;
    }

//        echo "Ура! Записали в файл ($filename)";

    fclose($handle);
//    } else {
//        echo "Файл $filename недоступен для записи";
//    }
}

//-----------------Функция для выполнения запроса------------------------
function curl($url_str, $url) {

    global $user_id;
    
    //$cookieFile = "cookies/cookie$user_id.txt"; расположение куки на сервере
    $cookieFile = "./cookie$user_id.txt";  //расположение куки локально
    $host = "Host:www." . $url;

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
    curl_setopt($curl, CURLOPT_COOKIESESSION, 0); //TRUE для указания текущему сеансу начать новую "сессию" cookies. Это заставит libcurl проигнорировать все 
    //"сессионные" cookies, которые она должна была бы загрузить, полученные из предыдущей сессии. По умолчанию, libcurl всегда сохраняет и загружает все cookies,
    // вне зависимости от того, являются ли они "сессионными" или нет. "Сессионные" cookies - это cookies без срока истечения, котоыре должны существовать только для текущей "сессии".

    curl_setopt($curl, CURLINFO_HEADER_OUT, true); //Посылаемая строка запроса.
    // Для работы этого параметра, добавьте опцию CURLINFO_HEADER_OUT к дескриптору с помощью вызова curl_setopt()

    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30); //Количество секунд ожидания при попытке соединения. Используйте 0 для бесконечного ожидания.

    if (!$handle = fopen($cookieFile, 'a')) {
        echo "Не могу открыть файл ($cookieFile)";
    }
    fclose($handle);
    
    $response = curl_exec($curl);

    //отсылаемый http заголовок
    //print_r(curl_getinfo($curl, CURLINFO_HEADER_OUT));
    //последний урл при редиректах
    $endUrl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);


    if (!$response) {
        $error = curl_error($curl) . '(' . curl_errno($curl) . ')';
        echo $error;
        die("Поисковая система $url недоступна");
    } else {
        //print($response);
    }
    curl_close($curl);

    return $response;
}

//----------------------Функция анализатор---------------------------
function parser($url, $url_str) {
    global $captcha_tablename, $seacher, $img_captcha, $user_id;
    $response = curl($url_str, $url);

    //подключаем регулярные выражения поиска сайтов и навигации
    require('google_regexp.php');
    
    if (count($matches[0]) == 0) {

        //парсим страницу с капчей
        preg_match_all('/<img src="([^>"]+?)"[^>]+?>/i', $response, $matches);  //URL of image with captcha
        $imgSrc = 'http://www.' . $url . $matches[1][0];

        preg_match_all('/<form action="([^>"]+?)"[^>]+?>/i', $response, $matches);  //Value of Input.continue
        $action = $matches[1][0];

        preg_match_all('/<input type="hidden" name="continue" value="([^>]+?)">/i', $response, $matches);  //Value of Input.continue
        $continue = urlencode($matches[1][0]);

        preg_match_all('/<input type="hidden" name="id" value="([^>]+?)">/i', $response, $matches);  //Value of Input.id
        $id = $matches[1][0];

        preg_match_all('/<input type="submit" name="submit" value="([^>"]+?)"[^>]+?>/i', $response, $matches);  //Value of Input.submit
        $submit = $matches[1][0];

//        echo $action;
//        echo"<br/>";

        /*
         * При попытке обновить капчу function reload по урл $endUrl говорит что такой страницы не существует
         * обновляется при повторении запроса по $url_str
         */
//        echo"<br/>";
//        echo $imgSrc;
        if ($id && $continue) {// т.е страница с капчей
            save_image($imgSrc, $url, $img_captcha);

            $img_captcha = dirname($_SERVER['PHP_SELF']) . '/' . $img_captcha;

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
            <img src='$img_captcha'/>
            <div id='reload' onClick='reloadGoogle(" . '"' . $url_str . '"' . ");'>reload</div><br/>
            <input type = 'text' id = 'captcha' name = 'captcha' value = '' size = '12'/><br/>
            <input type = 'hidden' id = 'action' name = 'action' value = '$action'/>
            <input type = 'hidden' id = 'continue' name = 'continue' value = '$continue'/>
            <input type = 'hidden' id = 'id' name = 'id' value = '$id'/>
            <input type = 'hidden' id = 'user_id' name = 'user_id' value = '$user_id'/>
            <input type = 'hidden' id = 'submitG' name = 'submitG' value = '$submit'/>
            <input value = 'Отправить' id = 'submit2' type = 'submit' onClick = 'SubmitCaptchaGoogle(" . '"' . $url_str . '"' . ");return false;'/><br/>

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

//-----------------Проверяем не заблокирован ли поиск Google----------------
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
//    echo"Работа сервиса приостановлена на $PauseC из-за блокировки Google";
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
$str_query = urlencode($value);
$url_str = "http://www." . $url . "/search?q=" . $str_query . "&sclient=psy-ab&hl=ru";
print_r(parser($url, $url_str));
//$ObjDb->db_close();
?>