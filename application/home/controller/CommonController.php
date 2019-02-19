<?php
namespace app\home\controller;
use think\Controller;
use wechat\Wechat;
use think\Request;
use app\home\model\Member;
use app\home\model\Store;
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

			$expiration_time = session('expiration_time');

			 if ((time() - $expiration_time) > 1800 && $module = 'home' && $controller = 'Index' && $module = 'index') { //缓存时间已超过半个小时并且回到首页 重新定位最近的店铺

			 	session('store_id',0);
			 	
			 }

			 if ($expiration_time > 0 && (time() - $expiration_time) > 1800) {
			 	
			 	session('store_id',0);
		 		
		 		$this->redirect('/home/Index/index');
		 	}

			$this->store_id = session('store_id');

		}else{
			 $url = urlencode(Request()->domain().'/home/Wx/getChatInfo');
			 $state = base64_encode(json_encode(["module" => $module, "controller" => $controller, "action" => $action]));
			 // $state = base64_encode('/'.$module.'/'.$controller.'/'.$action);
			 $wechat = new Wechat();
			 $wechat->accredit($url,$state);
		}

		
	}


}
