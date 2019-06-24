<?php 

// namespace Yob\Lib;

/**
 * 获取配置信息
 */
class Config 
{
	private $configs = [];

	// get('mysql.host')
	public function get($key) {
		$key = $this->splitFileAndKey($key);
		return $this->getKey($this->getFile($key[0]), $key[1]);
	}

	// 获取文件和Key
	public function splitFileAndKey($key = null) {
		$file = explode('.', $key)[0];
		$key = substr($key,strlen($file) + 1);
		return [$file, $key];
	}

	// 获取配置文件的数据
	public function getFile($file) {
		if (isset($this->configs[$file])) {
			return $this->configs[$file];
		}

		$file = BASE_PATH . '/config/' . $file . '.php';
		if (!is_file($file)) {
			echo $file . '文件不存在';
			die();
		}
		return $this->configs[$files] = require $file;
	}

	public function getKey($arr, $key) {
		// 如果没有key 则返回整个配置文件
		if ($key == false) {
			return $arr;
		}

		// 如果没有 . 则直接返回这个键
		if (strpos($key, '.') === false) {
			return $arr[$key];
		}

		foreach (explode('.', $key) as $segement) {
			$arr = $arr[$segement];
		}

		return $arr;
	}


}