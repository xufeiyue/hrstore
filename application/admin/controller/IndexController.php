<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
use app\admin\model\Store;
use app\admin\model\User;
class IndexController extends AdminController
{
    public function index($value='')
    {
        $data['user_name'] = session('user_name'); //账号名称

        $data['admin_type'] = session('admin_type') ? : 0; //判断是否显示切换店铺

        $this->assign('data',$data);

    	return view();
    }
    public function index_v1($value='')
    {
    	return view();
    }

    public function welcome()
    {

    	return view();
    }

    //切换店铺
    public function qiehuan_store(){

      if ($_POST) {
         
         $store_id = input('post.store_id/d');
         
         session('store_id',$store_id);

         return json(['code' => 200, 'msg' => '切换成功']);

      }else{

        $user = (new User)->Common_Find(['id' => session('user_id')]); //查询管理员信息

        $store_model = new Store();

        $store = $store_model->Common_All_Select(['status' => 1,'store_id' => ['in',$user['store_id']]],[],['store_id id','store_name name']);

        $this->assign('store',$store);

        $this->assign('store_id',$this->is_jurisdiction);

        return view();
      }

      
    }
}
