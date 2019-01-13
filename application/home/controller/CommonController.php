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

			$this->store_id = session('store_id');

		}else{
			// $url = urlencode(Request()->domain().'/home/Wx/getChatInfo');
			// $state = base64_encode(json_encode(["module" => $module, "controller" => $controller, "action" => $action]));
			// $wechat = new Wechat();
			// $wechat->accredit($url,$state);
		}

		
	}


}
