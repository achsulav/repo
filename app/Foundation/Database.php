<?php
namespace App\Foundation;
use PDO;
use PDOException;

class Database{
  private PDO $pdo;
  public function __construct(array $config)
  {
    $port = isset($config['port']) ? ";port={$config['port']}" : "";
    $dsn = "mysql:host={$config['host']}{$port};dbname={$config['dbname']};charset={$config['charset']}";
    try {
      $this->pdo = new PDO($dsn,$config['username'],$config['password'],
[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=> PDO::FETCH_ASSOC]
      );

    } catch (PDOException $e) {
      die("Database connection failed" . $e->getMessage());
    }
  }
  public function getConnection():PDO{
    return $this->pdo;
  } 
}
