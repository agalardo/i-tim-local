<link rel="stylesheet" type="text/css" href="css/user.css" media="all"/>
<!--<link href="css/selects.css" rel="stylesheet" type="text/css" />-->
<div id="main">
    <form action="/savePass" method="post" name="form">
    	   
            <?php
                        echo "<p><b>User ". $_SESSION['nickname']."</b></p>";
                        ?>
                        <div id="passContainerSlide">Изменить пароль</div></br>
                        <div id="passContainer">
            <label for="password0">Старый пароль:</label><br/>
            <input name="password0" id="password0" type="password" size="20" maxlength="20" onChange="passwordValidate('password0')"/>
            <div id="passwordText0" style="display:inline; position:relative; left:15px; top:1px;"></div><br/>
            
            <label for="password1">Новый пароль:</label><br/>
            <input name="password1" id="password1" type="password" size="20" maxlength="20" onChange="passwordValidate('password1')"/>
            <div id="passwordText1" style="display:inline; position:relative; left:15px; top:1px;"></div><br/>
            
            <label for="password2">Повторите пароль:</label><br/>
            <input name="password2" id="password2" type="password" size="20" maxlength="20" onChange="passwordValidate('password2')"/>
            <div id="passwordText2" style="display:inline; position:relative; left:15px; top:1px;"></div><br/><br/>
            </div>
                        <div id="emailContainerSlide">Изменить E-mail</div></br>
                        <div id="emailContainer">
            <label for="email0">Старый E-mail:</label><br/>
            <input disabled="disabled" name="email0" id="email0" type="text" size="30" maxlength="40" value="<?php echo $data['email'];?>"/>
           	
            <div id="emailText0" style="display:inline; position:relative; left:15px; top:1px;"></div><br/><br/>
            
            <label for="email1">Новый E-mail:</label><br/>
            <input name="email1" id="email1" type="text" size="30" maxlength="40" onChange="emailValidate('email1')"/>
           	<a class="auth" href="javascript: checkEmail()" style="display:inline; position:relative; left:10px;">проверить уникальность</a>
            <div id="emailText1" style="display:inline; position:relative; left:15px; top:1px;"></div><br/><br/>
            </div>
           <div id="captchaContainer">
            <img src="application/scripts/generation_of_pic.php" name="generation" width="110" height="40"/>
            <a class="auth" href="javascript: change()" style="display:inline; position:relative; left:10px; top:-15px;">не видите символов?</a></br></br>
           <label for="password3"> Введите код с изображения:</label>
            <br/>
            <input name="password" id="password" type="text" size="10" maxlength="10"/>
            <br/></br>
            <ul><li id="2_0" class="button Submit">Отправить</li></ul>
            <input id="2" style="display:none;" type="submit" value="Отправить"/>
            </div>
        </div>
    </form>

<div  id="div" onClick="focusBody();"></div>
<div id="progressBar">сохранение</div>


<script type="text/javascript" src="js/user.js"></script>
<!--<script src="js/select/jquery.selects.js" type="text/javascript"></script>
<script src="js/select/jsScroll.js" type="text/javascript"></script>-->



