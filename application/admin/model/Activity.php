<?php
namespace app\admin\model;

class Activity extends Common
{

	private $table = 'activity';

	public function __construct(){

		parent::__construct($this->table);
	}

}