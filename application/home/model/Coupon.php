<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/13
 * Time: 1:40
 */
namespace app\home\model;

use think\Db;

class Coupon extends Common
{

    private $table = 'card_ticket';

    public function __construct(){

        parent::__construct($this->table);
    }

    public function get_coupon($insert_data,$w_update,$update_data){
        Db::startTrans();
        try{
            Db::name('member_card_ticket_relation')->insert($insert_data);
            Db::name('card_ticket')->where($w_update)->update($update_data);
            //  提交事务
            Db::commit();
            return 1;
        }
        catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return 0;
        }
    }

    public function getNum($where){
        return Db::name($this->table)->where($where)->count();
    }

    public function get_my_coupon_pt($member_id){
        $time = time();
        $sql = "SELECT * from th_member_card_ticket_relation as r 
left join th_card_ticket as ct on ct.card_ticket_id = r.card_ticket_id 
left join th_card_ticket_type as ctt on ctt.card_type_id = ct.card_type_id 
where r.member_id = $member_id and r.`status` = '1' and ctt.end_time > $time and ctt.ticket_type='1'";
        return Db::query($sql);
    }

    public function get_my_coupon_dp($member_id){
        $time = time();
        $sql = "SELECT * from th_member_card_ticket_relation as r 
left join th_card_ticket as ct on ct.card_ticket_id = r.card_ticket_id 
left join th_card_ticket_type as ctt on ctt.card_type_id = ct.card_type_id
left join th_goods as g on g.id = ctt.goods_id
where r.member_id = $member_id and r.`status` = '1' and ctt.end_time > $time and ctt.ticket_type='2'";
        return Db::query($sql);
    }

    public function get_my_coupon_is_use_pt($member_id){
        $time = time();
        $sql = "SELECT * from th_member_card_ticket_relation as r 
left join th_card_ticket as ct on ct.card_ticket_id = r.card_ticket_id 
left join th_card_ticket_type as ctt on ctt.card_type_id = ct.card_type_id 
where r.member_id = $member_id and r.`status` = '2' and ctt.end_time > $time and ctt.ticket_type='1'";
        return Db::query($sql);
    }

    public function get_my_coupon_is_use_dp($member_id){
        $time = time();
        $sql = "SELECT * from th_member_card_ticket_relation as r 
left join th_card_ticket as ct on ct.card_ticket_id = r.card_ticket_id 
left join th_card_ticket_type as ctt on ctt.card_type_id = ct.card_type_id 
left join th_goods as g on g.id = ctt.goods_id
where r.member_id = $member_id and r.`status` = '2' and ctt.end_time > $time and ctt.ticket_type='2'";
        return Db::query($sql);
    }



    public function get_my_coupon_guoqi_pt($member_id){
        $time = time();
        $sql = "SELECT * from th_member_card_ticket_relation as r 
left join th_card_ticket as ct on ct.card_ticket_id = r.card_ticket_id 
left join th_card_ticket_type as ctt on ctt.card_type_id = ct.card_type_id 
where r.member_id = $member_id and ctt.end_time < $time and ctt.ticket_type='1'";
        return Db::query($sql);
    }

    public function get_my_coupon_guoqi_dp($member_id){
        $time = time();
        $sql = "SELECT * from th_member_card_ticket_relation as r 
left join th_card_ticket as ct on ct.card_ticket_id = r.card_ticket_id 
left join th_card_ticket_type as ctt on ctt.card_type_id = ct.card_type_id
left join th_goods as g on g.id = ctt.goods_id 
where r.member_id = $member_id and ctt.end_time < $time and ctt.ticket_type='2'";
        return Db::query($sql);
    }

    public function use_coupon($w,$data){
        return Db::name('member_card_ticket_relation')->where($w)->update($data);
    }

    public function get_member_card_ticket_relation_info($w){
        return Db::name('member_card_ticket_relation')->where($w)->find();
    }
}