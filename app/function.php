<?php 

// 数据库操作
function M($table='null') {
  static $_dbObj;
  if(!$_dbObj){
      $_dbObj=new \Yob\Mysql(config('mysql'));
  }
  return $_dbObj->table($table);
}

// 实例化模型
function D($model) {
    static $_model = [];
    if (!empty($_model[$model])) {
        return $_model[$model];
    } else {
        $class = $model . 'Model';
        $_model[$model] = new $class($model);
        return $_model[$model];
    }
}

// 获取配置信息
function config($name)
{
    $config = new \Config();
	return $config->get($name);
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}

// 日志记录
function logs($info = '', $module = null, $type = Log::INFO) {
    $logIns = Log::getInstance();
    $logIns->writeMessage($info,$type,$module);
}