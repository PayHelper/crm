<?php

namespace PH\PaymentHubBundle\Service;

use Oro\Bundle\ChannelBundle\Entity\Channel;
use Doctrine\ORM\EntityManagerInterface;
use PH\PaymentHubBundle\Entity\CustomerInterface;
use PH\PaymentHubBundle\Generator\RandomnessGeneratorInterface;

/**
 * Class SubscriptionService.
 */
class CustomerService implements CustomerServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var RandomnessGeneratorInterface
     */
    protected $randomnessGenerator;

    /**
     * CustomerService constructor.
     *
     * @param EntityManagerInterface       $entityManager
     * @param RandomnessGeneratorInterface $randomnessGenerator
     */
    public function __construct(EntityManagerInterface $entityManager, RandomnessGeneratorInterface $randomnessGenerator)
    {
        $this->entityManager = $entityManager;
        $this->randomnessGenerator = $randomnessGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareCustomer(CustomerInterface $customer)
    {
        $channelRepository = $this->entityManager->getRepository(Channel::class);
        $customer->setDataChannel($channelRepository->findOneBy(['name' => 'Payment Hub Channel']));
        $customer->setCreatedAt(new \DateTime());
        $customer->setUpdatedAt(new \DateTime());
        $customer->setCustomerUpdateToken($this->randomnessGenerator->generateUriSafeString(10));
        $this->reassignAddresses($customer);

        return $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function reassignAddresses(CustomerInterface $customer)
    {
        foreach ($customer->getAddresses() as $address) {
            $address->setOwner($customer);
        }

        return $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function resetUpdateToken(CustomerInterface $customer)
    {
        $customer->setCustomerUpdateToken($this->randomnessGenerator->generateUriSafeString(10));
        $this->reassignAddresses($customer);
    }
}
