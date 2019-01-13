<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/13
 * Time: 1:40
 */
namespace app\home\model;

class Coupon extends Common
{

    private $table = 'card_ticket_type';

    public function __construct(){

        parent::__construct($this->table);
    }
}