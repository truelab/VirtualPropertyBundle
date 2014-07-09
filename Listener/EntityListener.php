<?php

namespace Truelab\VirtualPropertyBundle\Listener;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Truelab\VirtualPropertyBundle\Generator\VirtualPropertyGeneratorBag;

class EntityListener
{

    /** @var EntityManager $entityManager  */
    protected $entityManager;
    /** @var array $entities */
    protected $entities;
    /** @var Reader $reader */
    protected $reader;
    /** @var string $annotationClass */
    protected $annotationClass = 'Truelab\\VirtualPropertyBundle\\Annotation\\VirtualProperty';
    /** @var VirtualPropertyGeneratorBag $virtualPropertyGeneratorBag */
    protected $virtualPropertyGeneratorBag;

    public function __construct(Reader $reader, VirtualPropertyGeneratorBag $virtualPropertyGeneratorBag)
    {
        $this->reader = $reader;
        $this->virtualPropertyGeneratorBag = $virtualPropertyGeneratorBag;
    }


    /**
     * Generate Dynamic fields after Load Event
     * @param LifecycleEventArgs $args
     *
     * @return mixed
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        return $this->updateVirtualProperties($entity);
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $this->entities = array();
        /* @var $em \Doctrine\ORM\EntityManager */
        $this->entityManager = $eventArgs->getEntityManager();
        /* @var $uow \Doctrine\ORM\UnitOfWork */
        $uow = $this->entityManager->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->entities[] = $entity;
        }
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $this->entities[] = $entity;
        }
    }

    /**
     * Generate Dynamic fields after flush
     * @param PostFlushEventArgs $eventArgs
     */
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        $this->entityManager = $eventArgs->getEntityManager();
        foreach ($this->entities as $entity) {
            $this->updateVirtualProperties($entity);
        }
    }


    /**
     * @param $entity
     */
    public function updateVirtualProperties($entity)
    {
        $reflectionObject = new \ReflectionObject($entity);
        $reflectionProperties = $reflectionObject->getProperties();
        foreach ($reflectionProperties as $reflectionProperty) {
            $annotation = $this->reader->getPropertyAnnotation($reflectionProperty, $this->annotationClass);
            Debug::dump($annotation);
            if ($annotation) {
                $generatorWrapper = $this->virtualPropertyGeneratorBag->getGeneratorWrapper($reflectionProperty);
                $generator = $generatorWrapper->getGenerator();
                $method = $generatorWrapper->getMethodName();
                $entity = $generator->$method($entity);
            }
        }

        return $entity;
    }
}