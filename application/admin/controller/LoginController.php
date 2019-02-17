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
    		if($rs && $rs['status'] == 1){
    			//设置session
    			session('user_name',$rs['user_name']);//账号
    			session('user_id',$rs['id']); //管理员id
                if (!is_numeric($rs['store_id'])) { //多店铺的时候
                    $rs['store_id'] = explode(',', $rs['store_id'])[0]; //多店铺默认管理第一个店铺
                    session('admin_type',1);
                }
                session('store_id',$rs['store_id']); //店铺id
                return json(['code' => 1 , 'msg' => '登录成功,正在跳转中....']);
    		}elseif ($rs && ($rs['status'] == 0)) {
                return json(['code' => 2 , 'msg' => '该账号已被冻结，请联系管理员']);
            } else{
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