<?php
namespace app\admin\model;
use think\Db;
class AdvertisementType extends Common
{

	private $table = 'advertisement_type';

	public function __construct(){

		parent::__construct($this->table);
	}
	//查询店铺下类型
	public function type($where=[],$order=[]){

		$data = Db::name($this->table)
			->where($where)
			->field('id,type_name')
			->order($order)
			->select();

		return $data;
	}
}