					
var i=0;
var boolDate = 0;
var dateError = new Array(0,0,0);
var boolConstruct=0; 
var boolCheckN=0;// 1 - не запускается chekNick()
var boolCheckM=0;// 1 - не запускается checkEmail()
var boolSex=0;
var input=new Array(0,0,0,0,0,0,0);
				
	
function change() 
{	
    document.images["generation"].src="application/scripts/generation_of_pic.php?b="+i;
    i++;
}		
function checkEmail() 
{	
    if(boolCheckM==0)
    {
        var emailElement = document.getElementById("email");
        var emailValue = document.getElementById("email").value;
			
        if (emailValue == "") 
        {	
            emailElement.style.backgroundColor="#FFD9DD";
            emailElement.focus();
            //emailElement.focus();
            document.getElementById("emailText").innerHTML="Пожалуйста введите E-mail для проверки!";
            return;
        }
        var url = "application/scripts/formvalidator.php?email=" + emailValue;
        
        $.ajaxSetup({
            "type":"Get",
            "url":url,
            "success":function(sResponseText){
                checkEmail_callBack(sResponseText);
            }                
        });
        $.ajax();
       
    }
}
function checkEmail_callBack(sResponseText) 
{
    var element = document.getElementById("emailText");
    var elementMain = document.getElementById("email")
            
    if (sResponseText == "available") 
    {
        element.innerHTML="Aдрес " + elementMain.value + " свободен!";
        element.style.color="#4FDF4A";
        elementMain.style.backgroundColor="white";
    } 
    else 
    {
        element.innerHTML="Извините, но адрес " + elementMain.value + " используется другим пользователем.";
        element.style.color="#FF4646";
        elementMain.style.backgroundColor="#FFD9DD";
    }
}
function checkNick() 
{	
    if(boolCheckN==0)
    {
        var nickElement = document.getElementById("nick");
        var nickValue = document.getElementById("nick").value;
				
        if (nickValue == "") 
        {	
            nickElement.style.backgroundColor="#FFD9DD";
            nickElement.focus();
            document.getElementById("nickText").innerHTML="Пожалуйста введите Ник для проверки!";
            return;
        }
            	
        var url = "application/scripts/formvalidator.php?nickname=" + nickValue;
            	
        $.ajaxSetup({
            "type":"Get",
            "url":url,
            "success":function(sResponseText){
                checkNick_callBack(sResponseText);
            }                
        });
        $.ajax();
    }
}
function checkNick_callBack(sResponseText) 
{	 
    var element = document.getElementById("nickText");
    var elementMain = document.getElementById("nick")
			 
    if (sResponseText == "available") 
    {
        element.innerHTML="Ник " + elementMain.value + " свободен!";
        element.style.color="#4FDF4A";
        elementMain.style.backgroundColor="white";
    } 
    else 
    {
        element.innerHTML="Извините, но ник " + elementMain.value + " используется другим пользователем.";
        element.style.color="#FF4646";
        elementMain.style.backgroundColor="#FFD9DD";
    }
}

    // Удалил поля имени оставил заглушку
    input[0]=1;
   
    // Удалил поля фамилии оставил заглушку
    input[1]=1; 
    // Удалил поля даты рождения оставил заглушку
    input[3]=1; 
    
    

function nickValidate() 
{
    var value = document.getElementById("nick").value;
    var elementMain=document.getElementById("nick");
    var element = document.getElementById("nickText");
    var myRegExp = /^[a-zа-я\d-_]?[a-zа-я\d-_]*[a-zа-я\d-_]?$/i;
    if(value!="")
    {
        if(myRegExp.test(value)==true)
        {
            element.innerHTML="Верно";
            element.style.color="#4FDF4A";
            txtNick_onchange();
            elementMain.style.backgroundColor="white";
            boolCheckN=0;
            input[2]=1;
            return;
        }
        else {
            element.innerHTML="Недопустимые символы";
            element.style.color="#FF4646";
            elementMain.style.backgroundColor="#FFD9DD";
            boolCheckN=1;
            return;
        }	
    }
    else
    {
        element.style.color="#FF4646";
        elementMain.style.backgroundColor="white";
        element.innerHTML="";
        boolCheckN=1;
    }
}	
function passwordValidate(id) 
{	
    var value = document.getElementById(id).value;
    var elementMain=document.getElementById(id);
    if(id=="password1")
    {
        var element = document.getElementById("passwordText1");
    }
    else
    { 
        var element = document.getElementById("passwordText2");
    }
    var myRegExp =/^[a-zа-я\d]?[a-zа-я\d]*[a-zа-я\d]?$/i;
    if(value!="")
    {	
        if(value.length<5)
        {
            element.innerHTML="Слишком короткий пароль! Используйте пароль не менее 5 символов!";
            element.style.color="#FF4646";
            elementMain.style.backgroundColor="#FFD9DD";
            elementMain.focus();
            return;	
        }
        if(myRegExp.test(value)==true)
        {	
            var element1=document.getElementById("password1");
            var element2 = document.getElementById("password2")
            if(element1.value!=""&&element2.value!="")
            {
                if(element1.value!=element2.value)
                {	
                    element.style.color="#FF4646";
                    element.innerHTML="Пароли не совпадают!";
                    elementMain.style.backgroundColor="#FFD9DD";
                    return;
                }
                else
                { 
                    document.getElementById("passwordText1").style.color="#4FDF4A";
                    document.getElementById("passwordText2").style.color="#4FDF4A";
                    document.getElementById("passwordText1").innerHTML="Верно";
                    document.getElementById("passwordText2").innerHTML="Верно";
                    document.getElementById("password1").style.backgroundColor="white";
                    document.getElementById("password2").style.backgroundColor="white";
                    input[5]=1;
                    return;
                }
						
            }
            element.innerHTML="Верно";
            element.style.color="#4FDF4A";
            elementMain.style.backgroundColor="white";
            input[4]=1;
            return;
        }
        else
        {	
            element.innerHTML="Недопустимые символы";
            element.style.color="#FF4646";
            elementMain.style.backgroundColor="#FFD9DD";
            return;
        }
    }
    else
    {
        element.style.color="#FF4646";
        elementMain.style.backgroundColor="white";
        element.innerHTML="";
    }
}
function emailValidate(id) 
{
    var value = document.getElementById(id).value;
    var elementMain=document.getElementById(id);
    var element = document.getElementById("emailText");
    //    var myRegExp =/^([a-zа-я\d-_]+(\.)?)+@([a-zа-я\d-]+(\.){1})+[a-zа-я]+$/i;   мой regexp
    var myRegExp = /^[-a-z0-9!#$%&'*+/=?^_`{|}~]+(?:\.[-a-z0-9!#$%&'*+/=?^_`{|}~]+)*@(?:[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])?\.)*(?:aero|arpa|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|[a-z][a-z])$/i;
    // Habrahabr regexp
    
    if(value!="")
    {	
        if(myRegExp.test(value)==true)
        {
            element.innerHTML="Верно";
            element.style.color="#4FDF4A";
            elementMain.style.backgroundColor="white";
            boolCheckM=0;
            input[6]=1;
            return;
        }
        else {
            element.innerHTML="Неверный формат email!";
            element.style.color="#FF4646";
            elementMain.style.backgroundColor="#FFD9DD";
            boolCheckM=1;
            return;
        }	
    }
    else
    {
        element.style.color="#FF4646";
        elementMain.style.backgroundColor="white";
        element.innerHTML="";
        boolCheckM=1;
    }
	
}

function txtNick_onchange() 
{
    window.status = "Здравствуйте " + document.form1.nick.value;
}

/* 
    * To change this template, choose Tools | Templates
    * and open the template in the editor.
    */


