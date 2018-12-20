<?php
namespace app\admin\model;
use think\Db;
class CommodityBank extends Common
{

	private $table = 'commodity_bank';

	public function __construct(){

		parent::__construct($this->table);
	}

	//商品库信息
	public function CommodityBank_Select($where=[],$order=[]){

		$data = Db::name($this->table)
			->where($where)
			->order($order)
			->field('*,FROM_UNIXTIME(create_time)create_time')
			->select();

		return $data;
	}
}