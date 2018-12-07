<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Member;
class MemberController extends Controller
{	
	//渲染页面
	public function member_list(){
	
		return view();
	}
	//ajax获取数据
	public function ajax_member_list(){

		$username = input('post.username/s');

		$where = [];

		if($username){

			$where['username']  = ['like','%{$username}%'];
		}

		$where['status'] = 1;

		$offset = (input('page') - 1) * input('limit') ? : 0;

		$limit = input('limit') ? : 10;

		$data = (new Member)->Common_Select($offset,$limit,$where);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}
	//添加
	public function member_add(){

		if($_POST){

			$data['email'] = input('post.email/s');

			$password = input('post.pass/s');

			$data['password'] = MD5($password);// 'r2'为盐值，默认是随机生成的两位字串

			$data['username'] = input('post.username/s');

			$data['head_portrait'] = input('post.head_portrait/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			$add = (new Member)->Common_Insert($data);

			if($add)
				return json(['code' => 1 , 'msg' => '添加成功']);
				return json(['code' => 2 , 'msg' => '添加失败']);

		}else{

			return view();
		}

	}
	//编辑
	public function member_edit(){

		$id = input('id/d');

		if($_POST){

			$data['email'] = input('post.email/s');

			$password = input('post.pass/s');

			if($password){

				$data['password'] = crypt($password,'r2');// 'r2'为盐值，默认是随机生成的两位字串
			}

			$data['username'] = input('post.username/s');

			$data['sign'] = input('post.sign/s');

			$data['update_time'] = time();

			$data['head_portrait'] = input('post.head_portrait/s');

			$edit = (new Member)->Common_Update($data,['id' => $id]);

			if($edit)
				return json(['code' => 1 , 'msg' => '编辑成功']);
				return json(['code' => 2 , 'msg' => '编辑失败']);

		}else{

			$list = (new Member)->Common_Find(['id' => $id]);

			$this->assign('list',$list);

			return view();
			
		}

	}
	//删除
	public function member_del(){

		$id = input('post.id/d');

		$del = (new Member)->Common_Update(['status' => 2],['id' => $id]);

		if($del)
			return json(['code' => 1 , 'msg' => '删除成功']);
			return json(['code' => 2 , 'msg' => '删除失败']);
	}
	//批量删除
	public function member_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new Member)->Common_Update(['status' => 2],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 1 , 'msg' => '删除成功']);
			return json(['code' => 2 , 'msg' => '删除失败']);
	}

	//上传图片
	public function upload(Request $request){

		// 获取表单上传文件
        $file = $request->file('file');

        if (empty($file)) {
            return json(['code' => 0 , 'msg' => '请选择文件']);
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' .DS .'member');

        if ($info) {
            // $this->success('文件上传成功：' . $info->getRealPath());
            return json(['code'=> 1 ,'data'=> '/uploads/member/' . $info->getSaveName()]);
        } else {
            // 上传失败获取错误信息
            // $this->error($file->getError());
            return json(['code'=> 0, 'data'=> '','msg'=> '上传失败']);
        }
	}

	public function member_password(){

		return view();
	}
}