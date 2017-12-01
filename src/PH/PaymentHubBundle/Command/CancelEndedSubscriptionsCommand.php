<?php

/*
 * Copyright 2017 Sourcefabric z.ú. and contributors.
 */

namespace PH\PaymentHubBundle\Command;

use Oro\Bundle\CronBundle\Command\CronCommandInterface;
use Oro\Bundle\IntegrationBundle\Provider\Rest\Client\Guzzle\GuzzleRestException;
use PH\PaymentHubBundle\Entity\Subscription;
use PH\PaymentHubBundle\Repository\SubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CancelEndedSubscriptionsCommand extends ContainerAwareCommand implements CronCommandInterface
{
    const COMMAND_NAME = 'oro:cron:subscription:cancel-ended';

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
            ->setDescription('Cancels ended subscriptions.');
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return true;
    }

    /**
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
        /** @var SubscriptionRepository $repository */
        $repository = $entityManager->getRepository(Subscription::class);
        $subscriptionCanceller = $container->get('ph_payment_hub.service.subscription_canceller');
        $endedSubscriptions = $repository->getEndedSubscriptions()->getResult();

        foreach ($endedSubscriptions as $endedSubscription) {
            $output->writeln(sprintf('<info>Processing subscription with id: %s.</info>', $endedSubscription->getId()));

            try {
                $subscriptionCanceller->cancel($endedSubscription);
            } catch (GuzzleRestException $e) {
                $output->writeln(sprintf('<error>Subscription with id: %s has not been processed!</error>', $endedSubscription->getId()));
                $output->writeln('<error>'.$e->getResponse()->getBodyAsString().'</error>');

                continue;
            }

            $output->writeln('<info>Subscription has been processed!</info>');
        }

        $entityManager->flush();
        $output->writeln(sprintf('<info>Processed subscriptions: %d.</info>', count($endedSubscriptions)));
    }
}
