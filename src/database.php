<?php

declare(strict_types=1);

namespace App;


use App\Exception\StorageException;
use App\Exception\ConfigurationException;
use PDO;
use PDOException;
use Throwable;

class Database
{
    private PDO $conn;
    public function __construct(array $config)
    {
        try {
            $this->validateConfig($config);
            $this->createConnection($config);
        } catch (PDOException $e) {
            throw new StorageException('Connection error');
        }
    }

    public function createNote(array $data): void
    {
        try {
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);
            $created = date('Y-m-d H:i:s');
            $query = "INSERT INTO notes(title,description,created) VALUES($title, $description, '$created')";
            $result = $this->conn->exec($query);
        } catch (throwable $e) {
            throw new StorageException('Nie udało się utworzyć nowej notatki', 400, $e);
        }
    }
    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        $this->conn = new PDO(
            $dsn,
            $config['user'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }

    private function validateConfig(array $config): void
    {
        if (empty($config['database']) || empty($config['user']) || empty($config['host'])) {
            throw new ConfigurationException('Problem z konfiguracją - skontaktuj się z administratorem');
        }
    }
}
