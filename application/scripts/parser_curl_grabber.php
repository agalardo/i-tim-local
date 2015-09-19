<?php

header('Content-Type: text/html; charset=UTF-8');

error_reporting(E_ALL ^ E_NOTICE);

?>
<?php

if ($_GET['submit'] == null) {
    echo"<h2>Задайте параметры поиска!</h2>";
    exit;
}

$time_start = time();
set_time_limit(600);

$url = $_GET['url'];
$depth = $_GET['depth'];
$depth = ceil($depth / 10);
$resultAdress = array();
$sites = array();
$countCurl = 0;

require_once('ggl_parser_curl_grabber.php');
require_once('../../libraries/idna_convert.class/idna_convert.class.php');

//------------------------Получаем из $_GET['data'] данные для запросов-----------------
$Data_text = $_GET['data'];
//------------------------json_decode работает только с UTF-8----------------------------
$Data = json_decode($Data_text, true);

if (!is_array($Data)) {
    $Data_text = iconv('windows-1251', 'utf-8', $Data_text);
    $Data = json_decode($Data_text, true);
}
unset($Data_text);

//-------------------Обход массива с данными $Data и выполнение запросов----------------

shuffle($Data); //мешаем элементы

/*главная функция
 * описана в ggl_parser_curl.php
 * осуществляет поиск и граббинг емэйл
 */

Main($depth, $url, $Data);


//Результаты парсинга здесь ----->>>       $resultAdress (array)((str)$keyword,(str)$site,(str)$title,(array)$email)
/*
 * Ответ возвращаемый index.php через ajax
 */

echo'Просмотрено ' . $countCurl . ' страниц<br/>';
echo'<table width="100%" id="resultTable" cellspacing="0">';
echo'<tr><td width="5%"><b>№</b></td><td width="10%"><b>keyword</b></td><td width="10%"><b>site</b></td><td width="45%"><b>title</b></td><td width="15%"><b>contact</b></td><td width="15%"><b>email</b></td></tr>';
foreach ($resultAdress as $i => $value) {
    echo'<tr>';
    echo'<td>';
    echo $i + 1;
    echo'</td>';
    foreach ($value as $i => $val) {
        if ($i == 4) {
            echo'<td>'; 
            if (is_array($val)) {
                echo'<ul>';
                foreach ($val as $email) {
                    echo'<li>';
                    echo "<nobr>".$email."</nobr>";
                    echo'</li>';
                }
                echo'</ul>';
            } 
            echo'</td>';
        } elseif ($i == 3) {
            echo'<td>';
            echo '<a href="http://' . $val . '">' . $val . '</>';
            echo'</td>';
        } else {
            echo'<td>';
            echo $val;
            echo'</td>';
        }
    }
    echo'</tr>';
}
echo'</table>';

/*Форма перехода на страницу result.php  с результатами в виде xml
 * 
 */
$xml = '<?xml version="1.0" encoding="UTF-8"?>';
$xml .= "<resultEmail>";
foreach ($resultAdress as $i => $value) {
    $xml.="<result><keyword>$value[0]</keyword><site>$value[1]</site><title>$value[2]</title><contact>$value[3]</contact><emailArray>";
    if (is_array($value[4])) {
        foreach ($value[4] as $k => $email) {
            $xml.="<email>" . $email . "</email>";
        }
    }
    $xml .="</emailArray></result>";
}
$xml .= "</resultEmail>";
$xml = urlencode($xml);
?><form action="/result" method="post">
    <input type="hidden" name="resultEmail" value="<?php echo $xml;?>" />  
    <input type="submit" id="1" style="display: none;" value="Result.xml"/>
    <br/>
    <ul class="buttonUl"><li id="1_0" class="button" onmousedown="$('#1_0').css({'background':'#2FAA00','color':'white'});"
                             onmouseup='$("#1_0").css({"background":"#A6A6A6","color":"black"});'
                             onmouseout='$("#1_0").css({"background":"#A6A6A6","color":"black"});'
                             onclick='$("input:submit#1").click();'>Result.xml</li></ul>
</form>
<?php
/*$filename = 'result/result.xml';

if (is_writable($filename)) {

    // В нашем примере мы открываем $filename в режиме "дописать в конец".
    // Таким образом, смещение установлено в конец файла и
    // наш $somecontent допишется в конец при использовании fwrite().

    if (!$handle = fopen($filename, 'w')) {
        echo "Не могу открыть файл ($filename)";
        exit;
    }

// Записываем $somecontent в наш открытый файл.
    if (fwrite($handle, $xml) === FALSE) {
        echo "Не могу произвести запись в файл ($filename)";
        exit;
    }
    fclose($handle);
} else {
    echo "Файл $filename недоступен для записи";
}*/
?>

