<?php
namespace app\admin\model;

class Questionnaire extends Common
{

	private $table = 'questionnaire';

	public function __construct(){

		parent::__construct($this->table);
	}

}