<?php
namespace app\admin\model;

class NewDiscovery extends Common
{

	private $table = 'new_discovery';

	public function __construct(){

		parent::__construct($this->table);
	}

}