<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
class RebateController extends AdminController
{
	//返利管理
    public function rebate_list($value='')
    {
    	$list = Db::name('rebate')->find();
    	if($list['frist_rebate']){
    		$this->assign('type',1);
    	}else{
    		$this->assign('type',2);
    	}
    	return view();
    }
    //返利ajax列表
    public function rebate_list_ajax($search='')
    {
    	$where = [];

        if($search){

            $where['frist_rebate|second_rebate'] = array('like',"%$search%");

        }

        $count = Db::name('rebate')->where($where)->count();

        $offset = input('offset')?:0;

        $pagesize =input('limit')?:20;

        $data = Db::name('rebate')->where($where)->limit($offset,$pagesize)->select();

        return json(['rows'=>$data,'total'=>$count]);

    }
    //编辑返利
    public function edit_rebate($id='')
    {
    	if($_POST){

    		$data = input('post.');

    		$data['update_time'] = date('Y-m-d H:i:s');

    		$list = Db::name('rebate')->where('id',$data['id'])->update($data);

    		if($list){

                $this->redirect(url('rebate_list'));

            }else{

                $this->error('修改失败');

            }

    	}else{

    		$id = input('id');

    		$data = Db::name('rebate')->where('id',$id)->find();

    		$this->assign('data',$data);

    		return view();

    	}
    }
    //添加返利
    public function add_rebate($value='')
    {
    	if($_POST){

    		$data = input('post.');

    		$data['update_time'] = date('Y-m-d H:i:s');

    		$data['user_id'] = session('user_id');

    		$list = Db::name('rebate')->insert($data);

    		if($list){

                $this->redirect(url('rebate_list'));

            }else{

                $this->error('添加失败');

            }
    	}else{
    		return view();
    	}
    }
}
