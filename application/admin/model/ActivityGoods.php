<?php
namespace app\admin\model;
class ActivityGoods extends Common
{

	private $table = 'activity_goods';

	public function __construct(){

		parent::__construct($this->table);
	}

}