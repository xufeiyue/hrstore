<?php
namespace app\home\controller;
use think\Controller;
use wechat\Wechat;
use app\home\model\Member;
class WxController extends Controller
{
//拉取信息 跳转页面
	public function getChatInfo(){

        // $thirdParams = $_GET['thirdParams'];

        // $param = $_GET['param'];

        // $key='crv_ehasdfu_sgfnasdbf_pw';   // 秘钥 这地方有可能会换
        // $hrinfo = $this->decrypt($_GET['param'],$key);  //解密
        // $hrinfo=preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', trim($hrinfo));
        // $userinfo = json_decode($hrinfo,true);  //这一步就获取到了用户信息

        // echo '<pre>';print_r($userinfo);exit;

		$wechat = new Wechat();//实例化微信类

        $code = $_GET['code'];  //获取跳转后的code

        $state = $_GET['state']; //跳转url
        
        $access_token = $wechat->getAccessToken($code); //根据code获取token

        
        // 验证是否为新用户
        $member = (new Member)->Common_Find(['openid' => $access_token['openid']]);

        if (empty($member)) {
               
           //根据access_token和openid获取到用户信息
    	   $we_chat_user_info = $wechat->getWeChatUserInfo($access_token['access_token'],$access_token['openid']);

//     	   Array
// (
//     [openid] => oiRQBxEy17AWC4A3gGVLIZRqSi6M
//     [nickname] => 裸着，裸着，就习惯了
//     [sex] => 1
//     [language] => zh_CN
//     [city] => 沈阳
//     [province] => 辽宁
//     [country] => 中国
//     [headimgurl] => http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eo2XwO80oct29hf398SaibGicvy7S7rOicRvxuR0SIUR54llicfupJVicPD2QMDnAm3EKiajibfQLnLQHl4A/132
//     [privilege] => Array
//         (
//         )

// )
    	   $data['openid'] = $we_chat_user_info['openid'];
    	   
    	   $data['nickname'] = $we_chat_user_info['nickname'];

    	   $data['sex'] = $we_chat_user_info['sex'];

    	   $data['language'] = $we_chat_user_info['language'];

    	   $data['city'] = $we_chat_user_info['city'];

    	   $data['province'] = $we_chat_user_info['province'];

    	   $data['country'] = $we_chat_user_info['country'];

    	   $data['wx_url'] = $we_chat_user_info['headimgurl'];

    	   $data['create_time'] = time();

    	   $data['update_time'] = time();

    	   //创建新用户
    	   $userId = (new Member)->Common_Insert($data);
           $member = (new Member)->Common_Find(['id' => $userId]); //用户信息
        }
      
       session('wechat_user',$member);

       $obj = $this->_redirect($state);

       if ($obj) {
            $this->redirect($obj);
        }else{
            $this->error('登陆失败');
        }
        
	}

	/*
     * 解析条转过来的页面地址
    * */
    public function _redirect($state) {
        return join('/', json_decode(base64_decode($state), true));
    }

    public function decrypt($dStr = '', $key, $use3des = true){
      //$key=$this->keyStr;
      if (empty($dStr) || empty($key))
      {
        return False;
      }
      
      $replaceStr = $this->replaceStr($dStr,False);
    //        $replaceStr = $dStr;
      $cipher = $use3des ? MCRYPT_TRIPLEDES : MCRYPT_DES;
      $modes  = MCRYPT_MODE_ECB;
      $td     = mcrypt_module_open( $cipher, '', $modes, '' );
      $iv     = mcrypt_create_iv( mcrypt_enc_get_iv_size( $td ), MCRYPT_RAND );
      mcrypt_generic_init($td, $key, $iv);
      //开始base64反编码
      $decode64=base64_decode($replaceStr);
      //开始解密
      $decrypted = mdecrypt_generic($td, $decode64);
      //执行清理工作
      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);
      return $decrypted;
    }
      
      public function replaceStr($rStr,$rMode=True){
        $replaceStr='';
        if($rMode)
        {
          $replaceStr = str_replace("+", "*", $rStr);
          $replaceStr = str_replace("/", ":", $replaceStr);
          $replaceStr = str_replace("=", "_", $replaceStr);
        }else
        {
          $replaceStr = str_replace("*", "+", $rStr);
          $replaceStr = str_replace(":", "/", $replaceStr);
          $replaceStr = str_replace("_", "=", $replaceStr);
        }
        return $replaceStr;
      }

}