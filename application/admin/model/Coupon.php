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
}