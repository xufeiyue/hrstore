<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
class TypeController extends AdminController
{
	public $table = 'type';
	//类型列表
	public function type_list(){

		return view();
	}

	//ajax获取类型列表
	public function ajax_type_list($search=''){

		$offset = input('offset');

		$limit = input('limit');

		$where = '';

		if($search){

			$where = "type_name like '%{$search}%'";
		}

		$list = Db::name($this->table)
				->where(['status' => 1 , 'level' => 1])
				->where($where)
				->limit($offset,$limit)
				->order('id desc')
				->field('id,type_name,FROM_UNIXTIME(add_time)add_time,pic')
				->select();

		$count = Db::name($this->table)->where($where)->where(['status' => 1])->count();

		return json(['rows' => $list , 'total' => $count]);
	}

	//添加类型
	public function add_type(Request $request){

		if ($_POST) {
			
			$type_name = input('post.type_name/s');

			$pic = '';

			$file = $request->file('pic');

			if($file){

				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

		        if($info){

		            // 成功上传后 获取上传信息
		            $pic = '/uploads/'.$info->getSaveName();

		        }
			}

			$add_time = time();

			$update_time = $add_time;

			$add = Db::name($this->table)->insert(['type_name' => $type_name , 'add_time' => $add_time , 'update_time' => $update_time , 'pic' => $pic , 'level' => 1]);

			if($add){

				$this->success('添加成功','/admin/type/type_list');
			
			}else{

				$this->error('添加失败');
			}

		}else{

			return view();
		}
	}

	//编辑类型
	public function edit_type(Request $request){

		$id = input('id/d');

		if($_POST){

			$data['type_name'] = input('post.type_name/s');

			$file = $request->file('pic');

			if($file){

				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

		        if($info){

		            // 成功上传后 获取上传信息
		            $data['pic'] = '/uploads/'.$info->getSaveName();

		        }
			}

			$data['update_time'] = time();

			$edit = Db::name($this->table)->where(['id' => $id])->update($data);

			if($edit){

				$this->success('编辑成功' , '/admin/type/type_list');

			}else{

				$this->error('编辑失败');

			}

		}else{

			$list = Db::name($this->table)->where(['id' => $id])->find();

			$this->assign('list',$list);

			return view();
		}
	}

	//删除
	public function del_type(){

		$id = input('post.id/d');

		$del = Db::name($this->table)->where(['id' => $id])->update(['status' => 2]);
		
		if($del)
			return json(['status' => 1]);
			return json(['status' => 2]);
	}
}