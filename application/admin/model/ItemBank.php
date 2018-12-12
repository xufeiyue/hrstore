<?php
namespace app\admin\model;
use think\Db;
class ItemBank extends Common
{

	private $table = 'item_bank';

	public function __construct(){

		parent::__construct($this->table);
	}

	//所有问题信息
	public function ItemBank_Select($where=[],$order=[]){

		$data = Db::name($this->table)
			->where($where)
			->order($order)
			->select();

		return $data;
	}

	
}