<?php

namespace PH\PaymentHubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class OrderItemsCollectionType extends AbstractType
{
    const NAME = 'ph_items_collection';

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'oro_collection';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::NAME;
    }
}
