<?php

ini_set("display_errors", "on");

require_once dirname(__DIR__) . '/api_sdk/vendor/autoload.php';

use Aliyun\Core\Config;
use Aliyun\Core\Exception\ClientException;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SingleCallByVoiceRequest;
use Aliyun\Api\Sms\Request\V20170525\SingleCallByTtsRequest;
use Aliyun\Api\Sms\Request\V20170525\IvrCallRequest;
use Aliyun\Api\Sms\Request\V20170525\MenuKeyMap;
use Aliyun\Api\Sms\Request\V20170525\ClickToDialRequest;
use Aliyun\Api\Sms\Request\V20170525\CancelCallRequest;
use Aliyun\Api\Sms\Request\V20170525\QueryCallDetailByCallIdRequest;

// 加载区域结点配置
Config::load();

/**
 * Class VmsDemo
 *
 * Created on 17/10/17.
 * 语音服务API产品的DEMO程序,工程中包含了一个SecretDemo类，直接通过
 * 执行此文件即可体验语音服务产品API功能(只需要将AK替换成开通了云通信-语音服务产品功能的AK即可)
 * 备注:Demo工程编码采用UTF-8
 */
class VmsDemo
{

    static $acsClient = null;

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient() {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dyvmsapi";

        //产品域名,开发者无需替换
        $domain = "dyvmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = "LTAIfPjl5tFqOrIr"; // AccessKeyId

        $accessKeySecret = "vcZiMqCFzC1UVQ0mON7menQvLQb9V3"; // AccessKeySecret


        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * 语音文件外呼
     *
     * @return stdClass
     * @throws ClientException
     */
    public static function singleCallByVoice() {

        //组装请求对象-具体描述见控制台-文档部分内容
        $request = new SingleCallByVoiceRequest();
        //必填-被叫显号
        $request->setCalledShowNumber("400100000");
        //必填-被叫号码
        $request->setCalledNumber("13700000000");
        //必填-语音文件Code
        $request->setVoiceCode("c2e99ebc-2d4c-4e78-8d2a-afbb06cf6216.wav");
        //选填-外呼流水号
        $request->setOutId("yourOutId");
        
        //hint 此处可能会抛出异常，注意catch
        $response = static::getAcsClient()->getAcsResponse($request);

        return $response;
    }

    /**
     * 文本转语音外呼
     *
     * @return stdClass
     * @throws ClientException
     */
    public static function singleCallByTts($tel='',$content='') {

        $acsClient = static::getAcsClient();

        //组装请求对象-具体描述见控制台-文档部分内容
        $request = new SingleCallByTtsRequest();
        //必填-被叫显号
        $request->setCalledShowNumber("02474884560");
        //必填-被叫号码
        $request->setCalledNumber($tel);
        //必填-Tts模板Code
        $request->setTtsCode("TTS_123670621");
        //选填-Tts模板中的变量替换JSON,假如Tts模板中存在变量，则此处必填
        $request->setTtsParam($content);
        //选填-外呼流水号
        $request->setOutId("");

        //hint 此处可能会抛出异常，注意catch
        $response = static::getAcsClient()->getAcsResponse($request);

        return $response;
    }


    /**
     * 交互式语音应答
     *
     * @return stdClass
     * @throws ClientException
     */
    public static function ivrCall() {

        //组装请求对象-具体描述见控制台-文档部分内容
        $request = new IvrCallRequest();
        //必填-被叫显号
        $request->setCalledShowNumber("05344757036");
        //必填-被叫号码
        $request->setCalledNumber("1500000000");
        //选填-播放次数
        $request->setPlayTimes(3);



        //必填-语音文件ID或者tts模板的模板号,有参数的模板需要设置模板变量的值
        //$request->setStartCode("ebe3a2b5-c287-42a4-8299-fc40ae79a89f.wav");
        $request->setStartCode("TTS_713900000");
        $request->setStartTtsParams("{\"product\":\"aliyun\",\"code\":\"123\"}");
        $menuKeyMaps = array();

        $menuKeyMap1 = new MenuKeyMap();
        $menuKeyMap1->setKey("1");
        $menuKeyMap1->setCode("9a9d7222-670f-40b0-a3af.wav");
        $menuKeyMaps[] = $menuKeyMap1;

        $menuKeyMap2 = new MenuKeyMap();
        $menuKeyMap2->setKey("2");
        $menuKeyMap2->setCode("44e3e577-3d3a-418f-932c.wav");
        $menuKeyMaps[] = $menuKeyMap2;

        $menuKeyMap3 = new MenuKeyMap();
        $menuKeyMap3->setKey("3");
        $menuKeyMap3->setCode("TTS_71390000");
        $menuKeyMap3->setTtsParams("{\"product\":\"aliyun\",\"code\":\"123\"}");
        $menuKeyMaps[] = $menuKeyMap3;

        $request->setMenuKeyMaps($menuKeyMaps);

        //结束语可以使一个无参模板或者一个语音文件ID
        $request->setByeCode("TTS_71400007");

        //选填-外呼流水号
        $request->setOutId("yourOutId");

        //hint 此处可能会抛出异常，注意catch
        $response = static::getAcsClient()->getAcsResponse($request);

        return $response;
    }



    /**
     * 点击拨号
     *
     * @return stdClass
     * @throws ClientException
     */
    public static function clickToDial() {

        //组装请求对象-具体描述见控制台-文档部分内容
        $request = new ClickToDialRequest();
        //必填-主叫显号
        $request->setCallerShowNumber("05344757036");
        //必填-主叫号码
        $request->setCallerNumber("1800000000");
        //必填-被叫显号
        $request->setCalledShowNumber("4001112222");
        //必填-被叫号码
        $request->setCalledNumber("1500000000");
        //选填-外呼流水号
        $request->setOutId("yourOutId");

        //hint 此处可能会抛出异常，注意catch
        $response = static::getAcsClient()->getAcsResponse($request);

        return $response;
    }


    /**
     * 取消呼叫
     *
     * @return stdClass
     * @throws ClientException
     */
    public static function cancelCall() {

        $request = new CancelCallRequest();

        //组装请求对象-CallId从上次呼叫调用的返回值中获取
        $request->setCallId("113853585007^100675005007");

        //hint 此处可能会抛出异常，注意catch
        $response = static::getAcsClient()->getAcsResponse($request);

        return $response;
    }


    /**
     * 通过呼叫ID获取呼叫记录
     *
     * @return stdClass
     * @throws ClientException
     */
    public static function queryCallDetailByCallId() {

        $request = new QueryCallDetailByCallIdRequest();

        //组装请求对象-CallId从上次呼叫调用的返回值中获取
        $request->setCallId("113853585007^100675005007");

        // 必填: 设置你需要查询的时间，会查询对应那一天的记录，注意：跨天情况可以判断一下昨天的记录
        $request->setQueryDate(time().'000');

        // 必填: 设置对应的产品类型
        // 语音通知(11000000300006)
        // 语音验证码(11010000138001)
        // IVR(11000000300005)
        // 点击拨号(11000000300004)
        // SIP(11000000300009)
        $request->setProdId("11000000300004");

        // 注意: 此处可能会抛出异常，注意catch
        $response = static::getAcsClient()->getAcsResponse($request);

        return $response;
    }



}


