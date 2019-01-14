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
}