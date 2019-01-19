<?php
namespace app\admin\model;
use think\Db;
class GoodsBrand extends Common
{

	private $table = 'goods_brand';

	public function __construct(){

		parent::__construct($this->table);
	}

	public function type($where=[],$order=[]){

		$data = Db::name($this->table)
			->where($where)
			->order($order)
			->select();

		return $data;
	}
}