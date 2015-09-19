<?php

require_once('yandex_parser_curl.php');

$url = $_GET['url'];
$rep = urlencode($_GET['rep']);
$action = $_GET['action'];
$key = $_GET['key'];
$retpath = urlencode($_GET['retpath']);
$submit2 = $_GET['submit2'];
$url_str2 = $_GET['url_str'];

$url_str = "http://" . $url . $action . "?rep=$rep&key=$key&retpath=$retpath";

//echo $url_str;


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
//        echo $endUrl;
//        echo"<br/>";
//        echo $imgSrc;
    if ($retpath) {
        $htmlCaptcha = "<form action='sendCaptcha.php' method='Get'>
            <img src='$imgSrc'/><div id='reload' onClick='reloadYandex(" . '"' . $url_str2 . '"' . ");'>reload</div><br/>
                <input type='text' id='rep' name='rep'/><br/>                
                <input type='hidden' id='action'name='action' value='$action'/>
                    <input type='hidden' id='key' name='key' value='$key'/>
                        <input type='hidden' id='retpath' name='retpath' value='$retpath'/>
                            <input value='Отправить' id='submit2' type='submit' onClick='SubmitCaptchaYandex(" . '"' . $url_str2 . '"' . ");return false;'/><br/>
                
            </form>";
        print_r($htmlCaptcha);
    }
//    print($response);
} else {
    print($response);
}
?>
