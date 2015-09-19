<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="examples.css" rel="stylesheet" type="text/css" />
<link href="selects.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.wid200 {
width: 200px;
}
.wid100 {
width: 100px;
}
.lineForm {
margin: 20px 0 0 20px;
}
-->
</style>
<script src="../jquery-1.7.1.js" type="text/javascript"></script>
<script src="jquery.selects.js" type="text/javascript"></script>
<script src="jsScroll.js" type="text/javascript"></script>

</head>
<body>
<div class="main">
	<div class="lineForm">
		<select class="wid200" id="country">
		<option value="1">Россия</option>
		<option value="2" selected="selected">Украина</option>
		<option value="3">Чехия</option>
		<option value="4">Словакия</option>
		<option value="5">Румыния</option>
		<option value="6">Болгария</option>
		<option value="7">Польша</option>
		<option value="8">Германия</option>
		<option value="9">Франция</option>
		<option value="10">Англия</option>
		<option value="11">Испания</option>
		</select>
	</div>
	<div class="lineForm">	
		<select class="wid100" id="city">
		<option value="1" selected="selected">Киев</option>
		<option value="2">Львов</option>
		<option value="3">Донецк</option>
		<option value="4">Ялта</option>
		</select>
	</div>
    <div>
       <TEXTAREA style="width:300px; border: 1px solid #bdbcbd; margin: 20px; height:300px; overflow:hidden; -moz-border-radius:6px;
    -webkit-border-radius:6px;
    border-radius:6px;" name="textarea" id="textarea">Пластиковые окна спб
       </TEXTAREA>
    </div>
    </div>
</body></html>

