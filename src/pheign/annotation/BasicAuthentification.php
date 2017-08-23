<?php
namespace pheign\annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Method marker
 *
 * @Annotation
 *
 */
class BasicAuthentification extends Annotation
{
    public $username;
    public $password;
}