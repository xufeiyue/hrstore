<?php
namespace app\admin\model;
use think\Db;
class Problem extends Common
{

	private $table = 'problem';

	public function __construct(){

		parent::__construct($this->table);
	}

	//查询店铺下所有题目
	public function Problem_Select($where=[],$order=[]){

		$data = Db::name($this->table)
			->where($where)
			->order($order)
			->field('*,FROM_UNIXTIME(create_time)create_time')
			->select();

		return $data;
	}

	//查看店铺下拉取的题库id
	public function Problem_Pid($where=[],$order=[]){

		$data = Db::name($this->table)
			->where($where)
			->order($order)
			->column('pid');

		return $data;
	}
}