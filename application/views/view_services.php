<link rel="stylesheet" type="text/css" href="css/services.css" media="all"/>
<!--<link href="css/selects.css" rel="stylesheet" type="text/css" />-->
<div id="main">
    <table width="100%" border="0">
        <tr><td width="65%"><b>Список сайтов</b></td>
            <td id="stat" rowspan="100">
                <?php
                $SiteArray = $data['sitearray'];
                $bool = $data['bool'];
                $keywordBool = $data['keywordbool'];
                $seacher = $data['seacher'];
//--------------Показываем статистику если заданы сайты и запросы---------------- 
                if (count($SiteArray) > 0 && $bool == 1) {
                    echo'<table border="0" id="statistic">
	<tr><td><b>Cтатистика</b></td></tr>
	<tr><td rowspan="3">
	<form method="post" action="statistic">
	<table align="left" border="0"><tr><td>Выберите URL поисковика:</td>
	<td><div class="wid200">
	<select name="url" id="url">';
                    foreach ($seacher as $url) {
                        if ($url == $_SESSION['stat_seacher']) {
                            echo"<option value=$url selected='selected'>$url</option>";
                        } else {
                            echo"<option value=$url>$url</option>";
                        }
                    }
                    echo'</select></div>
    </td></tr>
    <tr><td>Выберите сайт:</td>
    <td>';
                    //без id='site' select.js не отображает выбранное по умолчанию значение
                    echo"<div class='wid200'><select name='site' id='site'>";

                    for ($i = 0; $i < count($SiteArray); $i++) {
                        if ($keywordBool[$i] == 1) {
                            if ($_SESSION['site'] == $SiteArray[$i]) {
                                echo"<option value='$SiteArray[$i]' selected='selected'>$SiteArray[$i]</option>";
                            } else {
                                echo"<option value='$SiteArray[$i]'>$SiteArray[$i]</option>";
                            }
                        }
                    }
                    echo"</select></div>";

                    echo'</td></tr>
    <tr><td align="center" colspan="2">
	<ul><li id="4_0" class="button">Вперед</li></ul>
    <input id="4" style="display:none;" type="submit" name="submitStat" value="Вперед"/>
    </td></tr></table>
	</form></td>
	</tr>
	</table>';
                }
//------------------------------------------------------------------------------
                ?>
            </td>
        </tr>
        <tr><td><h4 id="h4">Введите URL сайтов в формате "Site.com", каждый с новой строки</h4></td>
            <td></td></tr>
        <tr><td rowspan="<?php echo count($SiteArray); ?>">
                <form method="post" action="parser" id="form">

                    <TEXTAREA style="width:350px; height:300px; overflow:hidden;" id="textarea" name="textarea" onFocus="focusTextarea()" onBlur="blurTextarea()" onChange="changeTextarea()"><?php
                if (count($SiteArray) != 0) {
                    for ($i = 0; $i < count($SiteArray); $i++) {
                        echo $SiteArray[$i] . "\n";
                    }
                }
                ?></TEXTAREA>
                    </br>
                    </br>
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <!--<input id="2" type="submit" name="submit" value="Сохранить" onClick="SaveSites();return false;"/>-->
                    <ul><li id="2" class="button" onClick="SaveSites();">Сохранить</li>
                        <li id="submit" class="button">Вперед</li></ul>
                    <input id="3" style="position:relative; left:80px; display:none;" type="submit" name="submit" value="Вперед"/>
                </form></td></tr>
    </table>
</div>
<div  id="div" onClick="focusBody();"></div>
<div id="progressBar">сохранение</div>
<?php if (count($SiteArray) == 0) { ?>
    <script type="text/javascript">				
        var element = document.getElementById("h4");
        element.innerHTML="Введите URL сайтов в формате \"Site.com\", каждый с новой строки";
    </script>
    <?php
} else {
    ?>
    <script type="text/javascript">				
        var element = document.getElementById("h4");
        element.innerHTML="Сохраненные данные";
                                                
    </script>
    <?php
}
?>

<script type="text/javascript" src="js/services.js"></script>
<!--<script src="js/select/jquery.selects.js" type="text/javascript"></script>
<script src="js/select/jsScroll.js" type="text/javascript"></script>-->



