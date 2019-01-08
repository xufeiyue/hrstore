<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\NewDiscovery;
class NewdiscoveryController extends AdminController
{
	/*
		新发现
	*/
	//列表
	public function newdiscovery_list(){

		return view();
	}

	//ajax获取新发现列表
	public function ajax_newdiscovery_list(){

		$where = [];

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['store_id'] = $this->is_jurisdiction;
		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$where['status'] = 0;

		$data = (new Newdiscovery)->Common_Select($offset,$limit,$where,$order);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//新增发现
	public function newdiscovery_add(){

		if ($_POST) {
			
			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$data['src'] = input('post.src/s');

			$data['url'] = input('post.url/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			$add = (new NewDiscovery)->Common_Insert($data);

			if($add)
				return json(['code' => 200, 'msg' => '新增成功']);
				return json(['code' => 400, 'msg' => '新增失败']);

		}else{

			return view();
		}
	}

	//更新发现
	public function newdiscovery_edit(){

		$id = input('id/d');

		if ($_POST) {
			
			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$data['src'] = input('post.src/s');

			$data['url'] = input('post.url/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			$edit = (new NewDiscovery)->Common_Update($data,['id' => $id]);

			if($edit)
				return json(['code' => 200, 'msg' => '新增成功']);
				return json(['code' => 400, 'msg' => '新增失败']);
		}else{

			$list = (new NewDiscovery)->Common_Find(['id' => $id]);

			$this->assign('list',$list);

			return view();
		}
	}

	//删除新发现
	public function newdiscovery_del(){

		$id = input('post.id/d');

		$del = (new NewDiscovery)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//批量删除
	public function newdiscovery_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new NewDiscovery)->Common_Update(['status' => 1],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}
}