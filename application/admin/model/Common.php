<?php
namespace app\admin\model;
use think\Db;
class Common
{
	/*
		公共模型
	*/
	private $table;

	public function __construct($table=''){ //传入表名

		$this->table = $table;
	}

	//公共列表+分页+搜索条件+排序
	public function Common_Select($offset=0,$limit=10,$where=[],$order=[]){

		$data = Db::name($this->table)
			->alias('a')
			->join('store s','s.store_id = a.store_id','left')
			->where($where)
			->limit($offset,$limit)
			->order($order)
			->field('a.*,FROM_UNIXTIME(a.create_time)create_time,COALESCE(s.store_name,"平台")store_name')
			->select();

		$count = Db::name($this->table)
			->alias('a')
			->join('store s','s.store_id = a.store_id','left')
			->where($where)
			->count();

		return ['data' => $data , 'count' => $count];
	}

	//全部数据
	public function Common_All_Select($where=[],$order=[],$field=[]){

		$data = Db::name($this->table)
			->where($where)
			->order($order)
			->field($field)
			->select();

		return $data;
	}

	//公共详情
	public function Common_Find($where=[]){

		return Db::name($this->table)
				->where($where)
				->find();
	}

	//公共新增
	public function Common_Insert($data=[]){

		return Db::name($this->table)->insertGetId($data);

	}

	//公共多条新增
	public function Common_InsertAll($data){

		return Db::name($this->table)->insertAll($data);
	}

	//公共更新 + 逻辑删除
	public function Common_Update($data=[],$where=[]){

		return Db::name($this->table)->where($where)->update($data);
	}

	//公共删除
	public function Common_Delete($where=[]){

		return Db::name($this->table)->where($where)->delete();
	}

}