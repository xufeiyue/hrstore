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
    public function addCards($data,$excel_datas,$picidata){
        Db::startTrans();
        try{
            foreach ($excel_datas as $key1=>$val1){
                $data['barcode'] = $val1[0];
                $this->Common_Insert($data);
            }
            Db::name('card_pici')->insert($picidata);
            //  提交事务
            Db::commit();
            return 1;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return 0;
        }
    }
    public function getAllCoupon($where=[],$where1){
//        $sql = "SELECT
//	count( * ) AS nums,
//	card_ticket_type_res.card_type_id,
//	card_ticket_type_res.ticket_name
//FROM
//	th_member_card_ticket_relation AS r
//	LEFT JOIN th_card_ticket AS ct ON ct.card_ticket_id = r.card_ticket_id
//	RIGHT JOIN ( ( SELECT card_type_id, ticket_name, ticket_type FROM th_card_ticket_type WHERE `status` = 1 ) card_ticket_type_res ) ON card_ticket_type_res.card_type_id = ct.card_type_id
//GROUP BY
//	card_ticket_type_res.card_type_id";
//        echo $sql;
//        return Db::query($sql);
        $subQuery = Db::table('th_card_ticket_type')->where($where1)->field('card_type_id, ticket_name, ticket_type')->buildSql();
        return Db::table('th_member_card_ticket_relation')->alias('r')
            ->join('card_ticket ct','ct.card_ticket_id = r.card_ticket_id','left')
            ->join($subQuery.' card_ticket_type_res','card_ticket_type_res.card_type_id = ct.card_type_id','right')
            ->where($where)
            ->group('card_ticket_type_res.card_type_id')
            ->field('count(*) as nums, card_ticket_type_res.card_type_id, card_ticket_type_res.ticket_name')
            ->select();
    }

    public function getAllCoupon1($w1='',$w2=''){
        $sql = "SELECT
	s.store_name,
	( IFNULL( n.num, 0 ) ) AS num,
	s.store_id,
	s.visits_num,
	( IFNULL( m.num1, 0 ) ) AS num1 
FROM
	th_store s
	LEFT JOIN (
	SELECT
		`store_id`,
		count( r.id ) AS num 
	FROM
		th_member_card_ticket_relation r
		LEFT JOIN th_card_ticket ct ON ct.card_ticket_id = r.card_ticket_id 
	WHERE
		ct.card_type_id = 67 
		$w1
	GROUP BY
		r.store_id 
	) n ON n.store_id = s.store_id
	LEFT JOIN ( SELECT count( * ) AS num1, `store_id` FROM `th_member` $w2 GROUP BY store_id ) m ON m.store_id = s.store_id 
WHERE
	s.STATUS = 1 
GROUP BY
	s.store_id";

        return Db::query($sql);
    }

    public function addPici($data){
        Db::name('th_card_pici')->insert($data);
    }
}