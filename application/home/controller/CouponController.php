<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/13
 * Time: 1:23
 */
namespace app\home\controller;
use app\home\model\Coupon;
use app\home\model\CouponType;
use think\Controller;
use app\home\model\Store;
use app\home\model\Goods;
use app\home\model\GoodsType;
use app\home\model\Activity;
use app\home\model\CollectionAndCoupons;
use app\home\model\BrowsingLog;
use app\home\model\NewDiscovery;
use app\home\model\Advertisement;
use app\home\model\AdvertisementType;
use app\home\model\Region;
use app\home\model\Questionnaire;
use app\home\model\Problem;
use app\home\model\Member;
class CouponController extends CommonController
{
    public function coupon_list(){
        // 获取所有优惠券分类
        $coupon_type_model = new CouponType();
        $time = time();
        $where_pt['end_time'] = array('<',$time);
        $where_pt['is_use'] = 2;
        $where_pt['ticket_type'] = 1;
        $field = [];
        $order=[];
        $coupon_type_pt_list = $coupon_type_model->Common_All_Select($where_pt,$order,$field);
        $where_dp['end_time'] = array('<',$time);
        $where_dp['is_use'] = 2;
        $where_dp['ticket_type'] = 1;
        $field = [];
        $order=[];
        $coupon_type_dp_list = $coupon_type_model->Common_All_Select($where_dp,$order,$field);
        $this->assign('coupon_type_pt_list',$coupon_type_pt_list);
        $this->assign('coupon_type_dp_list',$coupon_type_dp_list);
        return view();
    }
}
