<?php

namespace pheign\aspect;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\After;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\Execution;
use Go\Lang\Annotation\Pointcut;

use pheign\annotation\DefineParameters;
use pheign\builder\Caller;

class PheignAspect implements Aspect
{
    private $caller = null;

    /**
     * @param MethodInvocation $invocation
     *
     * @Before("@execution(pheign\annotation\Pheign)", order=1)
     */
    public function beforePheignExecution(MethodInvocation $invocation)
    {
        $this->caller = new Caller();
        $this->defineParameters($invocation);
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @Before("@execution(pheign\annotation\method\POST) || @execution(pheign\annotation\method\GET) || @execution(pheign\annotation\method\PUT) || @execution(pheign\annotation\method\DELETE)", order=2)
     */
    public function beforeMethodExecution(MethodInvocation $invocation)
    {
        foreach ($invocation->getMethod()->getAnnotations() as $annotation) {
            if (strpos(get_class($annotation), 'pheign\\annotation\\method\\') > -1) {
                $reflect = new \ReflectionClass($annotation);
                $this->caller->setMethod($reflect->getShortName());
            }
        }
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @Before("@execution(pheign\annotation\Target)", order=3)
     */
    public function beforeTargetExecution(MethodInvocation $invocation)
    {
        $this->caller->setTarget($invocation->getMethod()->getAnnotation(\pheign\annotation\Target::class)->value);
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @Before("@execution(pheign\annotation\Headers)", order=4)
     */
    public function beforeHeadersExecution(MethodInvocation $invocation)
    {
        $headers = (array)$invocation->getMethod()->getAnnotation(\pheign\annotation\Headers::class)->value;
        $this->caller->setHeaders($headers);
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @Before("@execution(pheign\annotation\Datas)", order=5)
     */
    public function beforeDatasExecution(MethodInvocation $invocation)
    {
        $datas = get_object_vars($invocation->getMethod()->getAnnotation(\pheign\annotation\Datas::class));
        $this->caller->setDatas($datas);
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @Before("@execution(pheign\annotation\Options)", order=5)
     */
    public function beforeOptionsExecution(MethodInvocation $invocation)
    {
        $datas = get_object_vars($invocation->getMethod()->getAnnotation(\pheign\annotation\Options::class));
        $this->caller->setOptions($datas);
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @Around("@execution(pheign\annotation\Pheign)")
     */
    public function afterPheignExecution(MethodInvocation $invocation)
    {
        $this->caller->setUri($invocation->getThis()->uri);
        return $this->caller->call($invocation->getThis());
    }

    private function defineParameters(MethodInvocation $invocation)
    {
        $parameters = array();

        foreach ($invocation->getMethod()->getParameters() as $index=>$parameter) {
            $parameters[$parameter->name] = $invocation->getArguments()[$index];
        }

        $this->caller->setParameters($parameters);
    }
}