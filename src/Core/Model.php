<?php

namespace App\Core;

abstract class Model
{
    /**
     * Check for model value
     *
     * @param string $name property name
     *
     * @return boolean
     */
    public function __isset(string $name)
    {
        $method = sprintf("get%s", ucfirst($name));

        if (method_exists($this, $method)) {
            return true;
        }

        if (property_exists($this, $name)) {
            return true;
        }

        return false;
    }

    /**
     * Get model values
     *
     * @param string $name property name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        $method = sprintf("get%s", ucfirst($name));

        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }

        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }

    /**
     * Set model value
     *
     * @param string $name property name
     * @param mixed $value property value to ser
     */
    public function __set(string $name, $value)
    {
        $method = sprintf("set%s", ucfirst($name));

        if (method_exists($this, $method)) {
            return call_user_func([$this, $method], $value);
        }
    }
}
