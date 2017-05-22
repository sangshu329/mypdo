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
        if (self::$_instance === null) {
            self::$_instance = new self($dbHost, $dbUser, $dbPassword, $dbName, $dbCharset);
        }
        return self::$_instance;
    }

    /* Query 查询 */
    public function query($strSql, $queryMode = 'All', $debug = false)
    {
        if ($debug === true) $this->debug($strSql);
        $recordset = $this->dbh->query($strSql);
        $this->getPDOError();
        if ($recordset) {
            $recordset->setFetchMode(PDO::FETCH_ASSOC);
            if ($queryMode == 'All') {
                $result = $recordset->fetchAll();
            } elseif ($queryMode == 'Row') {
                $result = $recordset->fetch();
            }
        } else {
            $result = null;
        }
        return $result;
    }

    /* 数据更新语句 */
    public function update($table, $arrayDataValue, $where = '', $debug = false)
    {
        $this->checkFields($table, $arrayDataValue);
        if ($where) {
            $strSql = '';
            foreach ($arrayDataValue as $key => $value) {
                $strSql .= ', `' . $key . '`="' . $value . '"';
            }
            $strSql = substr($strSql, 0, 1);
            $strSql = "UPDATE `$table` SET $strSql WHERE $where";
        } else {
            $strSql = "REPLACE INTO `$table` (`" . implode('`,`', array_keys($arrayDataValue)) . "`) VALUES (`" . impload("`,`", $arrayDataValue) . "`)";
        }
        if ($debug === true) $this->debug($strSql);
        $result = $this->dbh->exec($strSql);
        $this->getPDOError();
        return $result;
    }

    /* 插入 */
    public function insert($table, $arrayDataValue, $debug = false)
    {
        $this->checkFields($table, $arrayDataValue);
        $strSql = "INSTER INTO `$table` (`" . implode('`,`', array_keys($arrayDataValue)) . "`) 
        VALUES (`" . implode("','", $arrayDataValue) . "')";
        if($debug === true) $this->debug($strSql);
        $result = $this->dbh->exec($strSql);
        $this->getPDOError();
        return $result;
    }

    /* 检查制定字段是否在制定数据表中存在 */
    private function checkFields($table, $arrayFields)
    {
        $fields = $this->getFields($table);
        foreach ($arrayFields as $key => $value) {
            if (!in_array($key, $fields)) {
                $this->outputError("字段" . $key . "在" . "$table 中不存在！");
            }
        }
    }

    private function getFields($table)
    {
        $fields = array();
        $recordset = $this->dbh->query("SHOW COLUMNS FROM $table");
        $this->outputError();
        $recordset->setFetchMode(PDO::FETCH_ASSOC);
        $result = $recordset->fetchAll();
        foreach ($result as $rows) {
            $fields[] = $rows['Field'];
        }
        return $fields;
    }

    private function getPDOError()
    {
        if ($this->dbh->errorCode() != '00000') {
            $arrayError = $this->dbh->errorCode();
            $this->outputError($arrayError);
        }
    }

    private function debug($debuginfo)
    {
        var_dump($debuginfo);
        exit();
    }

    private function outputError($strErrMsg)
    {
        throw new Exception('数据库错误：' . $strErrMsg);
    }

    public function destruct()
    {
        $this->dbh = null;
    }


}
