<?php
namespace app\home\model;

class Advertisement extends Common
{

    private $table = 'advertisement';

    public function __construct(){

        parent::__construct($this->table);
    }
}