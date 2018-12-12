<?php
namespace app\admin\model;

class AdminLog extends Common
{

	private $table = 'admin_log';

	public function __construct(){

		parent::__construct($this->table);
	}

}