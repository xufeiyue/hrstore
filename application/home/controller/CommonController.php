<?php
namespace app\home\controller;
use think\Controller;
use wechat\Wechat;
use think\Request;
class CommonController extends Controller
{
	public $store_id;
	public $userId;

	//网页授权
	public function _initialize(){

		$request = Request::instance();

		$module = $request->module(); //模块名

		$controller = $request->controller(); //控制器
		
		$action = $request->action();//方法

		$this->store_id = 1;

		$this->userId = 1;

		if (session('wechat_user')) {
			
		}else{
			// $url = urlencode(Request()->domain().'/home/Common/getChatInfo');
			// $wechat = new Wechat();
			// $wechat->accredit($url);
		}

		
	}

	//拉取信息
	public function getChatInfo(){

		$wechat = new Wechat();//实例化微信类

        $code = $_GET['code'];  //获取跳转后的code
        
        $access_token = $wechat->getAccessToken($code); //根据code获取token
        //根据access_token和openid获取到用户信息
        $we_chat_user_info = $wechat->getWeChatUserInfo($access_token['access_token'],$access_token['openid']);

       session('wechat_user',$we_chat_user_info);
        
       echo '<pre>';print_r($we_chat_user_info);exit;
	}

}
