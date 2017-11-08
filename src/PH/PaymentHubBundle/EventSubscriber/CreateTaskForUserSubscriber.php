<?php

namespace PH\PaymentHubBundle\EventSubscriber;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\ActivityBundle\Manager\ActivityManager;
use Oro\Bundle\TaskBundle\Entity\Task;
use PH\PaymentHubBundle\Entity\CustomerInterface;
use PH\PaymentHubBundle\Entity\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Translation\TranslatorInterface;

class CreateTaskForUserSubscriber implements EventSubscriberInterface
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
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            CustomerInterface::CUSTOMER_UPDATED => 'userUpdated',
            CustomerInterface::CUSTOMER_CREATED => 'userCreated',
        );
    }

    /**
     * @param GenericEvent $event
     */
    public function userCreated(GenericEvent $event)
    {
        $this->createNewTask($event, $this->translator->trans('User created'));
    }

    /**
     * @param GenericEvent $event
     */
    public function userUpdated(GenericEvent $event)
    {
        $this->createNewTask($event, $this->translator->trans('User updated'));
    }

    /**
     * @param GenericEvent $event
     * @param              $taskSubject
     */
    protected function createNewTask(GenericEvent $event, $taskSubject)
    {
        /** @var CustomerInterface $customer */
        $customer = $event->getSubject();

        $defaultTaskUser = $this->manager->getRepository('OroUserBundle:User')->findOneBy(['username' => UserInterface::DEFAULT_TASKS_USER_NAME]);
        if (null === $defaultTaskUser) {
            return;
        }

        $normalTaskPriority = $this->manager->getRepository('OroTaskBundle:TaskPriority')->findOneBy(['name' => 'normal']);
        $task = new Task();
        $task->setTaskPriority($normalTaskPriority);
        $task->setOwner($defaultTaskUser);
        $task->setSubject($taskSubject);
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
