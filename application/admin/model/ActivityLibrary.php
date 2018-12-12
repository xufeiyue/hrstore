<?php
namespace app\admin\model;
use think\Db;
class ActivityLibrary extends Common
{

	private $table = 'activity_library';

	public function __construct(){

		parent::__construct($this->table);
	}

	//查询所有活动库数据
	public function ActivityLibrary_Select($where=[],$order=[]){

		$data = Db::name($this->table)
			->where($where)
			->order($order)
			->field('*,FROM_UNIXTIME(create_time)create_time')
			->select();

		return $data;
	}
}