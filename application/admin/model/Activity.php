<?php
namespace app\admin\model;
use think\Db;
class Activity extends Common
{

	private $table = 'activity';

	public function __construct(){

		parent::__construct($this->table);
	}

	//查看拉取过的数据
	public function Activity_Pid($where=[],$order=[]){

		$data = Db::name($this->table)
			->where($where)
			->order($order)
			->column('pid');

		return $data;
	}
}