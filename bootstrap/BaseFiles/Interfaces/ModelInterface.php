<?php


/**
 * Interface that every model should obey (implement)
 * Interface ModelInterface
 */
interface ModelInterface
{
    /**
     * Prepares pdo helper
     * @return mixed
     */
    public function __construct();

    /**
     * Closes PDO Connection made by helper
     * @return mixed
     */
    public function closePDO();

    /**
     * Selecting data from defined table
     * @param string $statement
     * @param array $params
     * @return mixed
     */
    public function select(string $statement = null, array $params = []);

    /**
     * Inserting data in defined table
     * @param array $params
     * @return mixed
     */
    public function insert(array $params);

    /**
     * Updating rows in desired table
     * @param array $params
     * @param array $values
     * @param string $condition
     * @return mixed
     */
    public function update(array $params ,array $values ,string $condition = null);

    /**
     * Deleting data from desired table
     * @param string $condition
     * @param array $params
     * @return mixed
     */
    public function delete(string $condition = null, array $params = []);

    /**
     * Preparing statement
     * @return mixed
     */
    public function prepareStatement();

    /**
     * Executing prepared statement
     * @param bool $is_select
     * @return mixed
     */
    public function execute(bool $is_select = false);
}