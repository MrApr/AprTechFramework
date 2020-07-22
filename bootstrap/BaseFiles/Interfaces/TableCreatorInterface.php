<?php


/**
 * Interface that every table creator needs to get implented in order to get work
 * Interface TableCreatorInterface
 */
interface TableCreatorInterface
{
    /**
     * make vars and related things ready;
     * @return mixed
     */
    public function makeReady();

    /**
     * Execute query function through this. Table creator starter executes this function
     * @return mixed
     */
    public function execute();
}