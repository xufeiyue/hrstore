<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
use app\admin\model\GoodsType;
use app\admin\model\Goods;
use app\admin\model\CommodityBank;
use app\admin\model\Activity;
use app\admin\model\GoodsBrand;
use app\admin\model\StoreTypeRecommend;
use app\admin\model\Store;
use app\admin\model\StoreTypeSort;
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

			$where['a.goods_type_name']  = ['like',"%{$goods_type_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家


			$where['a.store_id'] = ['in',"0,{$this->is_jurisdiction}"];

		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['a.id' => 'desc'];

		$where['a.status'] = 0;

		$data = (new GoodsType)->Common_Select($offset,$limit,$where,$order);

		$data['data'] = Model('Common/Tree')->toFormatTree($data['data'],'goods_type_name');

		foreach ($data['data'] as $key => $value) {
			
			$find = (new StoreTypeRecommend)->Common_Find(['type_id' => $value['id'], 'store_id' => $this->is_jurisdiction]);

			if ($find) {
				
				$data['data'][$key]['recommend_name'] = '是';

			}else{

				$data['data'][$key]['recommend_name'] = '否';
			}
		}

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//新增商品类型
	public function goods_type_add(){

		if ($_POST) {
				
			$store_id = input('post.store_id/a') ? : $this->is_jurisdiction;

			$data['goods_type_name'] = input('post.goods_type_name/s');

			$data['original_classification'] = input('post.original_classification/s');

			$data['url'] = input('post.url/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			$sort = input('post.sort/d');

			Db::startTrans();
        	try{

				if (is_array($store_id)) {

					$arr = [];
					
					foreach ($store_id as $key => $value) {
						
						$data['store_id'] = $value;

						$arr[] = $data;
					}

					(new GoodsType)->Common_InsertAll($arr);

				}else{

					$data['store_id'] = $store_id;

					$type_id = (new GoodsType)->Common_Insert($data);
				}


				if ($this->is_jurisdiction == 0) { //批量新增

					$zuijia = [['id' => 0]]; //追加一个店铺
					
					$store = (new Store)->Common_All_Select(['status' => 1],[],['store_id id','store_name name']);

					$arr = [];

					if ($store) {
						
						$store = array_merge($zuijia,$store);
					}

					foreach ($store as $key => $value) {
						
						$arr[] = ['type_id' => $type_id, 'sort' => $sort, 'store_id' => $value['id']];
					}

					(new StoreTypeSort)->Common_InsertAll($arr);

				}else{

					(new StoreTypeSort)->Common_Insert(['type_id' => $type_id, 'sort' => $sort, 'store_id' => $this->is_jurisdiction]);
				}


				//提交事务
				Db::commit();
				return json(['code' => 200 , 'msg' => '新增成功']);
			}catch (\Exception $e) {
	            // 回滚事务
	            Db::rollback();
	            return json(['code' => 400 , 'msg' => '新增失败']);
	        }


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

			$data['original_classification'] = input('post.original_classification/s');

			$data['url'] = input('post.url/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			$sort = input('post.sort/d');

			$list = (new GoodsType)->Common_Find(['id' => $id]);

			$StoreTypeSort = (new StoreTypeSort)->Common_Find(['type_id' => $id, 'store_id' => $this->is_jurisdiction]);

			if ($StoreTypeSort) {
				
				(new StoreTypeSort)->Common_Update(['sort' => $sort],['type_id' => $id, 'store_id' => $this->is_jurisdiction]);

			}else{

                (new StoreTypeSort)->Common_Insert(['type_id' => $id, 'sort' => $sort, 'store_id' => $this->is_jurisdiction]);
			}

			$edit = (new GoodsType)->Common_Update($data,['id' => $id]);

			if ($edit)
				return json(['code' => 200 , 'msg' => '编辑成功']);
				return json(['code' => 400 , 'msg' => '编辑失败']);

		}else{

			$list = (new GoodsType)->Common_Find(['id' => $id]);

			$StoreTypeSort = (new StoreTypeSort)->Common_Find(['type_id' => $id, 'store_id' => $this->is_jurisdiction]);

			if ($StoreTypeSort) {
				
				$list['sort'] = $StoreTypeSort['sort'];
			
			}else{
			
				$list['sort'] = 0;
			}

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

	//新增子分类
	public function son_goods_type_add(){

		$pid = input('pid/d'); //分类id

		if ($_POST) {
			
			$data['pid'] = $pid;

			$data['store_id'] = input('post.store_id/d');

			$data['goods_type_name'] = input('post.goods_type_name/s');

			$data['url'] = input('post.url/s');

			$data['create_time'] = time();

			$data['update_time'] = time();
			
			$add = (new GoodsType)->Common_Insert($data);

			if ($add)
				return json(['code' => 200 , 'msg' => '新增成功']);
				return json(['code' => 400 , 'msg' => '新增失败']);

		}else{

			$list = (new GoodsType)->Common_Find(['id' => $pid]);

			$this->assign('list',$list);

			return view();
		}
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

			$where['a.goods_name']  = ['like',"%{$goods_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家


			$where['a.store_id'] = $this->is_jurisdiction;
		}

		$store_id = input('post.store_id/d');

		if ($store_id || $store_id == -1) {

			$where['a.store_id'] = $store_id > 0 ? $store_id : 0;

		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['a.id' => 'desc'];

		$where['a.status'] = 0;

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

			$store_id = input('post.store_id/a') ? : $this->is_jurisdiction;

			if (!$store_id)
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$state = input('post.state/s');

			$sell_well = input('post.sell_well/s');

			$characteristic = input('post.characteristic/s');

			$popularity = input('post.popularity/s');

//			$relation = input('post.relation/s');

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

//			if($relation == $whether[0]){
//
//				$data['relation'] = 0;
//
//			}else{
//
//				$data['relation'] = 1;
//			}

			$data['brand_id'] = input('post.brand_id/d');

			$data['start_time'] = strtotime(input('post.start_time/s'));

			$data['end_time'] = strtotime(input('post.end_time/s'));

			$data['type_id'] = input('post.type_id/d');

			$data['activity_id'] = input('post.activity_id/d');

			$data['goods_name'] = input('post.goods_name/s');		

			$images = input('post.file/a');

			$data['goods_images'] = json_encode($images);

			$images_detail = input('post.file_detail/a');

			if (empty($images_detail)) {
				return json(['code' => 400 , 'msg' => '请上传图片']);
			}

			$images_detail1 = [];

			foreach ($images_detail as $key => $value) {
				
				$images_detail1[] = urldecode($value);
			}

			$data['images_detail'] = json_encode($images_detail1);

			//$data['goods_original_price'] = input('post.goods_original_price/f');

			$data['goods_present_price'] = input('post.goods_present_price/f');		

//			$data['goods_stock'] = input('post.goods_stock/d');

			//$goods_specifications = input('post.goods_specifications/a');

			//$data['goods_specifications'] = json_encode($goods_specifications);

//			$goods_attribute = input('post.goods_attribute/a');

//			$data['goods_attribute'] = json_encode($goods_attribute);

            $data['goods_bk_paixu'] = input('post.goods_bk_paixu/d');

            $data['goods_rq_paixu'] = input('post.goods_rq_paixu/d');

			$data['goods_detail'] = input('post.goods_detail/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			Db::startTrans();
        	try{

				if (is_array($store_id)) {

					$arr = [];
					
					foreach ($store_id as $key => $value) {

						$data['store_id'] = $value;
						
						$arr[] = $data;
					}
	
					(new Goods)->Common_InsertAll($arr);

				}else{

					$data['store_id'] = $store_id;

					(new Goods)->Common_Insert($data);
				}
				
				//  提交事务
            	Db::commit();
            	return json(['code' => 200 , 'msg' => '新增成功']);
			} catch (\Exception $e) {
	            // 回滚事务
	            Db::rollback();
	            return json(['code' => 400 , 'msg' => '新增失败']);
	        }


		}else{

			$goods_type = [];

			$order = ['id' => 'desc'];

			$goods_type = (new GoodsType)->type(['store_id' => ['in',$this->is_jurisdiction], 'status' => 0],$order);

			$goods_type = Model('Common/Tree')->toFormatTree($goods_type,'goods_type_name');

			$goods_brand = (new GoodsBrand)->type(['store_id' => $this->is_jurisdiction , 'status' => 0],$order);

			$activity = (new Activity)->Common_All_Select(['store_id' => $this->is_jurisdiction , 'status' => 0],['id' => 'desc'],['id','activity_name']);

			$this->assign('activity',$activity);

			$this->assign('goods_brand',$goods_brand);

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

//			$relation = input('post.relation/s');

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

//			if($relation == $whether[0]){
//
//				$data['relation'] = 0;
//
//			}else{
//
//				$data['relation'] = 1;
//			}

			$data['brand_id'] = input('post.brand_id/d');

			$data['start_time'] = strtotime(input('post.start_time/s'));

			$data['end_time'] = strtotime(input('post.end_time/s'));

			$data['type_id'] = input('post.type_id/d');

			$data['activity_id'] = input('post.activity_id/d');

			$data['goods_name'] = input('post.goods_name/s');		

			$images = input('post.images/a');

			$data['goods_images'] = json_encode($images);	

			$images_detail = input('post.file_detail/a');

			if (empty($images_detail)) {
				return json(['code' => 400 , 'msg' => '请上传图片']);
			}

			$images_detail1 = [];

			foreach ($images_detail as $key => $value) {
				
				$images_detail1[] = urldecode($value);
			}

			$data['images_detail'] = json_encode($images_detail1);

			//$data['goods_original_price'] = input('post.goods_original_price/f');

			$data['goods_present_price'] = input('post.goods_present_price/f');		

//			$data['goods_stock'] = input('post.goods_stock/d');

			//$goods_specifications = input('post.goods_specifications/a');

			//$data['goods_specifications'] = json_encode($goods_specifications);

//			$goods_attribute = input('post.goods_attribute/a');

//			$data['goods_attribute'] = json_encode($goods_attribute);

            $data['goods_bk_paixu'] = input('post.goods_bk_paixu/d');

            $data['goods_rq_paixu'] = input('post.goods_rq_paixu/d');

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

			$goods_type = (new GoodsType)->type(['store_id' => ['in',"0,{$list['store_id']}"] , 'status' => 0],$order);

			$goods_brand = (new GoodsBrand)->type(['store_id' => ['in',"0,{$list['store_id']}"] , 'status' => 0],$order);

			$activity = (new Activity)->Common_All_Select(['store_id' => $list['store_id'] , 'status' => 0],['id' => 'desc'],['id','activity_name']);

			$goods_type = Model('Common/Tree')->toFormatTree($goods_type,'goods_type_name');

			$list['start_time'] = date('Y-m-d H:i:s',$list['start_time']);

			$list['end_time'] = date('Y-m-d H:i:s',$list['end_time']);

			$list['goods_images'] = json_decode($list['goods_images'],true);

//			$list['goods_specifications'] = json_decode($list['goods_specifications'],true);

//			$list['goods_attribute'] = json_decode($list['goods_attribute'],true);

//			$list['goods_specifications_num'] = count($list['goods_specifications']);

//			$list['goods_attribute_num'] = count($list['goods_attribute']);

			$images_detail = json_decode($list['images_detail'],true);

			$list['images_detail1'] = [];

			$list['images_detail2'] = [];

			foreach ($images_detail as $key => $value) {
				
				$list['images_detail1'][] = json_decode($value,true);

				$list['images_detail2'][] = urlencode($value) ;
			}

			$this->assign('list',$list);

			$this->assign('activity',$activity);

			$this->assign('goods_brand',$goods_brand);

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

		$data = (new GoodsType)->type(['store_id' => ['in',"0,{$store_id}"] , 'status' => 0],$order);

		$data = Model('Common/Tree')->toFormatTree($data,'goods_type_name');

		if($data)
			return json(['code' => 200 , 'msg' => '请求成功' , 'data' => $data]);
			return json(['code' => 400 , 'msg' => '请求成功,没有数据']);
	}

	//店铺下活动
	public function store_activity_list(){

		$store_id = input('post.store_id/d');

		$order = ['id' => 'desc'];

		$data = (new Activity)->Common_All_Select(['store_id' => $store_id , 'status' => 0],$order,['id','activity_name']);

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

			$where['a.goods_name']  = ['like',"%{$goods_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家

			$where['a.store_id'] = $this->is_jurisdiction;

		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['a.id' => 'desc'];

		$where['a.status'] = 1;

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

			$where['a.goods_name']  = ['like',"%{$goods_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家


			$where['a.store_id'] = $this->is_jurisdiction;

		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['a.id' => 'desc'];

		$where['a.status'] = 0;

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

			if (empty($images)) {
				
				return json(['code' => 400 , 'msg' => '请上传图片']);
			}

			$data['goods_images'] = json_encode($images);


			$images_detail = input('post.file_detail/a');

			$images_detail1 = [];

			foreach ($images_detail as $key => $value) {
				
				$images_detail1[] = urldecode($value);
			}

			$data['images_detail'] = json_encode($images_detail1);

			//$data['goods_original_price'] = input('post.goods_original_price/f');

			$data['goods_present_price'] = input('post.goods_present_price/f');		

//			$data['goods_stock'] = input('post.goods_stock/d');

//			$goods_specifications = input('post.goods_specifications/a');

//			$data['goods_specifications'] = json_encode($goods_specifications);

//			$goods_attribute = input('post.goods_attribute/a');

//			$data['goods_attribute'] = json_encode($goods_attribute);

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

			$goods_type = (new GoodsType)->type(['store_id' => ['in',$this->is_jurisdiction] , 'status' => 0],$order);

			$goods_type = Model('Common/Tree')->toFormatTree($goods_type,'goods_type_name');

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

			if (empty($images)) {
				
				return json(['code' => 400 , 'msg' => '请上传图片']);
			}

			$data['goods_images'] = json_encode($images);	

			$images_detail = input('post.file_detail/a');

			$images_detail1 = [];

			foreach ($images_detail as $key => $value) {
				
				$images_detail1[] = urldecode($value);
			}

			$data['images_detail'] = json_encode($images_detail1);

			//$data['goods_original_price'] = input('post.goods_original_price/f');

			$data['goods_present_price'] = input('post.goods_present_price/f');		

//			$data['goods_stock'] = input('post.goods_stock/d');

//			$goods_specifications = input('post.goods_specifications/a');

//			$data['goods_specifications'] = json_encode($goods_specifications);

//			$goods_attribute = input('post.goods_attribute/a');

//			$data['goods_attribute'] = json_encode($goods_attribute);

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

			$goods_type = Model('Common/Tree')->toFormatTree($goods_type,'goods_type_name');

			$list['goods_images'] = json_decode($list['goods_images'],true);

//			$list['goods_specifications'] = json_decode($list['goods_specifications'],true);

//			$list['goods_attribute'] = json_decode($list['goods_attribute'],true);

			//$list['goods_specifications_num'] = count($list['goods_specifications']);

//			$list['goods_attribute_num'] = count($list['goods_attribute']);

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

		$Goods = (new Goods)->Goods_Pid(['status' => 0 , 'pid' => ['>', 0], 'store_id' => ['in',$this->is_jurisdiction]],$order);

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

			//$arr[$key]['goods_original_price'] = $value['goods_original_price'];

			$arr[$key]['goods_present_price'] = $value['goods_present_price'];

			$arr[$key]['goods_detail'] = $value['goods_detail'];

			$arr[$key]['state'] = 1;

//			$arr[$key]['goods_specifications'] = $value['goods_specifications'];

//			$arr[$key]['goods_attribute'] = $value['goods_attribute'];

//			$arr[$key]['goods_stock'] = $value['goods_stock'];

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

		//$list['goods_specifications'] = json_decode($list['goods_specifications'],true);

//		$list['goods_attribute'] = json_decode($list['goods_attribute'],true);

		//$list['goods_specifications_num'] = count($list['goods_specifications']);

//		$list['goods_attribute_num'] = count($list['goods_attribute']);

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

	//商品品牌列表
	public function goods_brand_list(){

		return view();
	}

	//ajax获取品牌数据
	public function ajax_goods_brand_list(){

		$goods_brand_name = input('post.goods_brand_name/s');

		if($goods_brand_name){

			$where['a.goods_brand_name']  = ['like',"%{$goods_brand_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['a.store_id'] = $this->is_jurisdiction;
		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['a.id' => 'desc'];

		$where['a.status'] = 0;

		$data = (new GoodsBrand)->Common_Select($offset,$limit,$where,$order);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);

	}

	//新增品牌
	public function goods_brand_add(){

		if ($_POST) {

			$store_id = input('post.store_id/a') ? : $this->is_jurisdiction;

			$data['goods_brand_name'] = input('post.goods_brand_name/s');

			$data['url'] = input('post.url/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			Db::startTrans();
        	try{

				if (is_array($store_id)) {

					$arr = [];
					
					foreach ($store_id as $key => $value) {
						
						$data['store_id'] = $value;

						$arr[] = $data;
					}

					(new GoodsBrand)->Common_InsertAll($arr);

				}else{

					$data['store_id'] = $store_id;

					(new GoodsBrand)->Common_Insert($data);
				}


				//提交事务
				Db::commit();
				return json(['code' => 200 , 'msg' => '新增成功']);
			}catch (\Exception $e) {
	            // 回滚事务
	            Db::rollback();
	            return json(['code' => 400 , 'msg' => '新增失败']);
	        }
 

		}else{

			return view();
		}
	}

	//编辑品牌
	public function goods_brand_edit(){

		$id = input('id/d');

		if ($_POST) {

			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			$data['goods_brand_name'] = input('post.goods_brand_name/s');

			$data['url'] = input('post.url/s');

			$data['update_time'] = time();
 
			$edit = (new GoodsBrand)->Common_Update($data,['id' => $id]);

			if($edit)
				return json(['code' => 200, 'msg' => '更新成功']);
				return json(['code' => 400, 'msg' => '更新失败']);

		}else{

			$list = (new GoodsBrand)->Common_Find(['id' => $id]);

			$this->assign('list',$list);

			return view();
		}
	}

	//删除品牌
	public function goods_brand_del(){

		$id = input('post.id/d');

		$del = (new GoodsBrand)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 200, 'msg' => '删除成功']);
			return json(['code' => 400, 'msg' => '删除失败']);
	}

	//批量删除品牌
	public function goods_brand_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new GoodsBrand)->Common_Update(['status' => 1],['id' => ['in',$id]]);

		if($del)
			return json(['code' => 200, 'msg' => '删除成功']);
			return json(['code' => 400, 'msg' => '删除失败']);
	}

	//首页推荐
	public function goods_type_update_recommend_type(){

		$id = input('post.id/d');

		$recommend_name = input('post.recommend_name/s');

		$store_id = input('post.store_id/d');



		if ($recommend_name == '是') {

			if ($store_id == 0 && $this->is_jurisdiction == 0) { //批量删除
			
				$edit = (new StoreTypeRecommend)->Common_Delete(['type_id' => $id]);

			}else{

				$edit = (new StoreTypeRecommend)->Common_Delete(['type_id' => $id, 'store_id' => $this->is_jurisdiction]);
			}
		}else{

			if ($store_id == 0 && $this->is_jurisdiction == 0) { //批量新增

				$zuijia = [['id' => 0]]; //追加一个店铺
				
				$store = (new Store)->Common_All_Select(['status' => 1],[],['store_id id','store_name name']);

				$arr = [];

				if ($store) {
					
					$store = array_merge($zuijia,$store);
				}

				foreach ($store as $key => $value) {
					
					$arr[] = ['type_id' => $id, 'state' => 0, 'store_id' => $value['id']];
				}

				$edit = (new StoreTypeRecommend)->Common_InsertAll($arr);

			}else{

				$edit = (new StoreTypeRecommend)->Common_Insert(['type_id' => $id, 'state' => 0, 'store_id' => $this->is_jurisdiction]);
			}

		}
		if($edit)
			return json(['code' => 200, 'msg' => '操作成功']);
			return json(['code' => 400, 'msg' => '操作失败']);
	}
}