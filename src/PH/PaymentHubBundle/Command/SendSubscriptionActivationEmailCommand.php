<?php

/*
 * Copyright 2017 Sourcefabric z.ú. and contributors.
 */

namespace PH\PaymentHubBundle\Command;

use Oro\Bundle\CronBundle\Command\CronCommandInterface;
use PH\PaymentHubBundle\Entity\NotificationLog;
use PH\PaymentHubBundle\Entity\Subscription;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendSubscriptionActivationEmailCommand extends ContainerAwareCommand implements CronCommandInterface
{
    const COMMAND_NAME = 'oro:cron:subscription:send-activation-notification';

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
            ->setDescription('Send subscription activation email');
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
        $repository = $entityManager->getRepository(Subscription::class);
        $subscriptionService = $container->get('ph_payment_hub.service.subscription');

        $subscriptionsForActivation = $repository->getWithoutActivationNotification()->getResult();
        /** @var SubscriptionInterface $subscription */
        foreach ($subscriptionsForActivation as $subscription) {
            $output->writeln(sprintf('Processing subscription %s', $subscription->getId()));
            $emailData = $subscriptionService->sendTransactionCompletedEmail($subscription);
            $subscription->setActivationEmailSend(new \DateTime('now'));
            $output->writeln(sprintf('Email with subject: %s was sent', $emailData['subject']));

            $notification = new NotificationLog(NotificationLog::TYPE_SUBSCRIPTION_ACTIVATION);
            $notification->setEmailContent($emailData['body']);
            $notification->setSubscription($subscription);
            $notification->setCustomer($subscription->getCustomer());
            $entityManager->persist($notification);
            $output->writeln('Notification was logged');
        }

        $entityManager->flush();
    }
}
