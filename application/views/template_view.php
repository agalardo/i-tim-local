<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Парсер поисковых систем</title>

        <link rel="stylesheet" type="text/css" href="css/parser_main.css" media="all"/>
        <script type="text/javascript" src="js/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="js/jquery.json-2.3.js"></script>
    </head>
    <body>

        <table width="100%" border="0" id="topTable">
            <tr><td align="left"><a href=".">На главную</a></td>
                <td align="right"><form method="post" style="display:inline;" action=".">
                        <?php
                        echo "User <a href='user'>".$_SESSION['nickname']."</a>";
                        ?>
                        <ul><li id="1_0" class="button">Выход</li></ul>
                        <input id="1" style="display:none;" type="submit" name="exit" value="Выход"/>
                    </form></td></tr>
            <tr><td id="title" colspan="3" align="center">SEO PARSER</td></tr>
        </table>
        <?php
        require_once 'application/views/' . $content_view;
        ?>
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
            })(document, window, "yandex_metrika_callbacks");
        */</script>
        <noscript><div><img src="//mc.yandex.ru/watch/21171994" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->
    </body>
</html>