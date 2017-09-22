<?php

namespace PH\PaymentHubBundle\Form\Type;

use Oro\Bundle\CurrencyBundle\Form\Type\CurrencyType;
use PH\PaymentHubBundle\Entity\Payment;
use PH\PaymentHubBundle\Entity\PaymentInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('methodCode', TextType::class)
            ->add('currencyCode', CurrencyType::class)
            ->add('amount', NumberType::class)
            ->add('state', ChoiceType::class, [
                'choices' => [
                    PaymentInterface::STATE_CART => 'Cart',
                    PaymentInterface::STATE_NEW => 'New',
                    PaymentInterface::STATE_PROCESSING => 'Processing',
                    PaymentInterface::STATE_COMPLETED => 'Completed',
                    PaymentInterface::STATE_FAILED => 'Failed',
                    PaymentInterface::STATE_CANCELLED => 'Canceled',
                    PaymentInterface::STATE_REFUNDED => 'Refunded',
                    PaymentInterface::STATE_UNKNOWN => 'Unknown',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Payment::class,
        ));
    }

    public function getName()
    {
        return 'subscriptions_payment';
    }
}
