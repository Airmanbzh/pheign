<?php
namespace pheign\annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Method marker
 *
 * @Annotation
 *
 */
class Datas extends Annotation
{
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}