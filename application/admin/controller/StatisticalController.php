<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Problem;
use app\admin\model\Questionnaire;
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

		$type_id = input('post.type_id');

		if (isset($type_id)) {

			$where['type'] = $type_id;
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

	//问卷模板渲染
	public function questionnaire_list(){

		return view();
	}

	//ajax获取问卷数据
	public function ajax_questionnaire_list(){

		$where = [];

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['store_id'] = $this->is_jurisdiction;
		}

		$where['status'] = 0;

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$data = (new Questionnaire)->Common_Select($offset,$limit,$where,$order);

		foreach ($data['data'] as $key => $value) {
			
			$data['data'][$key]['start_time'] = date('Y-m-d H:i:s',$value['start_time']);

			$data['data'][$key]['end_time'] = date('Y-m-d H:i:s',$value['end_time']);

		}

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//新增调查问卷
	public function questionnaire_add(){

		if ($_POST) {
			
			if (!$this->is_jurisdiction) {
				$data['store_id'] = input('post.store_id/d');

				if (!$data['store_id'])
					return json(['code' => 400 , 'msg' => '请选择店铺']);

			}

			$data['title'] = input('post.title/s');

			$data['start_time'] = strtotime(input('post.start_time'));

			$data['end_time'] = strtotime(input('post.end_time'));

			$data['questionnaire_text'] = input('post.questionnaire_text/s');

			$data['opinion_completion'] = input('post.opinion_completion/s');

			$problem_id = input('post.problem_id/a');

			if (empty($problem_id)) {
				return json(['code' => 400 , 'msg' => '请选择问题']);
			}

			$data['problem_id'] = implode(',', $problem_id);

			$data['create_time'] = time();

			$data['update_time'] = time();

			$add = (new Questionnaire)->Common_Insert($data);

			if($add)
				return json(['code' => 200 , 'msg' => '新增成功']);
				return json(['code' => 400 , 'msg' => '新增失败']);

		}else{

			$list = [];

			if ($this->is_jurisdiction) { // 店铺下所有题目

				$order = ['id' => 'desc'];

				$list = (new Problem)->Problem_Select(['store_id' => $this->is_jurisdiction , 'status' => 0],$order);
			}

			$this->assign('list',$list);

			return view();
		}
	}

	//编辑调查问卷
	public function questionnaire_edit(){

		$id = input('id/d');

		if ($_POST) {
			
			if (!$this->is_jurisdiction) {
				$data['store_id'] = input('post.store_id/d');

				if (!$data['store_id'])
					return json(['code' => 400 , 'msg' => '请选择店铺']);

			}

			$data['title'] = input('post.title/s');

			$data['start_time'] = strtotime(input('post.start_time'));

			$data['end_time'] = strtotime(input('post.end_time'));

			$data['questionnaire_text'] = input('post.questionnaire_text/s');

			$data['opinion_completion'] = input('post.opinion_completion/s');

			$problem_id = input('post.problem_id/a');
			if (empty($problem_id)) {
				return json(['code' => 400 , 'msg' => '请选择问题']);
			}
			$data['problem_id'] = implode(',', $problem_id);



			$data['update_time'] = time();

			$edit = (new Questionnaire)->Common_Update($data,['id' => $id]);

			if($edit)
				return json(['code' => 200 , 'msg' => '更新成功']);
				return json(['code' => 400 , 'msg' => '更新失败']);

		}else{

			$list = (new Questionnaire)->Common_Find(['id' => $id]);

			$list['start_time'] = date('Y-m-d H:i:s',$list['start_time']);

			$list['end_time'] = date('Y-m-d H:i:s',$list['end_time']);

			$list['problem_id'] = explode(',', $list['problem_id']);

			$problem = $this->problem_list($list['store_id']);

			$this->assign('list',$list);

			$this->assign('problem',$problem);

			return view();
		}
	}

	//删除问卷
	public function questionnaire_del(){

		$id = input('post.id/d');

		$del = (new Questionnaire)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);

	}

	//批量删除问卷
	public function questionnaire_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new Questionnaire)->Common_Update(['status' => 1],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);

	}

	//店铺下查询所有题目
	public function problem_list($storeId=0){

		$store_id = input('post.store_id/d') ? : $storeId;

		$order = ['type' => 'asc','id' => 'desc'];

		$list = (new Problem)->Problem_Select(['store_id' => $store_id , 'status' => 0],$order);

		$data = [];

		$arr = [];
		//分组
		foreach ($list as $key => $value) {
			
			$data[$value['type']][] = $value;

		}
		//重组
		foreach ($data as $key => $value) {
			
			foreach ($value as $key1 => $value1) {
				
				if ($value1['type'] == 1) {
					
					$arr[$key]['type'] = '多选题'; 
				}else{

					$arr[$key]['type'] = '单选题'; 
				}

				$arr[$key]['list'][] = $value1;
			}
		}

		if ($storeId) {
			return $arr;
		}

		if($list)
			return json(['code' => 200 , 'msg' => '请求成功' , 'data' => $arr]);
			return json(['code' => 400 , 'msg' => '请求成功,没有数据']);

	}

	//店铺下查询所有题目
	public function problem_list1($storeId=0){

		$store_id = input('store_id/d');

		$order = ['id' => 'desc'];

		$list = (new Problem)->Problem_Select(['store_id' => $store_id , 'status' => 0],$order);

		foreach ($list as $key => $value) {
			if ($value['type']) {
				$list[$key]['type_name'] = '多选题';
			}else{
				$list[$key]['type_name'] = '单选题';
			}
		}

		if($list)
			return json(['code' => 0 , 'msg' => '请求成功' , 'data' => $list]);
			return json(['code' => 1 , 'msg' => '请求成功,没有数据']);

	}
	//店铺下题目
	public function store_subject(){

		$store_id = input('store_id/d');

		$this->assign('store_id',$store_id);

		return view();
	}
}