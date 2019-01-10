<?php
namespace app\home\controller;
use think\Controller;

define("TOKEN", "wD1Hksmbm7uDds56sE7e");//定义你公众号自己设置的token
class TokenController
{
    //判断是介入还是用户  只有第一次介入的时候才会返回echostr
    public function index()
    {
    	// http://www.redh5.cn/index.php?g=Home&m=Weixin&a=index&token=eifylg1472702373  wD1Hksmbm7uDds56sE7e  NzPEJDCheUjXIjxkHAFRULsqfYsTZTaMsPqcsTjxNtu
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()) {
            echo $echoStr;exit;
        }
    }
    //验证微信开发者模式接入是否成功
    public function checkSignature()
    {
        //signature 是微信传过来的 类似于签名的东西
        $signature = $_GET["signature"];
        //微信发过来的东西
        $timestamp = $_GET["timestamp"];
        //微信传过来的值  什么用我不知道...
        $nonce     = $_GET["nonce"];
        //定义你在微信公众号开发者模式里面定义的token
        $token  = "wD1Hksmbm7uDds56sE7e";
        //三个变量 按照字典排序 形成一个数组
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce
        );
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        //哈希加密  在laravel里面是Hash::
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

}