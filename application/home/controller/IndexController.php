<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Store;
use app\home\model\Goods;
use app\home\model\GoodsType;
use app\home\model\Activity;
class IndexController extends CommonController
{
  public $title;
	/**
	 * [index 首页]
	 */
	public function index(){

    $this->title = '首页';

    $store = (new Store)->Common_Find(['store_id' => 1]); // 先写死

    //轮播图


    $where = ['store_id' => $store['store_id'], 'status' => 0, 'sell_well' => 0];

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

    $where = ['store_id' => $store['store_id'], 'status' => 0, 'pid' => 0];

    $goods_type_field = ['id','goods_type_name','url'];
    //产品分类
    $goods_type_list = (new GoodsType)->Common_Select($offset,$limit-1,$where,$order,$goods_type_field); 

    $this->assign('goods_list',$goods_list);

    $this->assign('goods_type_list',$goods_type_list);

    $this->assign('title',$this->title);

    return view();
  }

  //商品详情
  public function detail(){

    $this->title = '商品详情';

    $id = input('id/d'); //商品id

    $this->assign('title',$this->title);

    return view();
  }

  //店铺列表
  public function cityDian(){

    $this->title = '选择门店12';

    $this->assign('title',$this->title);

    return view();
  }
}
