<?php
namespace app\admin\model;

class Problem extends Common
{

	private $table = 'problem';

	public function __construct(){

		parent::__construct($this->table);
	}

}