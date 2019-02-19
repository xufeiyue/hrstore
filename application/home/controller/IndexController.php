<?php
namespace app\home\controller;
use app\home\model\Coupon;
use app\home\model\CouponType;
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
use think\Loader;

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

    // 判断当前用户是否领取过新人红包

        $coupon_model = new CouponType();

        $w['mr.member_id'] = $this->userId;
        $w['ctt.card_type_id'] = 30;
        $c = $coupon_model->getRegCoupon($w);
        if(empty($c)){
            $this->assign('rec_key',1);
        }else{
            $this->assign('rec_key',2);
        }

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

      session('expiration_time',time());
    }

    $activity = (new Activity)->Common_Find(['banner' => 0, 'store_id' => $store['store_id']]); //轮播活动

    $AdvertisementType = (new AdvertisementType)->Common_Find(['status' => 0, 'type_name' => '首页']);

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
          $arr = explode('.',$value['goods_present_price']);
          $goods_list[$key]['goods_price1'] = $arr[0];
          $goods_list[$key]['goods_price2'] = '.'.$arr[1];
      }
    }

    //底部商品列表
    $goods_top_list = (new Goods)->Common_Select(8,17,$where,$order,$goods_field); //商品列表

    $where = ['g.store_id' => ['in',"0,{$store['store_id']}"], 'g.status' => 0, 'g.pid' => 0];

    $goods_type_field = ['g.id','g.goods_type_name','g.url','COALESCE(s.id,0)recommend_type','COALESCE(t.sort,0)sort'];
    //产品分类
    $goods_type_list = (new GoodsType)->recommend_type($offset,$limit-1,$where,['sort' => 'ASC','recommend_type' => 'desc','g.id' => 'desc'],$goods_type_field,$store['store_id']);

    // 获取

    return json(['code' => 200, 'msg' => '请求成功', 'data' => ['store' => $store, 'goods_top_list' => $goods_top_list, 'activity' => $activity, 'advertisement' => $Advertisement, 'goods_list' => $goods_list, 'goods_type_list' => $goods_type_list]]);

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

    $goods_detail['start_time'] = date('Y-m-d',$goods_detail['start_time']);

    $goods_detail['end_time'] = date('Y-m-d',$goods_detail['end_time']);

      $arr = explode('.',$goods_detail['goods_present_price']);
      $goods_detail['price1'] = $arr[0];
      $goods_detail['price2'] = '.'.$arr[1];


    $collection = 0;

    $data = (new CollectionAndCoupons)->Common_Find(['goods_id' => $goods_id , 'userId' => $this->userId, 'status' => 0,'type' => 1]); //是否收藏

    if ($data) {

      $collection = 1; //收藏
    }

    // 获取商品优惠券

      $coupon_type_model = new CouponType();

    $coupon_type_info = $coupon_type_model->Common_Find(array('goods_id'=>$goods_id));

      $goods_detail['face_value'] = $coupon_type_info['face_value'];

      $goods_detail['card_type_id'] = $coupon_type_info['card_type_id'];

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

    Loader::import('character.Character',EXTEND_PATH);

    $store_list = (new \Character())->groupByInitials($store_list,'store_name');
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

    $where = ['g.store_id' => ['in',"0,{$this->store_id}"], 'g.status' => 0, 'g.pid' => 0];

    $goods_type_field = ['g.id','g.goods_type_name','g.url','COALESCE(s.id,0)recommend_type','COALESCE(t.sort,0)sort'];

    $GoodsType = (new GoodsType)->goods_type_all($where, ['recommend_type' => 'desc', 'sort' => 'ASC','g.id' => 'desc'],$goods_type_field,$this->store_id); //全部分类

    $Goods = [];

    if ($type_id) {

      if($GoodsType){
          // 判断当前分类pid是否为0，如果为0，显示当前分类下的二级分类，如果不为0，取出当前分类的pid，再取出当前二级分类
          $this_pid = (new GoodsType())->Common_Find(array('id'=>$type_id));
          if($this_pid['pid'] == 0){
              //遍历该分类下的所有商品
              $Goods = (new Goods)->getchildgoods($type_id,$this->store_id);
              // 取指定分类的二级分类
              $where_type_last = array('pid'=>$type_id,'status'=>0);

              $last_type_list =  (new GoodsType())->Common_All_Select($where_type_last,['id' => 'desc'],['id','goods_type_name','url']);

              if($last_type_list){
                  $two_type_id = $last_type_list[0]['id'];
                  $this->assign('two_type_id',$two_type_id);
              }

          }else{
              // 取指定分类的二级分类
              $where_type_last = array('pid'=>$this_pid['pid'],'status'=>0);

              $last_type_list =  (new GoodsType())->Common_All_Select($where_type_last,['id' => 'desc'],['id','goods_type_name','url']);

              $two_type_id = $type_id;

              $type_id = $this_pid['pid'];
              $this->assign('two_type_id',$two_type_id);

          }

      }

    }else{

      if ($GoodsType) {
        
        $type_id = $GoodsType['0']['id'];

          $Goods = (new Goods)->getchildgoods($type_id,$this->store_id);

          // 取最后一个分类的二级分类
          $where_type_last = array('pid'=>$type_id,'status'=>0);

          $last_type_list =  (new GoodsType())->Common_All_Select($where_type_last,['id' => 'desc'],['id','goods_type_name','url']);

          if($last_type_list){
              $two_type_id = $last_type_list[0]['id'];
              $this->assign('two_type_id',$two_type_id);
          }
      }
      $this->assign('type_id',$type_id);
      $this->assign('tk',1);
    }

    if ($Goods) {
      
      foreach ($Goods as $key => $value) {

        if ($value['goods_images']) {
        
          $Goods[$key]['goods_images'] = json_decode($value['goods_images'],true)[0]; //取第一张图片
            $str = explode('.',$Goods[$key]['goods_present_price']);
            $Goods[$key]['goods_present_price1'] = $str[0];
            $Goods[$key]['goods_present_price2'] = $str[1];
        
        }
      }
    }
    $this->assign('type_id',$type_id);

    $this->assign('Goods',$Goods);

    $this->assign('GoodsType',$GoodsType);
    $this->assign('last_type_list',$last_type_list);

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

    $AdvertisementType = (new AdvertisementType)->Common_Find(['status' => 0, 'type_name' => '人气']);

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
        $arr = explode('.',$value['goods_present_price']);
        $goods_list[$key]['price1'] = $arr[0];
        $goods_list[$key]['price2'] = '.'.$arr[1];
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

    $AdvertisementType = (new AdvertisementType)->Common_Find(['status' => 0, 'type_name' => '爆款']);

    $Advertisement = (new Advertisement)->Common_All_Select(['store_id' => $this->store_id, 'status' => 0, 'type_id' => $AdvertisementType['id']],['id' => 'desc'],['id','image','url']);

    $where = ['store_id' => $this->store_id, 'status' => 0, 'state' => 0, 'sell_well' => 0,'start_time' => ['<=',time()], 'end_time' => ['>=',time()]];

    $order = ['number_of_visits' => 'desc','']; //爆款人气排序

    $goods_field = ['id','goods_name','goods_original_price','goods_present_price','goods_images'];
    //print_r($where);exit;
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

   $content = input('post.data/a'); //问题数组

   $arr = [];

   $arr1 = [];

   $c = ['A' => 0,'B' => 1,'C' => 2,'D' => 3];

   foreach ($content as $key => $value) {
      
     $arr[$value['problem_id']][] = $value; //按问题id分组
   }

   foreach ($arr as $key => $value) {
      
     foreach ($value as $key1 => $value1) {

       $arr1[$key]['problem_id'] = $value1['problem_id']; //问题id

       if ($value1['type'] == 2) {

         $arr1[$key]['answer'] = $value1['answer'];
       
       }else{
          
         if ($value1['answer']) {

            $arr1[$key]['answer'][$c[$value1['answer']]] = $value1['answer']; //答案格式要与后台录入一致 好解
            
            (new Problem)->Common_SetInc($value1['answer'],['id' => $value1['problem_id']]); //选择这个答案的次数+1
          } 
       
       }
        
     }
   }

   $problem = [];
   //重组数组
   foreach ($arr1 as $key => $value) {
      
     $problem[] = $value;
   
    }

    if (empty($problem)) {
      return json(['code' => 400, 'msg' => '请答题']);
    }

    $data['content'] = json_encode($problem);

    $data['questionnaire_id'] = input('post.questionnaire_id/d');

    $data['create_time'] = time();

    $data['update_time'] = time();

    $data['userId'] = $this->userId;

    $data['store_id'] = $this->store_id;

    $add = (new MemberAndQuestionnaire)->Common_Insert($data);

    if ($add)
      return json(['code' => 200, 'msg' => '参与调研成功']);
      return json(['code' => 400, 'msg' => '参与调研失败']);

  }


}
