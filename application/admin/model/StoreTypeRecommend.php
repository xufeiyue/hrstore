<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/24
 * Time: 13:40
 */
namespace app\admin\model;
use think\Db;
class StoreTypeRecommend extends Common
{
    private $table = 'store_type_recommend';

    public function __construct(){

        parent::__construct($this->table);
    }


}