<link rel="stylesheet" type="text/css" href="css/statistic.css" media="all"/>

<div id="main" style="background-color:#EAFBEC">
    <?php
    if ($data == 1) {
        echo"<h2>Задайте параметры статистики!</h2>";
    } else {
        $ArrayPositionData = $data['ArrayPositionData'];
        $PositionData = $data['PositionData'];
                
//        echo"<pre>";
//        print_r($PositionData);
//        echo"</pre>";
//        exit;
        
        $site = urlencode($data['site']);
        $seacher = $data['seacher'];

        if (count($ArrayPositionData) > 0) {
            echo'<table width="100%" border="0"><tr><td>';
            echo"<table width='100%' border='0'>";
            //--------------показываем статистику только для отпарсеных запросов--------------------------
            foreach ($ArrayPositionData as $key => $val) {
                if ($key == 0) {
                    echo"<tr><td><b>Выберите запрос:</b></td></tr>";
                    echo"<tr><td><a class='keyword' href='statistic?keyword=$val[0]&site=$site&url=$seacher'>$val[0]</a></td></tr>";
                }
                else
                    echo"<tr><td><a class='keyword' href='statistic?keyword=$val[0]&site=$site&url=$seacher'>$val[0]</a></td></tr>";
            }
            echo'</table>';
            echo'<ul><li id="submit" class="button" onClick="document.location.href = '."'services'".';">Назад  к сайтам</li></ul>';
            echo'</td><td>';
            $site = urldecode($site);
            require_once "application/scripts/statisticGraph.php";
            echo'<div id="container" style="min-width: 800px; height: 450px;  margin: 0 auto"></div>';
            echo'</td></tr>';
            echo"</table>";
        }
        else
            echo"<table width='100%' border='0'><tr><td><h1 align='center'>Результатов пока нет</h1></td></tr></table>";
    }
    ?>
</div>


<!--<center><a href="services" style="position:relative; top:0px;">Назад  к сайтам</a></center>-->

<script type="text/javascript" src="js/statistic.js"></script>
<script src="libraries/Highcharts-2.3.2/js/highcharts.js" type="text/javascript"></script>
<script src="libraries/Highcharts-2.3.2/js/modules/exporting.js" type="text/javascript"></script>



