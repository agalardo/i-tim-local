
var change = false;
var iteration = 0;
var bool = 0;
function focusTextarea(){				
    var element = document.getElementById("h4");
    element.innerHTML="Введите URL сайтов в формате \"Site.com\", каждый с новой строки";
}
function blurTextarea(){
    var element = document.getElementById("h4");
    if(change==false){		
        element.innerHTML="Сохраненные данные";
    }
    else{
        element.innerHTML="Несохраненные данные";
    }
}
function changeTextarea(){
    change = true;
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
function SaveSites(){
	
    bool=0;
    id = setInterval("doAnimationSave()", 400);
    //document.getElementById("progressBar").style.visibility = 'visible';
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
    //document.getElementById("div").style.visibility = 'visible';
	
    var user_id = document.getElementById("user_id").value;		
    var site_list = document.getElementById("textarea").value;
	
    var array = new Array();
    var MyRegExp = /[\w-_А-Яа-я.\/]+/g;
    //массив из запросов
    array = site_list.match(MyRegExp);	
    var jsonTextSite = $.toJSON(array);	
    var url = "application/scripts/save_sites_keywords.php?jsonTextSite=" + jsonTextSite + "&user_id=" + user_id;
  
    $.ajaxSetup({
        "type":"Get",
        "url":url,
        "success":function(sResponseText){
            SaveSiteList_callBack(sResponseText);
        }                
    });
    $.ajax();
	
}
function SaveSiteList_callBack(sResponseText) {
    bool=1;		 
    if(sResponseText==1){
        document.getElementById("progressBar").innerHTML = 'Список сайтов сохранен';
        var element = document.getElementById("h4");
        change=false		
        element.innerHTML="Сохраненные данные";
    }
    else{
        //document.getElementById("progressBar").innerHTML = 'Произошла ошибка.';
        document.getElementById("progressBar").innerHTML = sResponseText;
    }
}
function focusBody(){
    if(bool==1){
        //document.getElementById("progressBar").style.visibility = 'hidden';
        //document.getElementById("div").style.visibility = 'hidden';
        jQuery(function($){
            $("#progressBar").fadeOut(300);
            $("#div").fadeOut(300);
        })
    }
}

jQuery(function($){
    //console.log("работает");
	
    $("li#4_0").bind({
        "click":function(){
            $("input:submit#4").click()
            }
        });
	
$("li#1_0").bind({
    "click":function(){
        $("input:submit#1").click()
        }
    });
	
$("li#submit").bind({
    "click":function(){
        $("input:submit#3").click()
        }
    });
	
//$(".button").bind({
//    "mousedown":function(){
//        $(this).css({
//            "background":"#2FAA00",
//            "color":"white"
//        })
//        }
//    });
//	
//$(".button").bind({
//    "mouseup mouseleave":function(){
//        $(this).css({
//            "background":"#A6A6A6",
//            "color":"black"
//        })
//        }
//    });
})
