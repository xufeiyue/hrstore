<?php
namespace app\home\controller;
use think\Controller;
use wechat\Wechat;
use think\Request;
use app\home\model\Member;
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
			
			$member = session('wechat_user');

			$this->userId = $member['id'];

		}else{
			// $url = urlencode(Request()->domain().'/home/Common/getChatInfo');
			// $state = base64_encode(json_encode(["module" => $module, "controller" => $controller, "action" => $action]));
			// $wechat = new Wechat();
			// $wechat->accredit($url,$state);
		}

		
	}

	//拉取信息 跳转页面
	public function getChatInfo(){

		$wechat = new Wechat();//实例化微信类

        $code = $_GET['code'];  //获取跳转后的code

        $state = $_GET['state']; //跳转url
        
        $access_token = $wechat->getAccessToken($code); //根据code获取token
        // 验证是否为新用户
        $member = (new Member)->Common_Find(['openid' => $access_token['openid']]);

        if (empty($member)) {
               
           //根据access_token和openid获取到用户信息
    	   $we_chat_user_info = $wechat->getWeChatUserInfo($access_token['access_token'],$access_token['openid']);
    	   echo '<pre>';print_r($we_chat_user_info);exit;
    	   //创建新用户
    	   $member = (new Member)->Common_Insert($data);
        }
       echo '<pre>';print_r($member);exit;
      
       session('wechat_user',$member);

       $this->userId = $member['id'];

       $obj = $this->_redirect($state);

       if ($obj) {
            $this->redirect($obj);
        }else{
            error('登陆失败');
        }
        
	}

	/*
     * 解析条转过来的页面地址
    * */
    public function _redirect($state) {
        return join('/', json_decode(base64_decode($state), true));
    }

}
