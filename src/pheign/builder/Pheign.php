<?php
/**
 * Created by IntelliJ IDEA.
 * User: brice_leboulch
 * Date: 11/08/2017
 * Time: 14:01
 */

namespace pheign\builder;

use Go\ParserReflection\ReflectionClass;

class Pheign
{
    private $class = null;
    private $uri = null;

    private $object;

    /**
     * @return null
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param null $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return null
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param null $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }


    public static function builder()
    {
        return new Pheign();
    }

    /**
     * WORK IN PROGRESS
     *
     * @return $this
     */
    public function encoder()
    {
        return $this;
    }

    /**
     * WORK IN PROGRESS
     *
     * @return $this
     */
    public function decoder()
    {
        return $this;
    }

    /**
     * @param $class
     * @return $this
     */
    public function target($class, $uri)
    {
        if (!class_exists($class)) {
            throw new \Exception("Unknown class $class");
        }
        $this->setClass($class);

        if (empty($uri)) {
            throw new \Exception("Uri can't be null");
        }

        $this->setUri($uri);

        return $this;
    }

    private function createObject()
    {
        $class = $this->getClass();
        $object = new $class();
        $object->uri = $this->getUri();

        return $object;
    }

    /**
     * @
     * @param $method
     * @param $arguments
     * @throws \Exception
     */
    public function __call($method, $arguments)
    {
        if (is_null($this->getObject())) {
            $this->setObject($this->createObject());
        }

        if (method_exists($this->getObject(), $method)) {
            return call_user_func_array(array($this->getObject(), $method), $arguments);
        } else {
            throw new \Exception("Unknown method $method for interface $this->class");
        }
    }

}