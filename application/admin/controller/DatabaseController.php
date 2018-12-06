<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 朱亚杰 <zhuyajie@topthink.net>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use admin\model\AuthRule;
use admin\model\AuthGroup;
use think\Db;
use think\Database;
/**
 * 菜单管理控制器
 * @author kjcx
 */
class DatabaseController extends AdminController{

	    /**
     * 数据库备份/还原列表
     * @param  String $type import-还原，export-备份
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index($type = null){
        switch ($type) {
            /* 数据还原 */
            case 'import':
                //列出备份文件列表
                $path = realpath(C('DATA_BACKUP_PATH'));
                $flag = \FilesystemIterator::KEY_AS_FILENAME;
                $glob = new \FilesystemIterator($path,  $flag);

                $list = array();
                foreach ($glob as $name => $file) {
                    if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                        $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                        $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                        $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                        $part = $name[6];

                        if(isset($list["{$date} {$time}"])){
                            $info = $list["{$date} {$time}"];
                            $info['part'] = max($info['part'], $part);
                            $info['size'] = $info['size'] + $file->getSize();
                        } else {
                            $info['part'] = $part;
                            $info['size'] = $file->getSize();
                        }
                        $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                        $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                        $info['time']     = strtotime("{$date} {$time}");

                        $list["{$date} {$time}"] = $info;
                    }
                }
                $title = '数据还原';
                break;

            /* 数据备份 */
            case 'export':

                $list  = Db::query('SHOW TABLE STATUS');
                $list  = array_map('array_change_key_case', $list);
                foreach ($list as $key => $value) {
                	$list[$key]['data_length'] = $this->format_bytes($list[$key]['data_length']);
                }
                $title = '数据备份';
                break;

            default:
                $this->error('参数错误！');
        }

        //渲染模板
        //$this->assign('meta_title', $title);

       // $this->assign('list', $list);
        return json(['code' => 0 , 'msg' => '' , 'data' => $list , 'count' => 100]);
       // $this->display($type);
    }

    public function format_bytes($size, $delimiter = '') {
	    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
	    return round($size, 2) . $delimiter . $units[$i];
	}

    public function database_export(){

    	return view();
    }

    /**
     * 备份数据库
     * @param  String  $tables 表名
     * @param  Integer $id     表ID
     * @param  Integer $start  起始行数
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function export($tables = null, $id = null, $start = null){
        if($_POST && !empty($tables) && is_array($tables)){ //初始化
            //读取备份配置
            $config = array(
                'path'     => realpath(config('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR,
                'part'     => '20971520', //该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M 数据库备份卷大小
                'compress' => 1,//启用压缩
                'level'    => 9,//压缩级别1普通4一般9最高,
            );

            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";

            if(is_file($lock)){
                $this->error('检测到有一个备份任务正在执行，请稍后再试！');
            } else {
                //创建锁文件
                file_put_contents($lock, time());
            }

            //检查备份目录是否可写
            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');
            session('backup_config', $config);

            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', time()),
                'part' => 1,
            );
            session('backup_file', $file);

            //缓存要备份的表
            session('backup_tables', $tables);

            //创建备份文件
            $Database = new Database($file, $config);
            if(false !== $Database->create()){
                $tab = array('id' => 0, 'start' => 0);
                //$this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
                return json(['code' => 1 , 'data' => ['tables' => $tables , 'tab' => $tab] , 'msg' => '初始化成功！']);
            } else {
                // $this->error('');
                return json(['msg' => '初始化失败，备份文件创建失败！']);
            }
        } elseif ($_GET && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = session('backup_tables');
            //echo '<pre>';print_r($tables);exit;
            //备份指定表
            $Database = new Database(session('backup_file'), session('backup_config'));
            $start  = $Database->backup($tables[$id], $start);
            if(false === $start){ //出错
                $this->error('备份出错！');
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $tab = array('id' => $id, 'start' => 0);
                    //$this->success('备份完成！', '', array('tab' => $tab));
                    return json(['msg' => '备份完成' , 'tab' => $tab , 'code' => 1]);
                } else { //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_file', null);
                    session('backup_config', null);
                    return json(['msg' => '备份完成','code' => 1]);
                }
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
                //$this->success("", '', array('tab' => $tab));
                return json(['code' => 1 , 'msg' => "正在备份...({$rate}%)" , 'tab' => $tab ]);
            }

        } else { //出错
            return json(['msg' => '备份失败']);
        }
    }
}