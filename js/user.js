					
var i=0;
var boolDate = 0;
var dateError = new Array(0,0,0);
var boolConstruct=0; 
var boolCheckN=0;// 1 - не запускается chekNick()
var boolCheckM=0;// 1 - не запускается checkEmail()
var boolSex=0;
var input=new Array(0,0,0,0,0);
				
	
function change() 
{	
    document.images["generation"].src="application/scripts/generation_of_pic.php?b="+i;
    i++;
}		
function checkEmail() 
{	
    if(boolCheckM==0)
    {
        var emailElement = document.getElementById("email1");
        var emailValue = document.getElementById("email1").value;
			
        if (emailValue == "") 
        {	
            emailElement.style.backgroundColor="#FFD9DD";
            emailElement.focus();
            //emailElement.focus();
            document.getElementById("emailText1").innerHTML="Пожалуйста введите E-mail для проверки!";
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
    var element = document.getElementById("emailText1");
    var elementMain = document.getElementById("email1");
            
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

function passwordValidate(id) 
{	
    var value = document.getElementById(id).value;
    var elementMain=document.getElementById(id);
    if(id=="password1")
    {
        var element = document.getElementById("passwordText1");
    }
    else if(id=="password2")
    { 
        var element = document.getElementById("passwordText2");
    }
    else{
        var element = document.getElementById("passwordText0");
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
            if(id=="password0")input[0]=0;
            if(id=="password1")input[1]=0;
            if(id=="password2")input[2]=0;
            return;	
        }
        if(myRegExp.test(value)==true)
        {
            if(id=="password1"||id=="password2"){
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
                        input[1]=1;
                        input[2]=1;
                        input[3]=1;
                        return;
                    }
						
                }
            }
            element.innerHTML="Верно";
            element.style.color="#4FDF4A";
            elementMain.style.backgroundColor="white";
            if(id=="password0")input[0]=1;
            if(id=="password1")input[1]=1;
            if(id=="password2")input[2]=1;
            return;
        }
        else
        {	
            element.innerHTML="Недопустимые символы";
            element.style.color="#FF4646";
            elementMain.style.backgroundColor="#FFD9DD";
            if(id=="password0")input[0]=0;
            if(id=="password1")input[1]=0;
            if(id=="password2")input[2]=0;
            return;
        }
    }
    else
    {
        element.style.color="#FF4646";
        elementMain.style.backgroundColor="white";
        element.innerHTML="";
        if(id=="password0")input[0]=0;
        if(id=="password1")input[1]=0;
        if(id=="password2")input[2]=0;
    }
}
function emailValidate(id) 
{
    var value = document.getElementById(id).value;
    var elementMain=document.getElementById(id);
    
    if(id=="email1")
    { 
        var element = document.getElementById("emailText1");
    }
    
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
            if(id=="email1")input[4]=1;
            return;
        }
        else {
            element.innerHTML="Неверный формат email!";
            element.style.color="#FF4646";
            elementMain.style.backgroundColor="#FFD9DD";
            if(id=="email1")input[4]=0;
            boolCheckM=1;
            return;
        }	
    }
    else
    {
        element.style.color="#FF4646";
        elementMain.style.backgroundColor="white";
        element.innerHTML="";
        if(id=="email1")input[4]=0;
        boolCheckM=1;
    }
	
}


/* 
    * To change this template, choose Tools | Templates
    * and open the template in the editor.
    */

$("#passContainerSlide").bind( "click", function() {
  $( "#passContainer" ).slideToggle( "slow", function() {
    // Animation complete.
    $(this).toggleClass("true");
  });
});
$("#emailContainerSlide").bind( "click", function() {
  $( "#emailContainer" ).slideToggle( "slow", function() {
    // Animation complete.
    $(this).toggleClass("true");
  });
});

$("li.button.Submit").click(function() {
    var passContainerClass = Boolean($( "#passContainer" ).attr('class'));
    var emailContainerClass = Boolean($( "#emailContainer" ).attr('class'));
   
    var summ = 0;
    input.forEach(function(item, i, arr) {
        summ+=parseInt(item);
    });
    
  if(summ==1||summ==4)
    {                
        var emailValue = document.getElementById("email1").value;
        var passValue0 = document.getElementById("password0").value;
        var passValue1 = document.getElementById("password1").value;
        var passValue2 = document.getElementById("password2").value;
        var passValue3 = document.getElementById("password").value;
			
        if(passContainerClass == true && emailContainerClass == true && emailValue && passValue0 && passValue1 && passValue2){            
            var url = "application/scripts/userPassEmail.php?email=" + emailValue + "&pass1=" + passValue1 + "&pass2=" + passValue2 + "&pass=" + passValue3 + "&pass0=" + passValue0;
        }
        else if( passContainerClass == true && passValue0 && passValue1 && passValue2 && emailContainerClass == false){
            var url = "application/scripts/userPassEmail.php?pass1=" + passValue1 + "&pass2=" + passValue2 + "&pass=" + passValue3 + "&pass0=" + passValue0;
        }
        else if (emailContainerClass == true && emailValue && passContainerClass == false) {
            var url = "application/scripts/userPassEmail.php?email=" + emailValue + "&pass=" + passValue3;
        }
        
        else{
            alert("Не заполнена необходимая информация!");
            return;
        }
        $.ajaxSetup({
            "type":"Get",
            "url":url,
            "success":function(sResponseText){
                savePassEmail_callBack(sResponseText);
            }                
        });
        $.ajax();
       
    }
    else{
        alert("Не заполнена необходимая информация!");
    }
});


function savePassEmail_callBack(sResponseText) 
{
   alert(sResponseText);
}

// кнопка Выход
$("li#1_0.button").bind({"click":function(){
	$("input:submit#1").click()}});