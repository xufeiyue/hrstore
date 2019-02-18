<?php
namespace app\admin\model;
use think\Model;
use think\Validate;
use think\Db;
class User extends Common
{
	private $table = 'user';

	public function __construct(){

		parent::__construct($this->table);
	}
	/**
	 * [check 验证用户名密码是否正确]
	 * @param  string $where [条件]
	 * @return [type]        [description]
	 */
	public function check($where,$ip)
	{
		// $where['status'] = 1; //有效
		$where['state'] = 0; //正常
		$rs = Db::name('user')->where($where)->find();
		if($rs){
			//登录成功 加入登录日志
			$data['user_id'] = $rs['id'];
			$data['create_time'] = time();
			$data['log_info'] = '登录系统';
			$data['ip_address'] = $ip;
			$data['user_name'] = $rs['user_name'];
			$this->loginlog($data);

			return $rs;
		}else{
			return false;
		}
	}
	// 新增用户数据
	public function add($data)
	{
	    return Db::name('user')->insert($data);
	}

	public function loginlog($data)
	{
		// 插入记录
		Db::table('__ADMIN_LOG__')
		    ->insert($data);
	}

	public function lists($offset=0,$limit=10,$where=[],$order=[])
	{

		$data = Db::name('user')
			->alias('a')
			->where($where)
			->field('a.*,r.title')
			->join('__AUTH_GROUP__ r',' r.id = a.role_id ','left')
			->limit($offset,$limit)
			->order($order)
			->select();

		$count = Db::name('user')
			->alias('a')
			->where($where)
			->count();
			
		return ['total'=>$count,'rows'=>$data];
	}

	public function find($id){

		return Db::name('user')->where(['id' => $id])->find();
	}

	public function update($data){
		return Db::name('user')->where(['id' => $data['id']])->update($data);
	}
}