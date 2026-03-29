<?php

namespace app\logic;

use app\repositories\PersonRepository;
use RuntimeException;

class PersonService
{
    protected PersonRepository $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function getPersonsWithDbStatus(): array
    {
        if ($this->personRepository->testConnection() === false) {
            throw new RuntimeException('Database connection failed.');
        }

        $this->personRepository->ensureTableAndSeed();
        $persons = $this->personRepository->findAll();

        return [
            'database' => 'ok',
            'count' => count($persons),
            'persons' => $persons,
        ];
    }
}