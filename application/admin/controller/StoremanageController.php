<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/8
 * Time: 13:54
 */

namespace app\admin\controller;
use app\admin\model\Store;
use think\Controller;
use think\Db;
use think\Model;
use think\Request;
class StoremanageController extends Controller
{
    private $store_model;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->store_model = new Store();

    }


    // 渲染模板
    public function index(){
        return view();
    }

    // 店铺列表
    public function ajax_store_list(){
        $store_name = input('post.store_name/s');

        $where = [];

        if($store_name){

            $where['store_name']  = ['like',"%{$store_name}%"];
        }

        $where['status'] = array('<>',3);

        $offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

        $limit = input('post.limit/d') ? : 10;

        $order = ['store_id' => 'desc'];

        $store_model = new Store();

        $data = $store_model->Common_Select($offset,$limit,$where,$order);
        $data_list = $data['data'];
        foreach($data_list as $k=>$v){
            if($v['status'] == 1){
                $data_list[$k]['status_desc'] = "<font color='green'>开启</font>";
            }else{
                $data_list[$k]['status_desc'] = "<font color='red'>关闭</font>";
            }
        }

        return json(["code" =>  0, "msg" => "请求成功", 'data' => $data_list , 'count' => $data['count']]);
    }

    /*
     *  添加商家
     * */
    public function store_add(){
        if(Request::instance()->isPost()){
            $data['store_name'] = input('post.store_name');
            $data['login_account'] = input('post.login_account');
            $pwd = input('post.login_pwd');
            $data['login_pwd'] = md5($pwd);
            $data['longitude'] = input('post.longitude');
            $data['latitude'] = input('post.latitude');
            $data['phone_number'] = input('post.phone_number');
            $data['address'] = input('post.address');
            $data['business_hours_desc'] = input('post.business_hours_desc');
            $data['store_desc'] = input('post.store_desc');
            $data['banner1'] = input('post.banner1');
            $data['banner2'] = input('post.banner2');
            $data['banner3'] = input('post.banner3');
            $data['banner4'] = input('post.banner4');
            $data['create_time'] = time();
            $data['update_time'] = time();
            if(input('post.open')){
                $data['status'] = 1;
            }else{
                $data['status'] = 2;
            }
            //echo json_encode($data);
            $store_model = new Store();
            $add = $store_model->Common_Insert($data);
            if($add){
                return json(['code' => 1 , 'msg' => '添加成功']);
            }else{
                return json(['code' => 2 , 'msg' => '添加失败']);
            }
        }else{
            return view();
        }
    }

    // 获取商家信息

    public function show_storeinfo(){

        $store_id = Request::instance()->param('store_id');

        $store_model = new Store();

        $info = $store_model->Common_Find(array('store_id'=>$store_id));

        $this->assign('info',$info);

        return view();
    }

    public function store_edit(){
        $store_id = Request::instance()->param('store_id');
        if($this->request->isPost()){
            $data['store_name'] = input('post.store_name');
            $data['login_account'] = input('post.login_account');
            $pwd = input('post.login_pwd');
            if($pwd != ''){
                $data['login_pwd'] = md5($pwd);
            }
            $data['longitude'] = input('post.longitude');
            $data['latitude'] = input('post.latitude');
            $data['phone_number'] = input('post.phone_number');
            $data['address'] = input('post.address');
            $data['business_hours_desc'] = input('post.business_hours_desc');
            $data['store_desc'] = input('post.store_desc');
            $data['banner1'] = input('post.banner1');
            $data['banner2'] = input('post.banner2');
            $data['banner3'] = input('post.banner3');
            $data['banner4'] = input('post.banner4');

            $data['update_time'] = time();
            if(input('post.open')){
                $data['status'] = 1;
            }else{
                $data['status'] = 2;
            }

            $store_model = new Store();
            $add = $store_model->Common_Update($data,array('store_id'=>$store_id));
            if($add){
                return json(['code' => 1 , 'msg' => '编辑成功']);
            }else{
                return json(['code' => 2 , 'msg' => '编辑失败']);
            }
        }else{


            $store_model = new Store();

            $info = $store_model->Common_Find(array('store_id'=>$store_id));

            $this->assign('info',$info);

            return view();
        }

    }
    // 删除门店
    public function store_del(){
        $store_id = Request::instance()->param('store_id');
        $data['status'] = '3';
        $store_model = new Store();
        $add = $store_model->Common_Update($data,array('store_id'=>$store_id));
        if($add){
            return json(['code' => 1 , 'msg' => '删除成功']);
        }else{
            return json(['code' => 2 , 'msg' => '删除失败']);
        }
    }

    // 批量删除店铺

    public function store_del_all(){
        $store_id = array_unique(input('post.store_id/a'));
        $store_model = new Store();
        $del = $store_model->Common_Update(['status' => 3],['store_id' => ['in', $store_id]]);

        if($del)
            return json(['code' => 1 , 'msg' => '删除成功']);
        return json(['code' => 2 , 'msg' => '删除失败']);
    }
}