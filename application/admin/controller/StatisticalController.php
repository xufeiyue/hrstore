<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Problem;
class StatisticalController extends AdminController
{
	/*
		统计系统控制器
	*/
	//渲染题目页面
	public function subject_list(){

		return view();
	}

	//ajax获取题目数据
	public function ajax_subject_list(){

		$where = [];

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['store_id'] = $this->is_jurisdiction;
		}

		$where['status'] = 0;

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$data = (new Problem)->Common_Select($offset,$limit,$where,$order);

		foreach ($data['data'] as $key => $value) {
			
			if ($value['type']) {
				
				$data['data'][$key]['type_name'] = '多选题';

			}else{

				$data['data'][$key]['type_name'] = '单选题';
			}
		}

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//新增题目
	public function subject_add(){

		$type = input('get.type/d');

		if ($_POST) {
		
			if (!$this->is_jurisdiction) {
				$data['store_id'] = input('post.store_id/d');

				if (!$data['store_id'])
					return json(['code' => 400 , 'msg' => '请选择店铺']);

			}

			$data['problem'] = input('post.problem/s');

			$answer = input('post.answer/a');

			$data['answer'] = json_encode($answer);

			$data['type'] = input('post.type/d');

			$content = input('content/a');

			$data['content'] = json_encode($content);

			$data['create_time'] = time();

			$data['update_time'] = time();

			$add = (new Problem)->Common_Insert($data);

			if ($add)
				return json(['code' => 200 , 'msg' => '新增成功']);
				return json(['code' => 400 , 'msg' => '新增失败']);

		}else{

			$this->assign('type',$type);

			return view();
		}
	}

	//更新题目
	public function subject_edit(){

		$id = input('id/d');

		if ($_POST) {
			
			if (!$this->is_jurisdiction) {
				$data['store_id'] = input('post.store_id/d');

				if (!$data['store_id'])
					return json(['code' => 400 , 'msg' => '请选择店铺']);

			}

			$data['problem'] = input('post.problem/s');

			$answer = input('post.answer/a');

			$data['answer'] = json_encode($answer);

			$data['type'] = input('post.type/d');

			$content = input('content/a');

			$data['content'] = json_encode($content);

			$data['create_time'] = time();

			$data['update_time'] = time();

			$edit = (new Problem)->Common_Update($data,['id' => $id]);

			if ($edit)
				return json(['code' => 200 , 'msg' => '更新成功']);
				return json(['code' => 400 , 'msg' => '更新失败']);

		}else{

			$list = (new Problem)->Common_Find(['id' => $id]);

			$list['content'] = json_decode($list['content'],true);

			$list['answer'] = json_decode($list['answer'],true);

			$this->assign('list',$list);

			return view();
		}
	}

	//删除题目
	public function subject_del(){

		$id = input('post.id/d');

		$del = (new Problem)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);

	}

	//批量删除题目
	public function subject_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new Problem)->Common_Update(['status' => 1],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}
}