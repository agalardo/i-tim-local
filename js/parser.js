//автоматически глобальная переменная
var DataArray = new Array();
var CountOfRequest = new Array();
var Sites = new Array();
var bool = 0; // 1 - поиск завершен
var iteration=0;
var id=0; //идентификаторы таймеров
var id1=0;
var move=0;
var change = 0;// новые запросы не вводились
var selectChange = 0;// новый сайт из списка не выбирался 
//Используется в SaveTextarea как текущий элемент input site
var indexSelect=0;
var maxQuery = 50;
var boolAvail = 0;//Доступность поиска. 1 если производится перерыв
var query = 0;
var titleval=""; //Переменная для хранения title

//Обновляем стили progressBar---
function CSSProgessBar(style){
    if(style=='WidthAuto'){
        jQuery(function($){
            $("#progressBar").css({
                "width":"auto",
                "max-width":"300px",
                "height":"auto",                
                "padding":"30px 5px"
            }); 
        }) 
    }    
    else{
        jQuery(function($){
            $("#progressBar").css({
                "width":"150px",
                "height":"auto",                
                "padding":"30px 5px"
            }); 
        })
    }
  
}
//--------обновить капчу Яндекс
function reloadYandex(url_str){  
    
    
    url = document.getElementById("url").value; 
    user_id = document.getElementById("user_id").value;
    url = "application/scripts/reloadYandex.php?url_str=" + url_str + "&url=" + url+ "&user_id=" + user_id;

    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            bool=1;              
            document.getElementById("progressBar").innerHTML = sResponseText;
            CSSProgessBar('WidthAuto');
        }                
    });
    $.ajax();
}
//-----------Отправить капчу Яндекс
function SubmitCaptchaYandex(url_str){
   
    
    url = document.getElementById("url").value;
    rep = document.getElementById("rep").value;
    action = document.getElementById("action").value;
    key = document.getElementById("key").value;
    retpath = document.getElementById("retpath").value;
    submit2 = document.getElementById("submit2").value;
    user_id = document.getElementById("user_id").value;
    
    url = "application/scripts/sendCaptchaYandex.php?url=" + url+"&rep="+rep+"&action="+action+"&key="+key+"&retpath="+retpath+"&submit2="+submit2 + "&url=" + url+"&url_str=" + url_str+ "&user_id=" + user_id;
         	

    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            bool=1;  
            //Если возвращается форма с отправкой капчи отображаем ее в progressBar
            if(sResponseText.search("<form action='sendCaptcha.php' method='Get'>")!=-1){
                document.getElementById("progressBar").innerHTML = sResponseText;  
            }
            else{
                document.getElementById("progressBar").innerHTML = 'Капча отправлена';
                CSSProgessBar('WidthAuto');
            }
        }                
    });
    $.ajax();
}
function reloadGoogle(url_str){
    
    //    titleval = window.document.title;
    url = document.getElementById("url").value;
    user_id = document.getElementById("user_id").value;
    url = "application/scripts/reloadGoogle.php?url_str=" + url_str + "&url=" + url+ "&user_id=" + user_id;
       	
        
    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            bool=1;
           
            //            window.document.title = titleval;
            document.getElementById("progressBar").innerHTML = sResponseText; 
            CSSProgessBar('WidthAuto');
           
                       
        }                
    });
    $.ajax();
}
function SubmitCaptchaGoogle(url_str){
    //    titleval = window.document.title;
    
    url = document.getElementById("url").value;
    contin = document.getElementById("continue").value;
    action = document.getElementById("action").value;
    captcha = document.getElementById("captcha").value;
    submit = document.getElementById("submitG").value;
    id = document.getElementById("id").value;
    user_id = document.getElementById("user_id").value;
    
    url = "application/scripts/sendCaptchaGoogle.php?url=" + url+"&continue="+contin+"&action="+action+"&id="+id+"&captcha=" + captcha+"&url_str=" + url_str+"&submit=" + submit+"&user_id=" + user_id;
        	

    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            bool=1;
           
            //            window.document.title = titleval;
             
            document.getElementById("progressBar").innerHTML = sResponseText;
            
            CSSProgessBar('WidthAuto');
           
                       
        }                
    });
    $.ajax();
}

///-------------------------------------------------------------------------------
function Focus(id){
    var element = document.getElementById(id);
    element.value="";
	
}
function Blur(id,value){
    var element = document.getElementById(id);
    if(element.value==""){
        element.value=value;
    }		
}

function focusTextarea(){				
    var element = document.getElementById("h4");
    element.innerHTML="Введите запросы, каждый с новой строки";		
}
function ChangeTextarea(){
    var element = document.getElementById("h4");
    var text = document.getElementById("textarea").value;
    if(text == ""){
        element.innerHTML="Введите запросы, каждый с новой строки";	
    }	
    else{
        if(selectChange==1&&change==0){
            element.innerHTML="Cохраненные запросы";
        }
        else{
            element.innerHTML="Несохраненные запросы";
            change=1;
        }
        selectChange=0;
    }
}
function blurTextarea(){
    
    //Сохраняем данные из textarea в DataArray
    var index = indexSelect;	
    SaveTextarea(index);
    
    var element = document.getElementById("h4");
    if(change==0){		
        element.innerHTML="Сохраненные запросы";
    }
    else{
        element.innerHTML="Несохраненные запросы";
    }
}
function SaveTextarea(index){
    //Сохраняем данные из textarea в DataArray
    var text = document.getElementById("textarea").value;	
    var site = Sites[index];
	
    if(site==null){
        return site;
    }
	
    DataArray[index] = new Array();
    DataArray[index][0]=site;					
    var array = new Array();
    var MyRegExp = /[\w\d-_А-Яа-я.,":;' ]+/g;
    //массив из запросов
    array = text.match(MyRegExp);	
    var j;
    var k;
    for(j in array){
        k=parseInt(j)+1;
        DataArray[index][k]=array[j];
    }
  
    //Пересчитываем query
    query = 0;
    for(var i=0; i<DataArray.length; i++){
        query += DataArray[i].length-1;
    }
    document.getElementById("query").innerHTML = query;
    if(query>maxQuery){
        document.getElementById("query").style.color = 'red'; 
    }
    else{
        document.getElementById("query").style.color = '#31D231';
    }
          
    return site;			
}
function onChangeSelect(){
  
    selectChange = 1;
    //ChangeTextarea();	
    //Сохраняем данные из textarea в DataArray
    var index = indexSelect;	
    SaveTextarea(index);	
   
    //Вставляем данные из DataArray в textarea
    var textarea = document.getElementById("textarea");	
    index = document.getElementById("site").selectedIndex;		
    var array = DataArray[index];
    var count = array.length;
    var temp = new Array();
    var j;	
    for(var i=1; i<count; i++){
        j=i-1;
        temp[j]=array[i];
    }
    var text="";
    for(i=0; i<count-1; i++){
        if(i==count-2){
            text+=temp[i];
        }
        else{
            text+=temp[i]+"\n";
        }
    }	
    textarea.value = text;
    ChangeTextarea();
    indexSelect = index;
}
function onChangeSeacher(){
    index = document.getElementById("url").selectedIndex;
    url = document.getElementById("url").value;
    var seacher = url.split('.')[0];
     
    for(j in CountOfRequest){
        if(j==seacher){ 
            document.getElementById("countOfRequest").innerHTML = CountOfRequest[j];
        }
    }
    if(seacher=="yandex"){
        document.getElementById("time").value = 2;
        document.getElementById("random").value = 2;                
    }
    else{
        document.getElementById("time").value = 0;
        document.getElementById("random").value = 0;
    }
}
function ReloadCountOfRequest(){
  
    url = "application/scripts/CountOfRequest.php";

    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            //------------Читаем php данные $CountOfRequest
    CountOfRequest = $.evalJSON(sResponseText);
    onChangeSeacher();
        }                
    });
    $.ajax();
}
function SubmitDown(){
    //$.toJSON() из библиотеки library\jquery.json-2.3.js(плагин к jquery)
    // toJSON с ассоциативными массивами неработает
    
    //Сохраняем данные из textarea в DataArray
    var index = document.getElementById("site").selectedIndex;
    
    site = SaveTextarea(index);	
    if(site==null) return site;
   
    var encoded = $.toJSON(DataArray);
    var hidden = document.getElementById("data");
    hidden.value = encoded;
    return site;	
			
}
function doAnimation(){	
    if(bool==1){
        clearInterval(id);
        return;
    }
    if(iteration==0){
        document.getElementById("progressBar").innerHTML = '&nbsp;поиск.';
        iteration++;
        return;
    }
    if(iteration==1){
        document.getElementById("progressBar").innerHTML = '&nbsp;&nbsp;поиск..';
        iteration++;
        return;
    }
    if(iteration==2){
        document.getElementById("progressBar").innerHTML = '&nbsp;&nbsp;&nbsp;поиск...';
        iteration++;
        return;
    }
    if(iteration==3){
        document.getElementById("progressBar").innerHTML = 'поиск';
        iteration=0;
        return;
    }	
	
}
function SubmitDownAjax(){
    titleval = window.document.title;
    window.document.title = "Поиск...";
    
    bool=0;
    id = setInterval("doAnimation()", 400);
    document.getElementById("progressBar").innerHTML = 'поиск';
    SubmitDown();
    CSSProgessBar();
    
    //document.getElementById("progressBar").style.visibility = 'visible';
    //document.getElementById("div").style.visibility = 'visible';
    jQuery(function($){
        $("#progressBar").css({
            "visibility":"visible",
            "opacity":"0"
        }).fadeTo(300,1);
        $("#div").css({
            "visibility":"visible",
            "opacity":"0"
        }).fadeTo(300,0.5);
    })
    //-------------------------Проверяем доступность поиска---------------------
    if(boolAvail==1){        
        bool=1;
        document.getElementById("progressBar").innerHTML = 'Возможность поиска временно заблокирована';
        $("#availability").mouseover();       
        return;
    }
    //------------------------------------------------------------------------
    var data = document.getElementById("data").value;	
    var depth = document.getElementById("depth").value;   		
    var url = document.getElementById("url").value;
    var time = document.getElementById("time").value;
    var randomTime = document.getElementById("random").value;
    var user_id = document.getElementById("user_id").value; 
    
    if(url == 'yandex.ru'){
        var url = "application/scripts/parser_curl_yandex.php?depth=" + depth + "&url=" + url + "&time=" + time + "&randomTime=" + randomTime + "&submit=" + 1 + "&data=" + data + "&user_id=" + user_id;
    }
    else{
        var url = "application/scripts/parser_curl_google.php?depth=" + depth + "&url=" + url + "&time=" + time + "&randomTime=" + randomTime + "&submit=" + 1 + "&data=" + data + "&user_id=" + user_id;
    } 
    
    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            SubmitDownAjax_callBack(sResponseText);
        }                
    });
    $.ajax();
}
function SubmitDownAjax_callBack(sResponseText){
    bool=1;
    var MyRegExp = /\d{1,2}:\d{1,2}:\d{1,2}/;
    window.document.title = titleval;
    ReloadCountOfRequest();
    
    if(sResponseText==4){
        url = document.getElementById("url").value;
        document.getElementById("progressBar").innerHTML = 'Поисковая система '+url+' недоступна';
        CSSProgessBar();
        return;
    }
    else if(sResponseText==3){
        document.getElementById("progressBar").innerHTML = 'Возможность поиска временно заблокирована';
        $("#availability").mouseover();
        CSSProgessBar();
        return;
    }
    else if(sResponseText==1){
        document.getElementById("progressBar").innerHTML = 'Поиск завершен';
        CSSProgessBar();
        return;
    }
    else if(sResponseText==2){
        document.getElementById("progressBar").innerHTML = 'За последние 12 часов поиск уже осуществлялся';
        CSSProgessBar();
        return;
    }
    else if(MyRegExp.test(sResponseText)){
        document.getElementById("progressBar").innerHTML = 'Превышено максимально допустимое число обращений к поисковой системе. Счетчик обнулиться через '+sResponseText;
        CSSProgessBar();
        return;
    }
    else if(sResponseText==0){
        document.getElementById("progressBar").innerHTML = sResponseText;
        CSSProgessBar();
        return;
    }
    else{
        document.getElementById("progressBar").innerHTML = sResponseText;
        CSSProgessBar('WidthAuto');
        return;
    }
}
function doAnimationSave(){	
    if(bool==1){
        clearInterval(id);
        return;
    }
    if(iteration==0){
        document.getElementById("progressBar").innerHTML = '&nbsp;сохранение.';
        iteration++;
        return;
    }
    if(iteration==1){
        document.getElementById("progressBar").innerHTML = '&nbsp;&nbsp;сохранение..';
        iteration++;
        return;
    }
    if(iteration==2){
        document.getElementById("progressBar").innerHTML = '&nbsp;&nbsp;&nbsp;сохранение...';
        iteration++;
        return;
    }
    if(iteration==3){
        document.getElementById("progressBar").innerHTML = 'сохранение';
        iteration=0;
        return;
    }	
	
}
function SaveKeyword(){
    change = 0;
    bool=0;
    id = setInterval("doAnimationSave()", 400);
    document.getElementById("progressBar").innerHTML = 'сохранение';
    
    jQuery(function($){
        $("#progressBar").css({
            "visibility":"visible",
            "opacity":"0"
        }).fadeTo(300,1);
        $("#div").css({
            "visibility":"visible",
            "opacity":"0"
        }).fadeTo(300,0.5);
    })
    site = SubmitDown();
   
    //Проверка количества запросов
    if(query>maxQuery){
        SaveKeywordList_callBack("Количество запросов выше допустимого!");
        return;
    }
    //Прверка заданы ли сайты
    if(site==null){
        SaveKeywordList_callBack("Список сайтов пуст!");        
        return;
    }
	
    var user_id = document.getElementById("user_id").value;   		
    var jsonTextKeyword = $.toJSON(DataArray);	
	
    var url = "application/scripts/save_sites_keywords.php?jsonTextKeyword=" + jsonTextKeyword + "&user_id=" + user_id;
            	
    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            SaveKeywordList_callBack(sResponseText);
        }                
    });
    $.ajax();
}
function SaveKeywordList_callBack(sResponseText) {
    bool=1;		 
    if(sResponseText==1){
        document.getElementById("progressBar").innerHTML = 'Список запросов сохранен';
        document.getElementById("h4").innerHTML="Cохраненные запросы";
    }
    else{
        document.getElementById("progressBar").innerHTML = sResponseText;
        document.getElementById("h4").innerHTML="Несохраненные запросы";
    }  
}
function focusDiv(){
    if(bool==1&&move==0){
        jQuery(function($){
            $("#progressBar").fadeOut(300);
            $("#div").fadeOut(300);
        })
        document.getElementById("div").innerHTML = '';
    }
}
function focusPbDouble(){
    if(bool==1&&move==0){
        jQuery(function($){
            $("#progressBar").fadeOut(300);
            $("#div").fadeOut(300);
        })
        document.getElementById("div").innerHTML = '';
    }
}

jQuery(function($){
    //console.log("работает");
    
    $("li#1_0").bind({
        "click":function(){
            $("input:submit#1").click()
        }
    });
	
    $("#countOfRequest").hover(function(){
        $("#requestInfo").stop().animate({
            "opacity":"1"
        },220,"swing")
    });
    $("#countOfRequest").mouseout(function(){
        $("#requestInfo").stop().animate({
            "opacity":"0"
        },200,"swing")
    });
})
