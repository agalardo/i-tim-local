<?php
/*preg_match_all('/<a class="b-link b-link_cropped_no serp-item__title-link" href="http:\/\/([^>]+?)"[^>]+?>/', $response, $matches); //URL of site 30.07.2015
preg_match_all('/<a class="button button_theme_pseudo button_counter_yes button_pseudo_yes button_size_s b-link b-link_pseudo_yes i-bem" [^>]+? href="([^>]+?)"[^>]+?>/', $response, $matches_nav);  //URL of page 30.07.2015
preg_match_all('/<a class="button button_theme_pseudo button_counter_yes button_pseudo_yes button_size_s b-link b-link_pseudo_yes i-bem" [^>]+? href="[^>]+?"[^>]+?><span class="button__text">(\d{1,3})<\/span><\/a>/', $response, $page_nav);  //Number of page 30.07.2015

//Убираем из массива элементы соответствующие кнопкам навигации вперед назад
array_pop($matches_nav[1]);
if(count($page_nav[1]) < count($matches_nav[1])){
    array_shift($matches_nav[1]);
}
//-----------------------------------------------
*/
//12.08.2015
preg_match_all('/<a class="link serp-url__link" target="_blank" href="http:\/\/([^>]+?)"[^>]+?>/', $response, $matches); //URL of site 19.04.2015
preg_match_all('/<a class="button button_theme_pseudo button_counter_yes button_pseudo_yes button_size_s link link_pseudo_yes i-bem" [^>]+? href="([^>]+?)"[^>]+?>/', $response, $matches_nav);  //URL of page 24.04.2015
preg_match_all('/<a class="button button_theme_pseudo button_counter_yes button_pseudo_yes button_size_s link link_pseudo_yes i-bem" [^>]+? href="[^>]+?"[^>]+?><span class="button__text">(\d{1,3})<\/span><\/a>/', $response, $page_nav);  //Number of page 24.04.2015



//Убираем из массива рекламные ссылки и дубли искомых сайтов--------------------
$temp = array();
foreach ($matches[1] as $key => $value) {
    if(strpos($value, 'yabs.yandex.ru') === false) $temp[] = $value;
}
$matches[1] = $temp;
unset($temp);

$keyArr = array();
foreach ($matches[1] as $key => $value) {
    
    if(in_array($key, $keyArr)) continue;
    
    foreach ($matches[1] as $key2 => $value2) {
       if(strpos($value2, $value) !== false){
           $temp0[] = $value;
           $keyArr[] = $key2;
       }
    }
    $temp[] = array_pop($temp0);
    unset($temp0);
}


$matches[1] = $temp;
unset($temp);
unset($keyArr);
//------------------------------------------------------------------------------


//Убираем из массива элементы соответствующие кнопкам навигации вперед назад
array_pop($matches_nav[1]);
if(count($page_nav[1]) < count($matches_nav[1])){
    array_shift($matches_nav[1]);
}

?>