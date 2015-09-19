<form method="post" action="services">
    <center>
        <table id="authtor" border="1" width="250" cellpadding="0" cellspacing="3"  bgcolor="#E5E5E5">
            <tr><td width="100%" colspan="2" align="center" nowrap="nowrap">Вход</td></tr>
            <tr><td width="30%" align="right" nowrap="nowrap">Логин</td>
                <td width="70%" nowrap="nowrap">
                    <input type="hidden" name="action" value="login"/>
                    <input type="text" name="nickname" size="24" maxlength="20"/>
                </td>
            </tr>
            <tr><td width="30%" align="right" nowrap="nowrap">Пароль</td>
                <td width="70%" nowrap="nowrap"><input type="password" name="userpassword" size="24" maxlength="20" /></td>
            </tr>
            <tr><td width="100%" colspan="2" align="center" nowrap="nowrap">
                    <ul><li id="3_0" class="button">Войти</li></ul><br/>
                    <input id="3" style="display:none;" type="submit" value="Отправить" name="login"/></td></tr>
        </table></center>
</form>