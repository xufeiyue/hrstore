<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Store;
use app\home\model\Goods;
use app\home\model\GoodsType;
use app\home\model\Activity;
use app\home\model\CollectionAndCoupons;
use app\home\model\BrowsingLog;
use app\home\model\NewDiscovery;
use app\home\model\Advertisement;
use app\home\model\AdvertisementType;
use app\home\model\Region;
use app\home\model\Questionnaire;
use app\home\model\Problem;
use app\home\model\Member;
use app\home\model\MemberAndQuestionnaire;
class IndexController extends CommonController
{
  public $title;
	/**
	 * [index 首页]
	 */
	public function index(){

    $this->title = '首页';

    $store_id = input('store_id/d') ? : 0;

    if ($store_id) {
     
      session('store_id',$store_id);
    
    }

    $this->assign('store_id',$store_id);

    $this->assign('title',$this->title);

    return view();
  }

  //ajax获取首页数据
  public function ajax_index(){

    $store_id = input('store_id/d') ? : session('store_id');

    $where = [];

    if ($store_id) {
      
      $where = ['store_id' => $store_id];
    }

    $longitude = input('post.longitude/s') ? : '123.454688';

    $latitude = input('post.latitude/s') ? : '41.778517';

    (new Member)->Common_Update(['longitude' => $longitude, 'latitude' => $latitude],['id' => $this->userId]); //更新经纬度

    $store = (new Store)->Common_Find($where,['juli' => 'ASC'],['store_id','store_name',"ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$latitude} * PI() / 180 - latitude * PI() / 180) / 2),2) + COS({$latitude} * PI() / 180) * COS(latitude * PI() / 180) * POW(SIN(({$longitude} * PI() / 180 - longitude * PI() / 180) / 2),2))),2) AS juli"]); // 根据经纬度查询最近的一家门店 距离Km

    if ($store) {
      session('store_id',$store['store_id']);
    }

    $activity = (new Activity)->Common_Find(['banner' => 0]); //轮播活动

    $AdvertisementType = (new AdvertisementType)->Common_Find(['store_id' => $store['store_id'], 'status' => 0, 'type_name' => '首页']);

    $Advertisement = (new Advertisement)->Common_All_Select(['store_id' => $store['store_id'], 'status' => 0, 'type_id' => $AdvertisementType['id']],['id' => 'desc'],['id','image','url']);

    $where = ['store_id' => $store['store_id'], 'status' => 0, 'state' => 0, 'sell_well' => 0,'start_time' => ['<=',time()], 'end_time' => ['>=',time()]];

    $offset = 0;

    $limit = 8;

    $order = ['id' => 'desc'];

    $goods_field = ['id','goods_name','goods_original_price','goods_present_price','goods_images'];

    $goods_list = (new Goods)->Common_Select($offset,$limit,$where,$order,$goods_field); //商品列表

    foreach ($goods_list as $key => $value) {

      if ($value['goods_images']) {
      
        $goods_list[$key]['goods_images'] = json_decode($value['goods_images'],true)[0]; //取第一张图片
      
      }
    }

    //底部商品列表
    $goods_top_list = (new Goods)->Common_Select(8,17,$where,$order,$goods_field); //商品列表

    $where = ['store_id' => $store['store_id'], 'status' => 0, 'pid' => 0];

    $goods_type_field = ['id','goods_type_name','url'];
    //产品分类
    $goods_type_list = (new GoodsType)->Common_Select($offset,$limit-1,$where,$order,$goods_type_field);

    return json(['code' => 200, 'msg' => '请求成功', 'data' => ['store' => $store, 'goods_top_list' => $goods_top_list, 'activity' => $activity, 'Advertisement' => $Advertisement, 'goods_list' => $goods_list, 'goods_type_list' => $goods_type_list]]);

  }

  //商品详情
  public function detail(){

    $this->title = '商品详情';

    $goods_id = input('id/d');//商品id

    //新增访问数量
    (new Goods)->Common_SetInc('number_of_visits',['id' => $goods_id]);

    $log['pid'] = $goods_id;

    $log['userId'] = $this->userId;

    $log['type'] = 1;

    $log['status'] = 0;

    $BrowsingLog = (new BrowsingLog)->Common_Find($log);

    if (empty($BrowsingLog) && $goods_id) {
      
      $log['createTime'] = time();

      $log['updateTime'] = time();

      //新增浏览记录
      (new BrowsingLog)->Common_Insert($log);
    }

    $goods_detail = (new Goods)->Common_Find(['id' => $goods_id]);

    if ($goods_detail['goods_images']) {
        
      $goods_detail['goods_images'] = json_decode($goods_detail['goods_images'],true);
    }

    $goods_detail['start_time'] = date('Y-m-d H:i:s',$goods_detail['start_time']);

    $goods_detail['end_time'] = date('Y-m-d H:i:s',$goods_detail['end_time']);

    $collection = 0;

    $data = (new CollectionAndCoupons)->Common_Find(['goods_id' => $goods_id , 'userId' => $this->userId, 'status' => 0,'type' => 1]); //是否收藏

    if ($data) {

      $collection = 1; //收藏
    }

    $this->assign('collection',$collection);

    $this->assign('goods_detail',$goods_detail);

    $this->assign('title',$this->title);

    return view();
  }

  //店铺列表
  public function citydian(){

    $this->title = '选择门店';

    $store_id = input('store_id/d') ? : 0;

    $member = (new Member)->Common_Find(['id' => $this->userId]);

    $longitude = $member['longitude'] ? :'123.454688';

    $latitude = $member['latitude'] ? : '41.778517';

    $store_list = (new store)->Common_All_Select(['status' => 1],['juli' => 'ASC'],['store_id','store_name',"ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$latitude} * PI() / 180 - latitude * PI() / 180) / 2),2) + COS({$latitude} * PI() / 180) * COS(latitude * PI() / 180) * POW(SIN(({$longitude} * PI() / 180 - longitude * PI() / 180) / 2),2))),2) AS juli"]);

    foreach ($store_list as $key => $value) {
      
      if ($value['store_id'] == $store_id) {

        $store_list[$key]['class'] = 1;
      
      }else{

        $store_list[$key]['class'] = 0;
      }
    }

    $this->assign('store_list',$store_list);

    $this->assign('title',$this->title);

    return view();
  }

  //店铺详情
  public function city_info(){

    $id = input('id/d'); //店铺id

    $store = (new Store)->Common_Find(['store_id' => $id]);

    $this->assign('store',$store);

    return view();
  }

  //收藏与加入优惠卷
  public function collection_and_coupons_update(){

      $data['goods_id'] = input('post.goods_id/d'); //商品id

      $data['type'] = input('post.type/d'); //1收藏 2优惠券

      if ($data['type'] == 1) {
         
        $msg = '收藏成功';
      
      }else{

        $msg = '领取成功';
      }

      $data['userId'] = $this->userId;

      $collection = (new CollectionAndCoupons)->Common_Find(['goods_id' => $data['goods_id'] , 'userId' => $this->userId, 'status' => 0,'type' => 1]);

      if ($collection && $data['type'] == 1) {

        (new Goods)->Common_SetDec('collection_number',['id' => $data['goods_id']]); //-1
        
        $line = (new CollectionAndCoupons)->Common_Update(['status' => 1],['id' => $collection['id']]);
        return json(['code' => 200 , 'msg' => '取消收藏']);
      }


      $data['createTime'] = time();

      $data['updateTime'] = time();

      if ($data['type'] == 1) {

        (new Goods)->Common_SetInc('collection_number',['id' => $data['goods_id']]); //+1
      
      }

      $line = (new CollectionAndCoupons)->Common_Insert($data);

      if($line)
        return json(['code' => 200 , 'msg' => $msg]);
  }

  //产品分类
  public function category(){

    $type_id = input('type_id/d') ? : 0;

    $where = ['store_id' => $this->store_id, 'status' => 0, 'pid' => 0];

    $GoodsType = (new GoodsType)->Common_All_Select($where, ['id' => 'desc'],['id','goods_type_name','url']); //全部分类

    $Goods = [];

    if ($type_id) {

      $Goods = (new Goods)->Common_All_Select(['type_id' => $type_id, 'state' => 0, 'status' => 0],['id' => 'desc'], ['id','goods_name','goods_images','goods_original_price','goods_present_price']);
    
    }else{

      if ($GoodsType) {
        
        $type_id = $GoodsType['0']['id'];

        $Goods = (new Goods)->Common_All_Select(['type_id' => $GoodsType['0']['id'], 'state' => 0, 'status' => 0],['id' => 'desc'], ['id','goods_name','goods_images','goods_original_price','goods_present_price']);

      }
    }

    if ($Goods) {
      
      foreach ($Goods as $key => $value) {

        if ($value['goods_images']) {
        
          $Goods[$key]['goods_images'] = json_decode($value['goods_images'],true)[0]; //取第一张图片
        
        }
      }
    }

    $this->assign('type_id',$type_id);

    $this->assign('Goods',$Goods);

    $this->assign('GoodsType',$GoodsType);

    return view();
  }


  //底部新发现
  public function xfx(){

    $this->title = '新发现 新生活';

    $NewDiscovery = (new NewDiscovery)->Common_All_Select(['status' => 0,'store_id' => $this->store_id], ['id' => 'desc'],['id','src','url']);

    $this->assign('title',$this->title);

    $this->assign('NewDiscovery',$NewDiscovery);

    return view();
  }

  //底部人气排行
  public function rqph(){

    $this->title = '人气商品排行榜';

    $AdvertisementType = (new AdvertisementType)->Common_Find(['store_id' => $this->store_id, 'status' => 0, 'type_name' => '人气']);

    $Advertisement = (new Advertisement)->Common_All_Select(['store_id' => $this->store_id, 'status' => 0, 'type_id' => $AdvertisementType['id']],['id' => 'desc'],['id','image','url']);

    $offset = 0;

    $limit = 10;

    $where = ['store_id' => $this->store_id, 'status' => 0, 'state' => 0, 'start_time' => ['<=',time()], 'end_time' => ['>=',time()]];

    $order = ['number_of_visits' => 'desc'];

    $goods_field = ['id','goods_name','goods_original_price','goods_present_price','goods_images'];

    $goods_list = (new Goods)->Common_Select($offset,$limit,$where,$order,$goods_field);

    foreach ($goods_list as $key => $value) {
      if ($value['goods_images']) {
        $goods_list[$key]['goods_images'] = json_decode($value['goods_images'],true)[0];
      }
    }

    $this->assign('title',$this->title);

    $this->assign('Advertisement',$Advertisement);

    $this->assign('goods_list',$goods_list);

    return view();
  }

  //推荐爆款列表
  public function tuijian(){

    $this->title = '推荐爆款';

    $AdvertisementType = (new AdvertisementType)->Common_Find(['store_id' => $this->store_id, 'status' => 0, 'type_name' => '爆款']);

    $Advertisement = (new Advertisement)->Common_All_Select(['store_id' => $this->store_id, 'status' => 0, 'type_id' => $AdvertisementType['id']],['id' => 'desc'],['id','image','url']);

    $where = ['store_id' => $this->store_id, 'status' => 0, 'state' => 0, 'sell_well' => 0,'start_time' => ['<=',time()], 'end_time' => ['>=',time()]];

    $order = ['number_of_visits' => 'desc']; //爆款人气排序

    $goods_field = ['id','goods_name','goods_original_price','goods_present_price','goods_images'];

    $goods_list = (new Goods)->Common_All_Select($where,$order,$goods_field); //商品列表

    foreach ($goods_list as $key => $value) {

      if ($value['goods_images']) {
      
        $goods_list[$key]['goods_images'] = json_decode($value['goods_images'],true)[0]; //取第一张图片
      
      }

      $goods_list[$key]['key'] = $key + 1; //排名
    }

    $this->assign('goods_list',$goods_list);

    $this->assign('Advertisement',$Advertisement);

    return view();
  }

  //搜索
  public function sousuo(){

    $goods_name = input('goods_name/s');

    $a = ['goods_name' => ['like',"%{$goods_name}%"], 'state' => 0, 'status' => 0, 'store_id' => $this->store_id];

    $goods = (new Goods)->Common_All_Select(['goods_name' => ['like',"%{$goods_name}%"], 'state' => 0, 'status' => 0, 'store_id' => $this->store_id],['id' => 'desc'],['id','goods_name','goods_original_price','goods_present_price','goods_images']);

    foreach ($goods as $key => $value) {

        if ($value['goods_images']) {
        
          $goods[$key]['goods_images'] = json_decode($value['goods_images'],true)[0]; //取第一张图片
        
        }
      }

    $this->assign('goods',$goods);

    return view();
  }

  //城市
  public function city(){

    $region = (new Region)->Common_All_Select(['fid' => ['>',0]]);

    $this->assign('region',$region);

    return view();
  }

  //渲染模板
  public function diaoyan(){

    $this->title = '调研问卷';

    $id = input('id/d');//调研问卷id

    $this->assign('id',$id);

    $this->assign('title',$this->title);

    return view();
  }

  //新发现调研
  public function ajax_diaoyan_list(){

    $id = input('id/d');//调研问卷id

    $questionnaire = (new Questionnaire)->Common_Find(['id' => $id]);

    $problem = (new Problem)->Common_All_Select(['id' => ['in',$questionnaire['problem_id']]],['id' => 'desc'],['id','type','problem','answer','content']);

    foreach ($problem as $key => $value) {
      
      $problem[$key]['content'] = json_decode($value['content'],true);
    }

    if ($problem)
      return json(['code' => 200, 'msg' => '请求成功', 'data' => $problem]);
      return json(['code' => 400, 'msg' => '请求失败', 'data' => []]);

  }

  //提交答卷
  public function diaoyan_add(){

    $content = input('post.data/a');

    if (empty($content)) {
      return json(['code' => 400, 'msg' => '请答题']);
    }

    $data['content'] = json_encode($content);

    $data['questionnaire_id'] = input('post.questionnaire_id/d');

    $data['create_time'] = time();

    $data['update_time'] = time();

    $add = (new MemberAndQuestionnaire)->Common_Insert($data);

    if ($add)
      return json(['code' => 200, 'msg' => '参与调研成功']);
      return json(['code' => 400, 'msg' => '参与调研失败']);

  }

}
