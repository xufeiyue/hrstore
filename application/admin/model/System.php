<?php
namespace app\admin\model;

class System extends Common
{

	private $table = 'information';

	public function __construct(){

		parent::__construct($this->table);
	}

}