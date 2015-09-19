<?php

require_once('google_parser_curl.php');

$url = $_GET['url'];
$user_id = $_GET['user_id'];
$url_str = $_GET['url_str'];


//Кодирует значение q=
preg_match('/([^\?]+?\?q=)([^&]*)(&?[^\?]*)/i', $url_str, $matches);
//Закоментированное от Яндекс
//$temp = explode(' ', $matches[2]);
//$temp = implode("%20", $temp);
$temp = urlencode($matches[2]);
$url_str = $matches[1] . $temp . $matches[3];

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

        $htmlCaptcha = "<form action='sendCaptcha.php' method='Get'><img src='$img_captcha"."?date=$todayU'"."/>
            <div id='reload' onClick='reloadGoogle(" . '"' . $url_str . '"' . ");'>reload</div><br/>
            <input type = 'text' id = 'captcha' name = 'captcha' value = '' size = '12'/><br/>
            <input type = 'hidden' id = 'action' name = 'action' value = '$action'/>
            <input type = 'hidden' id = 'continue' name = 'continue' value = '$continue'/>
            <input type = 'hidden' id = 'id' name = 'id' value = '$id'/>
            <input type = 'hidden' id = 'user_id' name = 'user_id' value = '$user_id'/>
            <input type = 'hidden' id = 'submitG' name = 'submitG' value = '$submit'/>
            <input value = 'Отправить' id = 'submit2' type = 'submit' onClick = 'SubmitCaptchaGoogle(" . '"' . $url_str . '"' . ");return false;'/><br/>

            </form>";
        print_r($htmlCaptcha);
    } else {
        print($response);
    }
} else {
    print("Google доступен");
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
