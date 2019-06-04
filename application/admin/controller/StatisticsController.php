<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/7
 * Time: 4:33
 */
namespace app\admin\controller;
use app\admin\model\Coupon;
use think\Controller;
use think\Db;
use think\Request;
use admin\model\AuthRule;
use admin\model\Auth;
use admin\model\Menu;
use admin\model\AuthGroup;
use think\Session;
use app\admin\model\Store;
// 统计
class StatisticsController extends AdminController{
    public function index(){
        return view();
    }
    // 按券统计
    public function statistics_list(){
        // 默认全平台各种优惠券的领取数量
//        $card_type = input('post.card_type');
//        $where = [];
//        $where1 = ['status'=>1];
//        if($card_type !='' && $card_type>0){
//            $where = ['card_ticket_type_res.ticket_type'=>$card_type];
//        }
//        $start_time = input('post.start_time');
//
//        $end_time = input('post.end_time');
//        if($start_time !='' && $end_time!=''){
//            $start_time = strtotime($start_time);
//            $end_time = strtotime($end_time);
//
//            $where = ['r.create_time'=>[['gt',$start_time],['lt',$end_time]]];
//        }
        $start_time = strtotime(input('post.start_time'));
        $end_time = strtotime(input('post.end_time'));
        $end_time+=3600*24-1;
        $w1 = '';$w2=''; $w3 = '';$w4 = '';
        $start_days = date('Y-m-d',$start_time);
        $end_days = date('Y-m-d',$end_time);
        if($start_time!='' && $end_time !=''){
            $w1 = " AND ( r.create_time > $start_time AND r.create_time < $end_time )";

            $w2 = "WHERE create_time > $start_time AND create_time < $end_time";
            $time = time();

            $w3 = "where `status` = 0 and `state` = 0 and `sell_well` = 0 and ((xianshi = 0 and end_time >= {$time}) or (start_time <= {$time} and end_time >= {$time}))";

            $w4 = "where visits_date >=  '$start_days' and visits_date <= '$end_days'";
        }
        $list = (new Coupon())->getAllCoupon1($w1,$w2,$w3,$w4);

        return json(["code" =>  0, "msg" => "请求成功", 'data' => $list , 'count' => count($list)]);
    }

//    public function statistics_list1(){
//        $where = [];
//        $where1 = ['status'=>1];
//        $list = (new Coupon())->getAllCoupon1($where,$where1);
//        return json(["code" =>  0, "msg" => "请求成功", 'data' => $list , 'count' => 2]);
//    }
}