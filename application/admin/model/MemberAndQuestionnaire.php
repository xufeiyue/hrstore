<?php
namespace app\admin\model;
use think\Db;
class MemberAndQuestionnaire extends Common
{

	private $table = 'member_and_questionnaire';

	public function __construct(){

		parent::__construct($this->table);
	}

	//列表
	public function lists($offset=0,$limit=10,$where=[],$order=[],$field=[]){

		$data = Db::name('member_and_questionnaire')
			->alias('m_a_q')
			->join('questionnaire q','q.id = m_a_q.questionnaire_id')
			->join('member m','m.id = m_a_q.userId','left')
			->where($where)
			->field($field)
			->limit($offset,$limit)
			->order($order)
			->select();

		$count = Db::name('member_and_questionnaire')
			->alias('m_a_q')
			->join('questionnaire q','q.id = m_a_q.questionnaire_id')
			->join('member m','m.id = m_a_q.userId','left')
			->where($where)
			->field($field)
			->order($order)
			->count();

		return ['data' => $data, 'count' => $count];
	}

}