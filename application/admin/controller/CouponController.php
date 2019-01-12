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

        $store_name = input('post.store_name/s');

        $where = [];

        if ($store_name) {

            $where['store_name'] = ['like', "%{$store_name}%"];
        }

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
            $data['instructions'] = input('post.instructions');
            $data['start_time'] = strtotime(input('post.start_time'));
            $data['end_time'] = strtotime(input('post.end_time'));
            $data['scope_of_application'] = input('post.scope_of_application');
            $data['ticket_name'] = input('post.ticket_name');
            $data['end_time_desc'] = input('post.end_time_desc');
            $data['reserve'] = input('post.reserve');
            $data['face_value'] = input('post.face_value');
            $data['ticket_type'] = input('post.ticket_type');
            $data['end_time_desc'] = input('post.end_time');
            $data['create_time'] = time();
            $add = $this->coupon_type->Common_Insert($data);
            if ($add) {
                return json(['code' => 1, 'msg' => '添加成功']);
            } else {
                return json(['code' => 2, 'msg' => '添加失败']);
            }
        } else {
            return view();
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
            $file_url=str_replace("\\","/",$file_url);
            $excel_datas = $this->read_excel($file_url); //获取表中数据
            if($this->coupon->addCards($data,$excel_datas)){
                return json(['code' => 1, 'msg' => '添加成功']);
            }else{
                return json(['code' => 2, 'msg' => '添加失败']);
            }

        } else {
            // 获取卡券类型列表
            $coupon_type_list = $this->coupon_type->Common_All_Select();
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
}