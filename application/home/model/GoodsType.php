<?php
namespace app\home\model;

class GoodsType extends Common
{

    private $table = 'goods_type';

    public function __construct(){

        parent::__construct($this->table);
    }
}