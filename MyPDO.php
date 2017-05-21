<?php

/**
 * Created by PhpStorm.
 * User: sien
 * Date: 2017/5/21 0021
 * Time: 15:53
 */
class MyPDO
{
    protected static $_instance = null;
    protected $dbName = '';
    protected $dsn;
    protected $dbh;

    private function __construct($dbHost, $dbUser, $dbPassword, $dbName, $dbCharset)
    {
        try {
            $this->dsn = 'mysql:host=' . $dbHost . ';dbname=' . $dbName;
            $this->dbh = new PDO($this->dsn, $dbUser, $dbPassword);
            $this->dbh->exec('SET character_set_connection=' . $dbCharset . ', character_set_result=' . $dbCharset . ', Character_set_clinet=binary');
        } catch (PDOException $e) {
            $this->outputError($e->getMessage());
        }
    }

    /* 防止克隆对象 */
    private function __clone()
    {
    }

    public static function getInstance($dbHost, $dbUser, $dbPassword, $dbName, $dbCharset)
    {
        if(self::$_instance === null) {
            self::$_instance = new self($dbHost, $dbUser, $dbPassword, $dbName, $dbCharset);
        }
        return self::$_instance;
    }

    
}
