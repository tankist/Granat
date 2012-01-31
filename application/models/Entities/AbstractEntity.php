<?php
namespace Entities;

abstract class AbstractEntity
{

    /**
     * @param $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __get($name)
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($this, $getter)) {
            return call_user_func(array($this, $getter));
        }
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new \InvalidArgumentException('Property ' . $name . ' not found');
    }

    /**
     * @param $name
     * @param $value
     * @return AbstractEntity|mixed
     * @throws \InvalidArgumentException
     */
    public function __set($name, $value)
    {
        $setter = 'set' . ucfirst($name);
        if (method_exists($this, $setter)) {
            return call_user_func(array($this, $setter), $value);
        }
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
            return $this;
        }
        throw new \InvalidArgumentException('Property ' . $name . ' not found');
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return property_exists($this, $name) && isset($this->$name);
    }

    /**
     * @param $name
     * @throws \InvalidArgumentException
     */
    public function __unset($name)
    {
        if (property_exists($this, $name)) {
            unset($this->$name);
        }
        throw new \InvalidArgumentException('Property ' . $name . ' not found');
    }

    /**
     * @param array $data
     * @return AbstractEntity
     */
    public function populate(array $data)
    {
        foreach ($data as $field => $value) {
            if (property_exists($this, $field)) {
                $this->__set($field, $value);
            }
        }
        return $this;
    }

}
