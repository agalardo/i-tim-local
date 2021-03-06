<?php
//Отдельный файл с curl() нет обращения к базе
//используется только curl()

$user_id = $_GET['user_id'];

//-----------------Функция для выполнения запроса------------------------
function curl($url_str, $url) {


//    echo $url_str;
//    echo"<br/>";
//    echo $url; 

    global $endUrl, $user_id;
    
//    $cookieFile = "cookies/cookie$user_id.txt";
    $cookieFile = "./cookie$user_id.txt";
    
    $host = "Host: " . $url;

    $curl = curl_init($url_str);

    $header = array($host, 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 YaBrowser/15.2.2214.3645 Safari/537.36',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language: ru,en;q=0.8',
        'Accept-Encoding: gzip, deflate, sdch',
        'Connection: keep-alive',
        'Cache-Control: max-age=0');
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_HEADER, 0); //При установке этого параметра в ненулевое значение результат будет включать полученные заголовки.
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); //отключение корневого агенства сертификата 2014 google выдал ошибку: ssl certificate problem  
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);   // переходит по редиректам	
    curl_setopt($curl, CURLOPT_ENCODING, "gzip, deflate");
    curl_setopt($curl, CURLOPT_AUTOREFERER, true); //TRUE для автоматической установки поля Referer: в запросах, перенаправленных заголовком Location: 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //При установке этого параметра в ненулевое значение CURL будет возвращать результат, а не выводить его.
    curl_setopt($curl, CURLOPT_COOKIESESSION, 0); //TRUE для указания текущему сеансу начать новую "сессию" cookies. Это заставит libcurl проигнорировать все 
    //"сессионные" cookies, которые она должна была бы загрузить, полученные из предыдущей сессии. По умолчанию, libcurl всегда сохраняет и загружает все cookies,
    // вне зависимости от того, являются ли они "сессионными" или нет. "Сессионные" cookies - это cookies без срока истечения, котоыре должны существовать только для текущей "сессии".

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
//    $endUrl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);


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
    global $countOfRequest, $endUrl;
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
        if ($action && $retpath) {
            $htmlCaptcha = "<form action='sendCaptcha.php' method='Get'>
            <img src='$imgSrc'/><div id='reload' onClick='reloadYandex(" . '"' . $url_str . '"' . ");'>reload</div><br/>
                <input type='text' id='rep' name='rep'/><br/>                
                <input type='hidden' id='action' name='action' value='$action'/>
                    <input type='hidden' id='key' name='key' value='$key'/>
                        <input type='hidden' id='retpath' name='retpath' value='$retpath'/>
                            <input value='Отправить' id='submit2' type='submit' onClick='SubmitCaptchaYandex(" . '"' . $url_str . '"' . ");return false;'/><br/>
                
            </form>";
            print_r($htmlCaptcha);

            exit;
        } else {
            return 0;
        }
    }

    $countOfRequest++;
    foreach ($matches_nav[1] as $i => $v) {
        $href_nav[$page_nav[1][$i]] = "http://" . $url . $v;
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

        $str_query = urlencode($keyword);
        $url_str = "http://" . $url . "/search/?text=" . $str_query;
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
                $data = array("Relevant page not found in Yandex", $site, $keyword);
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