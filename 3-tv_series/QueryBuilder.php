<?php

/**
 * Build a query for a Model
 */
class QueryBuilder {
    protected string $model;
    protected array $wheres = [];
    protected array $orders = [];
    protected array $joins = [];
    protected array $bindings = [];
    protected bool $first = false;

    /**
     * Instanciate a QueryBuilder
     * @param string $model
     */
    public function __construct(string $model) {
        $this->model = $model;
    }

    /**
     * Add a where clause
     * @param string $field
     * @param string $operator
     * @param string $value
     * @return QueryBuilder
     */
    public  function where(string $field, string $operator, string $value): QueryBuilder {
        $this->wheres[] = array('field'=>$field, 'operator'=> $operator, 'value'=>":$field");
        $this->setBinding($field, $value);
        return $this;
    }

    /**
     * Add a order by
     * @param string $field
     * @param string $type
     * @return QueryBuilder
     */
    public  function orderBy(string $field, string $type): QueryBuilder {
        $this->orders[] = compact('field', 'type');
        return $this;
    }

    /**
     * Add a join
     * @param string $model
     * @param string $foreignKey
     * @param string $ownerKey
     * @return QueryBuilder
     */
    public function join(string $model, string $foreignKey, string $ownerKey): QueryBuilder {
        $this->joins[] = compact('model', 'foreignKey', 'ownerKey');
        return $this;
    }

    /**
     * Get the first result of the query only ("adds a limit 1" to the query)
     * @return QueryBuilder
     */
    public function first(): QueryBuilder {
        $this->first = true;
        return $this;
    }

    /**
     * Add a binding to the query
     * @param string $name
     * @param mixed $value
     * @return QueryBuilder
     */
    public function setBinding(string $name, $value): QueryBuilder {
        $this->bindings[$name] = $value;
        return $this;
    }

    /**
     * Build and execute the query
     * @return array|Model
     */
    public function get(): array|Model {
        $dbConfig = include "database.php";
        $mysqli = new PDO("mysql:host=".$dbConfig['host'].";dbname=".$dbConfig['dbname']."", $dbConfig['user'], $dbConfig['pass']);
        $query = "select ";
        $table = $this->model::getTable();
        $columns = array($this->model=>array('table' => $table, 'columns' => $this->model::getColumns()));
        $hasJoins = count($this->joins) > 0;
        if($hasJoins) {
            foreach($this->joins as $join) {
                $columns[$join['model']] = array('table' => $join['model']::getTable(), 'columns' => $join['model']::getColumns());
            }
        }
        $firstColumn = true;
        foreach($columns as $modelName=>$modelInfo) {
            $tableName = $modelInfo['table'];
            foreach($modelInfo['columns'] as $index=>$column) {
                $query.=($firstColumn ? '' : ', ') . "$tableName.$column as '$modelName.$column'";
                $firstColumn = false;
            }
        }
            
                
        $query .= " from $table ";

        if($hasJoins) {
            foreach($this->joins as $join) {
                $joinTable = $join['model']::getTable();
                $foreignKey = $join['foreignKey'];
                $ownerKey = $join['ownerKey'];
                $query .= " join $joinTable on $joinTable.$foreignKey=$table.$ownerKey";
            }
        }

        if(count($this->wheres) > 0) {
            $query .=' where ';
            foreach($this->wheres as $index=>$where) {
                $query.=($index === 0 ? '' : ' and ') . $where['field'] . $where['operator'] . $where['value'];
            }
        }
        
        if(count($this->orders) > 0) {
            $query .=' order by ';
            foreach($this->orders as $index=>$order) {
                $query.=($index === 0 ? '' : ', ') . $order['field'] . ' ' . $order['type'];
            }
        }
        if($this->first)
            $query.=' limit 1';
        $stmt = $mysqli->prepare($query);
        foreach($this->bindings as $name => $value) {
            $stmt->bindValue($name, $value);
        }
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($this->first)
            return $this->makeInstance($result[0]);
        else {
            $modelArray = array();
            foreach($result as $row)
                $modelArray[] = $this->makeInstance($row);
            return $modelArray;
        }
    }

    /**
     * Make a instance of a model
     * @param array $values
     * @return Model
     */
    protected function makeInstance(array $values): Model {
        return new $this->model($values);
    }
}