<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\System;
use app\admin\model\AdminLog;
class SystemController extends AdminController
{
	/*
		系統設置
	*/
	public function system_setup(){

		$id = input('id/d') ? : 0;

		if ($_POST) {
			
			$data['search_box_content'] = input('post.search_box_content/s');

			$data['site_name'] = input('post.site_name/s');

			$data['site_title'] = input('post.site_title/s');

			$data['phone'] = input('post.phone/s');

			$data['email'] = input('post.email/s');

			$data['address'] = input('post.address/s');

			$data['copyright_information'] = input('post.copyright_information/s');

			$data['record_information'] = input('post.record_information/s');

			$data['key_word'] = input('post.key_word/s');

			$data['site_description'] = input('post.site_description/s');

			$data['friendship_link'] = input('post.friendship_link/s');

			$site_switch_state = input('post.site_switch_state/s');

			if ($site_switch_state == 'on') {

				$data['site_switch_state'] = 1;

			}else{

				$data['site_switch_state'] = 0;
			}

			if ($id) {

				$change = (new System)->Common_Update($data,['id' => $id]);

			}else{

				$change = (new System)->Common_Insert($data);
				
			}

			if($change)
					return json(['code' => 200 , 'msg' => '保存成功']);
					return json(['code' => 200 , 'msg' => '保存失敗']);

		}else{

			$list = (new System)->Common_Find();

			$this->assign('list',$list);

			return view();
		}
	}

	//渲染系统日志模板
	public function log_list(){

		return view();
	}

	//ajax获取日志数据
	public function ajax_log_list(){

		$where = [];

		$user_name = input('post.user_name/s');

		if ($user_name) {
			
			$where['user_name'] = ['like',"%{$user_name}%"];
		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$data = (new AdminLog)->Common_Select($offset,$limit,$where,$order);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}
}