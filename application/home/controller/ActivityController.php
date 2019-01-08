<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Activity;
use app\home\model\ActivityLog;
class ActivityController extends CommonController
{
	/*
		活动控制器
	*/
	//参加活动列表
	public function huodong(){

		$ActivityLog = (new ActivityLog)->ActivityLog_list(['a_l.userId' => $this->userId, 'a_l.status' => 0],['a_l.id' => 'desc'],['a.id','a.activity_url']);

		$this->assign('ActivityLog',$ActivityLog);

		return view();
	}

	//详情
	public function lq_huodong(){

		$this->title = '活动详情';

		$id = input('id/d');

		$data['activity_id'] = $id;

		$data['userId'] = $this->userId;

		$ActivityLog = (new ActivityLog)->Common_Find(['activity_id' => $id, 'userId' => $this->userId]);

		if (empty($ActivityLog)) {

			$data['createTime'] = time();

			$data['updateTime'] = time();

			(new ActivityLog)->Common_Insert($data);
		}
		

		$Activity = (new Activity)->Common_Find(['id' => $id],[],['id','activity_name','activity_url','activity_detail']);

		$this->assign('title',$this->title);

		$this->assign('Activity',$Activity);

		return view();
	}
}