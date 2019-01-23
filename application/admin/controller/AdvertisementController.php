<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\AdvertisementType;
use app\admin\model\Advertisement;
class AdvertisementController extends AdminController
{
	/*
		广告控制器
	*/

	//渲染页面
	public function advertisement_type_list(){

		return view();
	}

	//ajax获取数据
	public function ajax_advertisement_type_list(){

		$where = [];

		$type_name = input('post.type_name/s');

		if ($type_name) {
			
			$where['a.type_name'] = ['like',"%{$type_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['a.store_id'] = $this->is_jurisdiction;
		}

		$where['a.status'] = 0;

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['a.id' => 'desc'];

		$data = (new AdvertisementType)->Common_Select($offset,$limit,$where,$order);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//新增广告类型
	public function advertisement_type_add(){

		if ($_POST) {
			
			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$data['type_name'] = input('post.type_name/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			$add = (new AdvertisementType)->Common_Insert($data);

			if ($add)
				return json(['code' => 200 , 'msg' => '新增成功']);
				return json(['code' => 400 , 'msg' => '新增失败']);

		}else{

			return view();
		}
	}

	//编辑广告类型
	public function advertisement_type_edit(){

		$id = input('id/d');

		if ($_POST) {
			
			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$data['type_name'] = input('post.type_name/s');

			$data['update_time'] = time();

			$edit = (new AdvertisementType)->Common_Update($data,['id' => $id]);

			if ($edit)
				return json(['code' => 200 , 'msg' => '更新成功']);
				return json(['code' => 400 , 'msg' => '更新失败']);

		}else{

			$list = (new AdvertisementType)->Common_Find(['id' => $id]);

			$this->assign('list',$list);

			return view();
		}
	}

	//删除
	public function advertisement_type_del(){

		$id = input('post.id/d');

		$del = (new AdvertisementType)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//批量删除
	public function advertisement_type_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new AdvertisementType)->Common_Update(['status' => 1],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//广告页面渲染
	public function advertisement_list(){

		return view();
	}

	//ajax获取广告页面数据
	public function ajax_advertisement_list(){

		$where = [];

		$name = input('post.name/s');

		if ($name) {
			
			$where['a.name'] = ['like',"%{$name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['a.store_id'] = $this->is_jurisdiction;
		}

		$where['a.status'] = 0;

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$data = (new Advertisement)->Advertisement_Select($offset,$limit,$where,$order);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//新增广告类型
	public function advertisement_add(){

		if ($_POST) {
			
			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$data['type_id'] = input('post.type_id/d');

			$data['name'] = input('post.name/s');

			$data['image'] = input('post.image/s');

			$data['url'] = input('post.url/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			$add = (new Advertisement)->Common_Insert($data);

			if ($add)
				return json(['code' => 200 , 'msg' => '新增成功']);
				return json(['code' => 400 , 'msg' => '新增失败']);

		}else{

			$type = [];

			if ($this->is_jurisdiction) { // 店铺下广告类型

				$order = ['id' => 'desc'];

				$type = (new AdvertisementType)->type(['store_id' => $this->is_jurisdiction , 'status' => 0],$order);
			}

			$this->assign('type',$type);

			return view();
		}
	}

	//编辑广告类型
	public function advertisement_edit(){

		$id = input('id/d');

		if ($_POST) {
			
			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$data['type_id'] = input('post.type_id/d');

			$data['name'] = input('post.name/s');

			$data['image'] = input('post.image/s');

			$data['url'] = input('post.url/s');

			$data['update_time'] = time();

			$edit = (new Advertisement)->Common_Update($data,['id' => $id]);

			if ($edit)
				return json(['code' => 200 , 'msg' => '更新成功']);
				return json(['code' => 400 , 'msg' => '更新失败']);

		}else{

			$type = [];

			$order = ['id' => 'desc'];

			$list = (new Advertisement)->Common_Find(['id' => $id]);

			$type = (new AdvertisementType)->type(['store_id' => $list['store_id'] , 'status' => 0],$order);

			$this->assign('type',$type);

			$this->assign('list',$list);

			return view();
		}
	}

	//删除
	public function advertisement_del(){

		$id = input('post.id/d');

		$del = (new Advertisement)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//批量删除
	public function advertisement_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new Advertisement)->Common_Update(['status' => 1],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//获取店铺下广告类型
	public function type(){

		$store_id = input('post.store_id/d');

		$order = ['id' => 'desc'];

		$data = (new AdvertisementType)->type(['store_id' => $store_id , 'status' => 0],$order);

		if($data)
			return json(['code' => 200 , 'msg' => '请求成功' , 'data' => $data]);
			return json(['code' => 400 , 'msg' => '请求成功,没有数据']);

	}

}