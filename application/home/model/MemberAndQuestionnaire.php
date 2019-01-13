<?php
namespace app\home\model;

class MemberAndQuestionnaire extends Common
{

    private $table = 'member_and_questionnaire';

    public function __construct(){

        parent::__construct($this->table);
    }
}