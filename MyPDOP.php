<?php

/**
 * Created by PhpStorm.
 * User: sien
 * Date: 2017/5/22 0022
 * Time: 23:17
 * https://www.sunbloger.com/article/466.html
 */
class MyPDOP
{
    /* 定义属性 dsn,dbh,_instance*/
    protected static $_instance = null;
    protected $dsn;
    protected $dbh;

    /* 初始化方法 */
    public function __construct($dbHost, $dbName, $dbUser, $dbPassword, $dbCharset)
    {
        try {
            $this->dsn = "mysql:host=" . $dbHost . ",dbname=" . $dbName;
            $this->dbh = new PDO($this->dsn, $dbUser, $dbPassword);
            $this->dbh->exec('set names ' . $dbCharset);
        } catch (PDOException $e) {
            $this->outputError($e->getPDOError());
        }
    }

    /* 获取单例链接资源 */
    public static function getInstance($dbHost, $dbName, $dbUser, $dbPassword, $dbCharset)
    {
        if (self::$_instance === null) {
            self::$_instance = new self($dbHost, $dbName, $dbUser, $dbPassword, $dbCharset);
        }
        return self::$_instance;
    }

    /* Query 查询 */

    /* Update 更新 */

    /* Insert 插入 */

    /* Replace 覆盖插入 */

    /* Delete 删除 */

    /* execSql 执行sql语句 */

    /* getMaxValue 获取字段最大值 */

    /* getCount 获取指定列的数量 */

    /* getTableEngine 获取表引擎 */

    /* beginTransaction 事务开始 */

    /* commit 事务提交 */

    /* rollback 事务回滚 */

    /* transcation 通过事务处理多条sql语句 */


    /* 错误输出方法 */
    private function outputError($errMsg)
    {
        throw new Exception ("数据库错误:" . $errMsg);
    }

    /* 调试方法 */
    private function debug($str)
    {
        var_dump($str);
        exit();
    }

    /* 捕获PDO错误方法 */
    private function getPDOError()
    {
        if ($this->dbh->errorCode() != '00000') {
            $arrayError = $this->dbh->errorInfo();
            $this->outputError($arrayError);
        }
    }

    /* 防止克隆 */
    private function __clone()
    {

    }
}