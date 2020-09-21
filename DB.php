<?php


class DB
{
    public $connect;
    public function __construct() {
        $this->getInstance();
        return $this->connect;
    }

    public function getInstance($host=null,$dbname=null,$username=null,$password=null)
    {
        $host = __HOSTNAME__;
        $dbname = __DATABASENAME__;
        $username = __USERNAME__;
        $password = __PASSWORD__;
        $this->connect = pg_connect("host=$host port=5432 dbname=$dbname user=$username password=$password");
    }
}
