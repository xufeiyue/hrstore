<?php
namespace app\home\controller;
use app\home\model\Coupon;
use think\Controller;
use think\Db;
use app\home\model\Goods;
use app\home\model\Member;
use app\home\model\CollectionAndCoupons;
use app\home\model\BrowsingLog;
use app\home\model\SmsLog;
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

	    if ($member['age'] && $member['family'] && $member['mobilephone']) {
	    	
	    	$Integrity = 100;

	    }elseif ($member['age'] || $member['family'] || $member['mobilephone']) {

	    	$Integrity = 80;

	    }else{

	    	$Integrity = 50;
	    }

	    $this->assign('Integrity',$Integrity);

	    $this->assign('member',$member);

		$this->assign('goods_list',$goods_list);

		return view();
	}

	//完善资料
	public function infor(){

		$this->title = '个人资料';

		$member = (new Member)->Common_Find(['id' => $this->userId]);

		$this->assign('member',$member);

		$this->assign('title',$this->title);

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

	// 我的卡卷
	public function yhq(){
        $member_id = $this->userId;
        $coupon_model = new Coupon();
        // 我的优惠券且未过期的未使用的平台券
        $list1 = $coupon_model->get_my_coupon_pt($member_id);
        // 我的优惠券且未过期的未使用的单品券
        $list2 = $coupon_model->get_my_coupon_dp($member_id);
        foreach($list2 as $key=>$val){
            $list2[$key]['goods_img'] = json_decode($val['goods_images'],true)[0];
        }

        // 我的已使用的优惠券且未过期的平台券
        $list3 = $coupon_model->get_my_coupon_is_use_pt($member_id);
        // 我的已使用的优惠券且未过期的单品券
        $list4 = $coupon_model->get_my_coupon_is_use_dp($member_id);
        foreach($list4 as $key=>$val){
            $list4[$key]['goods_img'] = json_decode($val['goods_images'],true)[0];
        }
        // 我的过期优惠券的平台券
        $list5 = $coupon_model->get_my_coupon_guoqi_pt($member_id);
        // 我的过期优惠券的单品券
        $list6 = $coupon_model->get_my_coupon_guoqi_dp($member_id);
        foreach($list6 as $key=>$val){
            $list6[$key]['goods_img'] = json_decode($val['goods_images'],true)[0];
        }
        $this->assign('list1',$list1);
        $this->assign('list2',$list2);
        $this->assign('list3',$list3);
        $this->assign('list4',$list4);
        $this->assign('list5',$list5);
        $this->assign('list6',$list6);
		return view();
	}

	//发送短信
	public function sms_send(){

		$mobilephone = input('post.mobilephone/s');

		vendor('sms.api_demo.SmsDemo');

		$sms = Sms($mobilephone,config('SMS_Template_ID'),1);

		if ($sms)
			return json(['code' => 200, 'msg' => '短信发送成功']);
			return json(['code' => 400 , 'msg' => '发送频繁，请稍后重试']);

	}

	//绑定手机号
	public function update_member(){

		$mobilephone = input('post.mobilephone/s');

		$code = input('post.code/s');

		$sms = (new SmsLog)->Common_Find(['phone' => $mobilephone, 'code' => $code , 'create_time' => ['>',time() - 300], 'status' => 0]);

		if (!$sms)
			return json(['code' => 400 , 'msg' => '验证码不正确']);

		// 启动事务
		Db::startTrans();
		try{
		   $sms_edit = (new SmsLog)->Common_Update(['status' => 1],['id' => $sms['id']]);

		   $member_edit = (new Member)->Common_Update(['mobilephone' => $mobilephone],['id' => $this->userId]);
		   if ($sms_edit && $member_edit) {
		   	 // 提交事务
		     Db::commit();
		     return json(['code' => 200 , 'msg' => '绑定成功']);
		   }
		     
		} catch (\Exception $e) {
		    // 回滚事务
		    Db::rollback();
		}
		return json(['code' => 400 , 'msg' => '绑定失败']);

	}


	//修改资料
	public function edit_member(){

		$mobilephone = input('post.mobilephone/s');

		$code = input('post.code/s');

		if ($mobilephone) {
			
			$sms = (new SmsLog)->Common_Find(['phone' => $mobilephone, 'code' => $code , 'create_time' => ['>',time() - 300], 'status' => 0]);

			if (!$sms)
				return json(['code' => 400 , 'msg' => '验证码不正确']);


			(new SmsLog)->Common_Update(['status' => 1],['id' => $sms['id']]);

			$data['mobilephone'] = $mobilephone;

		}

		$data['nickname'] = input('post.nickname/s');

		$data['sex'] = input('post.sex/d');

		$data['age'] = input('post.age/d');

		$data['family'] = input('post.family/d');

		$edit = (new Member)->Common_Update($data,['id' => $this->userId]);

		if ($edit)
			return json(['code' => 200, 'msg' => '更新成功']);
			return json(['code' => 400, 'msg' => '更新失败']);

	}

}