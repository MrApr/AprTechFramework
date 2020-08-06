<?php


class Model implements ModelInterface
{

    /**
     * Container for holding related table to model name
     * @var
     */
    protected $table;

    /**
     * Container for holding dynamically created statements
     * @var
     */
    protected $statement;

    /**
     * Container that holds statement parameters
     * @var array
     */
    protected $params = [];

    /**
     * Container for holding pdo helper
     * @var
     */
    protected $pdo_helper;

    /**
     * Prepares pdo helper
     * @return mixed
     */
    public function __construct()
    {
        $this->pdo_helper = new PDOHelper();
        $this->pdo_helper->connectToDB();
    }

    /**
     * Closes PDO Connection made by helper
     * @return mixed
     */
    public function closePDO()
    {
        unset($this->pdo_helper);
    }

    /**
     * Selecting data from defined table
     * @param string $statement
     * @param array $params
     * @return mixed
     */
    public function select(string $statement = null,array $params = [])
    {
        $this->statement = "SELECT * FROM `{$this->table}` ";

        if(is_countable($params) && $statement)
        {
            $this->statement .= $statement;
            $this->params = $params;
        }

        $this->prepareStatement();
        return $this;
    }

    /**
     * Inserting data in defined table
     * @param array $params
     * @return mixed
     */
    public function insert(array $params)
    {
        $this->statement = "INSERT INTO `{$this->table}` (".implode(array_keys($params),',').") VALUES (".implode(',', array_fill(0, count($params), '?')).")";
        $this->params = array_values($params);

        $this->prepareStatement();
        return $this;
    }

    /**
     * Updating rows in desired table
     * @param array $params
     * @param string $condition
     * @return mixed
     */
    public function update(array $params, string $condition = null)
    {
        $this->statement = "UPDATE FROM SET";
        $counter = 0;
        foreach ($params as $param_key => $param_value)
        {
            $this->statement .= "{$param_key} = {$param_value}";
            if($counter < count($params))
            {
                $this->statement .= ", ";
            }
            $counter++;
        }

        if($condition)
        {
            $this->statement .= $condition;
        }

        $this->prepareStatement();
        return $this;
    }

    /**
     * Deleting data from desired table
     * @param string $condition
     * @param array $params
     * @return mixed
     */
    public function delete(string $condition = null, array $params = [])
    {
        $this->statement = "DELETE FROM `table`";
        if($condition && is_countable($params))
        {
            $this->statement .= $condition;
            $this->params = $params;
        }

        $this->prepareStatement();
        return $this;
    }

    /**
     * Preparing statement
     * @return mixed
     */
    public function prepareStatement()
    {
        $this->pdo_helper->prepareQuery($this->statement,(is_countable($this->params)) ? $this->params : null);
    }

    /**
     * Executing prepared statement
     * @param bool $is_select
     * @param bool $first_value
     * @return mixed
     */
    public function execute(bool $is_select = false, bool $first_value = false)
    {
        $return_values = $this->pdo_helper->execute($is_select);
        if($is_select && $first_value)
        {
            if (count($return_values) != count($return_values, COUNT_RECURSIVE))
            {
                $return_values = call_user_func_array('array_merge', $return_values);
            }
        }
        return $return_values;
    }

    public function __destruct()
    {
        unset($this->pdo_helper);
    }
}