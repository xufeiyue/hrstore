<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Problem;
use app\admin\model\Questionnaire;
use app\admin\model\ItemBank;
use app\admin\model\Goods;
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
			
			$where['store_id'] = ['in',$this->is_jurisdiction];
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

			if ($value['pid']) {
				
				$data['data'][$key]['pid_name'] = '题库';

			}else{

				$data['data'][$key]['pid_name'] = '自创';
			}
		}

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//新增题目
	public function subject_add(){

		$type = input('get.type/d');

		if ($_POST) {
		
			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

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
			
			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

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

	//查看详情
	public function subject_see(){

		$id = input('id/d');

		$list = (new ItemBank)->Common_Find(['id' => $id]);

		$list['content'] = json_decode($list['content'],true);

		$list['answer'] = json_decode($list['answer'],true);

		$this->assign('list',$list);

		return view();
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
			
			$where['store_id'] = ['in',$this->is_jurisdiction];
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
			
			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

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

				foreach ($list as $key => $value) {
					
					if ($value['type']) {
				
						$list[$key]['type_name'] = '多选题';

					}else{
						
						$list[$key]['type_name'] = '单选题';

					}

					if ($value['pid']) {
				
						$list[$key]['pid_name'] = '题库';

					}else{

						$list[$key]['pid_name'] = '自创';
					}
				}
			}

			$this->assign('list',json_encode($list));

			return view();
		}
	}

	//编辑调查问卷
	public function questionnaire_edit(){

		$id = input('id/d');

		if ($_POST) {
			
			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

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

			foreach ($problem as $key => $value) {
				
				foreach ($list['problem_id'] as $value1) {
					
					if ($value['id'] == $value1) {
						
						$problem[$key]['LAY_CHECKED'] = true;
					}
				}
			}

			$this->assign('list',$list);

			$this->assign('problem',json_encode($problem));

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

		$store_id = input('store_id/d') ? : $storeId;

		$order = ['id' => 'desc'];

		$list = (new Problem)->Problem_Select(['store_id' => $store_id , 'status' => 0],$order);

		foreach ($list as $key => $value) {
			
			if ($value['type']) {
				
				$list[$key]['type_name'] = '多选题';

			}else{
				
				$list[$key]['type_name'] = '单选题';
			}

			if ($value['pid']) {
				
				$list[$key]['pid_name'] = '题库';

			}else{

				$list[$key]['pid_name'] = '自创';
			}
		}

		if ($storeId) {
			return $list;
		}

		if($list)
			return json(['code' => 0 , 'msg' => '请求成功' , 'data' => $list]);
			return json(['code' => 1 , 'msg' => '请求成功,没有数据','data' => []]);

	}


	//店铺拉取题库模板
	public function subject_item_bank(){

		return view();
	}

	//ajax获取所有问题信息
	public function ajax_subject_item_bank(){

		$where = [];

		$where['status'] = 0;

		$order = ['id' => 'desc'];

		$problem = (new Problem)->Problem_Pid(['status' => 0 , 'pid' => ['>', 0], 'store_id' => ['in',$this->is_jurisdiction];],$order);

		if (!empty($problem)) {
			
			$where['id'] = ['not in', $problem];
		}

		$data = (new ItemBank)->ItemBank_Select($where,$order);

		foreach ($data as $key => $value) {
			
			if ($value['type']) {
				
				$data[$key]['type_name'] = '多选题';

			}else{

				$data[$key]['type_name'] = '单选题';
			}

		}

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data ]);
	}

	//拉取信息到店铺下题库列表
	public function store_problem(){

		$id = array_unique(input('post.id/a'));

		$order = ['id' => 'desc'];

		$data = (new ItemBank)->ItemBank_Select(['id' => ['in',$id] , 'status' => 0],$order);

		$arr = [];

		foreach ($data as $key => $value) {
			
			$arr[$key]['type'] = $value['type'];

			$arr[$key]['problem'] = $value['problem'];

			$arr[$key]['answer'] = $value['answer'];

			$arr[$key]['content'] = $value['content'];

			$arr[$key]['pid'] = $value['id'];

			$arr[$key]['store_id'] = $this->is_jurisdiction;

			$arr[$key]['create_time'] = time();

			$arr[$key]['update_time'] = time();

		}

		$add = (new Problem)->Common_InsertAll($arr);

		if($add)
			return json(['code' => 200 , 'msg' => '拉取成功']);
			return json(['code' => 400 , 'msg' => '拉取失败']);

	}

	//渲染建立题库模板
	public function item_bank_list(){

		return view();
	}

	//ajax获取题库数据
	public function ajax_item_bank_list(){

		$where = [];

		$type_id = input('post.type_id');

		if (isset($type_id)) {

			$where['type'] = $type_id;
		}

		$where['status'] = 0;

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['id' => 'desc'];

		$data = (new ItemBank)->Common_Select($offset,$limit,$where,$order);

		foreach ($data['data'] as $key => $value) {
			
			if ($value['type']) {
				
				$data['data'][$key]['type_name'] = '多选题';

			}else{

				$data['data'][$key]['type_name'] = '单选题';
			}
		}

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	} 

	//新增题库
	public function item_bank_add(){

		$type = input('get.type/d');

		if ($_POST) {
		
			$data['problem'] = input('post.problem/s');

			$answer = input('post.answer/a');

			$data['answer'] = json_encode($answer);

			$data['type'] = input('post.type/d');

			$content = input('content/a');

			$data['content'] = json_encode($content);

			$data['create_time'] = time();

			$data['update_time'] = time();

			$add = (new ItemBank)->Common_Insert($data);

			if ($add)
				return json(['code' => 200 , 'msg' => '新增成功']);
				return json(['code' => 400 , 'msg' => '新增失败']);

		}else{

			$this->assign('type',$type);

			return view();
		}
	}

	//更新题库
	public function item_bank_edit(){

		$id = input('id/d');

		if ($_POST) {

			$data['problem'] = input('post.problem/s');

			$answer = input('post.answer/a');

			$data['answer'] = json_encode($answer);

			$data['type'] = input('post.type/d');

			$content = input('content/a');

			$data['content'] = json_encode($content);

			$data['update_time'] = time();

			$edit = (new ItemBank)->Common_Update($data,['id' => $id]);

			if ($edit)
				return json(['code' => 200 , 'msg' => '更新成功']);
				return json(['code' => 400 , 'msg' => '更新失败']);

		}else{

			$list = (new ItemBank)->Common_Find(['id' => $id]);

			$list['content'] = json_decode($list['content'],true);

			$list['answer'] = json_decode($list['answer'],true);

			$this->assign('list',$list);

			return view();
		}
	}

	//删除题库
	public function item_bank_del(){

		$id = input('post.id/d');

		$del = (new ItemBank)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);

	}

	//批量删除题库
	public function item_bank_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new ItemBank)->Common_Update(['status' => 1],['id' => ['in', $id]]);

		if($del)
			return json(['code' => 200 , 'msg' => '删除成功']);
			return json(['code' => 400 , 'msg' => '删除失败']);
	}

	//渲染商品收藏排行模板
	public function collection_ranking_list(){

		return view();
	}

	//ajax获取商品收藏数据
	public function ajax_collection_ranking_list(){

		$where = [];

		$goods_name = input('post.goods_name');

		if ($goods_name) {

			$where['goods_name'] = ['like',"%{$goods_name}%"];
		}

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['store_id'] = ['in',$this->is_jurisdiction];
		}

		$where['status'] = 0;

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['collection_number' => 'desc','id' => 'desc'];

		$data = (new Goods)->Common_Select($offset,$limit,$where,$order);

		foreach ($data['data'] as $key => $value) {
			
			$data['data'][$key]['key'] = $key + 1 + $offset; //排序从1开始 +页数
		}

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);
	}

	//编辑
	public function collection_ranking_edit(){

		$id = input('id/d');

		if ($_POST) {

			$data['collection_number'] = input('post.collection_number/d');

			$data['update_time'] = time();

			$edit = (new Goods)->Common_Update($data,['id' => $id]);

			if($edit)
				return json(['code' => 200 , 'msg' => '更新成功']);
				return json(['code' => 400 , 'msg' => '更新失败']);

		}else{

			$order = ['id' => 'desc'];

			$list = (new Goods)->Common_Find(['id' => $id]);

			$this->assign('list',$list);

			return view();
		}
	}
}