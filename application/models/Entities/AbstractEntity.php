<?php
namespace Entities;

abstract class AbstractEntity
{

    public function __get($name)
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($this, $getter)) {
            return call_user_func(array($this, $getter));
        }
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw \InvalidArgumentException('Property ' . $name . ' not found');
    }

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

    public function __isset($name)
    {
        return property_exists($this, $name) && isset($this->$name);
    }

    public function __unset($name)
    {
        if (property_exists($this, $name)) {
            unset($this->$name);
        }
        throw \InvalidArgumentException('Property ' . $name . ' not found');
    }

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
