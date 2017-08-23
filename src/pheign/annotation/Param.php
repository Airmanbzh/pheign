<?php
namespace pheign\annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Method marker
 *
 * @Annotation
 *
 */
class Param extends Annotation
{
    public $var = array();
}