<?php
//配置文件
return [
	// 视图输出字符串内容替换
	'view_replace_str'       => [
        '__ROOT__'      =>  '',
        '__PUBLIC__'    =>  '/public/',
        '__STATIC__'    =>  '/static/home',
        '__JS__'    =>  '/static/home/js',
        '__CSS__'    =>  '/static/home/css',
        '__IMG__'    =>  '/static/home/img',
        '__IMAGES__'    =>  '/static/home/images',
    ],
	  // 控制器类后缀
    'controller_suffix'      => true,
    'ADMIN_MENU_LIST'      =>false,//缓存菜单
];