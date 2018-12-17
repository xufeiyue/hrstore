<?php
namespace app\admin\model;
use think\Db;
class GoodsType extends Common
{

	private $table = 'goods_type';

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