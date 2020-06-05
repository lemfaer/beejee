<?php

namespace App\Controller;

use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repository\TaskRepository;

class TaskController extends Controller
{
    /**
     * @example /
     */
    public function list(): Response
    {
        $tasks = $this
            ->container
            ->get(TaskRepository::class)
            ->getTasks();

        return $this->jsonResponse(200, $tasks);
    }

    /**
     * @example /get/{id}
     */
    public function single(string $id): Response
    {
        $task = $this
            ->container
            ->get(TaskRepository::class)
            ->getTaskById($id);

        return $this->jsonResponse(200, (array) $task);
    }
}
