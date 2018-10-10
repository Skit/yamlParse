<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 08.10.2018
 * Time: 14:36
 */
namespace model;

use PDO;

class Db
{
    private
        $_db,
        $_stmt,
        $_lastInsertId;

    private
        $_host = 'localhost',
        $_dbname = 'key',
        $_user = 'root',
        $_password = '';

    protected function __construct()
    {

        try {
            $dsn = "mysql:host={$this->_host};dbname={$this->_dbname}";
            $options = [PDO::ATTR_PERSISTENT => true];

            $this->_db = new PDO($dsn, $this->_user, $this->_password, $options);

            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->exec("set names utf8");
            $this->_db->query("SET wait_timeout=1200;");
        }
        catch(\PDOExceptiontion $e) {
            print $e->getMessage();
        }
    }

    public function getLastInsertId(){

        return $this->_lastInsertId;
    }

    protected function setQuery($query){

        $this->_stmt = $this->_db->prepare($query);

        return $this;
    }

    protected function setValue($pattern, $value){

        $this->_stmt->bindValue($pattern, $value);
    }

    protected function runQuery($condition){

        $result = self::run($condition);
        $this->_lastInsertId = $this->_db->lastInsertId();

        return $result;
    }

    protected function run($condition=null, $destroy=false){

        $this->_stmt->execute($condition);

        if($destroy)
            $this->_stmt = null;

        return $this;
    }

    protected function fetch(){

        return $this->_stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function one(){

        return self::run()->fetch();
    }

    protected function startTransaction(){

        return $this->_db->beginTransaction();
    }

    protected function finishTransaction(){

        return $this->_db->commit();
    }
}