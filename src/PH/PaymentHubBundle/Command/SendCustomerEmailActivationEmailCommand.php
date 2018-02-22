<?php

/*
 * Copyright 2017 Sourcefabric z.ú. and contributors.
 */

namespace PH\PaymentHubBundle\Command;

use Oro\Bundle\CronBundle\Command\CronCommandInterface;
use PH\PaymentHubBundle\Entity\Customer;
use PH\PaymentHubBundle\Entity\CustomerInterface;
use PH\PaymentHubBundle\Entity\NotificationLog;
use PH\PaymentHubBundle\Entity\NotificationLogInterface;
use PH\PaymentHubBundle\Entity\Subscription;
use PH\PaymentHubBundle\Repository\CustomerRepository;
use PH\PaymentHubBundle\Service\CustomerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendCustomerEmailActivationEmailCommand extends ContainerAwareCommand implements CronCommandInterface
{
    const COMMAND_NAME = 'oro:cron:subscription:send-customer-email-verification';

    /**
     * {@inheritdoc}
     */
    public function getDefaultDefinition()
    {
        return '*/1 * * * *';
    }

    /**
     * Console command configuration.
     */
    public function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Send customer new email activation email');
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return true;
    }

    /**
     * Runs command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \InvalidArgumentException
     *
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $entityManager = $container->get('doctrine')->getManager();
        /** @var CustomerRepository $repository */
        $repository = $entityManager->getRepository(Customer::class);
        /** @var CustomerServiceInterface $customerService */
        $customerService = $container->get('ph_payment_hub.service.customer');

        $customers = $repository->getForEmailVerification()->getResult();
        /* @var CustomerInterface $customer */
        foreach ($customers as $customer) {
            $output->writeln(sprintf('Processing customer %s', $customer->getId()));
            $emailData = $customerService->sendEmailVerificationEmail($customer);
            $customer->setProcessEmailVerification(false);
            $output->writeln(sprintf('Email with subject: %s was sent', $emailData['subject']));

            $notification = new NotificationLog(NotificationLogInterface::TYPE_CUSTOMER_EMAIL_VERIFICATION);
            $notification->setEmailContent($emailData['body']);
            $notification->setCustomer($customer);
            $entityManager->persist($notification);
            $output->writeln('Notification was logged');
        }

        $entityManager->flush();
    }
}
