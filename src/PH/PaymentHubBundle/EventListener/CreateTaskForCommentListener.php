<?php

namespace PH\PaymentHubBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\ActivityBundle\Manager\ActivityManager;
use Oro\Bundle\TaskBundle\Entity\Task;
use PH\PaymentHubBundle\Entity\CustomerInterface;
use PH\PaymentHubBundle\Entity\UserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Translation\TranslatorInterface;

class CreateTaskForCommentListener
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var ActivityManager
     */
    protected $activityManager;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * CreateTaskForCommentListener constructor.
     *
     * @param EntityManager       $manager
     * @param ActivityManager     $activityManager
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManager $manager, ActivityManager $activityManager, TranslatorInterface $translator)
    {
        $this->manager = $manager;
        $this->activityManager = $activityManager;
        $this->translator = $translator;
    }

    /**
     * @param GenericEvent $event
     */
    public function onCreate(GenericEvent $event)
    {
        /** @var CustomerInterface $customer */
        $customer = $event->getSubject();
        if (null === $customer->getComment()) {
            return;
        }

        $defaultTaskUser = $this->manager->getRepository('OroUserBundle:User')->findOneBy(['username' => UserInterface::DEFAULT_TASKS_USER_NAME]);
        if (null === $defaultTaskUser) {
            return;
        }

        $normalTaskPriority = $this->manager->getRepository('OroTaskBundle:TaskPriority')->findOneBy(['name' => 'normal']);
        $task = new Task();
        $task->setTaskPriority($normalTaskPriority);
        $task->setOwner($defaultTaskUser);
        $task->setSubject($this->translator->trans('New user comment'));
        $task->setDescription($customer->getComment());
        $task->setOrganization($defaultTaskUser->getOrganization());
        $this->activityManager->addActivityTarget(
            $task,
            $customer
        );

        $this->manager->persist($task);
        $this->manager->flush();
    }
}
