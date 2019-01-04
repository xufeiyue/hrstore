<?php
namespace app\home\model;

class Goods extends Common
{

    private $table = 'goods';

    public function __construct(){

        parent::__construct($this->table);
    }
}