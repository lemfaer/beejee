<?php

namespace App\Validator;

use Exception;
use App\Model\Task;

class TaskFormValidator
{
    protected const NAME_MAX = 255;
    protected const DESCRIPTION_LIMIT = 1000;

    /**
     * Found validating errors
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Get last errors and remove
     *
     * @return array
     */
    public function getErrors(): array
    {
        $errors = $this->errors;

        unset($this->errors);

        return $errors;
    }

    /**
     * Sanitize data from input form
     *
     * @param \App\Model\Task update model
     * @param array $data form data
     *
     * @return void
     */
    public function sanitizeData(Task $task, array $data): void
    {
        $nameLength = mb_strlen($data["name"]);
        $descriptionLength = mb_strlen($data["description"]);

        $data["email"] = filter_var($data["email"], FILTER_SANITIZE_EMAIL);
        $data["executed"] = (bool) filter_var($data["executed"] ?? null, FILTER_VALIDATE_BOOLEAN);

        if (empty($data["email"])) {
            $this->errors[] = "Wrong email provided";
        }

        if ($nameLength > static::NAME_MAX) {
            $this->errors[] = "Wrong name provided";
        }

        if ($descriptionLength > static::DESCRIPTION_LIMIT) {
            $this->errors[] = "Wrong description provided";
        }

        if (!empty($this->errors)) {
            throw new Exception("Wrong data provided");
        }

        $this->buildEntity($task, $data);
    }

    protected function buildEntity(Task $task, array $data): void
    {
        $task->user->name = $data["name"];
        $task->user->email = $data["email"];
        $task->description = $data["description"];
        $task->setExecuted($data["executed"]);
    }
}
