<?php
namespace Controller;
class Database {
	public $host;
	public $database;
    public $username;
    public $password;

    public function __construct($host, $database, $username, $password) {
      $this->host = $host;
      $this->database = $database;
      $this->username = $username;
      $this->password = $password;
    }

    public function Connection()
    {
    	$dsn = "mysql:dbname=$this->database;host=$this->host";
        try 
        {
          $dbh = new \PDO($dsn, $this->username, $this->password);
          $dbh->exec("set names utf8");
          return $dbh;
        } 
        catch (\PDOException $e) 
        {
          echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }

}    


