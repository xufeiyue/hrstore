<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Store;
use app\home\model\Goods;
use app\home\model\GoodsType;
class IndexController extends CommonController
{

	/**
	 * [index 首页]
	 */
	public function index(){

    $Store = (new Store)->Common_Find(['store_id' => 1]); // 先写死

    $where['store_id'] = $Store['store_id'];

    $where['status'] = 0;

    $offset = 0;

    $limit = 8;

    $order = ['id' => 'desc'];

    $goods_field = ['id','goods_name','goods_original_price','goods_present_price','goods_images'];

    $goods_list = (new Goods)->Common_Select($where,$offset,$limit,$order,$goods_field); //商品列表

    foreach ($goods_list as $key => $value) {

      if ($value['goods_images']) {
      
        $goods_list[$key]['goods_images'] = json_decode($value['goods_images'],true)[0]; //取第一张图片
      
      }
    }

    $where['pid'] = ['>',0];

    $goods_type_field = ['id','goods_type_name','url'];
    //产品分类
    $goods_type_list = (new GoodsType)->Common_Select($where,$offset,$limit-1,$order,$goods_type_field); //商品列表

    $this->assign('goods_list',$goods_list);

    $this->assign('goods_type_list',$goods_type_list);

    return view();
  }

  //商品详情
  public function detail(){

    $id = input('id/d'); //商品id

    return view();
  }
}
