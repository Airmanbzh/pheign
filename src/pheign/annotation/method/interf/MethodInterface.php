<?php
/**
 * Created by IntelliJ IDEA.
 * User: brice_leboulch
 * Date: 11/08/2017
 * Time: 11:14
 */

namespace pheign\annotation\method\interf;


use Doctrine\Common\Annotations\Annotation;

class MethodInterface extends Annotation
{
    protected $method = null;

    /**
     * @return null
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param null $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

}