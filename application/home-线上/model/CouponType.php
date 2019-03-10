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

    // 获取单品卡券列表
    public function get_coupon_type_dp_list($w,$field){
        return $data = Db::name($this->table)
            ->alias('t1')
            ->join('goods g','g.id = t1.goods_id','LEFT')
            ->where($w)
            ->field($field)
            ->select();
    }

    public function getRegCoupon($w,$field=[]){
        return $data = Db::name('member_card_ticket_relation')
            ->alias('mr')
            ->join('card_ticket ct','ct.card_ticket_id = mr.card_ticket_id','LEFT')
            ->join('card_ticket_type ctt','ctt.card_type_id = ct.card_type_id','LEFT')
            ->where($w)
            ->field($field)
            ->select();
    }

    public function selAll($w){
        return Db::name($this->table)->where($w)->select();
    }

    public function getTypeInfo($w,$field){
        return Db::name($this->table)->where($w)->field($field)->find();
    }
}