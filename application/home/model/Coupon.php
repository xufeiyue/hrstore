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

    public function get_coupon($insert_data,$w_update,$update_data,$status){
        Db::startTrans();
        try{
            if($status == 1){
                Db::name('member_card_ticket_relation')->insert($insert_data);
                Db::name('card_ticket')->where($w_update)->update($update_data);
            }else{
                Db::name('member_card_ticket_relation')->insert($insert_data);
            }

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

    // 根据优惠券类型id查询用户是否有此商品的优惠券
    public function is_coupon_type_card($w){
        return Db::name('member_card_ticket_relation')
            ->alias('mr')
            ->join('card_ticket ct','ct.card_ticket_id = mr.card_ticket_id','LEFT')
            ->where($w)->select();

    }

    // 我的品类券未使用未过期
    public function get_my_coupon_pl($member_id){
        $time = time();
        $sql = "SELECT * from th_member_card_ticket_relation as r 
left join th_card_ticket as ct on ct.card_ticket_id = r.card_ticket_id 
left join th_card_ticket_type as ctt on ctt.card_type_id = ct.card_type_id 
where r.member_id = $member_id and r.`status` = '1' and ctt.end_time > $time and ctt.ticket_type='3'";
        return Db::query($sql);
    }

    // 我的品类券已使用
    public function get_my_coupon_is_use_pl($member_id){
        $time = time();
        $sql = "SELECT * from th_member_card_ticket_relation as r 
left join th_card_ticket as ct on ct.card_ticket_id = r.card_ticket_id 
left join th_card_ticket_type as ctt on ctt.card_type_id = ct.card_type_id 
where r.member_id = $member_id and r.`status` = '2' and ctt.end_time > $time and ctt.ticket_type='3'";
        return Db::query($sql);
    }

    //我的品类券过期

    public function get_my_coupon_guoqi_pl($member_id){
        $time = time();
        $sql = "SELECT * from th_member_card_ticket_relation as r 
left join th_card_ticket as ct on ct.card_ticket_id = r.card_ticket_id 
left join th_card_ticket_type as ctt on ctt.card_type_id = ct.card_type_id 
where r.member_id = $member_id and ctt.end_time < $time and ctt.ticket_type='3'";
        return Db::query($sql);
    }
    //判断用户是否有这个优惠券
    public function my_is_have_coupon($member_id,$card_type_id){
        $sql = "select count(*) as c from (select ct.card_type_id from th_member_card_ticket_relation as ctr right join th_card_ticket
 as ct on ct.card_ticket_id = ctr.card_ticket_id where ctr.member_id = $member_id) a where card_type_id = $card_type_id";
        return Db::query($sql);
    }

    // 查询没有领取的优惠券
    public function get_not_use_coupon($w){
        return Db::name('card_ticket')->where($w)->select();
    }
    // 查询重复领取的card_ticket_id
    public function get_cf_list(){
        $sql = "select mr.card_ticket_id,count(*) as c from th_member_card_ticket_relation as mr left join th_card_ticket as ct on ct.card_ticket_id = mr.card_ticket_id where ct.card_type_id = 30 GROUP BY mr.card_ticket_id HAVING c>1";
        return Db::query($sql);
    }

    // 修改优惠券
    public function edit_cf($w_cf){
        // 查询重复的id
        $sql_cf = "select id,card_ticket_id from th_member_card_ticket_relation where card_ticket_id = {$w_cf['card_ticket_id']} limit 1,{$w_cf['c']}";
        $cf_id_list = Db::query($sql_cf);
        $limit_wlq = count($cf_id_list);
        // 查找未领取的优惠券
        //$sql_wlq = "select card_ticket_id from th_card_ticket_id where card_type_id = 30 and status = '2' limit $limit_wlq";
        //$wlq_id_list = Db::query($sql_wlq);
        // 分配优惠券

    }

    public function aaa($id){
        // 查询重复的
        $sql = "select id from th_member_card_ticket_relation where card_ticket_id = $id";
        $list = Db::query($sql);

        $count = count($list);
        //echo $count;
        // 查询未使用的优惠券
        $sql1 = "select card_ticket_id from th_card_ticket where card_type_id = 30 and status = '2' limit 0,$count";
        $list1 = Db::query($sql1);
        //echo '<pre>';print_r($list);exit;
        foreach($list as $key=>$val){
            $w['id'] = $val['id'];
            $data['card_ticket_id'] = $list1[$key]['card_ticket_id'];
            Db::name('member_card_ticket_relation')->where($w)->update($data);
            $w1['card_ticket_id'] = $list1[$key]['card_ticket_id'];
            $data1['status'] = 1;
            Db::name('card_ticket')->where($w1)->update($data1);
        }
    }
}