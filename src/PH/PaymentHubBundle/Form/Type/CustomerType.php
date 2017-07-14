<?php

namespace PH\PaymentHubBundle\Form\Type;

use PH\PaymentHubBundle\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CustomerType.
 */
class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('gender')
            ->add('birthday')
            ->add('email')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Customer::class,
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'subscriptions_customer';
    }
}
