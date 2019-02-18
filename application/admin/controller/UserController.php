<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
class UserController extends AdminController
{
	/**
	 * [user_list 用户列表]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
    public function user_list($value='')
    {
    	return view();
    }
    /**
     * [index_ajax ajax获取列表]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function index_ajax($search='')
    {
    	$where = [];
        if($search){
            $where['user_name|nickname|phone'] = array('like',"%$search%");
        }
        $count = Db::name('lock_user')->where($where)->count();
        $offset = input('offset')?:0;
        $pagesize =input('limit')?:20;
        $data = Db::name('lock_user')->where($where)->limit($offset,$pagesize)->select();
        return json(['rows'=>$data,'total'=>$count]);
    }
    public function user_add($value='')
    {
    	if($_POST){
    		$data = input('post.');
    		$data['password'] = MD5(input('password'));
			$User = Model('lock_user');
			$rs = $User->add($data);
			if($rs){
				$this->success('添加成功',url('user_list'));
			}else{
				$this->error('添加失败');
			}
    	}else{
    		return view();
    	}
    }
   
}
