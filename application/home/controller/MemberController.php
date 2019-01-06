<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Goods;
use app\home\model\Member;
use app\home\model\CollectionAndCoupons;
use app\home\model\BrowsingLog;
class MemberController extends CommonController
{

	/*
		个人中心控制器
	*/
	public function index(){

		$member = (new Member)->Common_Find(['id' => $this->userId]);

		$where = ['store_id' => $this->store_id, 'status' => 0, 'state' => 0, 'characteristic' => 0,'start_time' => ['<=',time()], 'end_time' => ['>=',time()]];

		$offset = 0;

		$limit = 10;

		$order = ['id' => 'desc'];

		$goods_field = ['id','goods_name','goods_original_price','goods_present_price','goods_images'];

		$goods_list = (new Goods)->Common_Select($offset,$limit,$where,$order,$goods_field);

		foreach ($goods_list as $key => $value) {

	      if ($value['goods_images']) {
	      
	        $goods_list[$key]['goods_images'] = json_decode($value['goods_images'],true)[0]; //取第一张图片
	      
	      }
	    }

	    $this->assign('member',$member);

		$this->assign('goods_list',$goods_list);

		return view();
	}

	//收藏列表
	public function collect(){

		$this->title = '我的收藏';

		//查询收藏并且没用到期的商品
		$collection = (new CollectionAndCoupons)->collection_goods(['c.userId' => $this->userId, 'c.status' => 0, 'c.type' => 1, 'g.start_time' => ['<=',time()], 'g.end_time' => ['>=',time()] ],['c.id' => 'desc'],['c.id','c.goods_id','g.goods_name','g.goods_original_price','g.goods_present_price','g.goods_images']);

		//过期商品id
		$collection_id = (new CollectionAndCoupons)->collection_id(['c.userId' => $this->userId, 'c.status' => 0, 'c.type' => 1, 'g.end_time' => ['<',time()] ],['c.id' => 'desc'],['c.id']);

		if ($collection_id) {
			//删除过期的商品
			(new CollectionAndCoupons)->Common_Update(['status' => 1],['id' => ['in',$collection_id]]);
		}

		foreach ($collection as $key => $value) {
			
			if ($value['goods_images']) {
				
				$collection[$key]['goods_images'] = json_decode($value['goods_images'],true)[0];
			}
		}

		$this->assign('collection',$collection);

		$this->assign('title',$this->title);

		return view();
	}

	//我的足迹
	public function zuji(){

		$this->title = '我的足迹';

	    $where['b.userId'] = $this->userId;

	    $where['b.type'] = 1;

	    $where['b.status'] = 0;

	    $where['g.start_time'] = ['<=',time()];

	    $where['g.end_time'] = ['>=',time()];

	    $order = ['b.id' => 'desc'];

	    $field = ['b.id','b.pid','g.goods_name','g.goods_original_price','g.goods_present_price','g.goods_images'];

    	$browsinglog = (new BrowsingLog)->zuji_goods($where,$order,$field);

    	//过期商品id
		$zuji_id = (new BrowsingLog)->zuji_id(['b.userId' => $this->userId, 'b.status' => 0, 'b.type' => 1, 'g.end_time' => ['<',time()] ],['b.id' => 'desc'],['b.id']);

		if ($zuji_id) {
			//删除过期的商品
			(new BrowsingLog)->Common_Update(['status' => 1],['id' => ['in',$zuji_id]]);
		}

    	foreach ($browsinglog as $key => $value) {
			
			if ($value['goods_images']) {
				
				$browsinglog[$key]['goods_images'] = json_decode($value['goods_images'],true)[0];
			}
		}

    	$this->assign('browsinglog',$browsinglog);

    	$this->assign('title',$this->title);

		return view();
	}

	//清除收藏
	public function remove_collect(){

		$id = array_unique(input('post.id/a'));

		$edit = (new CollectionAndCoupons)->Common_Update(['status' => 1],['id' => ['in',$id]]);

		if($edit)
			return json(['code' => 200 , 'msg' => '清除成功']);
			return json(['code' => 400 , 'msg' => '清除失败']);
	}

	//清除足迹
	public function remove_zuji(){

		$id = array_unique(input('post.id/a'));

		$edit = (new BrowsingLog)->Common_Update(['status' => 1],['id' => ['in',$id]]);

		if($edit)
			return json(['code' => 200 , 'msg' => '清除成功']);
			return json(['code' => 400 , 'msg' => '清除失败']);
	}

}