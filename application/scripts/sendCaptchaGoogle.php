<?php

require_once('google_parser_curl.php');

$url = $_GET['url'];
$captcha = $_GET['captcha'];
$continue = $_GET['continue'];
$action = $_GET['action'];
$id = $_GET['id'];
$user_id = $_GET['user_id'];
$submit = $_GET['submit'];
$url_str2 = $_GET['url_str']; //url для reload и sent
//Кодирует значение q=
//preg_match('/([^\?]+?\?q=)([^&]*)(&?[^\?]*)/i', $continue, $matches);
//$temp = urlencode($matches[2]);
//$continue = $matches[1] . $temp . $matches[3];
//$matches = explode('search', $continue);
//$continue = $matches[0].'search'.urlencode($matches[1]);

$continue = urlencode($continue);

$submit = urlencode($submit);

$url_str = "http://www." . $url . "/sorry/" . $action . "?continue=$continue&id=$id&captcha=$captcha";

// url-кодированный запрос содержится в запросе отправки капчи
// такой запрос некорректен(запрашиваемая страница не существует), нужен обычный текст
//
//echo"sendCaptcha";
//echo"<br/>";
//echo $url_str;
//echo"<br/>";
//exit;



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

    preg_match_all('/<input type="submit" name="submit" value="([^>]+?)" [^>]+?>/i', $response, $matches);  //Value of Input.submit
    $submit = $matches[1][0];





//        echo $action;
//        echo"<br/>";
//        echo $endUrl;
//        echo"<br/>";
//        echo $imgSrc;
    $todayU = intval(date("U"));

    if ($id && $continue) {

        save_image($imgSrc, $url, $img_captcha);

        $img_captcha = dirname($_SERVER['PHP_SELF']) . '/' . $img_captcha;

        $htmlCaptcha = "<form action='sendCaptcha.php' method='Get'><img src='$img_captcha" . "?date=$todayU'" . "/>
            <div id='reload' onClick='reloadGoogle(" . '"' . $url_str2 . '"' . ");'>reload</div><br/>
            <input type = 'text' id = 'captcha' name = 'captcha' value = '' size = '12'/><br/>
            <input type = 'hidden' id = 'action' name = 'action' value = '$action'/>
            <input type = 'hidden' id = 'continue' name = 'continue' value = '$continue'/>
            <input type = 'hidden' id = 'id' name = 'id' value = '$id'/>
            <input type = 'hidden' id = 'user_id' name = 'user_id' value = '$user_id'/>
            <input type = 'hidden' id = 'submitG' name = 'submitG' value = '$submit'/>
            <input value = 'Отправить' id = 'submit2' type = 'submit' onClick = 'SubmitCaptchaGoogle(" . '"' . $url_str2 . '"' . ");return false;'/><br/>

            </form>";
        print_r($htmlCaptcha);
    } else {
//        print($response);
        echo'Капча отправлена';
    }
} else {
//    print($response);
    echo'Капча отправлена';
}
?>
