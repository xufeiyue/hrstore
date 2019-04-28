<?php
namespace app\home\controller;
use app\home\model\Advertisement;
use app\home\model\AdvertisementType;
use think\Controller;
use app\home\model\Activity;
use app\home\model\ActivityLog;
use app\home\model\Goods;
class ActivityController extends CommonController
{
	/*
		活动控制器
	*/
	//参加活动列表
	public function huodong(){

		$ActivityLog = (new ActivityLog)->ActivityLog_list(['a_l.userId' => $this->userId, 'a_l.status' => 0],['a_l.id' => 'desc'],['a.id','a.activity_url']);

		$this->assign('ActivityLog',$ActivityLog);

		return view();
	}

	//详情
	public function lq_huodong(){

		$this->title = '活动详情';

		$id = input('id/d');

		$data['activity_id'] = $id;

		$data['userId'] = $this->userId;

		$ActivityLog = (new ActivityLog)->Common_Find(['activity_id' => $id, 'userId' => $this->userId]);

		if (empty($ActivityLog)) {

			$data['createTime'] = time();

			$data['updateTime'] = time();

			(new ActivityLog)->Common_Insert($data);
		}


		$Activity = (new Activity)->Common_Find(['id' => $id],[],['id','activity_name','activity_url','activity_detail']);

		$this->assign('title',$this->title);

		$this->assign('Activity',$Activity);

		return view();
	}

	//活动产品列表
	public function activity_default(){

		$id = input('id/d');
		$time = time();
		$store_id = $this->store_id;
        $AdvertisementType = (new AdvertisementType)->Common_Find(['status' => 0, 'type_name' => '首页轮播图']);
        $whereor = "(xianshi = 0 and end_time >= {$time}) or (start_time <= {$time} and end_time >= {$time})";
        $Advertisement = (new Advertisement)->Common_All_Select(['store_id' => $store_id, 'status' => 0,
            'type_id' => $AdvertisementType['id']],['id' => 'asc'],['id','image','url'],$whereor);
        $this->assign('Advertisement',$Advertisement);

		$whereor = "((xianshi = 0 and end_time >= {$time}) or (start_time <= {$time} and end_time >= {$time}))";

		$goods_list = (new Goods)->Common_All_Select(['activity_id' => $id, 'store_id' => $this->store_id,'state'=>0,'status'=>0],['hd_goods_px' => 'asc'],['id','goods_name','goods_original_price','goods_present_price','goods_images'],$whereor);
        $Activity = (new Activity)->get_activity_goods(['id' => $id]);

		foreach ($goods_list as $key => $value) {

	      if ($value['goods_images']) {
	      
	         $goods_list[$key]['goods_images'] = json_decode($value['goods_images'],true)[0]; //取第一张图片
	          $arr = explode('.',$value['goods_present_price']);
	          $goods_list[$key]['price1'] = $arr[0];
	          $goods_list[$key]['price2'] = '.'.$arr[1];
	      }
	    }

		$this->assign('goods_list',$goods_list);
        $this->assign('Activity',$Activity);
		return view();
	}
}