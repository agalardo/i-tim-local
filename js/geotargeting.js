function reloadYandex(url_str){  
    
    url = 'yandex.ru';    
    url = "reloadYandex.php?url_str=" + url_str + "&url=" + url; 
    
    document.getElementById("result").innerHTML = '';      	
    
    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            bool=1;              
            document.getElementById("result").innerHTML = sResponseText; 
        }                
    });
    $.ajax();
}
//-----------Отправить капчу Яндекс
function SubmitCaptchaYandex(url_str){
   
    
    url = 'yandex.ru'; 
    rep = document.getElementById("rep").value;
    action = document.getElementById("action").value;
    key = document.getElementById("key").value;
    retpath = document.getElementById("retpath").value;
    submit2 = document.getElementById("submit2").value;
    
    url = "sendCaptchaYandex.php?url=" + url+"&rep="+rep+"&action="+action+"&key="+key+"&retpath="+retpath+"&submit2="+submit2 + "&url=" + url+"&url_str=" + url_str;
    document.getElementById("result").innerHTML = '';      	

    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            bool=1;           
            document.getElementById("result").innerHTML = sResponseText;             
        }                
    });
    $.ajax();
}
function reloadGoogle(url_str){
    
    //    titleval = window.document.title;
    url = 'google.ru';
    user_id = document.getElementById("user_id").value;
    url = "reloadGoogle.php?url_str=" + url_str + "&url=" + url+ "&user_id=" + user_id;
    //    document.getElementById("progressBar").innerHTML = '';  
    //document.getElementById("result").innerHTML = '';      	
        
    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            bool=1;
           
            //            window.document.title = titleval;
            document.getElementById("result").innerHTML = sResponseText; 
        //            document.getElementById("progressBar").innerHTML = 'Поиск завершен';
        //            document.getElementById("result").innerHTML = sResponseText;
        //            document.getElementById("div").style.height = getDocumentHeight()+'px';
           
                       
        }                
    });
    $.ajax();
}
function SubmitCaptchaGoogle(url_str){
    //    titleval = window.document.title;
    
    url = 'google.ru';
    contin = document.getElementById("continue").value;
    action = document.getElementById("action").value;
    captcha = document.getElementById("captcha").value;
    submit = document.getElementById("submitG").value;
    id = document.getElementById("id").value;
    user_id = document.getElementById("user_id").value;
    
    url = "sendCaptchaGoogle.php?url=" + url+"&continue="+contin+"&action="+action+"&id="+id+"&captcha=" + captcha+"&url_str=" + url_str+"&submit=" + submit+"&user_id=" + user_id;
        	

    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            bool=1;
           
            //            window.document.title = titleval;
             
            document.getElementById("result").innerHTML = sResponseText;
            
        //            document.getElementById("div").style.height = getDocumentHeight()+'px';
           
                       
        }                
    });
    $.ajax();
}


jQuery(function($){
    console.log("работает");
    
    $("#progressBar").bind({
            "click":function(){
                $(this).css({
                    "background":"#2FAA00",
                    "color":"white"
                })
            }
        });
})

