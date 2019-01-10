<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Config;
class JssdkController extends Controller {
  private $appId;
  private $appSecret;

  public function __construct() {
    $this->appId = config('appId');
    $this->appSecret = config('appSecret');
  }

  public function getSignPackage() {

        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // $url = input('post.url/s');

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = [
          "appId"     => $this->appId,
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
          "rawString" => $string
        ];
        return json(['data' => $signPackage]); 
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
          $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        //$data = json_decode('{"jsapi_ticket":"","expire_time":0}');
        $data = (new Config)->Common_Find(['key' => 'jsapi_ticket']);

        if ($data['createTime'] < time()) {
          $accessToken = $this->getAccessToken();
          // 如果是企业号用以下 URL 获取 ticket
          // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
          $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
          $res = json_decode($this->httpGet($url));
          $ticket = $res->ticket;
          if ($ticket) {
            $expire_time = time() + 7000;
            // $data->jsapi_ticket = $ticket;
            (new Config)->Common_Update(['value' => json_encode($res) , 'createTime' => $expire_time,'updateTime' => $expire_time],['key' => 'jsapi_ticket']);
            // $this->set_php_file("jsapi_ticket.php", json_encode($data));
          }
        } else {
          $ticket = json_decode($data['value'])->ticket;
        }

        return $ticket;
    }

    private function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        //$data = json_decode('{"access_token":"","expire_time":0}');
        $data = (new Config)->Common_Find(['key' => 'access_token']);
        if ($data['createTime'] < time()) {
          // 如果是企业号用以下URL获取access_token
          // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
          $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";

          $res = json_decode($this->httpGet($url));

          // p($res,1);
          $access_token = $res->access_token;
          if ($access_token) {
            $expire_time = time() + 7000;
            // $data->access_token = $access_token;
            (new Config)->Common_Update(['value' => json_encode($res) , 'createTime' => $expire_time,'updateTime' => $expire_time],['key' => 'access_token']);
            // $this->set_php_file("access_token.php", json_encode($data));
          }
        } else {
          $access_token = json_decode($data['value'])->access_token;
        }
        return $access_token;
    }

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    private function get_php_file($filename) {
        return trim(substr(file_get_contents($filename), 15));
    }
    private function set_php_file($filename, $content) {
        $fp = fopen($filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }

}

