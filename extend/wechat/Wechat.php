<?php
namespace Wechat;
use think\Controller;

class Wechat extends Controller
{
	protected $appid='1212';
    protected $appsecret = '232323';

    //授权
	public function accredit($redirect_url=''){

		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri={$redirect_url}&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";

        $this->redirect($url);
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