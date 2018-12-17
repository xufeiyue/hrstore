<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
use app\admin\model\GoodsType;
use app\admin\model\Goods;
class GoodsController extends AdminController
{
	/*
		商品管理控制器
	*/

	//渲染商品类型模板
	public function goods_type_list(){

		return view();
	}

	//ajax获取商品类型数据
	public function ajax_goods_type_list(){

		$where = [];

		$goods_type_name = input('post.goods_type_name/s');

		if($goods_type_name){

			$where['goods_type_name']  = ['like',"%{$goods_type_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['store_id'] = $this->is_jurisdiction;
		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$where['status'] = 0;

		$data = (new GoodsType)->Common_Select($offset,$limit,$where,$order);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//新增商品类型
	public function goods_type_add(){

		if ($_POST) {
				
			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			$data['goods_type_name'] = input('post.goods_type_name/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			$add = (new GoodsType)->Common_Insert($data);

			if ($add)
				return json(['code' => 200 , 'msg' => '新增成功']);
				return json(['code' => 400 , 'msg' => '新增失败']);

		}else{

			return view();
		}
	}

	//编辑商品类型
	public function goods_type_edit(){

		$id = input('id/d');

		if ($_POST) {
				
			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			$data['goods_type_name'] = input('post.goods_type_name/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			$add = (new GoodsType)->Common_Update($data,['id' => $id]);

			if ($add)
				return json(['code' => 200 , 'msg' => '新增成功']);
				return json(['code' => 400 , 'msg' => '新增失败']);

		}else{

			$list = (new GoodsType)->Common_Find(['id' => $id]);

			$this->assign('list',$list);

			return view();
		}
	}

	//删除商品类型
	public function goods_type_del(){

		$id = input('post.id/d');

		$del = (new GoodsType)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//批量删除商品类型
	public function goods_type_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new GoodsType)->Common_Update(['status' => 1],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//渲染商品列表模板
	public function goods_list(){

		return view();
	}

	//ajax获取商品列表数据
	public function ajax_goods_list(){

		$where = [];

		$goods_name = input('post.goods_name/s');

		if($goods_name){

			$where['goods_name']  = ['like',"%{$goods_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['store_id'] = $this->is_jurisdiction;
		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$where['status'] = 0;

		$data = (new Goods)->Common_Select($offset,$limit,$where,$order);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//新增商品
	public function goods_add(){

		if ($_POST) {

			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$state = input('post.state/s');

			if($state == '上架'){

				$state = 0;

			}else{

				$state = 1;
			}

			$data['type_id'] = input('post.type_id/d');

			$data['goods_name'] = input('post.goods_name/s');		

			$images = input('post.file/a');

			$data['goods_images'] = json_encode($images,true);	

			$data['goods_original_price'] = input('post.goods_original_price/f');		

			$data['goods_present_price'] = input('post.goods_present_price/f');		

			$data['goods_stock'] = input('post.goods_stock/d');		

			$goods_specifications = input('post.goods_specifications/a');		

			$data['goods_specifications'] = json_encode($goods_specifications);

			$goods_attribute = input('post.goods_attribute/a');		

			$data['goods_attribute'] = json_encode($goods_attribute);

			$data['goods_detail'] = input('post.goods_detail/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			$add = (new Goods)->Common_Insert($data);

			if($add)
				return json(['code' => 200 , 'msg' => '新增成功']);
				return json(['code' => 400 , 'msg' => '新增失败']);

		}else{

			$goods_type = [];

			$order = ['id' => 'desc'];

			$goods_type = (new GoodsType)->type(['store_id' => $this->is_jurisdiction , 'status' => 0],$order);

			$this->assign('goods_type',$goods_type);

			return view();
		}
	}

	//更新商品
	public function goods_edit(){

		$id = input('id/d');

		if ($_POST) {

			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$state = input('post.state/s');

			if($state == '上架'){

				$state = 0;

			}else{
				
				$state = 1;
			}

			$data['type_id'] = input('post.type_id/d');

			$data['goods_name'] = input('post.goods_name/s');		

			$images = input('post.file/a');

			$data['goods_images'] = json_encode($images,true);	

			$data['goods_original_price'] = input('post.goods_original_price/f');		

			$data['goods_present_price'] = input('post.goods_present_price/f');		

			$data['goods_stock'] = input('post.goods_stock/d');		

			$goods_specifications = input('post.goods_specifications/a');		

			$data['goods_specifications'] = json_encode($goods_specifications);

			$goods_attribute = input('post.goods_attribute/a');		

			$data['goods_attribute'] = json_encode($goods_attribute);

			$data['goods_detail'] = input('post.goods_detail/s');

			$data['update_time'] = time();

			$add = (new Goods)->Common_Update($data,['id' => $id]);

			if($add)
				return json(['code' => 200 , 'msg' => '更新成功']);
				return json(['code' => 400 , 'msg' => '更新失败']);

		}else{

			$goods_type = [];

			$order = ['id' => 'desc'];

			$list = (new Goods)->Common_Find(['id' => $id]);

			$goods_type = (new GoodsType)->type(['store_id' => $list['store_id'] , 'status' => 0],$order);

			$this->assign('list',$list);

			$this->assign('goods_type',$goods_type);

			return view();
		}
	}
	//删除商品
	public function goods_del(){

		$id = input('post.id/d');

		$del = (new Goods)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//批量删除商品类型
	public function goods_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new Goods)->Common_Update(['status' => 1],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//批量上下架商品
	public function goods_state_update(){

		
	}

	//店铺下的商品类型
	public function store_goods_type(){

		$store_id = input('post.store_id/d');

		$order = ['id' => 'desc'];

		$data = (new GoodsType)->type(['store_id' => $store_id , 'status' => 0],$order);

		if($data)
			return json(['code' => 200 , 'msg' => '请求成功' , 'data' => $data]);
			return json(['code' => 400 , 'msg' => '请求成功,没有数据']);
	}
}