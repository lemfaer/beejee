<?php

namespace App\Controller;

use Exception;
use App\Model\User;
use App\Model\Task;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repository\TaskRepository;
use App\Validator\TaskFormValidator;

class TaskController extends Controller
{
    protected const TASK_LIMIT = 3;
    protected const PAGE_LIMIT = 5;

    /**
     * @example /
     */
    public function list(string $sort = "name-desc", int $current = 1): Response
    {
        $page = (object) $this->calculatePagination($current);

        $tasks = $this->container
            ->get(TaskRepository::class)
            ->getTasks(null, $page->limit, $page->offset, $sort);

        return $this->template(200, "main", compact("tasks", "sort", "page"));
    }

    /**
     * @example /new
     */
    public function new(): Response
    {
        return $this->template(200, "task");
    }

    /**
     * @example /update/{id}
     */
    public function update(int $id): Response
    {
        // ...
    }

    /**
     * @example POST /new
     * @example POST /update/{id}
     */
    public function formSubmit(): Response
    {
        $data = $this->request->getParsedBody();
        $validator = $this->container->get(TaskFormValidator::class);

        $task = new Task();
        $task->user = new User();
        $success = true;

        if (!$this->isCsrfTokenValid("save-task", $data["token"])) {
            $this->message->enqueue("Anti-CSRF is not valid");
            $success = false;
        }

        try {
            $validator->sanitizeData($task, $data);
        } catch (Exception $e) {
            $success = false;
            foreach ($validator->getErrors() as $error) {
                $this->message->enqueue($error);
            }
        }

        if ($success) {
            $this->container
                ->get(TaskRepository::class)
                ->save($task);
        }

        return $this->redirect(200, "/");
    }

    /**
     * @example /get/{id}
     */
    public function single(string $id): Response
    {
        $task = $this->container
            ->get(TaskRepository::class)
            ->getTaskById($id);

        return $this->jsonResponse(200, (array) $task);
    }

    /**
     * Returns info needed for pagination to display
     *
     * @param integer $current current page number
     *
     * @return array
     */
    protected function calculatePagination(int $current = 1): array
    {
        $tasks = $this->container
            ->get(TaskRepository::class)
            ->getTaskCount();

        $limit = static::TASK_LIMIT;
        $offset = ($current - 1)  * $limit;
        $pages = (int) ceil($tasks / $limit);
        $start = ($current - 1) ? $current - 1 : $current;
        $end = min($pages, $start + static::PAGE_LIMIT - 1);
        $prev = $current > 1 ? $current - 1 : null;
        $next = $current < $end ? $current + 1 : null;
        $display = $pages > 1;

        if ($offset >= $tasks) {
            throw new Exception("Not found", 404);
        }

        return compact(
            "display",
            "current",
            "limit",
            "offset",
            "tasks",
            "pages",
            "start",
            "end",
            "prev",
            "next",
        );
    }
}
