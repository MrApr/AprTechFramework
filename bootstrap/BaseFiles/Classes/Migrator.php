<?php


class Migrator
{
    private $table_creators;

    public function launch()
    {
        $this->getTableCreators();
        $this->execute();
    }

    public function getTableCreators()
    {
        $creator_files = glob(ROOT_DIR."/database/Tables/*.php");
        if(!is_countable($creator_files) || !count($creator_files))
        {
            echo "There is no Table creator file\n";
            exit;
        }
        $this->table_creators = $creator_files;
    }

    public function execute()
    {
        foreach ($this->table_creators as $table_creator)
        {
            require_once $table_creator;
            echo $table_creator;
            $creator_name = explode("/",$table_creator);
            $creator_name = $creator_name[count($creator_name) - 1];
            $creator_name = explode(".",$creator_name);
            $creator_name = $creator_name[0];
            $creator = new $creator_name(new PDOHelper());
            $creator->makeReady();
            $creator->execute();
        }
    }
}