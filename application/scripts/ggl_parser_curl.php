<?php

/**
 * Функция сохраняет изображение средствами cURL,
 * когда опция allow_url_fopen отключена.
 */
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

//-----------------Функция для записи результатов------------------------
function save_result($url, $depth, $data) {
    
    global $result_tablename, $site_tablename, $user_id;

    $seacher = $url;
    $depth *= 10;

    if (count($data) == 4) {
        $keyword = $data[3];
        $site = $data[1];
    } else {
        $site = $data[1];
        $keyword = $data[2];
    }


    $query = "SELECT keyword_id FROM $site_tablename WHERE site = '$site' AND user_id = '$user_id' AND keyword = '$keyword'";
    
    $result = mysql_query($query);
    if (!$result) {
        error_message(mysql_error());
    }
    $query_data = mysql_fetch_array($result);
    $keyword_id = $query_data['keyword_id'];

    if (count($data) == 4){
        $query = "INSERT INTO $result_tablename VALUES(NULL,'$seacher','$keyword_id','$data[2]','$data[0]','$depth',CURRENT_TIMESTAMP)";
    } else {
        $query = "INSERT INTO $result_tablename VALUES(NULL,'$seacher','$keyword_id',NULL,'$depth','$depth',CURRENT_TIMESTAMP)";
    }
    $result = mysql_query($query);
    if (!$result) {
        error_message(mysql_error());
        //return 0;
    }
}

//-----------------Функция для выполнения запроса------------------------
function curl($url_str, $url) {
    global $endUrl, $activity_tablename, $seacher, $user_id;

//    echo $url_str;
//    echo"<br/>";
    //$cookieFile = "cookies/cookie$user_id.txt"; расположение куки на сервере
    $cookieFile = "./cookie$user_id.txt";  //расположение куки локально
    $host = "Host:www." . $url;

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

    curl_setopt($curl, CURLINFO_HEADER_OUT, true); //Посылаемая строка запроса.
    // Для работы этого параметра, добавьте опцию CURLINFO_HEADER_OUT к дескриптору с помощью вызова curl_setopt()

    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30); //Количество секунд ожидания при попытке соединения. Используйте 0 для бесконечного ожидания.
    //Создаем файл с куки. Если существует то проверяем, что доступен для записи
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
//        $error = curl_error($curl) . '(' . curl_errno($curl) . ')';
//        echo $error;
//        echo'<br/>url = ' . $url_str;
//        $info = curl_getinfo($curl);
//        echo'<pre>';
//        print_r($info);
//        echo'</pre>';
        //---------------------Говорим что закончили парсинг---------------------
//        $query = "UPDATE $activity_tablename SET bool = '0' WHERE seacher = '$seacher'";
//        $result = mysql_query($query);
//
//        if (!$result) {
//            error_message(mysql_error());
//        }
        die("4");
    } else {
        //print($response);		
    }
    curl_close($curl);

    return $response;
}

//----------------------Функция анализатор---------------------------
function parser($url, $url_str, $site, $str_query) {
    global $maxCountOfRequest, $countOfRequest, $count_tablename, $captcha_tablename, $activity_tablename, $time_start, $endUrl, $user_id, $delta, $countRequest, $seacher, $img_captcha;

    //-------------------проверяем чтобы количество обращений не превышало допустимое-----------------------------------------------
    if ($countOfRequest >= $maxCountOfRequest) {

        $query = "SELECT accessdate FROM $count_tablename WHERE user_id = '$user_id' AND seacher = '$seacher'";
        $result = mysql_query($query);
        if (!$result) {
            error_message(mysql_error());
        }
        $query_data = mysql_fetch_array($result);
        $accessdate = $query_data['accessdate'];

//        $todayU = intval(date("U")) + (!date('I')) * 60 * 60;
        $todayU = intval(date("U"));
        $accessU = intval(strtotime($accessdate));

        // в россии нет зимнего времени
//        if (!date('I'))
//            $winter = 1;
//        else
//            $winter = 0;
//        $todayDate = mktime(date("H") + $winter, date("i"), date("s"), date("m"), date("d"), date("Y"));
        $todayDate = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
        $accessdate = date("Y-m-d H:i:s", $todayDate);

        $diff = $todayU - $accessU;
        /*
         * Если перерыв больше $delta сбрасываем счетчик и записываем новое время
         * при последующем обновлении count в $count_tablename время не обновляется до истечения времени $delta
         */
        if ($diff > $delta) {
            $countOfRequest = 0;
            $query = "UPDATE $count_tablename SET count = '0', accessdate = '$accessdate' WHERE user_id = '$user_id' AND seacher = '$seacher'";
            $result = mysql_query($query);

            if (!$result) {
                error_message(mysql_error());
            }
        } else {
            $query = "UPDATE $count_tablename SET count = '$countOfRequest' WHERE user_id = '$user_id' AND seacher = '$seacher'";
            $result = mysql_query($query);

            if (!$result) {
                error_message(mysql_error());
            }
            $diff = $delta - $diff;
            $houer = floor($diff / (60 * 60));
            $min = floor($diff / 60) - $houer * 60;
            $sec = $diff - $houer * 60 * 60 - $min * 60;
            $time = $houer . ":" . $min . ":" . $sec;
            echo $time;

            //---------------------Говорим что закончили парсинг---------------------
//            $query = "UPDATE $activity_tablename SET bool = '0' WHERE seacher = '$seacher'";
//            $result = mysql_query($query);
//
//            if (!$result) {
//                error_message(mysql_error());
//            }

            exit;
        }
    }
    //-------------------------------------------------------------------------------------------------------------------------

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

        if ($id && $continue) {// т.е страница с капчей
            //сохраним $countOfRequest в базу
            $query = "UPDATE $count_tablename SET count = '$countOfRequest' WHERE user_id = '$user_id' AND seacher = '$seacher'";
            $result = mysql_query($query);

            if (!$result) {
                error_message(mysql_error());
            }

            //---------------------Говорим что закончили парсинг----------------
//            $query = "UPDATE $activity_tablename SET bool = '0' WHERE seacher = '$seacher'";
//            $result = mysql_query($query);
//
//            if (!$result) {
//                error_message(mysql_error());
//            }
//            //---------------------Говорим что выдана страница скапчей----------
//            // в россии нет зимнего времени
//            if (!date('I'))
//                $winter = 1;
//            else
//                $winter = 0;
//            $todayDate = mktime(date("H") + $winter, date("i"), date("s"), date("m"), date("d"), date("Y"));
//            $accessdate = date("Y-m-d H:i:s", $todayDate);
//
//            $query = "UPDATE $captcha_tablename SET bool = '1', accessdate = '$accessdate' WHERE seacher = '$seacher'";
//            $result = mysql_query($query);
//
//            if (!$result) {
//                error_message(mysql_error());
//            }
//
//            echo 0;
            save_image($imgSrc, $url, $img_captcha);

            $img_captcha = dirname($_SERVER['PHP_SELF']) . '/' . $img_captcha;
            $todayU = intval(date("U"));

            $htmlCaptcha = "<form action='sendCaptcha.php' method='Get'>
            <img src='$img_captcha" . "?date=$todayU'" . "/>
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
            exit;
        } else {
            echo'regexp error';
            return 0;
        }
    }
    $countOfRequest++;
    $countRequest++;
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
        preg_match('/^(www.)?(.+)/i', $value, $matchesSiteFull);
        $i++;
        //Пуникод - рускоязычные домены в utf-8
        $punycode = $matchesSite[2];
        $punycodeFull = $matchesSiteFull[2];

        $idn = new idna_convert(array('idn_version' => 2008));
        $punycode = (stripos($punycode, 'xn--') !== false) ? $idn->decode($punycode) : $idn->encode($punycode);
        $punycodeFull = (stripos($punycodeFull, 'xn--') !== false) ? $idn->decode($punycodeFull) : $idn->encode($punycodeFull);

        $arrayHref = explode('/', $value);
        $value = '';
        foreach ($arrayHref as $val) {
            if ($val) {
                $val = (stripos($val, 'xn--') !== false) ? $idn->decode($val) : $idn->encode($val);
                $value .=$val . '/';
            }
        }
        $punycode = mb_strtolower($punycode , "UTF-8");
        $punycodeFull = mb_strtolower($punycodeFull , "UTF-8");
        $site = mb_strtolower($site , "UTF-8");
//        echo"<br/>";
//        echo $punycode;
//        echo"<br/>";
//        echo $site;
//        echo"<br/>";
        if ($punycode == $site || $punycodeFull == $site) {
            $statistic = array("statistic", $i, $site, $value);
            return $statistic;
        }
    }
    if (is_array($href_nav))
        return $href_nav;
    else
        return 0;

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

//-----------------------Функция Поиск страницы выдачи по позиции сайта из базы----------------		
function seacherPosition($url, $url_str, $str_query, $site, $keyword, $positionDB, $delay, $depth, $last_key = 0) {

    $i = $last_key;

    $response = parser($url, $url_str, $site, $str_query);
    
    $last_key_old = $last_key;
    if ($response[0] == 'statistic') {
        $position = ($i) * 10 + $response[1];
        $data = array($position, $response[2], $response[3], $keyword);
        return $data;
    } 
    elseif ($response == 0) {
//Если результаты на одной странице и совпадений нет
        $data = array("Relevant page not found in Google", $site, $keyword);
        return $data;
    } 
    else {
        $last_key = array_pop(array_keys($response));

        if ($last_key < $last_key_old) {
            
            return $response;
        }
        if ($response[$positionDB] == NULL) {
            $url_str = str_replace("&amp;", "&", $response[$last_key]);
            sleep($delay[0] + rand(0, $delay[1]));
            $response = seacherPosition($url, $url_str, $str_query, $site, $keyword, $positionDB, $delay, $depth, $last_key);
        }
        return $response;
    }
}
function seacherPositionDown($url, $str_query, $site, $keyword, $response, $depth, $delay) {
    
    $last_key = array_pop(array_keys($response));
    $key = $last_key;
    while ($key >= 1) {
        
        $url_str = str_replace("&amp;", "&", $response[$key]);
        $response = parser($url, $url_str, $site, $str_query);
        
        if ($response[0] == 'statistic') {
                $position = ($i) * 10 + $response[1];
                $data = array($position, $response[2], $response[3], $keyword);
                return $data;
            }
        $key -- ;
        sleep($delay[0] + rand(0, $delay[1]));
    }
    $data = array("not found in $depth", $site, $keyword);
    return $data;    
}
//-------------------------Main function (обход страниц выдачи)--------------------------
function Main($depth, $url, $keywords, $site, $time_1, $delay) {
    global $result_tablename, $site_tablename, $searchRequire, $iterationBool, $searchBool, $user_id, $delta;
    $count = count($keywords);

    foreach ($keywords as $value) {
//        echo $value;
//        echo "<br/>";
//-------------------определение необходимости поиска("поиск сегодня осуществлялся")
        $keyword = $value;
        $seacher = $url;

        $query = "SELECT keyword_id FROM $site_tablename WHERE site = '$site' AND user_id = '$user_id' AND keyword = '$keyword'";
        $result = mysql_query($query);
        if (!$result) {
            error_message(mysql_error());
        }
        $query_data = mysql_fetch_array($result);
        $keyword_id = $query_data['keyword_id'];

        $query = "SELECT accessdate, position, url FROM $result_tablename WHERE keyword_id = '$keyword_id' AND seacher = '$seacher' ORDER BY accessdate DESC";

        //$site = iconv('windows-1251', 'utf-8', $site);

        $result = mysql_query($query);
        if (!$result) {
            error_message(mysql_error());
        }

//Определяем необходимость поиска берем первую запись-----------		
        $query_data = mysql_fetch_array($result);
        $accessdate = $query_data['accessdate'];
        $position = $query_data['position'];
        $urlDB = $query_data['url'];

        //$urlDB = iconv('windows-1251', 'utf-8', $urlDB);
        //сайт еще ниразу не искался $firstSeaching = 1
        $firstSeaching = $position ? NULL : 1;

//отменено зимнее время
//        $todayU = intval(date("U")) + (!date('I')) * 60 * 60;
        $todayU = intval(date("U"));
        $accessU = intval(strtotime($accessdate));

        $diff = $todayU - $accessU;

        $searchBool = 0;
//перерыв между поисками 12 часов
        if ($diff > $delta) {
            $searchBool = 1;
            $searchRequire = 1;
        }
//--------------------------------------------------------------
//Определяем последний найденый результат
        $position_first = $urlDB ? $position : NULL;
        $urlDB_first = $urlDB;

        for ($i = 1; $i < mysql_num_rows($result); $i++) {
            $query_data = mysql_fetch_array($result);
            $position = $query_data['position'];
            $urlDB = $query_data['url'];
            if ($urlDB) {
                $positionOld = $position;
                break;
            }
        }

        //---------------------------------------------

        if ($searchBool == 1) {
            //-----------------Определяем переменные-------------------
            //$str_query = str_replace(" ","+",$value);
            $str_query = urlencode($value);
            $url_str = "http://www." . $url . "/search?q=" . $str_query . "&sclient=psy-ab&hl=ru";
            //---------------------------------------------------------        

            $data = iteration($depth, $url, $url_str, $value, $site, $str_query, $delay, $position_first, $positionOld, $urlDB_first, $firstSeaching);

            unset($positionOld);

            $iterationBool = 1;
            //echo $value."</br>";		
            //print_r($data);

            save_result($url, $depth, $data);
        }

        $time_2 = time();
        //time_execution($time_1,$time_2);		
        $time_1 = $time_2;
        for ($g = 0; $g < 1; $g++) {
            flush();
        }
        //Задержка для всех keywords, кроме последнего
        if ($searchBool == 1) {
            if ($keywords[$count - 1] != $value) {
                sleep($delay[0] + rand(0, $delay[1]));
            }
        }
    }
    return $time_2;
}

function iteration($depth, $url, $url_str, $keyword, $site, $str_query, $delay, $positionDB, $positionDbOld, $urlDB, $firstSeaching) {
    global $depthNF;

//    if (ceil($depth / 2) < $depthNF) {
//        $depthNF = ceil($depth / 2);
//    }

    $positionDB = ceil($positionDB / 10);
    $positionDbOld = ceil($positionDbOld / 10);

    if ($firstSeaching || $depth < $positionDB || $positionDB == 1 || ($urlDB == NULL && $positionDbOld == 1)|| ($urlDB == NULL && $positionDbOld && $depth < $positionDbOld)) {
//если сайт еще не искалася | глубина поиска меньше позиции из базы | результат на первой странице
// | при прошлом поиске результат был на первой странице | Сайт когда-то был найден и глубина поиска меньше позиции из базы
//seacherPosition() не учитывает случая отсутствия на первой странице искомого сайта, если positionDB==1
        for ($i = 0; $i < $depth; $i++) {
            
            $response = parser($url, $url_str, $site, $str_query);

            if ($response[0] == 'statistic') {
                $position = ($i) * 10 + $response[1];
                $data = array($position, $response[2], $response[3], $keyword);
                return $data;
            } else {
                $j = $i + 2;
                if ($response == 0) {
                    //Если результаты на одной странице и совпадений нет
                    $data = array("Relevant page not found in Google", $site, $keyword);
                    return $data;
                }
                $last_key = array_pop(array_keys($response));
                if ($last_key < $j) {
                    $data = array("Relevant page not found in Google", $site, $keyword);
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
    } elseif ($urlDB == null) {//при прошлом поиске сайт не был найден
        
        if ($positionDbOld) {//когда-то был наден
            $data = seacherPosition($url, $url_str, $str_query, $site, $keyword, $positionDbOld, $delay, $depth, $last_key);
        } 
        else {//ниразу не был найден
            $data = seacherPosition($url, $url_str, $str_query, $site, $keyword, $depth, $delay, $depth, $last_key);
        }
        
        if (count($data) == 3 && $data[2] == $keyword) {  
            return $data;
        }
        elseif (count($data) == 4 && $data[3] == $keyword) {
            return $data;
        }
        else {
            //нашли страницу выдачи с необходимой позицией, теперь ищем сайт
            if ($positionDbOld) {
                $positionDB = $positionDbOld;
            } else {
                $positionDB = $depth;
            }
            $response = $data;
            
            //если в seacherPosition() насткнулись на конечную страницу раньше чем нашли необходимую
            $last_key = array_pop(array_keys($response));
            if($last_key < $positionDB){
               $data = seacherPositionDown($url, $str_query, $site, $keyword, $response, $depth, $delay);
               return $data;
            }
            //----------------------------------------------------------------------------------------
            
            $url_str = str_replace("&amp;", "&", $response[$positionDB]);
            $key = array($positionDB, $positionDB);
            $href = array();
//            $href[0] = $response;
//            $href[1] = $response;
            $index = 1;
            for ($i = 0; $i < $depthNF * 2; $i++) {
                
                if ($i % 2 == 0)
                    $d = 1;
                else
                    $d = -1;
                
                if (($d > 0 && $key[0] > $depth) || ($d < 0 && $key[1] <= 1)) {
                    continue;
                }

                sleep($delay[0] + rand(0, $delay[1]));
                
                $response = parser($url, $url_str, $site, $str_query);
                
                if ($response[0] == 'statistic') {

                    if (($index) > 0)
                        $position = ($key[0] - 1) * 10 + $response[1];
                    if (($index) < 0)
                        $position = ($key[1] - 1) * 10 + $response[1];
                    //Ввели $index. response получается с предыдущего $d.
                    $data = array($position, $response[2], $response[3], $keyword);
                    return $data;
                }
                
                if ($i == 0) {
                    $href[0] = $response;
                    $href[1] = $response;
                    
                    //google выкидывает повторяющиеся результаты (последние страницы)
                    // поэтому возьмем ключи на страницы у него и если last_key < глубины поиска
                    // то задействуем seacherPositionDown()
                    $last_key = array_pop(array_keys($response));
                    if($last_key < $positionDB){

                        $data = seacherPositionDown($url, $str_query, $site, $keyword, $response, $depth);
                        return $data;
                    }                    
                }
                
                if ($index > 0) {
                    $href[0] = $response;
                }
                if ($index < 0) {
                    $href[1] = $response;
                }

                
                if ($response == 0) {
                    //Если результаты на одной странице и совпадений нет
                    $data = array("Relevant page not found in Google", $site, $keyword);
                    return $data;
                } 
                else {

                    if ($d > 0 && $key[0] < $depth) {
                        $key[0] ++;
                        $index = $d;
                        
                        if($href[0][$key[0]]):
                            $url_str = str_replace("&amp;", "&", $href[0][$key[0]]);
                        else: 
                            //меняем и пропускаем итерацию если подошли к конечной странице выдачи
                            $key[1] --;
                            $index = -$d;
                            $url_str = str_replace("&amp;", "&", $href[1][$key[1]]);
                            continue;
                        endif;
                    } elseif ($d > 0 && $key[0] == $depth) {//без этого повторяется ход
                        $key[1] --;
                        $key[0] ++;
                        $index = $d;
                        $url_str = str_replace("&amp;", "&", $href[1][$key[1]]);
                    }
                    
                    if ($d < 0 && $key[1] > 1) {
                        $key[1] --;
                        $index = $d;
                        $url_str = str_replace("&amp;", "&", $href[1][$key[1]]);
                    }

                    if ($key[1] <= 1 && $key[0] >= $depth) {
                        $dep = $depth * 10;
                        //$dep=$depth;			
                        $data = array("not found in $dep", $site, $keyword);
                        return $data;
                    }
                }
            }
            //Если результаты не найдены в $depthNf
            $data = array("not found in $depthNf", $site, $keyword);
            return $data;
        }
    } else {//При прошлом поиске был найден, кроме  $positionDB == 1
    
        //-----------------------Поиск страницы по позиции сайта из базы----------------		
        $data = seacherPosition($url, $url_str, $str_query, $site, $keyword, $positionDB, $delay, $depth, $last_key);
        //------------------------------------------------------------------------------------

        if (count($data) == 3 && $data[2] == $keyword) {  
            return $data;
        }
        elseif (count($data) == 4 && $data[3] == $keyword) {
            return $data;
        }
        else {
            //нашли страницу с необходимой позицией, теперь ищем сайт
            $response = $data;
            
            //если в seacherPosition() насткнулись на конечную страницу раньше чем нашли необходимую
            $last_key = array_pop(array_keys($response));
            if($last_key < $positionDB){
               $data = seacherPositionDown($url, $str_query, $site, $keyword, $response, $depth, $delay);
               return $data;
            }
            //----------------------------------------------------------------------------------------
            
            $url_str = str_replace("&amp;", "&", $response[$positionDB]);
            $key = array($positionDB, $positionDB);
            $href = array();
//            $href[0] = $response;
//            $href[1] = $response;
            $index = 1;
            for ($i = 0; $i < $depth * 2; $i++) {
//                print_r($key);
                if ($i % 2 == 0)
                    $d = 1;
                else
                    $d = -1;

                if (($d > 0 && $key[0] > $depth) || ($d < 0 && $key[1] <= 1)) {
                    continue;
                }

                
                sleep($delay[0] + rand(0, $delay[1]));

                $response = parser($url, $url_str, $site, $str_query);
                if ($response[0] == 'statistic') {

                    if (($index) > 0)
                        $position = ($key[0] - 1) * 10 + $response[1];
                    if (($index) < 0)
                        $position = ($key[1] - 1) * 10 + $response[1];
                    //Ввели $index. response получается с предыдущего $d.
                    $data = array($position, $response[2], $response[3], $keyword);
                    return $data;
                }
                
                if ($i == 0) {
                    $href[0] = $response;
                    $href[1] = $response;
                    
                    //google выкидывает повторяющиеся результаты (последние страницы)
                    // поэтому возьмем ключи на страницы у него и если last_key < глубины поиска
                    // то задействуем seacherPositionDown()
                    $last_key = array_pop(array_keys($response));
                    if($last_key < $positionDB){

                        $data = seacherPositionDown($url, $str_query, $site, $keyword, $response, $depth, $delay);
                        return $data;
                    }  
                }
                if ($index > 0) {
                    $href[0] = $response;
                }
                if ($index < 0) {
                    $href[1] = $response;
                }

                
                if ($response == 0) {
                    //Если результаты на одной странице и совпадений нет
                    $data = array("Relevant page not found in Google", $site, $keyword);
                    return $data;
                } 
                else {

                    if ($d > 0 && $key[0] < $depth) {
                        $key[0] ++;
                        $index = $d;
                        
                        if($href[0][$key[0]]):
                            $url_str = str_replace("&amp;", "&", $href[0][$key[0]]);
                        else: 
                            //меняем и пропускаем итерацию если подошли к конечной странице выдачи
                            $key[1] --;
                            $index = -$d;
                            $url_str = str_replace("&amp;", "&", $href[1][$key[1]]);
                            continue;
                        endif;
                        
                    } elseif ($d > 0 && $key[0] == $depth) {//без этого повторяется ход
                        $key[1] --;
                        $key[0] ++;
                        $index = $d;
                        $url_str = str_replace("&amp;", "&", $href[1][$key[1]]);
                    }
                    if ($d < 0 && $key[1] > 1) {
                        $key[1] --;
                        $index = $d;
                        $url_str = str_replace("&amp;", "&", $href[1][$key[1]]);
                    }

                    if ($key[1] <= 1 && $key[0] >= $depth) {
                        $dep = $depth * 10;
                        //$dep=$depth;			
                        $data = array("not found in $dep", $site, $keyword);
                        return $data;
                    }
                }
            }
        }
    }
}

//Регистрирует функцию, которая выполнится по завершении работы скрипта
function shutdown() {
    global $activity_tablename, $seacher, $count_tablename, $countOfRequest, $user_id, $ObjDb;
// Это наша завершающая функция, 
// здесь мы можем выполнить кое-какую работу
// перед тем как скрипт полностью завершится.
//---------------------Говорим что закончили парсинг---------------------
    $query = "UPDATE $activity_tablename SET bool = '0' WHERE seacher = '$seacher'";
    $result = mysql_query($query);

    if (!$result) {
        error_message(mysql_error());
    }

//-------------------------Сохраняем значение числа запросов в базу-------------
    $query = "UPDATE $count_tablename SET count = '$countOfRequest' WHERE user_id = '$user_id' AND seacher = '$seacher'";
    $result = mysql_query($query);

    if (!$result)
        error_message(mysql_error());

//Если произошли ошибки выводим сообщение
    $error = error_get_last();
    if (isset($error)) {
        if ($error['type'] == E_ERROR || $error['type'] == E_PARSE || $error['type'] == E_COMPILE_ERROR || $error['type'] == E_CORE_ERROR) {
            print("В результате работы скрипта произошли критические ошибки, поиск прерван.<br/>");
            print("error: " . $error['message']);
        }
    }

    $ObjDb->db_close();
}

register_shutdown_function('shutdown');
?>