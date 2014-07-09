<?php

namespace Truelab\VirtualPropertyBundle\Generator;

class VirtualPropertyGeneratorBag
{
    /**
     * @var array $generatorWrappers
     */
    protected $generatorWrappers;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->generatorWrappers = array();
    }

    /**
     * @param $generator
     * @param string $className
     * @param string $propertyName
     * @param string $methodName
     */
    public function addGenerator($generator, $className, $propertyName, $methodName)
    {
        $generatorWrapper = new VirtualPropertyGeneratorWrapper();
        $generatorWrapper->setGenerator($generator);
        $generatorWrapper->setClassName($className);
        $generatorWrapper->setPropertyName($propertyName);
        $generatorWrapper->setMethodName($methodName);

        $this->generatorWrappers[] = $generatorWrapper;
    }


    public function getGeneratorWrapper(\ReflectionProperty $reflectionProperty)
    {
        $propertyName = $reflectionProperty->getName();
        $className = $reflectionProperty->getDeclaringClass()->getName();

        foreach ($this->generatorWrappers as $generatorWrapper)
        {
            /** @var VirtualPropertyGeneratorWrapper $generatorWrapper */
            if ($generatorWrapper->getPropertyName() === $propertyName &&
                $generatorWrapper->getClassName() === $className) {
                return $generatorWrapper;
            }
        }

    }
}