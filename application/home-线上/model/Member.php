<?php
namespace app\home\model;

class Member extends Common
{

    private $table = 'member';

    public function __construct(){

        parent::__construct($this->table);
    }
}