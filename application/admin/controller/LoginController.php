<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
class LoginController extends Controller
{
	public function login(Request $request){

    	if($_POST){
    		$data['user_name'] = input('username');
    		$data['password'] = md5(input('password'));

    		//验证用户名密码
    		$User = Model('User');

    		$rs = $User->check($data,$request->IP());
            //dump($rs);die;
    		if($rs){
    			//设置session
    			session('user_name',$rs['user_name']);
    			session('user_id',$rs['id']);
    			//$this->success('登录成功',url('admin/index/index'),'data',0);
                return json(['code' => 1 , 'msg' => '登录成功,正在跳转中....']);
    		}else{
    			//$this->error('用户名密码不对');
                return json(['code' => 2 , 'msg' => '用户名或密码错误']);
    		}
    	}else{
    		return view();
    	}
    }
    public function add()
    {
        $data = input('post.');
        // 数据验证
        $result = $this->validate($data,'User');
        if (true !== $result) {
            return $result;
        }
        $user = new UserModel;
        // 数据保存
        $user->allowField(true)->save($data);
        return '用户[ ' . $user->nickname . ':' . $user->id . ' ]新增成功';
    }
  	/* 退出登录 */
    public function logout(){
       
        session(null);

        return json(['code' => 1 , 'msg' => '退出成功!']);

    }

    public function verify(){
        $verify = new \Think\Verify();
        $verify->entry(1);
    }

}