//автоматически глобальная переменная
var DataArray = new Array();
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

function SaveTextarea(){
    //Сохраняем данные из textarea в DataArray
    var text = document.getElementById("textarea").value;	
    
    var array = new Array();
    var MyRegExp = /[\w-_А-Яа-я ]+/g;
    //массив из запросов
    if(!MyRegExp.test(text)){
        return 0;
    }
                
    array = text.match(MyRegExp);	
    var j;
    var k;
    DataArray = new Array();
    for(j in array){
        DataArray[j]=array[j];
    }
    
    if(DataArray.length > 5){
        return 1;
    }
}
function SubmitDown(){
    //$.toJSON() из библиотеки library\jquery.json-2.3.js(плагин к jquery)
    // toJSON с ассоциативными массивами неработает
	
    //DataArray кодируется в JSON и присваивается скрытому полю data
		
    var checkTextarea = SaveTextarea();
    if(checkTextarea==0){
        return 0;
    }
    if(checkTextarea==1){
        return 1;
    }
    
    var encoded = $.toJSON(DataArray);
    var hidden = document.getElementById("data");
    hidden.value = encoded;			
}
function doAnimation(){
    /**
                 *Меняет прогресс бар непосредственно
                 */
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
    /*
                 *Выполняется при нажатии на кнопку "поиск"
                 *передает данные скрипту посредствоя аякс
                 *ждет ответа, ответ выводит в элементе result
                 **/
    var titleval = window.document.title;  
                
    bool=0;
    id = setInterval("doAnimation()", 400);
    document.getElementById("progressBar").innerHTML = 'поиск';
                                 
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
    
    var checkTextarea = SubmitDown();
    if(checkTextarea==0){
        bool=1;
        document.getElementById("progressBar").innerHTML = 'Введите запросы';
        return;
    }
     if(checkTextarea==1){
        bool=1;
        document.getElementById("progressBar").innerHTML = 'Можно указать не больше 5 запросов';
        return;
    }
    
    window.document.title = "Поиск...";
    var data = document.getElementById("data").value;
    var url = document.getElementById("url").value;   
    var depth = document.getElementById("depth").value; 
	
    var url = "application/scripts/parser_curl_grabber.php?submit=" + 1 + "&data=" + data + "&url=" + url+ "&depth=" + depth;
            	
    /* var request = new HttpRequest(url,SubmitDownAjax_callBack);
                request.send();*/
    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            bool=1;
            if(sResponseText==0){
                document.getElementById("progressBar").innerHTML = 'Парсер заблокирован google';
            }
            else{
                window.document.title = titleval;
                document.getElementById("progressBar").innerHTML = 'Поиск завершен';
                document.getElementById("result").innerHTML = sResponseText;
                document.getElementById("div").style.height = getDocumentHeight()+'px';
            }
        }                
    });
    $.ajax();
}
function focusBody(){
    /*Выполняется при клике на первый слой за прогрессбаром
                 *убирает прогресс бар
                 **/
    if(bool==1&&move==0){
        //document.getElementById("progressBar").style.visibility = 'hidden';
        //document.getElementById("div").style.visibility = 'hidden';
        jQuery(function($){
            $("#progressBar").fadeOut(300);
            $("#div").fadeOut(300);
        })
    }
}
function focusPbDouble(){
    /*Выполняется при 2-ом клике на прогресс баре
             *убирает прогрессбар
             **/
    if(bool==1&&move==0){
        jQuery(function($){
            $("#progressBar").fadeOut(300);
            $("#div").fadeOut(300);
        })
    }
}
         



