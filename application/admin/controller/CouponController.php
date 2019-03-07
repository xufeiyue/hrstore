<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/3
 * Time: 11:01
 */
namespace app\admin\controller;
use app\admin\model\Coupon;
use app\admin\model\CouponType;
use think\Controller;
use think\Env;
use think\Request;
use app\admin\model\Member;
class CouponController extends Controller
{
    private $coupon;
    private $coupon_type;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->coupon_type = new CouponType();
        $this->coupon = new Coupon();

    }

    // 渲染模板
    public function index()
    {
        return view('coupon_type_list');
    }

    // 卡券类型列表
    public function coupon_type_list()
    {
        $ticket_name = input('post.ticket_name/s');

        $start_time = strtotime(input('post.start_time'));

        $end_time = strtotime(input('post.end_time'));

        if((Request::instance()->post('start_time') !='') && (Request::instance()->post('end_time') !='')){

            $where['end_time'] = array('between',"$start_time,$end_time");
        }

        if($ticket_name){

            $where['ticket_name']  = ['like',"%{$ticket_name}%"];
        }

        $where['status'] = 1;

        $offset = (input('post.page/d') - 1) * input('post.limit/d') ?: 0;

        $limit = input('post.limit/d') ?: 10;

        $order = ['card_type_id' => 'desc'];

        $data = $this->coupon_type->get_type_list($offset, $limit, $where, $order, "*,FROM_UNIXTIME(create_time)create_time,
        FROM_UNIXTIME(start_time,'%Y-%m-%d')start_time,FROM_UNIXTIME(end_time,'%Y-%m-%d')end_time,CONCAT(`face_value`,'元')face_value");

        return json(["code" => 0, "msg" => "请求成功", 'data' => $data['data'], 'count' => $data['count']]);

    }

    // 增加卡券类型
    public function add_coupon_type()
    {
        if (Request::instance()->post()) {
            $whether = ['on','off'];
            if('on' == $whether[0]){
                $data['xianshi'] = 0;
            }else{
                $data['xianshi'] = 1;
            }
            $data['instructions'] = input('post.instructions');
            $data['card_ticket_type_img'] = input('post.card_ticket_type_img');
            $data['start_time'] = strtotime(input('post.start_time'));
            $data['end_time'] = strtotime(input('post.end_time'));
            $data['scope_of_application'] = input('post.scope_of_application');
            $data['ticket_name'] = input('post.ticket_name');
            $data['end_time_desc'] = input('post.end_time');
            $data['face_value'] = input('post.face_value');
            $data['ticket_type'] = input('post.ticket_type');
            $data['is_use'] = 2;
            $data['create_time'] = time();
            // 判断是否为品类券
            if($data['ticket_type'] == 3){
                // 向th_card_ticket表插入一条记录
                $data['max_value'] = input('post.max_value');
                $data['small_value'] = input('post.small_value');
                $data1['barcode'] = input('post.coupon_code');
                //$data1['goods_id'] = input('post.goods_id');
                $res = $this->coupon_type->add_pinlei_coupon($data,$data1);
                if ($res) {
                    return json(['code' => 1, 'msg' => '添加成功']);
                } else {
                    return json(['code' => 2, 'msg' => '添加失败']);
                }
            }else{
                $add = $this->coupon_type->Common_Insert($data);
                if ($add) {
                    return json(['code' => 1, 'msg' => '添加成功']);
                } else {
                    return json(['code' => 2, 'msg' => '添加失败']);
                }
            }



        } else {
            return view();
        }
    }


    // 修改卡券类型

    public function edit_coupon_type(){
        if (Request::instance()->post()) {
            $whether = ['on','off'];
            if($xianshi == $whether[0]){
                $data['xianshi'] = 0;
            }else{
                $data['xianshi'] = 1;
            }
            $card_type_id = input('post.card_type_id');
            $data['instructions'] = input('post.instructions');
            $data['card_ticket_type_img'] = input('post.card_ticket_type_img');
            $data['start_time'] = strtotime(input('post.start_time'));
            $data['end_time'] = strtotime(input('post.end_time'));
            $data['scope_of_application'] = input('post.scope_of_application');
            $data['ticket_name'] = input('post.ticket_name');
            $data['end_time_desc'] = input('post.end_time');
            $data['face_value'] = input('post.face_value');
            $data['ticket_type'] = input('post.ticket_type');
            $data['create_time'] = time();
            $add = $this->coupon_type->Common_Update($data,array('card_type_id'=>$card_type_id));
            if ($add) {
                return json(['code' => 1, 'msg' => '修改成功']);
            } else {
                return json(['code' => 2, 'msg' => '修改失败']);
            }
        } else {
            $card_type_id = input('get.card_type_id');
            $info = $this->coupon_type->Common_Find(array('card_type_id'=>$card_type_id));
            $this->assign('info',$info);
            return view();
        }
    }

    // 删除卡券分类
    public function del_coupon_type(){
        $card_type_id = Request::instance()->param('card_type_id');
        $data['status'] = '2';
        $add = $this->coupon_type->Common_Update($data,array('card_type_id'=>$card_type_id));
        if($add){
            return json(['code' => 1 , 'msg' => '删除成功']);
        }else{
            return json(['code' => 2 , 'msg' => '删除失败']);
        }
    }
    // 添加卡券
    public function add_coupon()
    {
        if (Request::instance()->post()) {

            $data['card_type_id'] = input('post.card_type_id');
            $data['goods_id'] = input('post.goods_id');
            $data['create_time'] = time();
            $file_url = input('post.file_url');

            // 判断选中卡券类型是不是全场券
            $ticket_type = $this->coupon_type->Common_Find(array('card_type_id'=>$data['card_type_id']));
            if($ticket_type['ticket_type'] == 1){
                // 全场券不用绑定goods_id
                // 更新card_ticket_type表 goods_id
                $data['goods_id'] = 0;
                $this->coupon_type->Common_Update(array('is_use'=>2),array('card_type_id'=>$data['card_type_id']));
            }else if ($ticket_type['ticket_type'] == 2){
                // 更新card_ticket_type表 goods_id
                $this->coupon_type->Common_Update(array('goods_id'=>$data['goods_id'],'is_use'=>2),array('card_type_id'=>$data['card_type_id']));

            }else{
                return json(['code' => 2, 'msg' => '添加失败']);
            }

            $file_url=str_replace("\\","/",$file_url);
            $excel_datas = $this->read_excel($file_url); //获取表中数据
            if($this->coupon->addCards($data,$excel_datas)){
                return json(['code' => 1, 'msg' => '添加成功']);
            }else{
                return json(['code' => 2, 'msg' => '添加失败']);
            }


        } else {
            // 获取卡券类型列表
            $where['status'] = 1;
            $coupon_type_list = $this->coupon_type->Common_All_Select($where);
            $this->assign('coupon_type_list', $coupon_type_list);
            return view();
        }
    }



    // 渲染搜索商品列表
    public function search_goods()
    {
        return view();
    }

    function read_excel($filename)
    {
        //设置excel格式

        $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        //载入excel文件
        $excel = $reader->load($filename);
        //读取第一张表
        $sheet = $excel->getSheet(0);
        //获取总行数
        $row_num = $sheet->getHighestRow();
        //获取总列数
        $col_num = $sheet->getHighestColumn();

        $data = []; //数组形式获取表格数据
        for ($col = 'A'; $col <= $col_num; $col++) {
            //从第二行开始，去除表头（若无表头则从第一行开始）
            for ($row = 2; $row <= $row_num; $row++) {
                $data[$row - 2][] = $sheet->getCell($col . $row)->getValue();
            }
        }
        return $data;
    }


    public function download()
    {
        //$famlePath = $_GET['resum'];
        $file_dir = ROOT_PATH . 'public' . DS . 'uploads' . '/' . "卡券模板.xlsx";    // 下载文件存放目录

        // 检查文件是否存在
        if (!file_exists($file_dir)) {
            $this->error('文件未找到');
        } else {
            // 打开文件
            $file1 = fopen($file_dir, "r");
            // 输入文件标签
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length:" . filesize($file_dir));
            Header("Content-Disposition: attachment;filename=卡券模板.xlsx");
            ob_clean();     // 重点！！！
            flush();        // 重点！！！！可以清除文件中多余的路径名以及解决乱码的问题：
            //输出文件内容
            //读取文件内容并直接输出到浏览器
            echo fread($file1, filesize($file_dir));
            fclose($file1);
            exit();
        }
    }

    //改变状态
    public function coupon_xianshi_update(){

        $id = array_unique(input('post.id/a'));

        $xianshi = input('post.xianshi/d');

        $edit = $this->coupon_type->Common_Update(['xianshi' => $xianshi],['card_type_id' => ['in', $id]]);

        if($edit)
            return json(['code' => 200 , 'msg' => '操作成功']);
            return json(['code' => 400 , 'msg' => '操作失败']);
    }
}