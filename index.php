<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 检测是否是新安装
if(file_exists("./install") && !file_exists("./install/install.lock")){
	// 组装安装url
	$url=$_SERVER['HTTP_HOST'].trim($_SERVER['SCRIPT_NAME'],'index.php').'install/index.php';
	// 使用http://域名方式访问；避免./install 路径方式的兼容性和其他出错问题
	header("Location:http://$url");die;
}

// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
define('LOG_PATH', __DIR__ . '/log/');
// 定义应用缓存目录
define('RUNTIME_PATH', __DIR__ . '/runtime/');
// 开启调试模式
// define('APP_DEBUG', true);
// 加载框架引导文件

require __DIR__ . '/thinkphp/start.php';

\think\Log::init([
    'type'  =>  'File',
    'path'  =>  LOG_PATH,
    'level' => ['error']
]);