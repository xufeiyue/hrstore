<?php
namespace Wechat;
use think\Controller;

class Wechat extends Controller
{
	protected $appid='wx1e2fca3922c8c930';
    protected $appsecret = 'f40795355ffb9cb9f8925667bf9f1546';

    //授权
	public function accredit($redirect_url='',$state=''){

		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri={$redirect_url}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";

        $this->redirect($url);

        // $ourl= "http://crvweixin.crv.com.cn/activities/impl/actThird!toActOauth.action?actUrl=zqcj1&appidType=1";  //华润的接口地址
        // $ourl=str_replace('&amp;','&',$ourl);
        // // 接口回调后想带的参数，我这边做华润活动一般填写这次华润活动的路径，用于回调以后的跳转，需要使用base64加密
        // $lasturl = $ourl.'&thirdParams='.$state;     //拼接跳转华润的接口路径                                                                 
        // $oauthUrl=$lasturl;
        // $this->redirect($oauthUrl);
	}

	public function getAccessToken($code=''){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->appsecret}&code={$code}&grant_type=authorization_code";
        $res = file_get_contents($url); //获取文件内容或获取网络请求的内容
        $access_token = json_decode($res,true);
        return $access_token;
    }
    /*
     * 获取用户信息
     */
    public function getWeChatUserInfo($access_token,$openid){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $output = file_get_contents($url);
        $weChatUserInfo = json_decode($output,true);
        return $weChatUserInfo;
    }
}