<?php
session_start();
$textImage = imagecreate(110,40);
$white = imagecolorallocate($textImage, 255, 255, 255);
$black = imagecolorallocate($textImage, 0, 0, 0);
$grey = imagecolorallocate($textImage, 205, 205, 205);
$color1=imagecolorallocate($textImage, rand(0,255), rand(0,255), rand(0,255));
$color2=imagecolorallocate($textImage, rand(0,255), rand(0,255), rand(0,255));
$color3=imagecolorallocate($textImage, rand(0,255), rand(0,255), rand(0,255));
$color4=imagecolorallocate($textImage, rand(0,255), rand(0,255), rand(0,255));
$color5=imagecolorallocate($textImage, rand(0,255), rand(0,255), rand(0,255));
$x=20;//позиция надписи
$y=20;
$h=13;//высота шрифта

//lines coordinates
$x1=rand(0,40);
$y1=rand(0,40);
$y2=rand(0,40);
$y3=rand(0,40);
$y4=rand(0,40);
$y5=rand(0,40);
$y1_2=rand(0,40);
$y2_2=rand(0,40);
$y3_2=rand(0,40);
$y4_2=rand(0,40);
$y5_2=rand(0,40);

$x1=10;
$x2=10;
$x3=10;
$x4=10;
$x5=10;
$x1_2=100;
$x2_2=100;
$x3_2=100;
$x4_2=100;
$x5_2=100;

$password=rand(1000000,9000000);
$_SESSION["password"]=$password;
imagefill($textImage, 0, 0,$grey );
$degree=rand(-20,20);
if($degree>5){
	$y=40;
}
if($degree<-5){
	$y=$h;
}
imageline($textImage, $x1, $y1, $x1_2, $y1_2, $black);
imageline($textImage, $x2, $y2, $x2_2, $y2_2, $black);
imageline($textImage, $x3, $y3, $x3_2, $y3_2, $black);
imageline($textImage, $x4, $y4, $x4_2, $y4_2, $black);
imageline($textImage, $x5, $y5, $x5_2, $y5_2, $black);
imagefttext($textImage, $h, $degree, $x, $y, $black,"../fonts/BKANT.TTF", $password);
header("Content-type: image/png");
imagepng($textImage);
imagedestroy($textImage);
?>
