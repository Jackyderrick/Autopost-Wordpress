<?php 
class Mysql {
    private $host;
    private $user;
    private $pwd;
    private $dbName;
    private $charset;

    private $conn = null; // 保存连接的资源


    public function __construct() {
        // 应该是在构造方法里,读取配置文件
        // 然后根据配置文件来设置私有属性
        // 此处还没有配置文件,就直接赋值

        $this->host = 'localhost';
        $this->user = '数据库用户名';
        $this->pwd = '数据库密码';
        $this->dbName = '数据库名';

        // 连接
        $this->connect($this->host,$this->user,$this->pwd);

        // 切换库
        $this->switchDb($this->dbName);

        // 设置字符集
        $this->setChar($this->charset);
   
   
    }

    // 负责连接
    private function connect($h,$u,$p) {
        $conn = mysql_connect($h,$u,$p);
        $this->conn = $conn;
    }

    // 负责切换数据库,网站大的时候,可能用到不止一个库
    public function switchDb($db) {
        $sql = 'use ' . $db;
        $this->query($sql);
    }

    // 负责设置字符集
    public function setChar($char) {
        $sql = 'set names ' .  $char;
        $this->query($sql);
    }

    // 负责发送sql查询
    public function query($sql) {
        return mysql_query($sql,$this->conn);
    }

    // 负责获取多行多列的select 结果
    public function getAll($sql) {
        $list = array();

        $rs = $this->query($sql);
        if(!$rs) {
            return false;
        }

        while($row = mysql_fetch_assoc($rs)) {
            $list[] = $row;
        }

        return $list;

    }

    // 获取一行的select 结果
    public function getRow($sql) {
        $rs = $this->query($sql);
       
        if(!$rs) {
            return false;
        }

        return mysql_fetch_assoc($rs);
    }

    // 获取一个单个的值
    public function getOne($sql) {
        $rs = $this->query($sql);
        if(!$rs) {
            return false;
        }

        $row = mysql_fetch_row($rs);

        return $row[0];
    }

    public function close() {
        mysql_close($this->conn);
    }
}




$db = new Mysql();
mysql_query("SET NAMES 'GBK'");
$title=iconv('utf-8','gbk',$_POST['post_title']);
$content=iconv('utf-8','gbk',$_POST['post_content']);
$times=time();
$time=date('Y-m-d H:i:s',$times);

$sql = "INSERT INTO `wp_posts` (post_author,post_date,post_date_gmt,post_title,post_content,post_status) VALUES (1,'$time','$time','$title','$content','trash')";
$arr = $db->query($sql);

//print_r($time);








