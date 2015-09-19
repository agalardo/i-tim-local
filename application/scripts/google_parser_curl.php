<?php

//Используется функция curl и save_image для reload и  sendCaptcha
/**
 * Функция сохраняет изображение средствами cURL,
 * когда опция allow_url_fopen отключена.
 */
$user_id = $_GET['user_id'];

$img_captcha = "images/captcha" . $user_id . ".gif";

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
    
    global $endUrl, $user_id;

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

//    echo"<pre>";
//    print($response);
//    echo"</pre>";

    if (!$response) {
        $error = curl_error($curl) . '(' . curl_errno($curl) . ')';
        echo $error;
        die("Поисковая система $url недоступна");
    }

    curl_close($curl);

    return $response;
}

//----------------------Функция анализатор---------------------------
function parser($url, $url_str, $site, $str_query) {
    global $countOfRequest, $endUrl, $img_captcha;
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

        save_image($imgSrc, $url, $img_captcha);
//        echo $action;
//        echo"<br/>";

        /*
         * При попытке обновить капчу function reload по урл $endUrl говорит что такой страницы не существует
         * обновляется при повторении запроса по $url_str
         */
//        echo"<br/>";
//        echo $imgSrc;
        
        if ($id && $continue) {
            $htmlCaptcha = "<form action='sendCaptcha.php' method='Get'>
            <img src='$img_captcha'/>
            <div id='reload' onClick='reload(" . '"' . $url_str . '"' . ");'>reload</div><br/>
            <input type = 'text' id = 'captcha' name = 'captcha' value = '' size = '12'/><br/>
            <input type = 'hidden' id = 'action' name = 'action' value = '$action'/>
            <input type = 'hidden' id = 'continue' name = 'continue' value = '$continue'/>
            <input type = 'hidden' id = 'id' name = 'id' value = '$id'/>
            <input type = 'hidden' id = 'submitG' name = 'submitG' value = '$submit'/>
            <input value = 'Отправить' id = 'submit2' type = 'submit' onClick = 'SubmitCaptcha(" . '"' . $url_str . '"' . ");return false;'/><br/>

            </form>";
            print_r($htmlCaptcha);
//            print($response);

            exit;
        } else {
            return 0;
        }
    }

    $countOfRequest++;
    foreach ($matches_nav[1] as $i => $v) {
        $href_nav[$page_nav[1][$i]] = "http://www." . $url . $v;
    }
    foreach ($matches[1] as $v) {
        $href[] = $v;
    }
    //$href - array of URL of site 

    /* echo "url=".$url."</br>";
      echo "str_query=".$str_query."</br>";
      echo "url_str=".$url_str."</br></br>"; */

    //Поиск сайта	
    foreach ($href as $i => $value) {
        preg_match('/^(www.)?([^\/]+)/i', $value, $matchesSite);
        $i++;

        if ($matchesSite[2] == $site) {
            $statistic = array("statistic", $i, $site, $value);
            return $statistic;
        }
    }

    return $href_nav;

    //просмотр клиентских HTTP заголовков
    /* echo"<b>HTTP Headers:</b></br>";
      foreach (getallheaders() as $name => $value) {
      echo "$name: $value</br>";
      }
      echo"</br>"; */

    //Вывод URL найденых сайтов и следующих сраниц выдачи
    /* $i=1;
      foreach($href as $val){
      echo"<b>$i -- $val</b></br>";
      $i++;
      }

      foreach($href_nav as $key => $val){
      echo"<b>$key => $val</b></br>";
      } */
}

//-------------------------Main function (обход страниц выдачи)--------------------------
function Main($depth, $url, $site, $Data, $delay) {

    foreach ($Data as $keyword) {

        //-----------------Определяем переменные-------------------
//        $temp = explode(' ', $keyword);
//        $str_query = implode("%20", $temp);
//        unset($temp);
        $str_query = urlencode($keyword);

        $url_str = "http://www." . $url . "/search?q=" . $str_query . "&sclient=psy-ab&hl=ru";
        //---------------------------------------------------------

        $result = iteration($depth, $url, $url_str, $keyword, $site, $str_query, $delay);

        $resultSearch[] = $result;
    }

    //Задержка для всех keywords, кроме последнего
    if ($searchBool == 1) {
        if ($keywords[$count - 1] != $value) {
            sleep($delay[0] + rand(0, $delay[1]));
        }
    }

    return $resultSearch;
}

function iteration($depth, $url, $url_str, $keyword, $site, $str_query, $delay) {
    for ($i = 0; $i < $depth; $i++) {

        $response = parser($url, $url_str, $site, $str_query);

        if ($response[0] == 'statistic') {
            $position = ($i) * 10 + $response[1];
            $data = array($position, $response[2], $response[3], $keyword);
            return $data;
        } else {
            if ($response == 0) {
                //Если результаты на одной странице и совпадений нет
                $data = array("Relevant page not found in Google", $site, $keyword);
                return $data;
            }
            $j = $i + 2;
            $last_key = array_pop(array_keys($response));
            if ($last_key < $j) {
                $data = array("Relevant page not found in Google in " . ($last_key + 1) * 10, $site, $keyword);
                return $data;
            }
            $url_str = str_replace("&amp;", "&", $response[$j]);

            if ($i == $depth - 1) {
                $dep = $depth * 10;
                //$dep=$depth;			
                $data = array("not found in $dep", $site, $keyword);
                return $data;
            } else {
                sleep($delay[0] + rand(0, $delay[1]));
            }
        }
    }
}

?>