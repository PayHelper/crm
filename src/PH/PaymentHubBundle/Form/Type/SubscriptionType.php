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
            ->add('state', ChoiceType::class, [
                'choices' => [
                    SubscriptionInterface::STATE_NEW => 'Unconfirmed',
                    SubscriptionInterface::STATE_FULFILLED => 'Confirmed',
                    SubscriptionInterface::STATE_BOUNCED => 'Bounced',
                    SubscriptionInterface::STATE_CANCELED => 'Cancelled',
                    SubscriptionInterface::STATE_REFUNDED => 'Refunded',
                    SubscriptionInterface::STATE_TERMINATED => 'Terminated',
                    SubscriptionInterface::STATE_EXPIRED => 'Expired'
                ],
            ])
            ->add('purchaseState', ChoiceType::class, [
                'choices' => [
                    'new' => 'New',
                    'completed' => 'Completed',
                    'payment_selected' => 'Payment Selected',
                    'payment_skipped' => 'Payment Skipped',
                ],
            ])
            ->add('paymentState', ChoiceType::class, [
                'choices' => [
                    PaymentInterface::STATE_NEW => 'New',
                    PaymentInterface::STATE_AWAITING_PAYMENT => 'Awaiting payment',
                    PaymentInterface::STATE_PARTIALLY_PAID => 'Partially Paid',
                    PaymentInterface::STATE_CANCELLED => 'Canceled',
                    PaymentInterface::STATE_PAID => 'Paid',
                    PaymentInterface::STATE_PARTIALLY_REFUNDED => 'Partialy refunded',
                    PaymentInterface::STATE_REFUNDED => 'Refunded',
                ],
            ])
            ->add('items', OrderItemsCollectionType::class, [
                    'label' => '',
                    'type' => OrderItemType::class,
                    'required' => false,
                    'options' => array('data_class' => 'PH\PaymentHubBundle\Entity\OrderItem'),
                ]
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
            ->add('intention')
            ->add('source')
            ->add('endDate', OroDateType::class, [
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
