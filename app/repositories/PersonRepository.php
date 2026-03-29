<?php

namespace app\repositories;

use flight\database\PdoWrapper;

class PersonRepository
{
    protected PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    public function testConnection(): bool
    {
        $result = $this->db->fetchRow('SELECT 1 AS ok');

        return (int) ($result['ok'] ?? 0) == 1;
    }

    public function ensureTableAndSeed(): void
    {
        $this->db->runQuery(
            'CREATE TABLE IF NOT EXISTS persons (
                id SERIAL PRIMARY KEY,
                first_name VARCHAR(80) NOT NULL,
                last_name VARCHAR(80) NOT NULL,
                email VARCHAR(180) NOT NULL UNIQUE
            )'
        );

        $countResult = $this->db->fetchRow('SELECT COUNT(*) AS total FROM persons');
        $total = (int) ($countResult['total'] ?? 0);

        if ($total === 0) {
            $this->db->runQuery(
                'INSERT INTO persons (first_name, last_name, email) VALUES
                (?, ?, ?),
                (?, ?, ?),
                (?, ?, ?)',
                [
                    'Alice', 'Martin', 'alice.martin@example.com',
                    'Karim', 'Benali', 'karim.benali@example.com',
                    'Sofia', 'Rossi', 'sofia.rossi@example.com',
                ]
            );
        }
    }

    public function findAll(): array
    {
        return $this->db->fetchAll(
            'SELECT id, first_name, last_name, email FROM persons ORDER BY id ASC'
        );
    }
}