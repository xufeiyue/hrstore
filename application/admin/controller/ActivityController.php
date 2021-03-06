<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Activity;
use app\admin\model\ActivityLibrary;
class ActivityController extends AdminController
{	
	/*
		活动控制器
	*/
	//渲染页面
	public function Activity_list(){

		return view();
	}

	//ajax获取活动列表
	public function ajax_activity_list(){

		$where = [];

		$activity_name = input('post.activity_name/s');

		if($activity_name){

			$where['activity_name']  = ['like',"%{$activity_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['store_id'] = ['in',$this->is_jurisdiction];
		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$where['status'] = 0;

		$data = (new Activity)->Common_Select($offset,$limit,$where,$order);

		foreach ($data['data'] as $key => $value) {
			
			if ($value['pid']) {
				
				$data['data'][$key]['pid_name'] = '活动库';

			}else{
				
				$data['data'][$key]['pid_name'] = '自创';
			}
		}

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//导出
	public function activity_export(){

		$where = [];

		$activity_name = input('post.activity_name/s');

		if($activity_name){

			$where['activity_name']  = ['like',"%{$activity_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['store_id'] = ['in',$this->is_jurisdiction];
		}

		$order = ['id' => 'desc'];

		$where['status'] = 0;

		$data = (new Activity)->Common_All_Select($where,$order);

		if($data)
			return json(['code' => 200 , 'msg' => '请求成功', 'data' => $data]);
			return json(['code' => 400 , 'msg' => '没有可导出数据']);
	}

	//新增活动
	public function activity_add(){

		if ($_POST) {

			$state = input('post.state/s');

			if ($state == 'on') {
				
				$data['state'] = 0;
			
			}else{

				$data['state'] = 1;
			}

			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);


			$data['activity_name'] = input('post.activity_name/s');

			$data['activity_url'] = input('post.activity_url/s');

			$data['activity_detail'] = input('post.activity_detail/s');

			$data['activity_start_time'] = strtotime(input('post.activity_start_time/s'));

			$data['activity_end_time'] = strtotime(input('post.activity_end_time/s'));

			$data['create_time'] = time();

			$data['update_time'] = time();
			
			$add = (new Activity)->Common_Insert($data);

			if($add)
				return json(['code' => 200 , 'msg' => '新增成功']);
				return json(['code' => 400 , 'msg' => '新增失败']);
		}else{

			return view();
		}
	}

	//编辑活动
	public function activity_edit(){

		$id = input('id/d');

		if ($_POST) {
				
			$state = input('post.state/s');

			if ($state == 'on') {
				
				$data['state'] = 0;
			
			}else{

				$data['state'] = 1;
			}

			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$data['activity_name'] = input('post.activity_name/s');

			$data['activity_url'] = input('post.activity_url/s');

			$data['activity_detail'] = input('post.activity_detail/s');

			$data['activity_start_time'] = strtotime(input('post.activity_start_time/s'));

			$data['activity_end_time'] = strtotime(input('post.activity_end_time/s'));

			$data['update_time'] = time();
			
			$edit = (new Activity)->Common_Update($data,['id' => $id]);

			if($edit)
				return json(['code' => 200 , 'msg' => '更新成功']);
				return json(['code' => 400 , 'msg' => '更新失败']);


		}else{

			$list = (new Activity)->Common_Find(['id' => $id]);

			$list['activity_start_time'] = date('Y-m-d H:i:s',$list['activity_start_time']);

			$list['activity_end_time'] = date('Y-m-d H:i:s',$list['activity_end_time']);

			$this->assign('list',$list);

			return view();
		}
	}

	//更新开关
	public function activity_stateupdate(){

		$id = input('post.id/d');

		$state = input('post.state/d');

		$edit = (new Activity)->Common_Update(['state' => $state],['id' => $id]);

		if($edit)
			return json(['code' => 1 , 'msg' => '更新成功']);
			return json(['code' => 2 , 'msg' => '更新失败']);
	}

	//删除活动
	public function activity_del(){

		$id = input('post.id/d');

		$del = (new Activity)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 1 , 'msg' => '删除成功']);
			return json(['code' => 2 , 'msg' => '删除失败']);
	}

	//批量删除
	public function activity_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new Activity)->Common_Update(['status' => 1],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 1 , 'msg' => '删除成功']);
			return json(['code' => 2 , 'msg' => '删除失败']);
	}

	//渲染活动库模板
	public function activity_library_list(){

		return view();
	}

	//ajax获取活动库数据
	public function ajax_activity_library_list(){

		$where = [];

		$activity_name = input('post.activity_name/s');

		if($activity_name){

			$where['activity_name']  = ['like',"%{$activity_name}%"];
		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$where['status'] = 0;

		$data = (new ActivityLibrary)->Common_Select($offset,$limit,$where,$order);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//新增活动库
	public function activity_library_add(){

		if ($_POST) {

			$data['state'] = 1;

			$data['activity_name'] = input('post.activity_name/s');

			$data['activity_url'] = input('post.activity_url/s');

			$data['activity_detail'] = input('post.activity_detail/s');

			$data['activity_start_time'] = strtotime(input('post.activity_start_time/s'));

			$data['activity_end_time'] = strtotime(input('post.activity_end_time/s'));

			$data['create_time'] = time();

			$data['update_time'] = time();
			
			$add = (new ActivityLibrary)->Common_Insert($data);

			if($add)
				return json(['code' => 200 , 'msg' => '新增成功']);
				return json(['code' => 400 , 'msg' => '新增失败']);
		}else{

			return view();
		}
	}

	//编辑活动库
	public function activity_library_edit(){

		$id = input('id/d');

		if ($_POST) {
				
			$data['state'] = 1;

			$data['activity_name'] = input('post.activity_name/s');

			$data['activity_url'] = input('post.activity_url/s');

			$data['activity_detail'] = input('post.activity_detail/s');

			$data['activity_start_time'] = strtotime(input('post.activity_start_time/s'));

			$data['activity_end_time'] = strtotime(input('post.activity_end_time/s'));

			$data['update_time'] = time();
			
			$edit = (new ActivityLibrary)->Common_Update($data,['id' => $id]);

			if($edit)
				return json(['code' => 200 , 'msg' => '更新成功']);
				return json(['code' => 400 , 'msg' => '更新失败']);


		}else{

			$list = (new ActivityLibrary)->Common_Find(['id' => $id]);

			$list['activity_start_time'] = date('Y-m-d H:i:s',$list['activity_start_time']);

			$list['activity_end_time'] = date('Y-m-d H:i:s',$list['activity_end_time']);

			$this->assign('list',$list);

			return view();
		}
	}

	//更新活动库开关
	public function activity_library_stateupdate(){

		$id = input('post.id/d');

		$state = input('post.state/d');

		$edit = (new ActivityLibrary)->Common_Update(['state' => $state],['id' => $id]);

		if($edit)
			return json(['code' => 1 , 'msg' => '更新成功']);
			return json(['code' => 2 , 'msg' => '更新失败']);
	}

	//删除活动库
	public function activity_library_del(){

		$id = input('post.id/d');

		$del = (new ActivityLibrary)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 1 , 'msg' => '删除成功']);
			return json(['code' => 2 , 'msg' => '删除失败']);
	}

	//批量活动库
	public function activity_library_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new ActivityLibrary)->Common_Update(['status' => 1],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 1 , 'msg' => '删除成功']);
			return json(['code' => 2 , 'msg' => '删除失败']);
	}

	//拉取活动
	public function pull_up_activity(){

		return view();
	}

	//ajax获取活动库数据
	public function ajax_pull_up_activity(){

		$where = [];

		$activity_name = input('post.activity_name/s');

		if($activity_name){

			$where['activity_name']  = ['like',"%{$activity_name}%"];
		}

		$order = ['id' => 'desc'];

		$where['status'] = 0;

		$Activity = (new Activity)->Activity_Pid(['status' => 0 , 'pid' => ['>', 0], 'store_id' => $this->is_jurisdiction],$order);

		if (!empty($Activity)) {
			
			$where['id'] = ['not in', $Activity];
		}

		$data = (new ActivityLibrary)->ActivityLibrary_Select($where,$order);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data ]);
	}

	//查看活动
	public function activity_see(){

		$id = input('id/d');

		$list = (new ActivityLibrary)->Common_Find(['id' => $id]);

		$list['activity_start_time'] = date('Y-m-d H:i:s',$list['activity_start_time']);

		$list['activity_end_time'] = date('Y-m-d H:i:s',$list['activity_end_time']);

		$this->assign('list',$list);

		return view();
	}

	//拉取信息到店铺下
	public function store_activity(){

		$id = array_unique(input('post.id/a'));

		$order = ['id' => 'desc'];

		$data = (new ActivityLibrary)->ActivityLibrary_Select(['id' => ['in',$id] , 'status' => 0],$order);

		$arr = [];

		foreach ($data as $key => $value) {
			
			$arr[$key]['activity_name'] = $value['activity_name'];

			$arr[$key]['activity_url'] = $value['activity_url'];

			$arr[$key]['activity_start_time'] = $value['activity_start_time'];

			$arr[$key]['activity_end_time'] = $value['activity_end_time'];

			$arr[$key]['activity_detail'] = $value['activity_detail'];

			$arr[$key]['state'] = 1;

			$arr[$key]['activity_end_time'] = $value['activity_end_time'];

			$arr[$key]['link_state'] = $value['link_state'];

			$arr[$key]['participants_number'] = $value['participants_number'];

			$arr[$key]['number'] = $value['number'];

			$arr[$key]['pid'] = $value['id'];

			$arr[$key]['store_id'] = $this->is_jurisdiction;

			$arr[$key]['create_time'] = time();

			$arr[$key]['update_time'] = time();

		}

		$add = (new Activity)->Common_InsertAll($arr);

		if($add)
			return json(['code' => 200 , 'msg' => '拉取成功']);
			return json(['code' => 400 , 'msg' => '拉取失败']);

	}
}