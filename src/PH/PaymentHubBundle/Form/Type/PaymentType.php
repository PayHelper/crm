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
                    PaymentInterface::STATE_NEW => 'Unconfirmed',
                    PaymentInterface::STATE_AWAITING_PAYMENT => 'Awaiting payment',
                    PaymentInterface::STATE_PARTIALLY_PAID => 'Partially Paid',
                    PaymentInterface::STATE_CANCELLED => 'Canceled',
                    PaymentInterface::STATE_PAID => 'Paid',
                    PaymentInterface::STATE_PARTIALLY_REFUNDED => 'Partialy refunded',
                    PaymentInterface::STATE_REFUNDED => 'Refunded',
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
