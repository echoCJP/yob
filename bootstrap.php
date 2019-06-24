<?php 

/**
* 核心文件
*/
class bootstrap
{
	// 启动框架
	static public function run() {
		// TODO:
	}

	// 自动加载文件
	static public function autoload($class) {
		/* 引入相关文件 */
		$file = APP . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';
		if (file_exists($file)) {
	        require $file;
	    }
	}  
}