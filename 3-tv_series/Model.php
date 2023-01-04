<?php

/**
 * Interact with a database table (retrieves database records from a table)
 */
class Model
{

    protected string $table;

    protected array $relationships = array();

    protected array $columns;

    protected array $attributes;

    protected array $hidden = [];
    /**
     * Instantiate a Model
     * @param array|null $attributes
     * @return void
     */
    public function __construct(array $attributes = null)
    {
        if ($attributes === null)
            return;
        $modelName = get_class(new static);
        $relationshipsToAdd = array();
        foreach($attributes as $columnName=>$value) {
            if (!is_string($columnName))
                continue;
            $columnInfo = explode('.', $columnName);
            if(!isset($columnInfo[1]))
                $this->attributes[$columnInfo[0]] = $value;
            else {
                $columnModel = $columnInfo[0];
                $column = $columnInfo[1];
                if($columnModel === $modelName)
                    $this->attributes[$column] = $value;
                else
                    $relationshipsToAdd[$columnModel][$column] = $value;
            }
        }

        foreach ($relationshipsToAdd as $model => $attributes)
            $this->addRelationShip($model, $attributes);

    }

    /**
     * Add a relationShip to another model
     * @param string $model
     * @param array $attributes
     * @return void
     */
    public function addRelationShip(string $model, array $attributes) {
        $relationship = new $model($attributes);
        $this->relationships[$model] = $relationship;
    }

    /**
     * Get the name of the table
     * @return string
     */
    public static function getTable(): string {
        return (new static)->table;
    }

    /**
     * Get the columns of the model
     * @return array
     */
    public static function getColumns(): array {
        return (new static)->columns;
    }

    /**
     * Instantiate a QueryBuilder with this model
     * @return QueryBuilder
     */
    public static function query(): QueryBuilder {
        return new QueryBuilder(get_class(new static));
    }

    /**
     * Parse the Model object to a JSON
     * @return stdClass
     */
    public function toJson(): stdClass {
        $json = new stdClass;
        foreach($this->attributes as $field=>$value) {
            if(!in_array($field, $this->hidden)) {
                $json->{$field} = $value;
            }
        }

        foreach($this->relationships as $relationshipName=>$obj) {
            $json->{$relationshipName} = $obj->toJson();
        }
        return $json;
    }
}