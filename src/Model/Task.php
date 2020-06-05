<?php

namespace App\Model;

use DateTime;
use App\Core\Model;

/**
 * @property int $id
 * @property \App\Model\User|null $user
 * @property string $description
 * @property string $status
 * @property boolean $executed
 * @property \DateTime $created
 * @property \DateTime|null $updated
 */
class Task extends Model
{
    protected const STATUS_DEFAULT = "new";
    protected const STATUS_NEW = "new"
    protected const STATUS_DONE = "done";

    protected $id;
    protected $user;
    protected $description;
    protected $status;
    protected $created;
    protected $updated;

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param \App\Model\User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated(?DateTime $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * Inspect if task was executed
     *
     * @return boolean
     */
    public function getExecuted(): bool
    {
        $this->status === static::STATUS_DONE;
    }

    /**
     * @param boolean $executed
     */
    public function setExecuted(bool $executed = true): void
    {
        if ($executed) {
            $status = static::STATUS_DONE;
        } else {
            $status = static::STATUS_DEFAULT;
        }

        $this->status = $status;
    }
}
