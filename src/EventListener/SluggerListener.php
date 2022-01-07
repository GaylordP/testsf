<?php

namespace App\EventListener;

use App\Annotation\Slugger;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SluggerListener
{
    private $em;
    private $annotationReader;
    private $accessor;
    private $slugger;

    public function __construct(
        EntityManagerInterface $em,
        Reader $annotationReader,
        PropertyAccessorInterface $accessor,
        SluggerInterface $slugger
    ) {
        $this->em = $em;
        $this->annotationReader = $annotationReader;
        $this->accessor = $accessor;
        $this->slugger = $slugger;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $reflection = new \ReflectionClass($entity);

        foreach ($reflection->getProperties() as $property) {
            $this->isSluggerInProperty($entity, $property);
        }
    }

    private function isSluggerInProperty(
        object $entity,
        \ReflectionProperty $property
    ): void {
        $annotation = $this->annotationReader->getPropertyAnnotation($property, Slugger::class);

        if (
            null !== $annotation
                &&
            null === $this->accessor->getValue($entity, $property->getName())
        ) {
            $slugify = $this->slugger->slug($this->accessor->getValue($entity, $annotation->field))->lower();


            $countAlreadySlugExist = (int) $this
                ->em
                ->createQueryBuilder()
                ->select('COUNT(slg.id)')
                ->from($property->class, 'slg')
                ->where('slg.' . $property->getName() . ' LIKE :slug')
                ->setParameter('slug', $slugify . '%')
                ->getQuery()
                ->getSingleScalarResult()
            ;

            if (0 !== $countAlreadySlugExist) {
                $slugify = $slugify . '-' . ($countAlreadySlugExist + 1);
            }

            $this->accessor->setValue(
                $entity,
                $property->getName(),
                $slugify
            );
        }
    }
}
