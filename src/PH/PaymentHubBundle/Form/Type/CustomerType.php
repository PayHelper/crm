<?php

namespace PH\PaymentHubBundle\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\OroBirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use PH\PaymentHubBundle\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

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
            ->add('gender', 'oro_gender', array('required' => false, 'label' => 'oro.contact.gender.label'))
            ->add(
                'birthday',
                OroBirthdayType::class,
                array('required' => false, 'label' => 'oro.contact.birthday.label')
            )
            ->add('email', EmailType::class, array(
                'constraints' => array(
                    new Email(array('message' => 'Invalid email address.')),
                ),
            ))
            ->add('phone')
            ->add('addresses', 'oro_address_collection', array(
                    'label' => '',
                    'type' => 'oro_typed_address',
                    'required' => false,
                    'options' => array('data_class' => 'PH\PaymentHubBundle\Entity\Address'),
                )
            )
            ->add('newsletterAllowed')
            ->add('giftAllowed')
            ->add('comment')
            ->add('publicComment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Customer::class,
                'csrf_protection' => false,
            )
        );
    }

    public function getName()
    {
        return 'subscriptions_customer';
    }
}
