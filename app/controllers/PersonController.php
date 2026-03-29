<?php

namespace app\controllers;

use app\logic\PersonService;
use app\repositories\PersonRepository;
use flight\Engine;
use Throwable;

class PersonController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function index(): void
    {
        try {
            $personRepository = new PersonRepository($this->app->db());
            $personService = new PersonService($personRepository);
            $payload = $personService->getPersonsWithDbStatus();

            $this->app->json($payload, 200, true, 'utf-8', JSON_PRETTY_PRINT);
        } catch (Throwable $exception) {
            $this->app->json([
                'database' => 'error',
                'message' => $exception->getMessage(),
            ], 500, true, 'utf-8', JSON_PRETTY_PRINT);
        }
    }
}