<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/13
 * Time: 1:44
 */
namespace app\home\model;

use think\Db;

class CouponType extends Common
{

    private $table = 'card_ticket_type';

    public function __construct(){

        parent::__construct($this->table);
    }

    public function getInfo($w,$field){
        return $data = Db::name($this->table)
            ->alias('a_l')
            ->join('goods a','a.id = a_l.goods_id','LEFT')
            ->where($w)
            ->field($field)
            ->select();
    }
}