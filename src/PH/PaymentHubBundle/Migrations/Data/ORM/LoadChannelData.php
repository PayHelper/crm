<?php

namespace PH\PaymentHubBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Oro\Bundle\ChannelBundle\Entity\Channel;
use Oro\Bundle\ChannelBundle\Builder\BuilderFactory;

class LoadChannelData extends AbstractFixture implements ContainerAwareInterface
{
    /** @var BuilderFactory */
    protected $factory;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->factory = $container->get('oro_channel.builder.factory');
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $om)
    {
        $channel = $this
            ->factory
            ->createBuilder()
            ->setStatus(Channel::STATUS_ACTIVE)
            ->setEntities()
            ->setChannelType('payment_hub')
            ->setName('Payment Hub Channel')
            ->getChannel();

        $om->persist($channel);
        $om->flush();
    }
}
