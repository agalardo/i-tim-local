<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Парсер поисковых систем</title>

<link rel="stylesheet" type="text/css" href="css/parser_main.css" media="all"/>
<link rel="stylesheet" type="text/css" href="css/index.css" media="all"/>
</head>
<body bgcolor="#C4FFBB">
<center>
<table width="1000" border="0" id="header">
<tr>        
<?php
if(!isset($_SESSION['nickname'])){
	echo'<td width="10%" id="title0"><a id="registrA" href="/authorization">Регистрация</a></td>';
}
else{echo"<td width='13%'>Геотаргетинг <a href='application/scripts/geotargeting_google.php' title='Геотаргетинг Google'>1</a>&nbsp;
    <a href='application/scripts/geotargeting_yandex.php' title='Геотаргетинг Yandex'>2</a></td>";}
echo'<td width="20%"><a href="/emailgrabber" title="Поиск коллекций email">Email Grabber</a></td>';
?>
<td width="33%" id="title">SEO PARSER</td>
<?php
if(isset($_SESSION['nickname'])){				
	echo"<td width='33%' id='title1'><table border='0' width='100%'><tr><form class='parserForm' method='post' action='services'>"
	."<td width='53%'>User ".$_SESSION['nickname']."</td>".
	"<td width='23%'>
	<ul><li id='1_0' class='button'>Парсер</li></ul>
	<input id='1' style='display:none;' type='submit' value='Парсер'/></td>
	</form>".
	"<form class='parserForm' method='post' action='/main'>".
	"<td width='23%'>
	<ul><li id='2_0' class='button'>Выход</li></ul>
	<input id='2' style='display:none;' type='submit' name='exit' value='Выход'/></td>
	</form></tr></table></td>";
}
else{echo"<td width='33%'></td>";}
?>
</tr>
</table>
</center>
<hr id="sidebar_hr"/>
<noscript>В вашем браузере отключен Java Script, включите его и обновите сраницу.</noscript>
<div id="content">
<?php
if(!isset($_SESSION['nickname'])){
    require_once 'application/views/'.$content_view;
}
else{
    $routes = explode('/', $_SERVER['REQUEST_URI']);
    if(count($routes)==2&&strtolower($routes[1])=='register'){
        require_once 'application/views/'.$content_view;
    }
}
?>
</div>
<script type="text/javascript" src="js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="js/index.js"></script>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
/*(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter21171994 = new Ya.Metrika({id:21171994,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");*/
</script>
<noscript><div><img src="//mc.yandex.ru/watch/21171994" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>

