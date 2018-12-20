<?php
namespace app\admin\model;
use think\Db;
class Goods extends Common
{

	private $table = 'goods';

	public function __construct(){

		parent::__construct($this->table);
	}

	//获取店铺已拉取的商品库id
	public function Goods_Pid($where=[],$order=[]){

		$data = Db::name($this->table)
			->where($where)
			->order($order)
			->column('pid');

		return $data;
	}
}