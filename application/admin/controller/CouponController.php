<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/3
 * Time: 11:01
 */
namespace app\admin\controller;
use app\admin\model\CouponType;
use think\Controller;
use think\Request;
use app\admin\model\Member;
class CouponController extends Controller{
    private $coupon;
    private $coupon_type;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->coupon_type = new CouponType();

    }

    // 渲染模板
    public function index(){
        return view('coupon_type_list');
    }
    // 卡券类型列表
    public function coupon_type_list(){

        $store_name = input('post.store_name/s');

        $where = [];

        if($store_name){

            $where['store_name']  = ['like',"%{$store_name}%"];
        }

        //$where['status'] = array('<>',3);

        $offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

        $limit = input('post.limit/d') ? : 10;

        $order = ['card_type_id' => 'desc'];

        $data = $this->coupon_type->Common_Select($offset,$limit,$where,$order);

        return json(["code" =>  0, "msg" => "请求成功", 'data' => $data , 'count' => $data['count']]);
    }

    // 增加卡券类型并批量生产对应卡券
    public function add_coupon_type(){
        if(Request::instance()->post()){
            $data['card_type_name'] = input('post.card_type_name');
            $data['instructions'] = input('post.instructions');
            $data['start_time'] = strtotime(input('post.start_time'));
            $data['end_time'] = strtotime(input('post.end_time'));
            $data['scope_of_application'] = input('post.scope_of_application');
            $data['ticket_name'] = input('post.ticket_name');
            $data['end_time_desc'] = input('post.end_time_desc');
            $data['reserve'] = input('post.reserve');
            $data['face_value'] = input('post.face_value');
            $data['create_time'] = time();
            $add = $this->coupon_type->add_coupons($data);
            if($add){
                return json(['code' => 1 , 'msg' => '添加成功']);
            }else{
                return json(['code' => 2 , 'msg' => '添加失败']);
            }
        }else{
            return view();
        }
    }
}