<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/25
 * Time: 15:57
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
use think\Db;
use think\Loader;

class TestController extends Controller
{
    public function jinji(){
        $model = New Coupon();
        // 查询没有领取的card_type_id = 30的优惠券
        $w_not_use['status'] = 2;
        $w_not_use['card_type_id'] = 30;
        $list_not_use = (New Coupon())->get_not_use_coupon($w_not_use);
        // 查询领取重复的优惠券card_type_id = 30
        $list_cf = (New Coupon())->get_cf_list();
        echo '<pre>';
        //for($i=0;$i<count($list_cf);$i++){
            foreach($list_cf as $key=>$val){
                $w_cf['card_ticket_id'] = $val['card_ticket_id'];
                $w_cf['c'] = $val['c']- 1;
                $up_list = $model->edit_cf($w_cf);
                print_r($up_list);
            }
       // }
    }

    public function aaa(){
        $id = input('id');
        $model = New Coupon();
        $model ->aaa($id);
    }

    public function hwwebscan_verify(){
        return view();
    }

    public function index(){
        return view('hwwebscan_verify');
    }
}