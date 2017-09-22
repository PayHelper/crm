<?php

namespace PH\PaymentHubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class PaymentsCollectionType extends AbstractType
{
    const NAME = 'ph_payments_collection';

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
