<?php
return [
  'host'     => getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '127.0.0.1'),
  'port'     => getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '3306'),
  'dbname'   => getenv('DB_DATABASE') ?: ($_ENV['DB_DATABASE'] ?? 'blogify'),
  'username' => getenv('DB_USERNAME') ?: ($_ENV['DB_USERNAME'] ?? 'phpuser'),
  'password' => getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? 'root'),
  'charset'  => getenv('DB_CHARSET') ?: ($_ENV['DB_CHARSET'] ?? 'utf8mb4')
];
