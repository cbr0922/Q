<?php
error_reporting(0);
@session_start();

$Num1 = rand(0,255);
$Num2 = rand(0,105);
$Num3 = rand(0,105);
$Num4 = rand(150,205);

//生成验证码图片
Header("Content-type: image/PNG");
srand((double)microtime()*1000000);
$im = imagecreate(60,20);
//$black = ImageColorAllocate($im, 253,65,90);
//$white = ImageColorAllocate($im, 125,0,255);
$black = ImageColorAllocate($im, $Num1,$Num2,$Num3);
$white = ImageColorAllocate($im, $Num3,$Num1,$Num1);
$gray = ImageColorAllocate($im, 255,255,255);
imagefill($im,30,10,$gray);
while(($authnum=rand()%100000)<10000);
$result = md5($authnum);

//将四位整数验证码绘入图片
imagestring($im, 12, 8, 2, $authnum, $black);
for($i=0;$i<$Num4;$i++)   //加入干扰象素
{
	$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
	imagesetpixel($im, rand()%70 , rand()%30 , $randcolor);
}
ImagePNG($im);
ImageDestroy($im);
$_SESSION['Code_Contact'] =  $authnum ;
?>


