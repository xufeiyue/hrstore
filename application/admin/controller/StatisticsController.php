<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/7
 * Time: 4:33
 */
namespace app\Admin\controller;
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
        // 获取店铺列表
        $store_list = (new Store())->Common_All_Select();
        return view();
    }
   // 统计结果
    public function statistics_list(){
        // 默认全平台各种优惠券的领取数量
        
        return json(["code" =>  0, "msg" => "请求成功", 'data' => $data , 'count' => 2]);
    }
}