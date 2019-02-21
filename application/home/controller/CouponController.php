<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/13
 * Time: 1:23
 */
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
use think\Db;
use think\Loader;

class CouponController extends CommonController
{
    public function coupon_list(){
        // 获取所有平台优惠券
        $coupon_type_model = new CouponType();
        $time = time();
        $where_pt['end_time'] = array('>',$time);
        $where_pt['is_use'] = 2;
        $where_pt['ticket_type'] = 1;
        $where_pt['card_type_id'] = array('not in','30,31'); // 不显示新人注册券和完善资料券
        $field = [];
        $order=[];
        $coupon_type_pt_list = $coupon_type_model->Common_All_Select($where_pt,$order,$field);
        if(!empty($coupon_type_pt_list)){
            foreach($coupon_type_pt_list as $k_p=>$v_p){
                $n = (new Coupon())->getNum(array('card_type_id'=>$v_p['card_type_id'],'status'=>2));
                if($n>0){
                    $coupon_type_pt_list[$k_p]['num_status']= 1; // 有剩余
                }else{
                    $coupon_type_pt_list[$k_p]['num_status']= 2; // 已领完
                }
            }

        }

        // 获取所有单品券
        $where_dp['t1.end_time'] = array('>',$time);
        $where_dp['t1.is_use'] = 2;
        $where_dp['t1.ticket_type'] = 2;
        $field = ['t1.*','g.goods_images'];
        $coupon_type_dp_list = $coupon_type_model->get_coupon_type_dp_list($where_dp,$field);
        if(!empty($coupon_type_dp_list)){
            foreach($coupon_type_dp_list as $key=>$val){
                $coupon_type_dp_list[$key]['goods_img'] = json_decode($val['goods_images'],true)[0];
                $n = (new Coupon())->getNum(array('card_type_id'=>$val['card_type_id'],'status'=>2));
                if($n>0){
                    $coupon_type_dp_list[$key]['num_status']= 1; // 有剩余
                }else{
                    $coupon_type_dp_list[$key]['num_status']= 2; // 已领完
                }
            }

        }

        $this->assign('coupon_type_pt_list',$coupon_type_pt_list);
        $this->assign('coupon_type_dp_list',$coupon_type_dp_list);
        return view();
    }

    public function coupon_type_info(){
        $card_type_id = input('card_type_id/d');
        $card_ticket_id = input('card_ticket_id/d');
        $coupon_type_model = new CouponType();
        $w['a_l.card_type_id'] = $card_type_id;
        $field = ['a_l.*'];
        $info = $coupon_type_model->getInfo($w,$field);
        $this->assign('info',$info);
        $this->assign('card_ticket_id',$card_ticket_id);
        // 获取此条码是否被使用
        $card_ticket_model = new Coupon();
        $card_ticket_info = $card_ticket_model->Common_Find(array('card_ticket_id'=>$card_ticket_id));
        $check_status = $card_ticket_model->get_member_card_ticket_relation_info(array('card_ticket_id'=>$card_ticket_id));
        $this->assign('check_status',$check_status['status']);
        $this->assign('card_ticket_info',$card_ticket_info);
        return view();
    }
    // 条形码接口
    public function barcode(){
        $content= input('barcode');
        // 引用barcode文件夹对应的类

        Loader::import('barcode.class.BCGFontFile',EXTEND_PATH);
        Loader::import('barcode.class.BCGColor',EXTEND_PATH);
        Loader::import('barcode.class.BCGDrawing',EXTEND_PATH);
        // 条形码的编码格式
        Loader::import('barcode.class.BCGcode128',EXTEND_PATH,'.barcode.php');
        // $code = '';
        // 加载字体大小
        //$font = new \BCGFont('/Arial.ttf', 20);
        //颜色条形码
        $color_black = new \BCGColor(0, 0, 0);
        $color_white = new \BCGColor(255, 255, 255);

        //$drawException = null;
        //try
        //{
            $code = new \BCGcode128();
            $code->setScale(2);
            $code->setThickness(80); // 条形码的厚度
            $code->setForegroundColor($color_black); // 条形码颜色
            $code->setBackgroundColor($color_white); // 空白间隙颜色
             //$code->setFont($font); //
            $code->parse($content); // 条形码需要的数据内容
        //}
        //catch(\Exception $exception)
        //{
            //$drawException = $exception;
        //}

        //根据以上条件绘制条形码
        $drawing = new \BCGDrawing('', $color_white);
        //if($drawException) {
            //$drawing->drawException($drawException);
        //}else{
            $drawing->setBarcode($code);
            $drawing->draw();
        //}

        // 生成PNG格式的图片
        header('Content-Type: image/png');
        $drawing->finish(\BCGDrawing::IMG_FORMAT_PNG);
        // header('Content-Disposition:attachment; filename="barcode.png"'); //自动下载
    }

    // 领取卡券接口
    public function receive_coupon(){
        $goods_id = input('goods_id');
        $data['member_id'] = $this->userId;

        // 根据goods_id 分配 card_ticket_id
        $card_ticket_model = new Coupon();
        $card_ticket_info = $card_ticket_model->Common_Find(array('goods_id'=>$goods_id,'status'=>2));
        $data['card_ticket_id'] = $card_ticket_info['card_ticket_id'];
        $data['status'] = 1;
        $data['create_time'] = time();
        $res = $card_ticket_model->get_coupon($data,array('card_ticket_id'=>$card_ticket_info['card_ticket_id']),array('status'=>1));

        if($res){
            return json(['code' => 200 , 'msg' => '领取成功']);
        }else{
            return json(['code' => 100 , 'msg' => '领取失败']);
        }
    }

    public function receive_type_coupon(){
        $card_type_id = input('card_type_id');
        $data['member_id'] = $this->userId;
        // 根据card_type_id分配 card_ticket_id
        $card_ticket_model = new Coupon();
        $card_ticket = $card_ticket_model->Common_Find(array('card_type_id'=>$card_type_id,'status'=>2));
        $data['card_ticket_id'] = $card_ticket['card_ticket_id'];
        $data['status'] = 1;
        $data['create_time'] = time();
        $res = $card_ticket_model->get_coupon($data,array('card_ticket_id'=>$card_ticket['card_ticket_id']),array('status'=>1));

        if($res){
            return json(['code' => 200 , 'msg' => '领取成功']);
        }else{
            return json(['code' => 100 , 'msg' => '领取失败']);
        }
    }

    public function use_coupon(){
        $w['card_ticket_id'] = input('card_ticket_id/d');
        $w['member_id'] = $this->userId;
        $data['status'] = 2;
        $data['use_time'] = time();
        $coupon_model = new Coupon();
        $res = $coupon_model->use_coupon($w,$data);
        if($res){
            return json(['code' => 200 , 'msg' => '使用成功']);
        }else{
            return json(['code' => 100 , 'msg' => '使用失败']);
        }
    }

    // 首次登陆领取红包
    public function get_receive_coupon(){

        $card_type_id = 30;
        $data['member_id'] = $this->userId;
        // 根据card_type_id分配 card_ticket_id
        $card_ticket_model = new Coupon();
        $card_ticket = $card_ticket_model->Common_Find(array('card_type_id'=>$card_type_id,'status'=>2));
        if(!empty($card_ticket)){
            $data['card_ticket_id'] = $card_ticket['card_ticket_id'];
            $data['status'] = 1;
            $data['create_time'] = time();
            $res = $card_ticket_model->get_coupon($data,array('card_ticket_id'=>$card_ticket['card_ticket_id']),array('status'=>1));
            if($res){
                return json(['code' => 200 , 'msg' => '领取成功']);
            }else{
                return json(['code' => 100 , 'msg' => '领取失败']);
            }
        }else{
            return json(['code' => 300 , 'msg' => '领取失败']);
        }

    }
}
