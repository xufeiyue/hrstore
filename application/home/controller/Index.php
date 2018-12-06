<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use wxhongbao\Wxhongbao;
use weixin\Wechat;

class Index extends controller
{

	/**
	 * [index 首页]
	 * @return [type] [description]
	 */
	public function index()
	{

      $this -> judge_share();
  		//配置获取openID的参数
  		// $appid = config('appId');
      //   $appsecret = config('appSecret');

      //   if(isset($_GET['code'])){
      //       $code = $_GET['code'];
      //   }else{
      //       $this -> redirect(url('getBaseInfo'));
      //   }


      //   //获取openid
      //   $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';  
      //   $res = $this -> http_curl($url,'get');

        if( isset($_GET['openid']) ){

            $openid = $_GET['openid'];      
            $aaa = json_encode($_SERVER);

        }else{
            $this -> redirect(url('getBaseInfo'));
        }

        //判断用户是否注册过
        $rst_user = Db::name('member') -> where('openid',$openid) ->find();

        if($rst_user['sex']){
            $this -> redirect(url('questions')."?openid=".$openid);
        }else{
            if($rst_user){
            }else{            
                Db::name('member') -> insert(['openid'=>$openid]);
            }
            $this -> assign('openid',$openid);
            return view();
        }
    }


    /**
     * [save_user_info 保存用户信息]
     * @return [type] [description]
     */
    public function save_user_info()
    {
        $list = input("get.");
        $openid = input("get.openid");
        //判断是否传递数据
        if( empty($_GET['openid']) || empty($_GET['sex']) || empty($_GET['position']) ){          
            $this -> redirect(url('getBaseInfo'));
        }

        $rst_user = Db::name('member') -> where('openid',$_GET['openid']) ->find();
        if($rst_user['sex']){
            $this -> redirect(url('questions')."?openid=".$_GET['openid']);
        }else{
            $rs = Db::name('member') -> where('openid',$openid) -> update($list);
            $this -> redirect(url('questions')."?openid=".$_GET['openid']);
        }
        
    }

    

    /**
     * [questions 主菜单界面]
     * @return [type] [description]
     */
  	public function questions()    
    {
        $this -> judge_share();

        $aaa = json_encode($_SERVER);
        Db::name('ip_address') -> insert(['ip'=>$aaa]);

        $data = Db::name('know')->find();
        $this -> assign('data',$data);
        $this -> assign('openid',input('get.openid'));
        return view();
    }

    public function questions1()
    {
        $this -> judge_share();

        $data = Db::name('know')->find();
        $this -> assign('data',$data);
        $this -> assign('openid',input('get.openid'));
        return view();
    }

    /**
     * [professional 专题训练]
     * @return [type] [description]
     */
    public function professional()
    {   
        $this -> judge_share();

        $this -> assign('openid',$_GET['openid']);
        return view();
    }


   

    /**
     * [competition 竞赛答题]
     * @return [type] [description]
     */
    public function competition()
    {

        $this -> judge_share();

        $openid = $_GET['openid']; 
        $rs = Db::name('random')->where('openid',$openid)->find();

        if($rs){
           $data = Db::name('random')->where('openid',$openid)->delete();
        }
        $this -> assign('openid',$openid);
        return view();  
    }

    /**
     * [competition_ajax 竞赛答题 题列表]
     * @return [type] [description]
     */
   public function competition_ajax()
    {
      $data = Db::name('ti')
        ->where('state',1)
        ->order('rand()')
        ->field('answer',true)
        ->limit(6)
        ->select();
      $data['6'] = Db::name('ti')->where('state',2)->order('rand()')->field('answer',true)->limit(1)->find();
      $id_6 = $data['6']['id'];
$data['7'] = Db::name('ti')->where('state',2)->order('rand()')->field('answer',true)->where('id','neq',$id_6)->limit(1)->find();
      $id_7 = $data['7']['id'];
$data['8'] = Db::name('ti')->where('state',2)->order('rand()')->field('answer',true)->where('id','not in',"$id_6,$id_7")->limit(1)->find();
      $id_8 = $data['8']['id'];
$data['9'] = Db::name('ti')->where('state',2)->order('rand()')->field('answer',true)->where('id','not in',"$id_6,$id_7,$id_8")->limit(1)->find();  

        if($data){
            return $data;
        }     
    }

    public function professional_ajax(){


      $data = Db::name('ti')->select();
      $data['zongnum'] = count($data);
      return $data;


    }

    /**
     * [getBaseInfo 访问初始页]
     * @return [type] [description]
     */
    public function getBaseInfo()
    {
        $this -> redirect('https://zk.qxdqapp.com/hb.php');
        //获取code
        // $appid = config('appId');

        // // $redirect_uri = 'http://'.$_SERVER['SERVER_NAME'].url('home/Index/index');
        // $redirect_uri = 'https://zk.qxdqapp.com/hb.php';

        // $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_base&state=123#wechat_redirect';

        // echo "<script>window.location.href='$url'</script>";
    }


    /**
     * [http_curl curl方法]
     * @param  [type] $url  [请求地址]
     * @param  string $type [请求方式]
     * @param  string $res  [返回格式]
     * @param  string $arr  [POST传参数]
     * @return [type]       [description]
     */
    public function http_curl($url,$type='get',$res='json',$arr='')
    {
        //初始化
        $ch = curl_init();

        //设置参数
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  // 从证书中检查SSL加密算法是否存在 
        if($type == 'post'){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
        }

        //请求
        $output = curl_exec($ch);
        curl_close($ch);

        //返回数据
        if($res == 'json'){
            return json_decode($output,true);
        }
    }

    public function judge_ajax()
    {
       	$openid = $_GET['openid'];
        $choose = $_GET['choose'];
       	$tiid = $_GET['tiid'];
        // $str_all = '1234';
        // if(strpos($str_all,$tiid) === false){     //使用绝对等于
        //     //不包含
            $data_ti = Db::name('ti')->where('id',$tiid)->find();

            if($data_ti['answer'] !=$choose){
                $data_cuo = Db::name('random')->insert(['openid'=>$openid,'ti_id'=>$tiid,'choose'=>$choose]);
            }
        // // }else{
        //   //包含
        //   $data_ti = Db::name('ti')->where(['id'=>$tiid,'state'=>2])->find();

        //       if($data_ti['answer'] !== $choose){
        //           $data_cuo = Db::name('random')->insert(['openid'=>$openid,'ti_id'=>$tiid,'choose'=>$choose]);
        //       }
        // }
      

      	

      	$rs =  Db::name('random')->where('openid',$openid)->select();
        $send_state =  Db::name('member')->where('openid',$openid)->find();
        $send_state = $send_state['send_state']; 

      	if(!$rs){
      		$data = ['cuo'=>0,'send_state'=>$send_state];		
  				return $data;
      	}else{
      		$data = ['cuo'=>1,'send_state'=>$send_state,'answer'=>$data_ti['answer']];		
  				return $data;
      	}		
    }

    public function a_ajax()
    {
        //$openid = 1214444232;
        $tiid = $_GET['tiid'];
        $answer = $_GET['answer'];

        $data = Db::name('ti')->where('id',$tiid)->find();

        if($data['answer'] !== $answer){

            echo json_encode(['code' => 1 , 'data' => $data['answer']]); //'data' => 为正确答案

        }else{

            echo json_encode(['code' => 1000 , 'data' => $data['answer']]);
        }

        //$rs =  Db::name('random')->where('openid',$openid)->select();
    }

    /**
     * [jiang 开奖页面]
     * @return [type] [description]
     */
    public function jiang()
    {	        
        $this -> judge_share();  
        $openid = input('get.openid');
        //查看用户是否领取过红包
        $rst_user = Db::name('member') -> where('openid',$_GET['openid']) ->find();
        if($rst_user['send_state'] == 1){
            $this -> redirect(url('questions')."?openid=".$openid);
        }
        $this -> assign('openid',$openid);  	
	  	  return view();			
    }
      public function jiajiang()
    {      

        $this -> judge_share();     
        $openid = input('get.openid');
        $this -> assign('openid',$openid);    
        return view();      
    }
       public function xingshi()
    {    

        $this -> judge_share();
        $openid = input('get.openid');
        $this -> assign('openid',$openid);    
        return view();      
    }

    /**
     * [check 检查错题]
     * @return [type] [description]
     */
    public function check()
    {

        $this -> judge_share();
      	$openid = input('get.openid');	
      	$data_cuo = Db::name('random')->where('openid',$openid)->select();
      	$count = count($data_cuo);
        $this->assign('count',$count);
      	$this->assign('openid',$openid);
  	  	return view();		
    }

    /**
     * [cuo_list 错题列表页面]
     * @return [type] [description]
     */
    public function cuo_list()
    {
        $this -> judge_share();
        $openid = input('get.openid');
        $this -> assign('openid',$openid);
        return view();	
    }

    /**
     * [cuo_list_ajax 错题列表]
     * @return [type] [description]
     */
    public function cuo_list_ajax()
    {
      $this -> judge_share();
     	$openid = input('get.openid');	
    	$data = Db::name('random r')
    	->where('openid',$openid)
    	->join('ti t','r.ti_id = t.id','left')
  		->order('r.ti_id asc')
  		->select();

  		if($data)
      {
  			return $data;
  		}	

  	  return view();
			
    }

    /**
     * [cuo_list_num_ajax 打错题数]
     * @return [type] [description]
     */
    public function cuo_list_num_ajax()
    {
     	$openid = input('get.openid');	
    	$rs = Db::name('random')
      	->where('openid',$openid)
  		  ->select();
  		$num = count($rs);
  		$data = ['cuo'=>$num];		
		
			return $data;		
    }

				 	
    //判断是否答过竞赛答题
    public function zj_ajax()
    {
        $openid = input('get.openid');
        $time = time();
        $money = rand(1,5);
          $jiang_log = Db::name('jiang_log')->insert(['openid'=>$openid,'time'=>$time,'money'=>$money]);
        $rs = Db::name('member')
          ->where('openid',$openid)
          ->update(['send_state'=>1,'money'=>$money]);
        if($rs){
            $data = ['status'=>1];
            return $data;
        }
    }
     public function jiazj_ajax()
    {
        $openid = input('get.openid');
        // $time = time();
        // $money = rand(1,5);
        //   $jiang_log = Db::name('jiang_log')->insert(['openid'=>$openid,'time'=>$time,'money'=>$money]);
        $rs = Db::name('member')
          ->where('openid',$openid)
          ->update(['send_state'=>1,'money'=>$money]);
        if($rs){
            $data = ['status'=>1];
            return $data;
        }
    }

    //发红包
    public function fahongbao($openid=''){
      //判断今天发没发
      //判断今天题答对没答对（必须得答题）
      //判断今天题答对
        $hongbao_status = Db::name('hongbao_log')->where('openid',$openid)->find();

        if($hongbao_status){
            echo '非法访问';die;   
        }

        $arr['openid']=$openid;
        $arr['hbname']="答题";
        $arr['body']="红包";

        //时间为5月31日时改变爆率
        if( date('m',time()) == 5 && date('d',time()) == 31){
            $arr['fee']=rand(1,2);
        }else{       
            $hongbao =[1,2,5.31];   
            $xiabiao = rand(0,2);
            $arr['fee'] = $hongbao[$xiabiao];
        }

        $comm = new Wxhongbao();
        $re = $comm->sendhongbaoto($arr);

        if($re['return_code'] == 'SUCCESS'){
            //插入红包记录
            $info_arr = [
                'openid' => $openid,
                'total_amount' => $re['total_amount'],
                'add_time' => time(),
            ];
            Db::name('hongbao_log') -> insert($info_arr);            
        }    

        $this -> redirect(url('questions')."?openid=".$openid);
    }

    /**
     * [judge_share 判断是否为分享获得]
     * @return [type] [description]
     */
    public function judge_share()
    {
        if( isset($_GET['from']) || !empty($_GET['from']))
        {
            $this -> redirect(url('getBaseInfo'));
        }
    }






    //发红包
    public function fahongbao1($openid_th=''){

        $openid_arr = [       
          'oXzjkw_-sKLdbQXvJ_gjJtKaaTEo',
          'oXzjkw1NoATQat7xwhFyN_NPo9mQ',
          'oXzjkw-kbzlmVISf4lWtmC30sTFw',
          'oXzjkw649fORnm7DlIYCE9AhTUQE',
          'oXzjkw3qe8gZphw21nbBC9gBGedk',
        ];

        // if(1)
        if(in_array($openid_th,$openid_arr))
        {    
            //发红包  
            $arr['openid']=$openid_th;
            $arr['hbname']="答题红包";
            $arr['body']="红包";
            $arr['fee'] = 2;
            $comm = new Wxhongbao();
            $re = $comm->sendhongbaoto($arr);

            //记录
              //1、openID
              
              $arr_openid_str = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9','-','_');
              $rand_openid_legth = rand(22,26);

              $openid = 'oXzjkw';

              for($i=0;$i<$rand_openid_legth;$i++){

                  $rand_openid_str = rand(0,62);
                  $openid .= $arr_openid_str[$rand_openid_str];

              }



              
              //2、性别 职业 答题次数
              $arr_sex = ['男','女'];
              $arr_position = ['学生', '教师','公务员','医生','其他'];

              $rand_sex = rand(0,1);
              $rand_position = rand(0,4);

              $sex = $arr_sex[$rand_sex];
              $position = $arr_position[$rand_position];


              //member表
              
              $data_member = [
                  'openid'      => $openid,
                  'sex'         => $sex,
                  'position'    => $position,
                  'send_state'  => 1,
                  'number'      => rand(0,4),
                  'ti_states'   => 0,
                  'create_time' => time(),
              ];
              Db::name('member') -> insert($data_member);

              //hongbao_log表
              $data_hongbao = [
                  'openid' => $openid,
                  'total_amount' => 200,
                  // 'total_amount' => $re['total_amount'],
                  'add_time' => time(),
              ];
              Db::name('hongbao_log') -> insert($data_hongbao);                        
        }
        else
        {
            return die();
        }
    }

   

 }
