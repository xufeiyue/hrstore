<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
use app\admin\model\GoodsType;
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

		$data = (new GoodsType)->Common_Select($offset,$limit,$where,$order);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}


}