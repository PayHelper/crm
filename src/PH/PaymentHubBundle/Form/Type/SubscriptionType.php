<?php

namespace PH\PaymentHubBundle\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\OroDateType;
use PH\PaymentHubBundle\Entity\Customer;
use PH\PaymentHubBundle\Entity\PaymentInterface;
use PH\PaymentHubBundle\Entity\Subscription;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('providerType')
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
            ->add('items', OrderItemsCollectionType::class, array(
                    'label' => '',
                    'type' => OrderItemType::class,
                    'required' => false,
                    'options' => array('data_class' => 'PH\PaymentHubBundle\Entity\OrderItem'),
                )
            )
            ->add('payments', PaymentsCollectionType::class, [
                'label' => '',
                'type' => PaymentType::class,
                'required' => false,
                'options' => array('data_class' => 'PH\PaymentHubBundle\Entity\Payment'),
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'placeholder' => ' ',
                'choice_label' => function ($customer) {
                    return $customer->getFirstName().' '.$customer->getLastName();
                },
            ])
            ->add('interval', ChoiceType::class, [
                'placeholder' => ' ',
                'choices' => [
                    SubscriptionInterface::INTERVAL_MONTH => 'Monthly',
                    SubscriptionInterface::INTERVAL_QUARTERLY => 'Quarterly',
                    SubscriptionInterface::INTERVAL_YEAR => 'Yearly',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    SubscriptionInterface::TYPE_RECURRING => 'Recurring',
                    SubscriptionInterface::TYPE_NONRECURRING => 'Not recurring',
                ],
            ])
            ->add('startDate', OroDateType::class, [
                'required' => false,
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
