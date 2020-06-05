<?php

namespace App\Repository;

use Exception;
use Traversable;
use DateTime;
use App\Core\Repository;
use App\Model\User;

class UserRepository extends Repository
{
    /**
     * List of known users
     *
     * @var array of \App\Model\User
     */
    protected $userPool = [];

    /**
     * Get multiple users by ids
     *
     * @param array $ids
     *
     * @return array of \App\Model\User
     */
    public function getUsers(array $ids): array
    {
        if (!$ids) {
            return [];
        }

        $params = $ids;
        $sql = sprintf(
            "SELECT * FROM users WHERE id IN (%s)",
            str_repeat("?,", count($ids) - 1) . '?'
        );

        $statm = $this->db->prepare($query);
        $statm->execute($params);

        $users = $this->buildUsers($statm);

        return $users;
    }

    /**
     * Get unique user based on email. If not exists - create
     *
     * @param string $email
     * @param string $name
     *
     * @return \App\Model\User
     */
    public function makeUniqueUser($email, $name): User
    {
        /*
         * Create user if not exists
         */
        $sql = "INSERT IGNORE SET email=?, name=?";
        $statm = $this->db->prepare($sql);
        $statm->execute([$email, $name]);

        /*
         * Select user data from db by user email
         */
        $sql = "SELECT * FROM user WHERE email = ? LIMIT 1";
        $statm = $this->db->prepare($sql);
        $statm->execute([$email]);

        $users = $this->buildUsers($statm);

        return array_shift($users);
    }

    /**
     * Create user objects based on db result
     *
     * @param Traversable $result
     *
     * @return array of \App\Model\User
     */
    protected function buildUsers(Traversable $result): array
    {
        $users = [];

        foreach ($result as $data) {
            $id = $data["id"];

            $user = $this->fromPool($id);
            $user->id = $data["id"];
            $user->name = $data["name"];
            $user->email = $data["email"];
            $user->login = $data["login"];
            $user->hashPassword = $data["password"];
            $user->created = new DateTime($data["created"]);

            if ($data["updated"]) {
                $user->updated = new DateTime($data["updated"]);
            } else {
                $user->updated = null;
            }

            $users[$id] = $user;
        }

        return $users;
    }

    /**
     * Get User from pool
     *
     * @param int $id user id
     *
     * @return \App\Model\User
     */
    public function fromPool(int $id): User
    {
        $user =& $this->userPool[$id];

        if (!isset($user)) {
            $user = new User();
        }

        return $user;
    }

    /**
     * Update objects in pool
     *
     * @return array objects in pool
     */
    public function updatePool(): array
    {
        return $this->getUsers(array_keys($this->userPool));
    }

    /**
     * Remove all known objects
     */
    public function freePool(): void
    {
        $this->userPool = [];
    }
}
