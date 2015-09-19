<link rel="stylesheet" type="text/css" href="css/parser.css" media="all"/>
<!--<link href="css/selects.css" rel="stylesheet" type="text/css" />-->
<?php
$Sites = $data['Sites'];
$textArray = $data['textArray'];
$Data = $data['Data'];
$CountOfRequest = $data['CountOfRequest'];
?>
<div id="main">

    <table width="100%" border="0">
        <form method="post" action="" id="form">
            <tr><td width="45%">
                    <b>Выберите сайт для задания ключевых слов:</b></td>
                <td><span  id="countOfRequest"><?php echo $CountOfRequest['google'] ?></span>
                    <span id="requestInfo">Число доступных запросов</span></td>
            </tr>
            <tr><td><!--Блок для задания ширины при подключении selects.js--><div class="wid200">
                        <select name="site" id="site" onChange="onChangeSelect()">

                            <?php
                            echo'<option selected="selected" value="' . $Sites[0] . '">' . $Sites[0] . '</option>';
                            for ($i = 1; $i < count($Sites); $i++) {
                                echo'<option value="' . $Sites[$i] . '">' . $Sites[$i] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </td>
                <td rowspan="3">
                    <table border="0">
                        <tr><td>
                                <table>
                                    <tr><td>Выберите глубину поиска:</td></tr>
                                    <tr><td><!--<input name="depth" id="depth" type="text" size="30" maxlength="3" onFocus="Focus('depth')" onBlur="Blur('depth','50')" value="50"/>-->
                                            <div class="wid200"><select name="depth" id="depth">
                                                    <?php
                                                    for ($i = 1; $i <= 100; $i++) {
                                                        $val = $i * 10;
                                                        if ($i == 5) {
                                                            echo'<option value="' . $val . '" selected="selected">' . $val . '</option>';
                                                            continue;
                                                        }
                                                        echo'<option value="' . $val . '">' . $val . '</option>';
                                                    }
                                                    ?>
                                                </select></div></td></tr>
                                    <tr><td>Выберите URL поисковика:</td></tr>
                                    <tr><td><div class="wid200"><select name="url" id="url" onChange="onChangeSeacher()">
                                                    <option value="google.ru" selected="selected">google.ru</option>
                                                    <option value="yandex.ru">yandex.ru</option>
                                                    <option value="google.com">google.com</option>
                                                </select></div>
                                        </td></tr>
                                    <tr><td>Задайте время задержки:</td></tr>
                                    <tr><td><input name="time" id="time" type="text" size="30" maxlength="2" onFocus="Focus('time')" onBlur="Blur('time','0')" value="0"/></td></tr>
                                    <tr><td>+ "random" время задержки:</td></tr>
                                    <tr><td><input name="randomTime" id="random" type="text" size="30" maxlength="2" onFocus="Focus('random')" onBlur="Blur('random','0')" value="0"/></td></tr>
                                    <tr><td><input name="shuffle" type="checkbox" value=1 checked/> Перемешать запросы</td></tr>
                                </table>
                            </td></tr>
                    </table>
                </td>
            </tr>
            <tr><td>
                    <table width="100%" border="0" style="padding: 0; border-collapse: collapse;">    
                        <tr><td width="310px">
                                <h4 id="h4">Введите запросы, каждый с новой строки</h4></td>
                            <td id="query">Query</td>
                        </tr></table>
                </td>
            </tr>
            <tr><td>
                    <TEXTAREA style="width:350px; height:300px; overflow:hidden;" name="textarea" onFocus="focusTextarea()" onChange="ChangeTextarea()" onBlur="blurTextarea()" id="textarea"><?php
                                                    if (count($textArray) != 0) {
                                                        for ($i = 0; $i < count($textArray); $i++) {
                                                            echo $textArray[$i] . "\n";
                                                        }
                                                    }
                                                    ?></TEXTAREA>
                    </br><br>

                    <input type="hidden" name="data" id="data" value="">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <ul><li id="2" class="button" onClick="SaveKeyword();">Сохранить</li>
                        <li id="submit" class="button" onClick="SubmitDownAjax();">Поиск</li>
                    <li id="submit" class="button" onClick="document.location.href = 'services';">Назад  к сайтам</li></ul>
                        <!--<input type="submit" sty name="submit" value="Сохранить" onClick="SaveKeyword();return false;"/>-->
                        <!--<input id="3" style="display:none;" type="submit" name="submit" onClick="SubmitDownAjax();return false;" value="Поиск ajax"/>-->
        </form>
        </td></tr>
    </table>
</div><br />
<!--<center><a href="services">Назад  к сайтам</a></center>-->
<div  id="div" onClick="focusDiv();"></div>
<div id="progressBar" onDblClick="focusPbDouble();">поиск</div>


<?php if (count($textArray) == 0) { ?>
    <script type="text/javascript">				
        var element = document.getElementById("h4");
        element.innerHTML="Введите запросы, каждый с новой строки.";
    </script>
    <?php
} else {
    ?>
    <script type="text/javascript">				
        var element = document.getElementById("h4");
        element.innerHTML="Сохраненные запросы";
    </script>
    <?php
}
?> 

<?php
$jsonText = json_encode($Data);
$jsonTextCountOfRequest = json_encode($CountOfRequest);
?>

<script type="text/javascript" src="js/parser.js"></script>
<!--<script src="js/select/jquery.selects.js" type="text/javascript"></script>
<script src="js/select/jsScroll.js" type="text/javascript"></script>-->

<script type="text/javascript">
    //------------Читаем php данные $Data 			
    DataArray = $.evalJSON('<?php echo $jsonText; ?>');
    var query = 0;//число запросов
    for(var i=0; i<DataArray.length; i++){
        Sites[i]=DataArray[i][0];
        query += DataArray[i].length-1;
    }
    document.getElementById("query").innerHTML = query;
    
    //------------Читаем php данные $CountOfRequest
    CountOfRequest = $.evalJSON('<?php echo $jsonTextCountOfRequest; ?>');
</script>





