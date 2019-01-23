<?php
namespace app\admin\model;
use think\Db;
class Advertisement extends Common
{

	private $table = 'advertisement';

	public function __construct(){

		parent::__construct($this->table);
	}

	//列表
	public function Advertisement_Select($offset=0,$limit=10,$where=[],$order=[]){

		$data = Db::name($this->table)
			->alias('a')
			->join('advertisement_type a_t','a_t.id = a.type_id and a_t.status = 0','left')
			->join('store s','s.store_id = a.store_id','left')
			->where($where)
			->limit($offset,$limit)
			->order($order)
			->field('a.id,a.name,a.image,FROM_UNIXTIME(a.create_time)create_time,a_t.type_name,COALESCE(s.store_name,"平台")store_name')
			->select();

		$count = Db::name($this->table)
			->alias('a')
			->where($where)
			->count();

		return ['data' => $data , 'count' => $count];
	}
}