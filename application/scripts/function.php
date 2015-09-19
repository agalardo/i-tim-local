<?php

//----------------------Создает массив из запросов $text textarea, кодирует в JSON, записывает в $file. Возвращает массив строк.----------------------
function write($text, $file) {

    preg_match_all('/[\w. ]+/', $text, $matches);

    foreach ($matches[0] as $v) {
        $array[] = $v;
    }

    if (!file_exists($file)) {
        for ($i = 0; $i < 1; $i++) {
            echo"Файл $file... еще не создан, создаем его...<br/>\r\n";
            flush();
            sleep(1);
        }
    } else {
        for ($i = 0; $i < 1; $i++) {
            echo "Перезаписываем $file...<br>";
            flush();
            sleep(1);
            $bool = 1;
        }
    }
    $text = json_encode($array);

    $fp = fopen($file, 'w') or die("Не могу создать файл");
    fwrite($fp, $text) or die("Не могу записать в файл");
    fclose($fp);
    if ($bool == 0) {
        for ($i = 0; $i < 1; $i++) {
            echo "Файл создан.<br/>\r\n";
            flush();
        }
    } else {
        for ($i = 0; $i < 1; $i++) {
            echo "Файл перезаписан.<br/>\r\n";
            flush();
        }
    }
    return $array;
}

//----------------------Дополняет $Data данными из $text и записывает в $file и возвращает новый массив $Data.----------------------
function save_site($text, $file, $Data) {
    //Регулярка для Unicode 
    preg_match_all('/[\pL\pN\pP]+/u', $text, $matches);
    foreach ($matches[0] as $v) {
        $array[] = mb_strtolower($v, 'UTF-8');
    }
    if (count($Data) != 0) {
        foreach ($Data as $value) {
            $Sites[] = strtolower($value[0]);
        }
        $deleted_sites = array_unique(array_diff($Sites, $array));
        $add_sites = array_unique(array_diff($array, $Sites));

        foreach ($deleted_sites as $key => $value) {
            unset($Data[$key]);
        }
        foreach ($add_sites as $value) {
            $Data[] = array($value);
        }
    } else {
        foreach ($array as $value) {
            $Data[] = array($value);
        }
    }
    if (count($deleted_sites) != 0) {
        $temp = $Data;
        $Data = null;
        foreach ($temp as $value) {
            $Data[] = $value;
        }
        unset($temp);
    }

    //Ассоциативный массив json_encode() кодирует как объект stdClass-а(ключ-значение)???????????????		
    $text = json_encode($Data);
    write_text($text, $file);

    return $Data;
}

//----------------------Записывает текст в $file.----------------------
function write_text($text, $file) {

    if (!file_exists($file)) {
        for ($i = 0; $i < 1; $i++) {
            echo"Файл $file... еще не создан, создаем его...<br/>\r\n";
            flush();
            sleep(1);
        }
    } else {
        for ($i = 0; $i < 1; $i++) {
            echo "Перезаписываем $file...<br>";
            flush();
            sleep(1);
            $bool = 1;
        }
    }
    $fp = fopen($file, 'w') or die("Не могу создать файл");
    fwrite($fp, $text) or die("Не могу записать в файл");
    fclose($fp);
    if ($bool == 0) {
        for ($i = 0; $i < 1; $i++) {
            echo "Файл создан.<br/>\r\n";
            flush();
        }
    } else {
        for ($i = 0; $i < 1; $i++) {
            echo "Файл перезаписан.<br/>\r\n";
            flush();
        }
    }
}

//----------------------Читает JSON $file.----------------------
function read_json($file) {
    if (!file_exists($file)) {
        echo"Файл $file с данными не найден!";
    } else {
        $fp = fopen("$file", "r");
        if (!$fp)
            die("Невозможно открыть файл $file");
        while (!feof($fp)) {
            $text.=fgets($fp);
        }
        fclose($fp);
        $object = json_decode($text, true); //при true возвращается массив					
    }
    return $object;
}

//----------------------Функция времени. $time_1,$time_2 - временные метки unix------------------
function time_execution($time_1, $time_2) {
    $time = $time_2 - $time_1;
    $time_a['h'] = floor($time / 3600);
    $time = $time - $time_a['h'] * 3600;
    $time_a['m'] = floor($time / 60);
    $time_a['s'] = $time - $time_a['m'] * 60;
    foreach ($time_a as $k => $v) {
        if ($v < 10) {
            $v = "0" . $v;
        }
        $time_a[$k] = $v;
    }
    $time = $time_a['h'] . ":" . $time_a['m'] . ":" . $time_a['s'];
    echo "Скрипт выполнялся " . $time;
}

function error_message($msg) {    
    echo "Error: $msg";   
    exit;
}

?>