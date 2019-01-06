<?php
namespace app\home\model;
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
	public function Common_Select($offset=0,$limit=10,$where=[],$order=[],$field=[]){

		$data = Db::name($this->table)
			->where($where)
			->limit($offset,$limit)
			->order($order)
			->field($field)
			->select();

		return $data;
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
	public function Common_Find($where=[],$order=[],$field=[]){

		return Db::name($this->table)
				->where($where)
				->order($order)
				->field($field)
				->find();
	}

	//公共新增
	public function Common_Insert($data=[]){

		return Db::name($this->table)->insert($data);

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