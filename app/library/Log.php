<?php
/**
* PHP log 类  
*/
class Log{
  
    private $LogFile;

    const DEBUG  = 100;
    const INFO   = 75;
    const NOTICE = 50;
    const WARNING =25;
    const ERROR   = 10;
    const CRITICAL = 5;
    
    private function __construct(){            
        
    }

    public static function getInstance() {
        static $obj;
        if(!isset($obj)){
            $obj = new Log();
        }
        return $obj;
    }

    public function getLogFile($module = null) {
        // 设置目录时间
        $date = date('Y-m-d');

        //设置路径目录信息
        $url  = './log/';
        $url .= $module ? $module.'/': '';
        $url .= $date.'/'.$date.'.txt';

        // 取出目录路径中目录(不包括后面的文件)
        $dir_name = dirname($url);

        // 如果目录不存在就创建
        if(!file_exists($dir_name)) {
            //iconv防止中文乱码
            $res = mkdir(iconv("UTF-8","GBK",$dir_name),0777,true);
        }

        $this->LogFile = @fopen($url,'a+');
    }

    public function writeMessage($msg, $logLevel = Log::INFO,$module = null){
        // 获取日志路径
        $this->getLogFile($module);

        date_default_timezone_set('Asian/shanghai');

        $time = date('Y-m-d H:i:s');
        $msg = str_replace("\t",'',$msg);
        $msg = str_replace("\n",'',$msg);
        
        // 日志类型
        $strLogLevel = $this->levelToString($logLevel);
        
        // 日志编写
        $logLine = "$time\t$strLogLevel\t$msg\t\n";
        fwrite($this->LogFile,$logLine);
    }

    public function levelToString($logLevel){
         $ret = '[unknow]';
         switch ($logLevel){
                case Log::DEBUG:
                     $ret = '【DEBUG】';
                     break;
                case Log::INFO:
                     $ret = '【INFO】';
                     break;
                case Log::NOTICE:
                     $ret = '【NOTICE】';
                     break;
                case Log::WARNING:
                     $ret = '【WARNING】';
                     break;
                case Log::ERROR:
                     $ret = '【ERROR】';
                     break;
                case Log::CRITICAL:
                     $ret = '【CRITICAL】';
                     break;
         }
         return $ret;
    }

}

?>