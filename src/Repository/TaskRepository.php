<?php

namespace App\Repository;

use PDO;
use Exception;
use Traversable;
use DateTime;
use App\Core\Repository;
use App\Model\Task;
use Psr\Container\ContainerInterface as Container;

use function sprintf;
use function str_repeat;
use function array_column;

class TaskRepository extends Repository
{
    /**
     * @var \App\Repository\UserRepository
     */
    protected $user;

    /**
     * List of known tasks
     *
     * @var array of \App\Model\Task
     */
    protected $taskPool = [];

    /**
     * Repository construct
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->user = $container->get(UserRepository::class);
    }

    /**
     * Get task from db by its id
     *
     * @param int $id
     *
     * @return \App\Model\Task
     */
    public function getTaskById(int $id): Task
    {
        $tasks = $this->getTasks([$id], 1);

        if (!isset($tasks[$id])) {
            throw new Exception("Task not found", 404);
        }

        return $tasks[$id];
    }

    /**
     * Get all task count
     *
     * @return int
     */
    public function getTaskCount(): int
    {
        $sql = "SELECT count(*) FROM tasks";
        $statm = $this->db->prepare($sql);
        $statm->execute();

        $arr = $statm->fetch();

        return array_shift($arr);
    }

    /**
     * Get multiple tasks
     *
     * @param array|null $ids task ids
     * @param int $limit
     * @param int $offset
     * @param string $sort
     *
     * @return array of \App\Model\Task
     */
    public function getTasks(
        ?array $ids = null,
        int $limit = 10,
        int $offset = 0,
        string $sort = null
    ): array {
        $sql = "
            SELECT t.* FROM tasks AS t
            INNER JOIN users AS u
                ON t.user_id = u.id
            %s
            %s
            LIMIT %d OFFSET %d";

        $parts = ['', '', $limit, $offset,];
        $params = [];

        if ($ids !== null) {
            if ($ids) {
                $params = $ids;
                $parts[0] = sprintf(
                    "WHERE id IN (%s)",
                    str_repeat("?,", count($ids) - 1) . '?'
                );
            } else {
                return [];
            }
        }

        switch ($sort) {
            case "name-asc":    $parts[1] = "ORDER BY name ASC";    break;
            case "name-desc":   $parts[1] = "ORDER BY name DESC";   break;
            case "email-asc":   $parts[1] = "ORDER BY email ASC";   break;
            case "email-desc":  $parts[1] = "ORDER BY email DESC";  break;
            case "status-asc":  $parts[1] = "ORDER BY status ASC";  break;
            case "status-desc": $parts[1] = "ORDER BY status DESC"; break;
        }

        $query = sprintf($sql, ...$parts);
        $statm = $this->db->prepare($query);
        $statm->execute($params);

        $tasks = $this->buildTasks($statm);
        $users = $this->user->updatePool();

        return $tasks;
    }

    /**
     * Create task objects based on db result
     *
     * @param Traversable $result
     *
     * @return array of \App\Model\Task
     */
    protected function buildTasks(Traversable $result): array
    {
        $tasks = [];

        foreach ($result as $data) {
            $id = $data["id"];

            $task = $this->fromPool($id);
            $task->id = $data["id"];
            $task->user = $this->user->fromPool($data["user_id"]);
            $task->description = (string) $data["description"];
            $task->status = $data["status"];
            $task->created = new DateTime($data["created"]);

            if ($data["updated"]) {
                $task->updated = new DateTime($data["updated"]);
            } else {
                $task->updated = null;
            }

            $tasks[$id] = $task;
        }

        return $tasks;
    }

    /**
     * Get Task from pool
     *
     * @param int $id task id
     *
     * @return \App\Model\Task
     */
    public function fromPool(int $id): Task
    {
        $task =& $this->taskPool[$id];

        if (!isset($task)) {
            $task = new Task();
        }

        return $task;
    }

    /**
     * Remove all known objects
     */
    public function freePool(): void
    {
        $this->taskPool = [];
    }
}
