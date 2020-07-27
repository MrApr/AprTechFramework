<?php


/**
 * Abstract class that every table creator needs to get implented in order to get work
 * Abstract class TableCreatorClass
 */
abstract class TableCreatorClass
{

    /**
     * Container to hold queries
     * @var
     */
    private $query;

    /**
     * Container that holds injected pdo helper
     * @var PDOHelper
     */
    private $pdo_helper;

    /**
     * Container for pdo stmt values in execution
     * @var array
     */
    private $values = [];

    /**
     * Injecting instantiated pdo helper in order to execute queries
     * TableCreatorClass constructor.
     * @param PDOHelper $pdo_helper
     */
    public function __construct(PDOHelper $pdo_helper)
    {
        $this->pdo_helper = $pdo_helper;
    }

    /**
     * make vars and related things ready;
     * @return mixed
     */
    abstract public function makeReady();

    /**
     * Execute query function through this. Table creator starter executes this function
     * @return mixed
     */
    public function execute()
    {
        $this->pdo_helper->prepareQuery($this->query,(is_countable($this->values)) ? $this->values : null);
        $this->pdo_helper->execute();
    }
}