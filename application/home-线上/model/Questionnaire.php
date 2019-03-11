<?php
namespace app\home\model;

class Questionnaire extends Common
{

    private $table = 'questionnaire';

    public function __construct(){

        parent::__construct($this->table);
    }
}