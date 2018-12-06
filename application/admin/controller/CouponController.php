<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
class CouponController extends AdminController
{
	public $table = 'coupon';
	//优惠券列表
	public function coupon_list(){

		return view();
	
	}

	//ajax获取优惠券列表
	public function ajax_coupon_list($search=''){

		$offset = input('offset/d');

		$limit = input('limit/d');

		$where = '';

		if($search){

			$where = "coupon_name like '%{$search}%'";
		}

		$list = Db::name($this->table)->where(['status' => 1])->where($where)->limit($offset,$limit)->field('*,FROM_UNIXTIME(start_time)start_time,FROM_UNIXTIME(end_time)end_time,FROM_UNIXTIME(add_time)add_time')->select();

		$count = Db::name($this->table)->where(['status' => 1])->where($where)->count();

		return json(['rows' => $list , 'total' => $count]);
	}

	//添加
	public function add_coupon(Request $request){

		if($_POST){

			$file = $request->file('coupon_url');

			if($file){

				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

				if($info){
		            // 成功上传后 获取上传信息
		            $data['coupon_url'] = '/uploads/'.$info->getSaveName();
		        }
			}

			$data['type'] = input('post.type/d');

			$data['coupon_name'] = input('post.coupon_name/s');

			$data['amount_available'] = input('post.amount_available/f');

			$data['money'] = input('post.money/f');

			$data['money'] = input('post.money/f');

			$data['start_time'] = strtotime(input('post.start_time/s'));

			$data['end_time'] = strtotime(input('post.end_time/s'));

			$data['add_time'] = time();

			$data['update_time'] = time();

			$add = Db::name($this->table)->insert($data);

			if($add){

				$this->success('添加成功',url('coupon/coupon_list'));
			
			}else{

				$this->error('添加失败');
			}

		}else{

			return view();
		}
	}

	//编辑
	public function edit_coupon(Request $request){

		$id = input('id/d');

		if($_POST){

			$file = $request->file('coupon_url');

			if($file){

				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

				if($info){
		            // 成功上传后 获取上传信息
		            $data['coupon_url'] = '/uploads/'.$info->getSaveName();
		        }
			}

			$data['type'] = input('post.type/d');

			$data['coupon_name'] = input('post.coupon_name/s');

			$data['amount_available'] = input('post.amount_available/f');

			$data['money'] = input('post.money/f');

			$data['money'] = input('post.money/f');

			$data['start_time'] = strtotime(input('post.start_time/s'));

			$data['end_time'] = strtotime(input('post.end_time/s'));

			$data['add_time'] = time();

			$data['update_time'] = time();

			$edit = Db::name($this->table)->where(['id' => $id])->update($data);

			if($edit){

				$this->success('编辑成功',url('coupon/coupon_list'));
			
			}else{

				$this->error('编辑失败');
			}

		}else{

			$list = Db::name($this->table)->where(['id' => $id])->field('*,FROM_UNIXTIME(start_time)start_time,FROM_UNIXTIME(end_time)end_time')->find();

			$this->assign('list',$list);

			return view();
		}
	}

	//删除
	public function del_coupon(){

		$id = input('id/d');

		$del = Db::name($this->table)->where(['id' => $id])->update(['status' => 2]);

		if($del)
			return json(['status' => 1]);
			return json(['status' => 0]);
	}
}