<?php

namespace PH\PaymentHubBundle\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use PH\PaymentHubBundle\Entity\CustomerInterface;
use PH\PaymentHubBundle\Generator\RandomnessGeneratorInterface;

class CustomerEmailChangeEventListener
{
    /**
     * @var RandomnessGeneratorInterface
     */
    protected $randomnessGenerator;

    /**
     * CustomerEmailChangeEventListener constructor.
     *
     * @param RandomnessGeneratorInterface $randomnessGenerator
     */
    public function __construct(RandomnessGeneratorInterface $randomnessGenerator)
    {
        $this->randomnessGenerator = $randomnessGenerator;
    }

    public function preUpdate(PreUpdateEventArgs $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof CustomerInterface) {
            if ($event->hasChangedField('email')) {
                $entity->setProcessEmailVerification(true);
                $entity->setEmailVerificationToken($this->randomnessGenerator->generateUriSafeString(10));

                $em = $event->getEntityManager();
                $uow = $em->getUnitOfWork();
                $meta = $em->getClassMetadata(get_class($entity));
                $uow->recomputeSingleEntityChangeSet($meta, $entity);
            }
        }
    }
}
