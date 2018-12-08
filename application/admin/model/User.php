<?php
namespace app\admin\model;
use think\Model;
use think\Validate;
use think\Db;
class User extends Model
{
	 
	/**
	 * [check 验证用户名密码是否正确]
	 * @param  string $where [条件]
	 * @return [type]        [description]
	 */
	public function check($where,$ip)
	{
		$where['status'] = 1;
		$rs = $this->where($where)->find();
		if($rs){
			//登录成功 加入登录日志
			$data['user_id'] = $rs['id'];
			$data['log_time'] = date('Y-m-d H:i:s');
			$data['log_info'] = '登录系统';
			$data['ip_address'] = ip2long($ip);
			$this->loginlog($data);

			return $rs;
		}else{
			return $this->getError();
		}
	}
	// 新增用户数据
	public function add($data)
	{

	    if ($this->validate(true)->save($data)) {
	        return ['code'=>1];
	    } else {
	        return ['code'=>0,'msg'=>$this->getError()];
	    }
	}

	public function loginlog($data)
	{
		// 插入记录
		Db::table('__ADMIN_LOG__')
		    ->insert($data);
	}

	public function lists($offset=0,$limit=10,$where=[],$order=[])
	{

		$data = $this
			->alias('a')
			->where($where)
			->field('a.*,r.title')
			->join('__AUTH_GROUP__ r',' r.id = a.role_id ','left')
			->limit($offset,$limit)
			->order($order)
			->select();

		$count = $this
			->alias('a')
			->where($where)
			->count();
			
		return ['total'=>$count,'rows'=>$data];
	}
}