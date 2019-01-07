<?php
namespace app\home\model;

class Activity extends Common
{

    private $table = 'activity';

    public function __construct(){

        parent::__construct($this->table);
    }
}