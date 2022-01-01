<?php

class dbh
{

    private $dbhost = 'localhost';
    private $dbname = 'orders';
    private $dbuser = 'root';
    private $dbpass = '';
    private $dboptions = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    );


    protected function connect()
    {
        $dsn = 'mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname;
        return new PDO($dsn, $this->dbuser, $this->dbpass, $this->dboptions);
    }
}
