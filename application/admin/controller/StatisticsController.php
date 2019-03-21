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
class StatisticsController extends Controller{
    public function index(){
        return view();
    }
}