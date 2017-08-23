<?php
/**
 * Created by IntelliJ IDEA.
 * User: Airman
 * Date: 11/08/2017
 * Time: 23:32
 */

namespace pheign\builder;


use Curl\Curl;

class Caller
{
    /**
     * @var array
     */
    private $parameters = array();
    private $computedParameters = null;

    /**
     * @var array
     */
    private $headers = array();

    /**
     * @var string
     */
    private $method = "GET";

    /**
     * @var null
     */
    private $uri = null;

    /**
     * @var array
     */
    private $datas = array();

    /**
     * @var array
     */
    private $options = array();

    /**
     * @var string
     */
    private $target = "/";

    public function __construct()
    {
        $this->curl = new Curl();
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders($headers)
    {
        $headers = $this->replaceParameters($headers);
        foreach ($headers as $header) {
            if (strpos($header, ':') > -1) {
                list($key, $splitHeader) = explode(':', $header, 2);
                $this->headers[trim($key)] = trim($splitHeader);
            }
        }
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = strtolower($this->replaceParameters($method));
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
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $this->replaceParameters($target);
    }

    /**
     * @return array
     */
    public function getDatas()
    {
        return $this->datas;
    }

    /**
     * @param array $datas
     */
    public function setDatas($datas)
    {
        $this->datas = $this->replaceParameters($datas);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $this->replaceParameters($options);
    }

    public function call($object)
    {
        $result = false;

        $curl = new Curl();

        foreach ($this->getHeaders() as $key=>$header) {
            $curl->setHeader($key, $header);
        }

        foreach ($this->getOptions() as $constantName=>$value) {
            if (defined($constantName)) {
                $curl->setOpt(constant($constantName), 1.0*$value);
            }
        }

        switch (strtolower($this->getMethod())) {
            case 'get':
            case 'post':
            case 'delete':
            case 'put':
                call_user_func_array(array($curl, $this->getMethod()), array($this->getFullUrl(), $this->getDatas()));
                break;
        }

        if ($curl->error) {
            throw new \Exception($curl->error_message, $curl->error_code);
        }

        $result = $curl->response;

        return $result;
    }

    private function getFullUrl()
    {
        return $this->getUri() . $this->getTarget();
    }

    private function replaceParameters($content)
    {

        if (is_null($this->computedParameters)) {
            $this->computedParameters = array_map(function ($e) {
                return '{' . $e . '}';
            }, array_keys($this->parameters));
        }


        return $this->browseAndReplaceContent($content);
    }

    private function browseAndReplaceContent($content)
    {
        if (is_array($content)) {
            foreach ($content as $key=>$value) {
                if ($key === "value" && empty($value)) {
                    unset($content[$key]);
                } else {
                    $content[$key] = $this->browseAndReplaceContent($value);
                }
            }
        } else {
            $content = str_replace($this->computedParameters, $this->parameters, $content);
        }

        return $content;
    }


    private function validate()
    {
        return $this;
    }
}