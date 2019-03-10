<?php
namespace app\home\model;

class SmsLog extends Common
{

    private $table = 'sms_log';

    public function __construct(){

        parent::__construct($this->table);
    }
}