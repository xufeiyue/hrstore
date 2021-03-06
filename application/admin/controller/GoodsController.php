<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
use app\admin\model\GoodsType;
use app\admin\model\Goods;
use app\admin\model\CommodityBank;
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
			
			$where['store_id'] = ['in',$this->is_jurisdiction];
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
			
			$where['store_id'] = ['in',$this->is_jurisdiction];
		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$where['status'] = 0;

		$data = (new Goods)->Common_Select($offset,$limit,$where,$order);

		foreach ($data['data'] as $key => $value) {
			
			if ($value['pid']) {
				
				$data['data'][$key]['pid_name'] = '商品库';

			}else{

				$data['data'][$key]['pid_name'] = '自创';
			}
		}

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//新增商品
	public function goods_add(){

		if ($_POST) {

			$whether = ['on','off'];

			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$state = input('post.state/s');

			$sell_well = input('post.sell_well/s');

			$characteristic = input('post.characteristic/s');

			$popularity = input('post.popularity/s');

			$relation = input('post.relation/s');

			if($state == $whether[0]){

				$data['state'] = 0;

			}else{
				
				$data['state'] = 1;
			}

			if($sell_well == $whether[0]){

				$data['sell_well'] = 0;

			}else{
				
				$data['sell_well'] = 1;
			}

			if($characteristic == $whether[0]){

				$data['characteristic'] = 0;

			}else{
				
				$data['characteristic'] = 1;
			}

			if($popularity == $whether[0]){

				$data['popularity'] = 0;

			}else{
				
				$data['popularity'] = 1;
			}

			if($relation == $whether[0]){

				$data['relation'] = 0;

			}else{
				
				$data['relation'] = 1;
			}

			$data['type_id'] = input('post.type_id/d');

			$data['goods_name'] = input('post.goods_name/s');		

			$images = input('post.file/a');

			$data['goods_images'] = json_encode($images);

			$images_detail = input('post.file_detail/a');

			$images_detail1 = [];

			foreach ($images_detail as $key => $value) {
				
				$images_detail1[] = urldecode($value);
			}

			$data['images_detail'] = json_encode($images_detail1);

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

			$goods_type = (new GoodsType)->type(['store_id' => ['in',$this->is_jurisdiction]; , 'status' => 0],$order);

			$this->assign('goods_type',$goods_type);

			return view();
		}
	}

	//更新商品
	public function goods_edit(){

		$id = input('id/d');

		if ($_POST) {

			$whether = ['on','off'];

			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$state = input('post.state/s');

			$sell_well = input('post.sell_well/s');

			$characteristic = input('post.characteristic/s');

			$popularity = input('post.popularity/s');

			$relation = input('post.relation/s');

			if($state == $whether[0]){

				$data['state'] = 0;

			}else{
				
				$data['state'] = 1;
			}

			if($sell_well == $whether[0]){

				$data['sell_well'] = 0;

			}else{
				
				$data['sell_well'] = 1;
			}

			if($characteristic == $whether[0]){

				$data['characteristic'] = 0;

			}else{
				
				$data['characteristic'] = 1;
			}

			if($popularity == $whether[0]){

				$data['popularity'] = 0;

			}else{
				
				$data['popularity'] = 1;
			}

			if($relation == $whether[0]){

				$data['relation'] = 0;

			}else{
				
				$data['relation'] = 1;
			}

			$data['type_id'] = input('post.type_id/d');

			$data['goods_name'] = input('post.goods_name/s');		

			$images = input('post.images/a');

			$data['goods_images'] = json_encode($images);	

			$images_detail = input('post.file_detail/a');

			$images_detail1 = [];

			foreach ($images_detail as $key => $value) {
				
				$images_detail1[] = urldecode($value);
			}

			$data['images_detail'] = json_encode($images_detail1);

			$data['goods_original_price'] = input('post.goods_original_price/f');		

			$data['goods_present_price'] = input('post.goods_present_price/f');		

			$data['goods_stock'] = input('post.goods_stock/d');		

			$goods_specifications = input('post.goods_specifications/a');		

			$data['goods_specifications'] = json_encode($goods_specifications);

			$goods_attribute = input('post.goods_attribute/a');		

			$data['goods_attribute'] = json_encode($goods_attribute);

			$data['goods_detail'] = input('post.goods_detail/s');

			$data['update_time'] = time();

			$edit = (new Goods)->Common_Update($data,['id' => $id]);

			if($edit)
				return json(['code' => 200 , 'msg' => '更新成功']);
				return json(['code' => 400 , 'msg' => '更新失败']);

		}else{

			$goods_type = [];

			$order = ['id' => 'desc'];

			$list = (new Goods)->Common_Find(['id' => $id]);

			$goods_type = (new GoodsType)->type(['store_id' => $list['store_id'] , 'status' => 0],$order);

			$list['goods_images'] = json_decode($list['goods_images'],true);

			$list['goods_specifications'] = json_decode($list['goods_specifications'],true);

			$list['goods_attribute'] = json_decode($list['goods_attribute'],true);

			$list['goods_specifications_num'] = count($list['goods_specifications']);

			$list['goods_attribute_num'] = count($list['goods_attribute']);

			$images_detail = json_decode($list['images_detail'],true);

			$list['images_detail1'] = [];

			$list['images_detail2'] = [];

			foreach ($images_detail as $key => $value) {
				
				$list['images_detail1'][] = json_decode($value,true);

				$list['images_detail2'][] = urlencode($value) ;
			}

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

	//批量删除商品
	public function goods_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new Goods)->Common_Update(['status' => 1],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//批量上下架商品
	public function goods_state_update(){

		$id = array_unique(input('post.id/a'));

		$state = input('post.state/d');

		$edit = (new Goods)->Common_Update(['state' => $state],['id' => ['in', $id]]);

		if($edit)
			return json(['code' => 200 , 'msg' => '操作成功']);
			return json(['code' => 400 , 'msg' => '操作失败']);
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

	//渲染回收站模板
	public function recycle_bin_list(){

		return view();
	}

	//ajax获取回收站数据
	public function ajax_recycle_bin_list(){

		$where = [];

		$goods_name = input('post.goods_name/s');

		if($goods_name){

			$where['goods_name']  = ['like',"%{$goods_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['store_id'] = ['in',$this->is_jurisdiction];
		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$where['status'] = 1;

		$data = (new Goods)->Common_Select($offset,$limit,$where,$order);

		foreach ($data['data'] as $key => $value) {
			
			if ($value['pid']) {
				
				$data['data'][$key]['pid_name'] = '商品库';
				
			}else{

				$data['data'][$key]['pid_name'] = '自创';
			}
		}

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//回收站还原/清除
	public function recycle_bin_update_status(){

		$id = input('post.id/d');

		$status = input('post.status/d');

		$update = (new Goods)->Common_Update(['status' => $status],['id' => $id]);

		if($update)
			return json(['code' => 200 , 'msg' => '操作成功']);
			return json(['code' => 400 , 'msg' => '操作失败']);
	}

	//渲染商品库模板
	public function commodity_bank_list(){

		return view();
	}

	//ajax获取商品库数据
	public function ajax_commodity_bank_list(){

		$where = [];

		$goods_name = input('post.goods_name/s');

		if($goods_name){

			$where['goods_name']  = ['like',"%{$goods_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['store_id'] = ['in',$this->is_jurisdiction];
		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$where['status'] = 0;

		$data = (new CommodityBank)->Common_Select($offset,$limit,$where,$order);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//新增商品库商品
	public function commodity_bank_add(){

		if ($_POST) {

			$state = 1;

			$data['type_id'] = input('post.type_id/d');

			$data['goods_name'] = input('post.goods_name/s');		

			$images = input('post.file/a');

			$data['goods_images'] = json_encode($images);

			$images_detail = input('post.file_detail/a');

			$images_detail1 = [];

			foreach ($images_detail as $key => $value) {
				
				$images_detail1[] = urldecode($value);
			}

			$data['images_detail'] = json_encode($images_detail1);

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

			$add = (new CommodityBank)->Common_Insert($data);

			if($add)
				return json(['code' => 200 , 'msg' => '新增成功']);
				return json(['code' => 400 , 'msg' => '新增失败']);

		}else{

			$goods_type = [];

			$order = ['id' => 'desc'];

			$goods_type = (new GoodsType)->type(['store_id' => ['in',$this->is_jurisdiction]; , 'status' => 0],$order);

			$this->assign('goods_type',$goods_type);

			return view();
		}
	}

	//更新商品库商品
	public function commodity_bank_edit(){

		$id = input('id/d');

		if ($_POST) {

			$data['state'] = 1; //默认下架

			$data['type_id'] = input('post.type_id/d');

			$data['goods_name'] = input('post.goods_name/s');		

			$images = input('post.images/a');

			$data['goods_images'] = json_encode($images);	

			$images_detail = input('post.file_detail/a');

			$images_detail1 = [];

			foreach ($images_detail as $key => $value) {
				
				$images_detail1[] = urldecode($value);
			}

			$data['images_detail'] = json_encode($images_detail1);

			$data['goods_original_price'] = input('post.goods_original_price/f');		

			$data['goods_present_price'] = input('post.goods_present_price/f');		

			$data['goods_stock'] = input('post.goods_stock/d');		

			$goods_specifications = input('post.goods_specifications/a');		

			$data['goods_specifications'] = json_encode($goods_specifications);

			$goods_attribute = input('post.goods_attribute/a');		

			$data['goods_attribute'] = json_encode($goods_attribute);

			$data['goods_detail'] = input('post.goods_detail/s');

			$data['update_time'] = time();

			$edit = (new CommodityBank)->Common_Update($data,['id' => $id]);

			if($edit)
				return json(['code' => 200 , 'msg' => '更新成功']);
				return json(['code' => 400 , 'msg' => '更新失败']);

		}else{

			$goods_type = [];

			$order = ['id' => 'desc'];

			$list = (new CommodityBank)->Common_Find(['id' => $id]);

			$goods_type = (new GoodsType)->type(['store_id' => $list['store_id'] , 'status' => 0],$order);

			$list['goods_images'] = json_decode($list['goods_images'],true);

			$list['goods_specifications'] = json_decode($list['goods_specifications'],true);

			$list['goods_attribute'] = json_decode($list['goods_attribute'],true);

			$list['goods_specifications_num'] = count($list['goods_specifications']);

			$list['goods_attribute_num'] = count($list['goods_attribute']);

			$images_detail = json_decode($list['images_detail'],true);

			$list['images_detail1'] = [];

			$list['images_detail2'] = [];

			foreach ($images_detail as $key => $value) {
				
				$list['images_detail1'][] = json_decode($value,true);

				$list['images_detail2'][] = urlencode($value) ;
			}

			$this->assign('list',$list);

			$this->assign('goods_type',$goods_type);

			return view();
		}
	}
	//删除商品库商品
	public function commodity_bank_del(){

		$id = input('post.id/d');

		$del = (new CommodityBank)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//批量删除商品库商品
	public function commodity_bank_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new CommodityBank)->Common_Update(['status' => 1],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//拉取商品库商品
	public function pull_up_goods(){

		return view();
	}

	//ajax获取商品库数据
	public function ajax_pull_up_goods(){

		$where = [];

		$goods_name = input('post.goods_name/s');

		if($goods_name){

			$where['goods_name']  = ['like',"%{$goods_name}%"];
		}

		$order = ['id' => 'desc'];

		$where['status'] = 0;

		$Goods = (new Goods)->Goods_Pid(['status' => 0 , 'pid' => ['>', 0], 'store_id' => ['in',$this->is_jurisdiction];],$order);

		if (!empty($Goods)) {
			
			$where['id'] = ['not in', $Goods];
		}

		$data = (new CommodityBank)->CommodityBank_Select($where,$order);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data ]);
	}

	//拉取信息到店铺下商品列表
	public function store_goods_list(){

		$id = array_unique(input('post.id/a'));

		$order = ['id' => 'desc'];

		$data = (new CommodityBank)->CommodityBank_Select(['id' => ['in',$id] , 'status' => 0],$order);

		$arr = [];

		foreach ($data as $key => $value) {
			
			$arr[$key]['goods_name'] = $value['goods_name'];

			$arr[$key]['goods_images'] = $value['goods_images'];

			$arr[$key]['goods_original_price'] = $value['goods_original_price'];

			$arr[$key]['goods_present_price'] = $value['goods_present_price'];

			$arr[$key]['goods_detail'] = $value['goods_detail'];

			$arr[$key]['state'] = 1;

			$arr[$key]['goods_specifications'] = $value['goods_specifications'];

			$arr[$key]['goods_attribute'] = $value['goods_attribute'];

			$arr[$key]['goods_stock'] = $value['goods_stock'];

			$arr[$key]['type_id'] = $value['type_id'];

			$arr[$key]['pid'] = $value['id'];

			$arr[$key]['images_detail'] = $value['images_detail'];

			$arr[$key]['store_id'] = $this->is_jurisdiction;

			$arr[$key]['create_time'] = time();

			$arr[$key]['update_time'] = time();

		}

		$add = (new Goods)->Common_InsertAll($arr);

		if($add)
			return json(['code' => 200 , 'msg' => '拉取成功']);
			return json(['code' => 400 , 'msg' => '拉取失败']);

	}

	//商品查看（不可修改）
	public function goods_see(){

		$id = input('id/d');

		$goods_type = [];

		$order = ['id' => 'desc'];

		$list = (new CommodityBank)->Common_Find(['id' => $id]);

		$goods_type = (new GoodsType)->type(['store_id' => $list['store_id'] , 'status' => 0],$order);

		$list['goods_images'] = json_decode($list['goods_images'],true);

		$list['goods_specifications'] = json_decode($list['goods_specifications'],true);

		$list['goods_attribute'] = json_decode($list['goods_attribute'],true);

		$list['goods_specifications_num'] = count($list['goods_specifications']);

		$list['goods_attribute_num'] = count($list['goods_attribute']);

		$images_detail = json_decode($list['images_detail'],true);

		$list['images_detail1'] = [];

		$list['images_detail2'] = [];

		foreach ($images_detail as $key => $value) {
			
			$list['images_detail1'][] = json_decode($value,true);

			$list['images_detail2'][] = urlencode($value) ;
		}

		$this->assign('list',$list);

		$this->assign('goods_type',$goods_type);

		return view();
	}
}