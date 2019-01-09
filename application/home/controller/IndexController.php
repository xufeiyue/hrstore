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
class IndexController extends CommonController
{
  public $title;
	/**
	 * [index 首页]
	 */
	public function index(){

    $this->title = '首页';

    $store_id = input('store_id/d');

    $where = [];

    if ($store_id) {
      
      $where = ['store_id' => $store_id];
    }

    $longitude = '123.454688';

    $latitude = '41.778517';


    $store = (new Store)->Common_Find($where,['juli' => 'ASC'],['store_id','store_name',"ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$latitude} * PI() / 180 - latitude * PI() / 180) / 2),2) + COS({$latitude} * PI() / 180) * COS(latitude * PI() / 180) * POW(SIN(({$longitude} * PI() / 180 - longitude * PI() / 180) / 2),2))),2) AS juli"]); // 根据经纬度查询最近的一家门店 距离Km

    $activity = (new Activity)->Common_Find(['banner' => 1]); //轮播活动

    // $banner_list = (new Goods)->Common_All_Select(['activity_id' => $activity['id'], 'status' => 0, 'state' => 0], ['id' => 'desc'],['id','goods_images']);//轮播图

    // foreach ($banner_list as $key => $value) {
      
    //   if ($value['goods_images']) {

    //     $banner_list[$key]['goods_images'] = json_decode($value['goods_images'],true)[0];
    //   }
    // }

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

    $this->assign('store',$store);

    $this->assign('goods_top_list',$goods_top_list);

    $this->assign('activity',$activity);

    $this->assign('Advertisement',$Advertisement);

    $this->assign('goods_list',$goods_list);

    $this->assign('goods_type_list',$goods_type_list);

    $this->assign('title',$this->title);

    return view();
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

    $data = (new CollectionAndCoupons)->Common_Find(['goods_id' => $goods_id , 'userId' => 1, 'status' => 0,'type' => 1]); //是否收藏

    if ($data) {

      $collection = 1; //收藏
    }

    $this->assign('collection',$collection);

    $this->assign('goods_detail',$goods_detail);

    $this->assign('title',$this->title);

    return view();
  }

  //店铺列表
  public function cityDian(){

    $this->title = '选择门店';

    $longitude = '123.454688';

    $latitude = '41.778517';

    $store_list = (new store)->Common_All_Select(['status' => 1],['store_id' => 'desc'],['store_id','store_name',"ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$latitude} * PI() / 180 - latitude * PI() / 180) / 2),2) + COS({$latitude} * PI() / 180) * COS(latitude * PI() / 180) * POW(SIN(({$longitude} * PI() / 180 - longitude * PI() / 180) / 2),2))),2) AS juli"]);

    foreach ($store_list as $key => $value) {
      
      if ($key == 0) {

        $store_list[$key]['class'] = 1;
      
      }else{

        $store_list[$key]['class'] = 0;
      }
    }

    $this->assign('store_list',$store_list);

    $this->assign('title',$this->title);

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

      $data['userId'] = 1;

      $collection = (new CollectionAndCoupons)->Common_Find(['goods_id' => $data['goods_id'] , 'userId' => 1, 'status' => 0,'type' => 1]);

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
}
