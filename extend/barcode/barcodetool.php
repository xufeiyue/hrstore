<?php
// Including all required classes
require('class/BCGFont.php');
require('class/BCGColor.php');
require('class/BCGDrawing.php'); 

/*'BCGcodabar','BCGcode11','BCGcode39','BCGcode39extended','BCGcode93',
'BCGcode128','BCGean8','BCGean13','BCGisbn','BCGi25','BCGs25','BCGmsi',
'BCGupca','BCGupce','BCGupcext2','BCGupcext5','BCGpostnet','BCGothercode'*/
$codebar = $_REQUEST['codebar']; //¸ÃÈí¼þÖ§³ÖµÄËùÓÐ±àÂë£¬Ö»Ðèµ÷Õû$codebar²ÎÊý¼´¿É¡£

  // 包括条形码技术
include('class/'.$codebar.'.barcode.php'); 

    // 加载字体
$font = new BCGFont('./class/font/Arial.ttf', 10);

    // 参数是R，G，B的颜色。
$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255); 

$code = new $codebar();
$code->setScale(2); // Resolution
$code->setThickness(30); // Thickness
$code->setForegroundColor($color_black); // Color of bars
$code->setBackgroundColor($color_white); // Color of spaces
$code->setFont($font); // Font (or 0)
$text = $_REQUEST['text']; //条码下面的英文或s数字
$code->parse($text); 

 /**这里是参数列表
        1文件名（空：屏幕上显示）
        2 -背景色*/
$drawing = new BCGDrawing('', $color_white); //第一个参数为存储路径路径
$drawing->setBarcode($code);
$drawing->draw();

//        标头为图像（如果将条形码保存到文件中，将其删除）
header('Content-Type: image/png');

//绘制（或保存）图像到PNG格式。
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
?>