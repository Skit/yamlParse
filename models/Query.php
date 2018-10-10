<?php

namespace model;

use model\Db;

include_once 'Db.php';

class Query extends Db
{
    private $_queryStr = '';
    protected $table;

    public function __construct()
    {
        parent::__construct();
    }

    public function insert(array $condition, $ignore=false){

        $condition = self::_setConditionTypes($condition);
        self::_createInsertQuery($condition, $ignore);

        return $this->setQuery($this->_queryStr)->runQuery($condition);
    }

    public function batchInsert(\SimpleXMLElement $xmlObject, $ignore=false){

        $condition = self::_applyScheme($xmlObject);

        $this->startTransaction();

        $recordsCount = count($condition);
        for($i=0;$i<$recordsCount; $i++){

            $condition[$i] = self::_setConditionTypes($condition[$i]);
        }

        self::_createInsertQuery($condition[0], $ignore);

        foreach($condition as $insertRow){

            $this->setQuery($this->_queryStr)->run($insertRow, true);
        }

        return $this->finishTransaction();
    }

    public function select($select='*'){

        $this->_queryStr = "SELECT {$select} FROM {$this->table()}";

        return $this;
    }

    public function where($query, array $params){

        $this->_queryStr .= " WHERE {$query}";
        $this->setQuery($this->_queryStr);

        //var_dump($this->_queryStr); die();
        $countParams = count($params);
        for($i=0; $i != $countParams; $i++){

            $this->setValue($i+1, $params[$i]);
        }

        return $this;
    }

    public function whereLike($query, $param, $wrap='both'){

        $this->_queryStr .= " WHERE {$query}";

        switch ($wrap){

            case 'both':
                $param = "%{$param}%";
                break;
            case 'left':
                $param = "%{$param}";
                break;
            case 'right':
                $param = "{$param}%";
                break;
            default:
                $param = "%{$param}%";
                break;
        }

        $this->setQuery($this->_queryStr);
        $this->setValue(1, $param);

        return $this;
    }

    public function leftJoin($query){

        $this->_queryStr .= " LEFT JOIN {$query}";
        return $this;
    }

    public function getQueryStr(){

        return $this->_queryStr;
    }

    private function _applyScheme(\SimpleXMLElement $xmlObject){

        $i=0;
        $result = [];
        $scheme = $this->scheme();

        foreach($xmlObject as $element){

            foreach ($scheme as $key=>$pattern)
                $result[$i][$key] = self::_parsePattern($element, $pattern);

            $i++;
        }

        return $result;
    }

    private function _parsePattern(\SimpleXMLElement $xmlObjectElement, $pattern){

        $marker = substr($pattern, 0, 1);

        switch ($marker){

            case '@':
                $attributes = (array) $xmlObjectElement->attributes();
                $key = str_replace('@','', $pattern);
                $result = $attributes['@attributes'][$key];
                break;
            case '#':
                $method = str_replace('#','', $pattern);
                $result = $xmlObjectElement->{$method}();
                break;
            case '<':
                $element = str_replace('<','', $pattern);
                $result = (string) $xmlObjectElement->{$element};
                break;
            default:
                $result = $pattern;
                break;
        }
        return $result;
    }

    private function _setConditionTypes(array $condition){

        $types = $this->types();

        foreach ($condition as $key=>$val){

            if($key == '<xml_value>')
                $key = 0;

            switch ($types[$key]){

                case 'string':
                case 'str':
                    $condition[$key] = (string) $val;
                    break;
                case 'integer':
                case 'int':
                    $condition[$key] = (integer) $val;
                    break;
                default: break;

            }
        }
        return $condition;
    }

    private function _createInsertQuery(array $query, $ignore){

        $column=$values= '';

        foreach ($query as $param => $val){

            if($column != '')
                $column .= ',';

            $column .= $param;

            if($values != '')
                $values .= ',';

            $values .= ":{$param}";
        }

        $ignore = ($ignore) ? 'IGNORE ' : '';

        $this->_queryStr = "INSERT {$ignore}INTO {$this->table()} ({$column}) VALUES ({$values})";
    }

    protected function types(){

        return [];
    }

    protected function scheme(){

        return [];
    }

    protected function table(){

        return '';
    }
}