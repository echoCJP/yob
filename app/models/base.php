<?php 

class BaseModel
    {
        public $link;//存储连接对象
        public $tableName = "";//存储表名
        public $field = "*";//存储字段
        public $allFields = [];//存储当前表所有字段
        public $where = "";//存储where条件
        public $order = "";//存储order条件
        public $limit = "";//存储limit条件

        /**
         * 构造方法 初始化
         * @param string $tableName 要操作的表名
         */
        public function __construct($tableName)
        {
            //1.存储操作的表名
            $this->tableName = $tableName;
            //2.初始化连接数据库
            $this->getConnect();
            //3.获得当前表的所有字段
            $this->getFields();
        }

        /**
         * 初始化连接数据库操作
         */
        public function getConnect()
        {
            //1.连接数据库
            $this->link = mysqli_connect('127.0.0.7','root','root','test',3306);
            //2.判断连接
            if (mysqli_connect_errno($this->link)>0){
                echo mysqli_connect_error($this->link);
                exit;
            }
        }

        /**
         * 执行并发送SQL(查询)
         * @param string $sql  要查询的SQL语句
         * @return array 返回查询出来的二维数组
         */
        public function query($sql)
        {
            $result = mysqli_query($this->link,$sql);

            if ($result && mysqli_num_rows($result)>0) {
              $arr = [];
              while($row = mysqli_fetch_assoc($result)){
                    $arr[] = $row;
              }
            }
        return $arr;
        }

        /**
         * 获取当前表的所有字段
         */
        public function getFields()
        {
            //查看表结构
            $sql = "desc {$this->tableName}";
            //执行并发送SQL
            $result = $this->query($sql);
            $fields = [];
            foreach ($result as $k => $v){
                $fields[] = $v['Field'];
            }
            $this->allFields = $fields;
        }

        /**
         * 执行并发送SQL语句(增删改)
         * @param string $sql 要执行的SQL语句
         * @return bool|int|string 添加成功则返回上一次操作id,删除修改操作则返回true,失败则返回false
         */
        public function exec($sql)
        {
            $result = mysqli_query($this->link,$sql);

            //处理结果集
            if ($result && mysqli_affected_rows($this->link)>0){
                //判断是否为添加操作，是则返回上一次执行的id
                if (mysqli_insert_id($this->link)){
                    return mysqli_insert_id($this->link);
                }
                //删除修改操作成功则返回true
                return true;
            }else{
                //未执行成功则返回false
                return false;
            }
        }

        /**
         * 查询多条数据
         */
        public function select()
        {
            $sql = "select {$this->field} from {$this->tableName} {$this->where} {$this->order} {$this->limit}";

            //执行并发送SQL
            return $this->query($sql);
        }

        /**
         * 查询一条数据
         * @param string $id 要查询的id
         * @return array  返回一条数据
         */
        public function find($id="")
        {
            //判断id是否存在
            if (empty($id)){
                $where = $this->where;
            }else{
                $where = "where id={$id}";
            }

            $sql = "select {$this->field} from {$this->tableName} {$where} limit 1";
            //执行并发送sql
            $result = $this->query($sql);
            //返回一条数据
            return $result[0];
        }

        /**
         * 设置要查询的字段信息
         * @param string $field  要查询的字段
         * @return object 返回自己，保证连贯操作
         */
        public function field($field)
        {
            //判断字段是否存在
            if (empty($field)){
                return $this;
            }

            $this->field = $field;
            return $this;
        }

        /**
         * 统计总条数
         * @return int 返回总数
         */
        public function count()
        {
            //准备SQL语句
            $sql = "select count(*) as total from {$this->tableName} limit 1";

            $result = $this->query($sql);
            //返回总数
            return $result[0]['total'];

        }

        /**
         * 添加操作
         * @param array  $data 要添加的数组
         * @return bool|int|string 添加成功则返回上一次操作的id,失败则返回false
         */
        public function add($data){
            //判断是否是数组
            if (!is_array($data)){
                return $this;
            }
            //判断是否全是非法字段
            if (empty($data)){
                die("非法数据");
            }
            //过滤非法字段
            foreach ($data as $k => $v){
                if (!in_array($k,$this->allFields)){
                    unset($data[$k]);
                }
            }
            //将数组中的键取出
            $keys = array_keys($data);
            //将数组中取出的键转为字符串拼接
            $key = implode(",",$keys);
            //将数组中的值转化为字符串拼接
            $value = implode("','",$data);

            //准备SQL语句
            $sql = "insert into {$this->tableName} ({$key}) values('{$value}')";
            //执行并发送SQL
            return $this->exec($sql);
        }

        /**
         * 删除操作
         * @param string $id 要删除的id
         * @return bool  删除成功则返回true,失败则返回false
         */
        public function delete($id="")
        {
            //判断id是否存在
            if (empty($id)){
                $where = $this->where;
            }else{
                $where = "where id={$id}";
            }
            $sql = "delete from {$this->tableName} {$where}";
            echo $sql;
            //执行并发送
            return $this->exec($sql);
        }

        /**
         * 修改操作
         * @param  array $data  要修改的数组
         * @return bool 修改成功返回true，失败返回false
         */
        public function update($data){
            //判断是否是数组
            if (!is_array($data)){
                return $this;
            }
            //判断是否是全是非法字段
            if(empty($data)){
                die("非法数据");
            }
            $str = "";
            //过滤非法字段
            foreach ($data as $k => $v){
                if ($k == "id"){
                    $where = "where id={$v}";
                    unset($data[$k]);
                }
                if (in_array($k,$this->allFields)){
                    $str .= "{$k}='{$v}',";
                }else{
                    unset($data[$k]);
                }
            }
            //判断是否有条件
            if (empty($this->where)){
                die("请输入条件");
            }
            //去掉最右侧的逗号
            $str = rtrim($str,",");

            $sql = "update {$this->tableName} set {$str} {$this->where}";

            return $this->exec($sql);
        }

        /**
         * where条件
         * @param string $where 输入的where条件
         * @return $this 返回自己，保证连贯操作
         */
        public function where($where)
        {
           $this->where = "where ".$where;
           return $this;
        }

        /**
         * order排序条件
         * @param string  $order  以此为基准进行排序
         * @return $this  返回自己，保证连贯操作
         */
        public function order($order)
        {
            $this->order = "order by ".$order;
            return $this;
        }

        /**
         * limit条件
         * @param string $limit 输入的limit条件
         * @return $this 返回自己，保证连贯操作
         */
        public function limit($limit)
        {
            $this->limit = "limit ".$limit;
            return $this;
        }

        /**
         * 析构方法
         * 关闭数据库连接
         */
        public function __destruct()
        {
            mysqli_close($this->link);
        }
    }
    