<?php

namespace Truelab\VirtualPropertyBundle\Annotation;

/**
 * Class VirtualProperty
 * @package Truelab\VirtualPropertyBundle\Annotation
 * @Annotation
 */
class VirtualProperty
{
    protected $objectName;
    protected $methodName;

    /**
     * @param $options
     * @throws \InvalidArgumentException
     */
    public function __construct($options)
    {
        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->$key = $value;
        }
    }

    /**
     * @return string
     */
    public function getObjectName()
    {
        return $this->objectName;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }
}