<?php

namespace PH\PaymentHubBundle\Form\Type;

use PH\PaymentHubBundle\Entity\PaymentInterface;
use PH\PaymentHubBundle\Entity\Subscription;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('total')
            ->add('notes')
            ->add('orderState', ChoiceType::class, [
                'choices' => [
                    SubscriptionInterface::STATE_CART => 'Cart',
                    SubscriptionInterface::STATE_COMPLETED => 'Completed',
                    SubscriptionInterface::STATE_PAYMENT_SELECTED => 'Payment Selected',
                    SubscriptionInterface::STATE_PAYMENT_SKIPPED => 'Payment Skipped',
                    SubscriptionInterface::STATE_CANCELED => 'Canceled',
                    SubscriptionInterface::STATE_REFUNDED => 'Refunded',
                ],
            ])
            ->add('checkoutState', ChoiceType::class, [
                'choices' => [
                    'cart' => 'Cart',
                    'completed' => 'Completed',
                    'payment_selected' => 'Payment Selected',
                    'payment_skipped' => 'Payment Skipped',
                ],
            ])
            ->add('paymentState', ChoiceType::class, [
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Subscription::class,
        ));
    }

    public function getName()
    {
        return 'subscriptions_subscription';
    }
}
