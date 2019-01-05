<?php
namespace app\home\controller;
use think\Controller;
use wechat\Wechat;
use think\Request;
class CommonController extends Controller
{

	//网页授权
	public function _initialize(){
		// $url = Request()->domain().'/home/Common/getChatInfo';
		// $wechat = new Wechat();
		// $wechat->accredit($url);
		
	}

	//拉取信息
	public function getChatInfo(){

		$wechat = new Wechat();//实例化微信类

        $code = $_GET['code'];  //获取跳转后的code
        
        $access_token = $wechat->getAccessToken($code); //根据code获取token
        //根据access_token和openid获取到用户信息
        $we_chat_user_info = $wechat->getWeChatUserInfo($access_token['access_token'],$access_token['openid']);
        
        echo '<pre>';print_r($we_chat_user_info);exit;
	}

}
