<?php


class PDOHelper
{
    /**
     * Name of host that db works on
     * @var
     */
    private $host;

    /**
     * Related user that connects to db
     * @var
     */
    private $user;

    /**
     * user pass to get connect to that db
     * @var
     */
    private $pass;

    /**
     * name of related database
     * @var
     */
    private $database;

    /**
     * stores and keeps pdo connection in order to execute queries
     * @var
     */
    private $pdo_connection;

    /**
     * Contains pdo statement in order to get executed
     * @var
     */
    private $pdo_statement;

    /**
     * An array that contains values in for pdo execution statement
     * @var
     */
    private $pdo_stmt_values = [];


    /**
     * Set default values in
     * PDOHelper constructor.
     */
    public function __construct()
    {
        $this->database = DATABASE;
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
    }

    /**
     * Creates a new pdo connection and stores it in $pdo property;
     */
    public function connectToDB()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->database}";
        try{
            $this->pdo_connection = new PDO($dsn,$this->user,$this->pass,[PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4_general_ci'"]);
        }catch (PDOException $e)
        {
            die("cannot connect to pdo with error: ".$e->getMessage());
        }
        return $this;
    }

    /**
     * Config pdo and set desired options for PDO
     */
    public function setOptionsToPDOConnection()
    {
        $this->pdo_connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
        $this->pdo_connection->setAttribute(PDO::ATTR_PERSISTENT,true);
        $this->pdo_connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $this;
    }

    /**
     * Prepare PDO query statement to get executed
     * @param string $query
     * @param array|null $values
     * @return $this
     */
    public function prepareQuery(string $query, array $values = null)
    {
        $this->pdo_statement = $this->pdo_connection->prepare($query);

        if(!is_null($values) && is_array($values) && is_countable($values))
        {
            $this->pdo_stmt_values = $values;
        }
        return $this;
    }

    /**
     * executes pdo statement and if it's gonna suppose to return value it will return value
     * @param bool $has_return_value default is false
     * @return mixed
     */
    public function execute(bool $has_return_value = false)
    {
        try{
            $this->pdo_statement->execute((is_countable($this->pdo_stmt_values)) ? $this->pdo_stmt_values : null);
        }catch (PDOException $e)
        {
            die("Unable to execute pdo with error : ".$e->getMessage());
        }

        if($has_return_value){
            $return_value = $this->pdo_statement->fetchAll();
        }
        unset($this->pdo_stmt_values);
        return $return_value ?? true;
    }

    /**
     * Destroys pdo connection
     */
    public function __destruct()
    {
        if (!empty($this->pdo_connection) && !is_null($this->pdo_connection))
        {
            unset($this->pdo_connection);
        }
    }


}