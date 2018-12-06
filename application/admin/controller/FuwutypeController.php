<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
class FuwuTypeController extends AdminController
{
	public $table = 'type';

	public function fuwu_type_list(){

		$pid = input('pid/d');

		$this->assign('pid',$pid);

		return view();
	}

	//ajax获取服务类型
	public function ajax_fuwu_type_list(){

		$offset = input('offset/d');

		$limit = input('limit/d');

		$pid = input('pid/d');

		$list = Db::name($this->table)
			->alias('t')
			->where("t.`status` = 1 and t.`level` = 2 and t.pid = {$pid}")
			->order('t.id desc')
			->limit($offset,$limit)
			->field('t.id,t.type_name,FROM_UNIXTIME(t.add_time)add_time,t.pid')
			->select();

		$count = Db::name($this->table)
				->alias('t')
				->where("t.`status` = 1 and t.`level` = 2 and t.pid = {$pid}")
				->count();

		return json(['rows' => $list , 'total' => $count]);
	}

	//添加
	public function add_fuwu_type(){
		
		$pid = input('pid/d');

		if($_POST){


			$type_name = input('post.type_name/a');

			$type_name = array_filter($type_name);
			
			foreach ($type_name as $key => $value) {
				
				$data[$key]['pid'] = $pid;

				$data[$key]['type_name'] = $value;

				$data[$key]['add_time'] = time();

				$data[$key]['update_time'] = time();

				$data[$key]['level'] = 2;
			}

			$add = Db::name($this->table)->insertAll($data);

			if($add){

				$this->success('添加成功',url('fuwutype/fuwu_type_list',['pid' => $pid]));
			
			}else{

				$this->error('添加失败');
			}

		}else{

			$type = Db::name($this->table)
					->where(['status' => 1 , 'level' => 1 , 'id' => $pid])
					->find();

			$this->assign('type',$type);

			return view();

		}
	}

	//编辑
	public function edit_fuwu_type(){

		$id = input('id/d');

		$pid = input('pid/d');
		
		if($_POST){


			$type_name = input('post.type_name/s');

			$update_time = time();

			$edit = Db::name($this->table)->where(['id' => $id])->update(['pid' => $pid , 'type_name' => $type_name , 'update_time' => $update_time]);

			if($edit){

				$this->success('编辑成功',url('fuwutype/fuwu_type_list',['pid' => $pid]));
			
			}else{

				$this->error('编辑失败');
			}

		}else{

			$list = Db::name($this->table)->where(['id' => $id])->find();

			$type = Db::name($this->table)->where(['status' => 1,'level' => 1 , 'id' => $pid])->find();

			$this->assign('list',$list);

			$this->assign('type',$type);

			return view();
		}
	}

	//删除
	public function del_fuwu_type(){

		$id = input('post.id/d');

		$del = Db::name($this->table)->where(['id' => $id])->update(['status' => 2]);

		if($del)
			return json(['status' => 1]);
			return json(['status' => 2]);
	}

	//属性列表
	public function fuwutype_attribute(){

		$pid = input('pid/d');

		$this->assign('pid',$pid);

		return view();
	}

	//ajax获取属性列表
	public function ajax_fuwutype_attribute(){

		$offset = input('offset/d');

		$limit = input('limit/d');

		$pid = input('pid/d');

		$list = Db::name($this->table)
			->alias('t')
			->where(['status' => 1 , 'pid' => $pid])
			->limit($offset,$limit)
			->field('t.id,t.type_name,FROM_UNIXTIME(t.add_time)add_time,t.pid')
			->select();
		
		$count = Db::name($this->table)
				->where(['status' => 1 , 'pid' => $pid])
				->count();

		return json(['rows' => $list , 'total' => $count]);
	}

	//添加属性
	public function add_fuwutype_attribute(){

		if($_POST){

			$pid = input('pid/d');

			$type_name = input('post.type_name/a');

			$type_name = array_filter($type_name); //去除空值

			foreach ($type_name as $key => $value) {
				
				$data[$key]['type_name'] = $value;

				$data[$key]['pid'] = $pid;

				$data[$key]['add_time'] = time();

				$data[$key]['update_time'] = time();

				$data[$key]['level'] = 3;
			}

			$add = Db::name($this->table)->insertAll($data);

			if($add){

				$this->success('添加成功' , url('fuwutype/fuwutype_attribute',['pid' => $pid]));
			
			}else{

				$this->error('添加失败');
			}

		}else{

			return view();
		}
	}

	//编辑属性
	public function edit_fuwutype_attribute(){

		$id = input('id/d');

		$pid = input('pid/d');

		if($_POST){

			$type_name = input('post.type_name');

			$update_time = time();

			$edit = Db::name($this->table)->where(['id' => $id])->update(['type_name' => $type_name , 'update_time' => $update_time]);

			if($edit){

				$this->success('编辑成功',url('fuwutype/fuwutype_attribute',['pid' => $pid]));

			}else{

				$this->error('编辑失败');
			}

		}else{

			$list = Db::name($this->table)
					->where(['id' => $id])
					->find();

			$this->assign('list',$list);

			return view();
		}
	}

	//删除属性
	public function del_fuwutype_attribute(){

		$id = input('id/d');

		$del = Db::name($this->table)->where(['id' => $id])->update(['status' => 2 , 'update_time' => time()]);

		if($del)
			return json(['status' => 1]);
			return json(['status' => 0]);
	}
	
}