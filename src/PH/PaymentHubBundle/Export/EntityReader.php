<?php

namespace PH\PaymentHubBundle\Export;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;

class EntityReader extends \Oro\Bundle\ImportExportBundle\Reader\EntityReader
{
    protected function createSourceEntityQueryBuilder($entityName, Organization $organization = null, array $ids = [])
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->registry
            ->getManagerForClass($entityName);

        $qb = $entityManager
            ->getRepository($entityName)
            ->createQueryBuilder('o')
            ->where('o.customer IS NULL');

        if (is_subclass_of($entityName, SubscriptionInterface::class)) {
            $qb->leftJoin('o.customer', 'c')
                ->addSelect('c')
                ->orWhere('c.contactForbidden = :contactForbidden')
                ->setParameter('contactForbidden', false);
        }

        $metadata = $entityManager->getClassMetadata($entityName);
        foreach (array_keys($metadata->getAssociationMappings()) as $fieldName) {
            // can't join with *-to-many relations because they affects query pagination
            if ($metadata->isAssociationWithSingleJoinColumn($fieldName)) {
                $alias = '_' . $fieldName;
                $qb->addSelect($alias);
                $qb->leftJoin('o.' . $fieldName, $alias);
            }
        }

        foreach ($identifierNames = $metadata->getIdentifierFieldNames() as $fieldName) {
            $qb->orderBy('o.' . $fieldName, 'ASC');
        }
        if (! empty($ids)) {
            if (count($identifierNames) > 1) {
                throw new \LogicException(sprintf(
                    'not supported entity (%s) with composite primary key.',
                    $entityName
                ));
            }
            $identifierName = 'o.' . current($identifierNames);
            $qb
                ->andWhere($identifierName . ' IN (:ids)')
                ->setParameter('ids', $ids);
        }

        $this->addOrganizationLimits($qb, $entityName, $organization);

        return $qb;
    }
}
