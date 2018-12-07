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

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

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

				$data['password'] = MD5($password);// 'r2'为盐值，默认是随机生成的两位字串
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

	
	public function member_password(){

		return view();
	}

}