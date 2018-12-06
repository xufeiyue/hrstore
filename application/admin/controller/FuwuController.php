<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
class FuwuController extends AdminController
{
	public $table = 'product';

	//服务列表
	public function fuwu_list(){

		return view();
	
	}

	//AJAX获取服务列表
	public function ajax_fuwu_list($search=''){

		$where = [];

		if($search){

			$where = "product_name like '%{$search}%'";
		}

		$offset = input('get.offset/d');

		$limit = input('get.limit/d');

		$list = Db::name($this->table)
				->alias('p')
				->join('th_type t','t.id = p.type_id','left')
				->where(['p.status' => 1])
				->where($where)
				->limit($offset,$limit)
				->field("p.*,t.type_name,FROM_UNIXTIME(p.add_time)add_time,case when p.popular_recommendation = 1 then '热门推荐' else '' end popular_recommendation_name")
				->select();

		$count = Db::name($this->table)->where(['status' => 1])->where($where)->count();

		return json(['rows' => $list , 'total' => $count]);

	}

	//添加
	public function add_fuwu(Request $request){

		if($_POST){

			$data['product_name'] = input('post.product_name/s');

			$data['product_money'] = input('post.product_money/f');

			$data['service_type'] = input('post.service_type/s');

			$data['server_explain'] = input('post.server_explain/s');

			$data['service_scope'] = input('post.service_scope/s');

			$data['add_time'] = time();

			$data['update_time'] = time();

			$data['type_id'] = input('post.type_id/d');

			$data['sort'] = input('post.sort/d') ? input('post.sort/d') : 0;

			$file = $request->file('product_image');

			if($file){

				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

				if($info){
		            // 成功上传后 获取上传信息
		            $data['product_image'] = '/uploads/'.$info->getSaveName();
		        }
			}

			$add = Db::name($this->table)->insert($data);

			if($add){

				$this->success('添加成功','/admin/fuwu/fuwu_list');
			
			}else{

				$this->error('添加失败');
			}

		}else{

			$type = Db::name('type')->where(['status' => 1 , 'level' => 1])->select();

			$this->assign('type',$type);

			return view();
		}
	}

	//编辑
	public function edit_fuwu(Request $request){

		$id = input('id/d');

		if($_POST){

			$data['product_name'] = input('post.product_name/s');

			$data['product_money'] = input('post.product_money/f');

			$data['service_type'] = input('post.service_type/s');

			$data['server_explain'] = input('post.server_explain/s');

			$data['service_scope'] = input('post.service_scope/s');

			$data['update_time'] = time();

			$data['type_id'] = input('post.type_id/d');

			$data['sort'] = input('post.sort/d') ? input('post.sort/d') : 0;

			$file = $request->file('product_image');

			if($file){

				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

				if($info){
		            // 成功上传后 获取上传信息
		            $data['product_image'] = '/uploads/'.$info->getSaveName();
		        }
			}

			$edit = Db::name($this->table)->where(['id' => $id])->update($data);

			if($edit){

				$this->success('编辑成功','/admin/fuwu/fuwu_list');

			}else{

				$this->error('编辑失败');
			}

		}else{	

			$list = Db::name($this->table)->where(['id' => $id])->find();

			$type = Db::name('type')->where(['status' => 1,'level' => 1])->select();

			$this->assign('list',$list);

			$this->assign('type',$type);

			return view();
		}

	}

	//删除
	public function del_fuwu(){

		$id = input('id/d');

		$del = Db::name($this->table)->where(['id' => $id])->update(['status' => 2 , 'update_time' => time()]);

		if($del)
			return json(['status' => 1]);
			return json(['status' => 2]);
	}

	//普通->热门推荐
	public function recommendation(){

		$id = input('post.id/d');

		$type_id = input('post.type/d');

		if($type_id == 1){

			$data['popular_recommendation'] = 1;
		
		}else{

			$data['popular_recommendation'] = 0;
		}

		$data['update_time'] = time();

		$list = Db::name($this->table)->where(['id' => $id])->update($data);

		if($list)
			return json(['status' => 1]);
			return json(['status' => 2]);
	}

}