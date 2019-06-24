<?php 


// 定义根目录
define('BASE_PATH', dirname(__FILE__) . '/../');
// 定义项目目录
define('APP', BASE_PATH . 'app/');
// 定时时间
date_default_timezone_set('PRC');
// 报告 E_NOTICE 之外的所有错误
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors','On');

// 加载核心文件
require BASE_PATH . 'bootstrap.php';

// Autoload 自动载入
require BASE_PATH . '/vendor/autoload.php';

require APP . '/function.php';

spl_autoload_register('bootstrap::autoload');

// 加载路由配置
require BASE_PATH . '/config/routes.php';

bootstrap::run();