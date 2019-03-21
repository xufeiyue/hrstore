<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/12
 * Time: 23:45
 */
namespace app\admin\model;
use think\Db;
use think\Model;

class Coupon extends Common
{
    private $table = 'card_ticket';

    public function __construct()
    {

        parent::__construct($this->table);
    }

    // 批量生成优惠券
    public function addCards($data,$excel_datas){
        Db::startTrans();
        try{
            foreach ($excel_datas as $key1=>$val1){
                $data['barcode'] = $val1[0];
                $this->Common_Insert($data);
            }

            //  提交事务
            Db::commit();
            return 1;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return 0;
        }
    }

    public function getAllCoupon(){
        $sql = "SELECT count(*), card_ticket_type_res.card_type_id, card_ticket_type_res.ticket_name FROM th_member_card_ticket_relation
 AS r LEFT JOIN th_card_ticket AS ct ON ct.card_ticket_id = r.card_ticket_id LEFT JOIN 
 (( SELECT card_type_id, ticket_name, ticket_type FROM th_card_ticket_type WHERE `status` = 1 ) card_ticket_type_res) 
 ON card_ticket_type_res.card_type_id = ct.card_type_id group by card_ticket_type_res.card_type_id";
        return Db::query($sql);
    }
}