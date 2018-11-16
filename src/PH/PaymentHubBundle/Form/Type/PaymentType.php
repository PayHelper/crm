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
use Symfony\Component\Validator\Constraints\Bic;
use Symfony\Component\Validator\Constraints\Iban;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('methodCode', TextType::class, [
                'label' => 'ph.paymenthub.payments.label.method_code',
            ])
            ->add('currencyCode', CurrencyType::class, [
                'label' => 'ph.paymenthub.payments.label.currency',
            ])
            ->add('amount', NumberType::class, [
                'label' => 'ph.paymenthub.payments.label.amount',
            ])
            ->add('holderName', TextType::class, [
                'label' => 'ph.paymenthub.payments.label.holder_name',
            ])
            ->add('bankName', TextType::class, [
                'label' => 'ph.paymenthub.payments.label.bank_name',
            ])
            ->add('iban', TextType::class, [
                'label' => 'ph.paymenthub.payments.label.iban',
                'constraints' => [
                    new Iban(),
                ],
            ])
            ->add('accountNumber', TextType::class, [
                'label' => 'ph.paymenthub.payments.label.account_number',
            ])
            ->add('bin', TextType::class, [
                'label' => 'ph.paymenthub.payments.label.bic',
                'constraints' => [
                    new Bic([
                        'message' => 'ph.paymenthub.payments.error.bic',
                    ]),
                ],
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'ph.paymenthub.subscription.state.label',
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
