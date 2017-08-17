<?php

namespace PH\PaymentHubBundle\Form\Type;

use Oro\Bundle\AddressBundle\Form\Type\AddressType as BaseAddressType;
use PH\PaymentHubBundle\Entity\Address;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddressType.
 */
class AddressType extends BaseAddressType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class', Address::class);
    }
}
