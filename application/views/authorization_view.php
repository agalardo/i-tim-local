<style type="text/css">
div.fieldset ul{
	display:inline;
	margin:0;
	padding:0;
}

div.fieldset li{
	display:inline-block;
}
div.fieldset{width: 960px;margin: 0 auto;margin-top: 20px;margin-bottom: 20px;}
div.fieldset input{text-align: left;}
div.fieldset img{border: 1px solid #464646;}
div.fieldset h1{text-align:center; line-height:14px;font-size:18px; font-weight:100;}

a.auth:link {color:#666; /* Цвет ссылок */}
a.auth:visited{color:#666;}
a.auth:hover {color:#2159C0; /* Цвет ссылки */} 
</style>
<script type="text/javascript" src="js/HttpRequest.js"></script>
<script type="text/javascript" src="js/selectLock.js"></script>
<script type="text/javascript" src="js/authorization.js"></script>
    
	<form action="/register" method="post" name="form1">
    	<div class="fieldset" style="background-color:#EAFBEC;" id="elementParent">
        	<center><b>Регистрация</b></center>    
            
            <input type="hidden" name="action" value="register"/>
          
            
            <label for="nickname">Ник:</label><br/>
            <input name="nick" id="nick" type="text" size="20" maxlength="20" onChange="nickValidate('nick')"/>
            <a class="auth" href="javascript: checkNick()" style="display:inline; position:relative; left:10px;">проверить уникальность</a>
            <div id="nickText" style="display:inline; position:relative; left:15px; top:1px;"></div><br/>
            
            
            <label for="password1">Пароль:</label><br/>
            <input name="password1" id="password1" type="password" size="20" maxlength="20" onChange="passwordValidate('password1')"/>
            <div id="passwordText1" style="display:inline; position:relative; left:15px; top:1px;"></div><br/>
            
            
            <label for="password2">Повторите пароль:</label><br/>
            <input name="password2" id="password2" type="password" size="20" maxlength="20" onChange="passwordValidate('password2')"/>
            <div id="passwordText2" style="display:inline; position:relative; left:15px; top:1px;"></div><br/>
             
            <label for="email">E-mail:</label><br/>
            <input name="email" id="email" type="text" size="30" maxlength="40" onChange="emailValidate('email')"/>
           	<a class="auth" href="javascript: checkEmail()" style="display:inline; position:relative; left:10px;">проверить уникальность</a>
            <div id="emailText" style="display:inline; position:relative; left:15px; top:1px;"></div><br/><br/>
            
            Выберте ваш пол:<div id="sexText" style="display:inline; position:relative; left:15px; top:1px;"></div><br/>
            <label>
            	<input type="radio" name="sex" value="1" onFocus="sexFocus()"/>
                Я мужчина
            </label><br/>
            <label>
            	<input type="radio" name="sex" value="0" onFocus="sexFocus()"/>
                Я женщина
            </label><br/></br>
            <img src="application/scripts/generation_of_pic.php" name="generation" width="110" height="40"/>
            <a class="auth" href="javascript: change()" style="display:inline; position:relative; left:10px; top:-15px;">не видите символов?</a></br></br>
           <label for="password3"> Введите код с изображения:</label>
            <br/>
            <input name="password" id="password" type="text" size="10" maxlength="10"/>
            <br/></br>
            <ul><li id="1_0" class="button">Отправить</li></ul>
            <input id="1" style="display:none;" type="submit" value="Отправить"/>
        </div>
    </form>
<center id="onmain"><a href=".">На главную</a></center>
