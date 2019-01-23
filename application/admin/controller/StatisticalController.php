<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Problem;
use app\admin\model\Questionnaire;
use app\admin\model\ItemBank;
use app\admin\model\Goods;
use app\admin\model\CouponType;
use app\admin\model\MemberAndQuestionnaire;
use think\Db;
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
			
			$where['a.store_id'] = $this->is_jurisdiction;
		}

		$type_id = input('post.type_id');

		if (isset($type_id)) {

			$where['a.type'] = $type_id;
		}

		$where['a.status'] = 0;

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['a.id' => 'desc'];

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
		
			$store_id = input('post.store_id/a') ? : $this->is_jurisdiction;

			if (!$store_id)
				return json(['code' => 400 , 'msg' => '请选择店铺']);
			Db::startTrans();

			try{
				if (is_array($store_id)) {

					$arr = [];
					
					foreach ($store_id as $key => $value) {
						
						$data['store_id'] = $value;

						$data['problem'] = input('post.problem/s');

						$answer = input('post.answer/a');

						$data['answer'] = json_encode($answer);

						$data['type'] = input('post.type/d');

						$content = input('content/a');

						$data['content'] = json_encode($content);

						$data['create_time'] = time();

						$data['update_time'] = time();

						$arr[] = $data;
					}

					$add = (new Problem)->Common_InsertAll($arr);

				}else{

					$data['store_id'] = $store_id;

					$data['problem'] = input('post.problem/s');

					$answer = input('post.answer/a');

					$data['answer'] = json_encode($answer);

					$data['type'] = input('post.type/d');

					$content = input('content/a');

					$data['content'] = json_encode($content);

					$data['create_time'] = time();

					$data['update_time'] = time();

					$add = (new Problem)->Common_Insert($data);
				}
				
				//  提交事务
            	Db::commit();
				return json(['code' => 200 , 'msg' => '新增成功']);
			}catch (\Exception $e) {
	            // 回滚事务
	            Db::rollback();
				return json(['code' => 400 , 'msg' => '新增失败']);
	        }


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
			
			$where['a.store_id'] = $this->is_jurisdiction;
		}

		$where['a.status'] = 0;

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['a.id' => 'desc'];

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

			$whether = ['on','off'];

			$store_id = input('post.store_id/a') ? : $this->is_jurisdiction;

			if (!$store_id)
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$coupon_state = input('post.coupon_state/s');

			if($coupon_state == $whether[0]){

				$data['coupon_state'] = 0;

			}else{
				
				$data['coupon_state'] = 1;
			}

			$data['coupon_id'] = input('post.card_type_id/d');

			$data['title'] = input('post.title/s');

			$data['start_time'] = strtotime(input('post.start_time'));

			$data['end_time'] = strtotime(input('post.end_time'));

			$data['questionnaire_text'] = input('post.questionnaire_text/s');

			$data['opinion_completion'] = input('post.opinion_completion/s');

			$data['create_time'] = time();

			$data['update_time'] = time();

			$problem = input('post.problem/a'); //问题

			$type = input('post.type/a'); //问题类型

			$content = input('post.content/a');

			$answer = input('post.answer/a');

			Db::startTrans();

			try{

				if (is_array($store_id)) {

					$arr = [];

					foreach ($store_id as $k => $v) {

						$data['store_id'] = $v;

						$problem_id = []; //定义数组
						
						foreach ($problem as $key => $value) {

							$info['store_id'] = $v; //店铺id

							if ($type[$key] <= 1) { //1多选 0单选  2为文本只要问题

								$info['content'] = json_encode($content[$key]);

								$info['answer'] = json_encode($answer[$key]);
								
							}else{

								$info['content'] = '';

								$info['answer'] = '';
							}

							$info['type'] = $type[$key];
							
							$info['problem'] = $value;

							$info['create_time'] = time();
							
							$info['update_time'] = time();

							$problem_id[] = (new Problem)->Common_Insert($info);

						}

						if (empty($problem_id)) {
							return json(['code' => 400 , 'msg' => '请选择问题']);
						}

						$data['problem_id'] = implode(',', $problem_id);

						$arr[] = $data;
					}

					(new Questionnaire)->Common_InsertAll($arr);

				}else{

					$problem_id = [];

					foreach ($problem as $key => $value) {

						$info['store_id'] = $store_id; //店铺id

						if ($type[$key] <= 1) { //1多选 0单选  2为文本只要问题

							$info['content'] = json_encode($content[$key]);

							$info['answer'] = json_encode($answer[$key]);
							
						}else{
								
							$info['content'] = '';

							$info['answer'] = '';
						}

						$info['type'] = $type[$key];
						
						$info['problem'] = $value;

						$info['create_time'] = time();
						
						$info['update_time'] = time();

						$problem_id[] = (new Problem)->Common_Insert($info);

					}

					$data['store_id'] = $store_id;

					if (empty($problem_id)) {
						return json(['code' => 400 , 'msg' => '请选择问题']);
					}

					$data['problem_id'] = implode(',', $problem_id);

					(new Questionnaire)->Common_Insert($data);

				}

				//提交事务
				Db::commit();
				return json(['code' => 200 , 'msg' => '新增成功']);
			}catch (\Exception $e) {
	            // 回滚事务
	            Db::rollback();
				return json(['code' => 400 , 'msg' => '新增失败']);
	        }
			

		}else{

			$CouponType = (new CouponType)->Common_All_Select(['status' => 1],['card_type_id' => 'desc'],['card_type_id','ticket_name']);

			$this->assign('CouponType',$CouponType);
			
			return view();
		}
	}

	//编辑调查问卷
	public function questionnaire_edit(){

		$id = input('id/d');

		if ($_POST) {

			$whether = ['on','off'];

			$coupon_state = input('post.coupon_state/s');

			if($coupon_state == $whether[0]){

				$data['coupon_state'] = 0;

			}else{
				
				$data['coupon_state'] = 1;
			}

			$data['coupon_id'] = input('post.card_type_id/d');

			$data['store_id'] = input('post.store_id/d') ? : $this->is_jurisdiction;

			if (!$data['store_id'])
				return json(['code' => 400 , 'msg' => '请选择店铺']);

			$status_type = input('post.status_type/a'); //问题

			$problem = input('post.problem/a'); //问题

			$problem_id = input('post.problem_id/a'); //问题id

			$type = input('post.type/a'); //问题类型

			$content = input('post.content/a'); //问题内容

			$answer = input('post.answer/a'); //问题答案

			$arr = [];

			Db::startTrans();

			try{

				foreach ($problem as $key => $value) {

					$info = [];

					$info['store_id'] = $data['store_id'];

					$info['problem'] = $problem[$key];

					if (isset($problem_id[$key])) { //存在时 更新

						$info['id'] = $problem_id[$key];

						if (isset($content[$key])) {
							
							$info['content'] = json_encode($content[$key]);

							$info['answer'] = json_encode($answer[$key]);
						}

						if ($status_type[$key] == 'del') {
							
							(new Problem)->Common_Update(['status' => 1],['id' => $info['id']]);

							unset($problem_id[$key]);

						}else{

							(new Problem)->Common_Update($info,['id' => $info['id']]);

						}

					}else{ //新增

						$info['type'] = $type[$key];

						if ($type[$key] <= 1) {

							$info['content'] = json_encode($content[$key]);

							$info['answer'] = json_encode($answer[$key]);

						}else{

							$info['content'] = '';

							$info['answer'] = '';
						}

						$info['create_time'] = time();

						$info['update_time'] = time();

						$arr[] = (new Problem)->Common_Insert($info);

					}

				}

				if ($arr)
					$problem_id = array_merge($problem_id,$arr);


				$data['title'] = input('post.title/s');

				$data['start_time'] = strtotime(input('post.start_time'));

				$data['end_time'] = strtotime(input('post.end_time'));

				$data['questionnaire_text'] = input('post.questionnaire_text/s');

				$data['opinion_completion'] = input('post.opinion_completion/s');

				if (empty($problem_id)) {
					return json(['code' => 400 , 'msg' => '请选择问题']);
				}

				$data['problem_id'] = implode(',', $problem_id);

				$data['update_time'] = time();

				$edit = (new Questionnaire)->Common_Update($data,['id' => $id]);
				//提交事务
				Db::commit();
				return json(['code' => 200 , 'msg' => '更新成功']);
			}catch (\Exception $e) {
	            // 回滚事务
	            Db::rollback();
				return json(['code' => 400 , 'msg' => '更新失败']);
	        }


		}else{

			$list = (new Questionnaire)->Common_Find(['id' => $id]);

			$CouponType = (new CouponType)->Common_All_Select(['status' => 1],['card_type_id' => 'desc'],['card_type_id','ticket_name']);

			$list['start_time'] = date('Y-m-d H:i:s',$list['start_time']);

			$list['end_time'] = date('Y-m-d H:i:s',$list['end_time']);

			$list['problem_id'] = explode(',', $list['problem_id']);

			$problem = (new Problem)->Common_All_Select(['id' => ['in',$list['problem_id']], 'store_id' => $list['store_id'], 'status' => 0],['id' => 'asc'],['id','type','problem','answer','content']);

			foreach ($problem as $key => $value) {
				
				$problem[$key]['answer'] = json_decode($value['answer'],true);

				$problem[$key]['content'] = json_decode($value['content'],true);

			}

			$this->assign('list',$list);

			$this->assign('problem',$problem);
			
			$this->assign('CouponType',$CouponType);

			$this->assign('problem_num',count($problem));

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

		$problem = (new Problem)->Problem_Pid(['status' => 0 , 'pid' => ['>', 0], 'store_id' => $this->is_jurisdiction],$order);

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

			$where['a.type'] = $type_id;
		}

		$where['a.status'] = 0;

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['a.id' => 'desc'];

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
			
			$where['store_id'] = $this->is_jurisdiction;
		}

		$where['a.status'] = 0;

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['a.collection_number' => 'desc','a.id' => 'desc'];

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

	//调研问卷答题列表
	public function answer_list(){

		return view();
	}

	//ajax获取调研问卷答题数据
	public function ajax_answer_list(){

		$where['m_a_q.status'] = 0;

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['m_a_q.store_id'] = $this->is_jurisdiction;
		}

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['m_a_q.id' => 'desc'];

		$field = ['m_a_q.id','q.title','m_a_q.questionnaire_id','m.nickname','FROM_UNIXTIME(m_a_q.create_time)create_time'];

		$data = (new MemberAndQuestionnaire)->lists($offset,$limit,$where,$order,$field);

		return json(["code" =>  0, "msg" => "请求成功", 'data' => $data['data'] , 'count' => $data['count']]);

	}

	//查看答题情况
	public function answer_edit(){

		$id = input('id/d');//自增id

		$questionnaire_id = input('questionnaire_id/d'); //问卷id

		$MemberAndQuestionnaire = (new MemberAndQuestionnaire)->Common_Find(['id' => $id]); //用户答题试卷

		$data = json_decode($MemberAndQuestionnaire['content'],true); //试卷问题答案

		$list = (new Questionnaire)->Common_Find(['id' => $questionnaire_id]);

		$problem = (new Problem)->Common_All_Select(['id' => ['in',$list['problem_id']]],['id' => 'asc'],['id','type','problem','answer','content']);

		foreach ($problem as $key => $value) {

			foreach ($data as $k => $v) {
				
				if ($value['id'] == $v['problem_id']) {

					$problem[$key]['answer'] = $v['answer']; //用户所选答案
				}
			}
			
			$problem[$key]['content'] = json_decode($value['content'],true);
		}

		$this->assign('list',$list);

		$this->assign('problem',$problem);

		return view();
	}

	//删除
	public function answer_del(){

		$id = input('post.id/d');

		$del = (new MemberAndQuestionnaire)->Common_Update(['status' => 1],['id' => $id]);

		if($del)
			return json(['code' => 200, 'msg' => '删除成功']);
			return json(['code' => 400, 'msg' => '删除失败']);
	}

	//批量删除
	public function answer_delAll(){

		$id = array_unique(input('post.id/a'));

		$del = (new MemberAndQuestionnaire)->Common_Update(['status' => 1],['id' => ['in',$id]]);

		if($del)
			return json(['code' => 200, 'msg' => '删除成功']);
			return json(['code' => 400, 'msg' => '删除失败']);
	}

	//问题统计
	public function problem_statistical_list(){

		return view();
	}

	//ajax获取问题数据
	public function ajax_problem_statistical_list(){

		$where = [];

		if ($this->is_jurisdiction) { //判断是管理员还是商家
			
			$where['a.store_id'] = $this->is_jurisdiction;
		}

		$type_id = input('post.type_id');

		if (isset($type_id)) {

			$where['a.type'] = $type_id;
		}

		$where['a.status'] = 0;

		$offset = (input('post.page/d') - 1) * input('post.limit/d') ? : 0;

		$limit = input('post.limit/d') ? : 10;

		$order = ['a.id' => 'desc'];

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

}