<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/3
 * Time: 11:23
 */
namespace app\admin\model;
use think\Db;
use think\Model;

class CouponType extends Common{
    private $table = 'card_ticket_type';

    public function __construct(){

        parent::__construct($this->table);
    }

    // 批量生成优惠券最大100
    public function add_coupons($datas){
        Db::startTrans();
        try{
            Db::name($this->table)->insert($datas);

            for($i=0;$i<$datas['reserve'];$i++){
                $str = $this->random(13);
                $data['store_id'] = $datas['store_id'];
                $data['card_code'] = $str;
                Db::name('card_ticket')->insert($data);
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


    /**
     * @param $length
     * @param bool|false $numeric
     * @return string
     * 生成指定长度的随机字符串并返回。
     */
    function random($length, $numeric = false) {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        if ($numeric) {
            $hash = '';
        } else {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }
        return $hash;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $where
     * @param array $order
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_type_list($offset=0, $limit=10, $where=[], $order=[], $field=''){

        $data = Db::name($this->table)
            ->where($where)
            ->limit($offset,$limit)
            ->order($order)
            ->field($field)
            ->select();

        $count = Db::name($this->table)
            ->where($where)
            ->count();

        return ['data' => $data , 'count' => $count];
    }

    public function add_pinlei_coupon($data,$data1){

        Db::startTrans();
        try{
            $last_id = $this->Common_Insert($data);
            $data1['card_type_id'] = $last_id;
            $data1['create_time'] = time();
            Db::table('th_card_ticket')->insertGetId($data1);
            Db::commit();
            return 1;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return 0;
        }
    }
}