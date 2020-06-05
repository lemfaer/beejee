<?php

namespace App\Model;

use DateTime;
use App\Core\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property ?string $login
 * @property ?string $password
 * @property ?string $hashPassword
 * @property \DateTime $created
 * @property \DateTime|null $updated
 */
class User extends Model
{
    protected $id;
    protected $name;
    protected $email;
    protected $login;
    protected $hashPassword;
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
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $login
     */
    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->hashPassword = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @param string $hashPassword
     */
    public function setHashPassword(?string $hashPassword): void
    {
        $this->hashPassword = $hashPassword;
    }

    /**
     * Check if user password is correct
     *
     * @param string $password provided password
     *
     * @return boolean
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->hashPassword);
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
}
