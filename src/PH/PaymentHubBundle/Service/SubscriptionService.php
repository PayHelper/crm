<?php

namespace PH\PaymentHubBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\EmailBundle\Entity\EmailTemplate;
use Oro\Bundle\EmailBundle\Form\Model\Email;
use Oro\Bundle\EmailBundle\Mailer\Processor;
use Oro\Bundle\EmailBundle\Provider\EmailRenderer;
use PH\PaymentHubBundle\Entity\OrderItem;
use PH\PaymentHubBundle\Entity\Payment;
use PH\PaymentHubBundle\Entity\PaymentInterface;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;

/**
 * Class SubscriptionService.
 */
class SubscriptionService
{
    /**
     * @var Processor
     */
    protected $emailProcessor;

    /**
     * @var EmailRenderer
     */
    protected $emailRenderer;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(Processor $emailProcessor, EmailRenderer $emailRenderer, EntityManagerInterface $entityManager)
    {
        $this->emailProcessor = $emailProcessor;
        $this->emailRenderer = $emailRenderer;
        $this->entityManager = $entityManager;
    }

    /**
     * @param SubscriptionInterface $subscription
     * @param                       $data
     */
    public function processIncoimingData(SubscriptionInterface $subscription, $data)
    {
        $previousOrderState = $subscription->getOrderState();
        $subscription->setOrderState($data['state']);
        $subscription->setTotal($data['total']);
        $subscription->setOrderId($data['id']);
        $subscription->setCheckoutState($data['checkout_state']);
        $subscription->setPaymentState($data['payment_state']);
        $subscription->setCheckoutCompletedAt($data['checkout_completed_at']);
        $subscription->setToken($data['token_value']);

        if (
            $previousOrderState !== PaymentInterface::STATE_COMPLETED &&
            $subscription->getOrderState() === PaymentInterface::STATE_COMPLETED
        ) {
            $this->sendTransactionCompletedEmail($subscription);
        }

        $subscription->setItems($this->handleOrderItems($subscription, $data));
        $subscription->setPayments($this->handlePayments($subscription, $data));
    }

    /**
     * @param SubscriptionInterface $subscription
     * @param                       $data
     *
     * @return array
     */
    protected function handleOrderItems(SubscriptionInterface $subscription, $data)
    {
        $orderItems = [];
        $orderItemRepository = $this->entityManager->getRepository(OrderItem::class);
        foreach ($data['items'] as $key => $item) {
            $orderItem = $orderItemRepository->findOneBy(['orderItemId' => $item['id']]);
            if (null === $orderItem) {
                $orderItem = new OrderItem();
                $orderItem->setOrderItemId($item['id']);
                $orderItem->setCreatedAt(new \DateTime());
                $this->entityManager->persist($orderItem);
            } else {
                $orderItem->setUpdatedAt(new \DateTime());
            }

            $orderItem->setQuantity($item['quantity']);
            $orderItem->setUnitPrice($item['unit_price']);
            $orderItem->setTotal($item['total']);
            $orderItem->setName('change ME!!');
            $orderItem->setSubscription($subscription);
            $orderItems[] = $orderItem;
        }

        return $orderItems;
    }

    /**
     * @param SubscriptionInterface $subscription
     * @param                       $data
     *
     * @return array
     */
    protected function handlePayments(SubscriptionInterface $subscription, $data)
    {
        $payments = [];
        $paymentRepository = $this->entityManager->getRepository(Payment::class);
        foreach ($data['payments'] as $singlePayment) {
            $payment = $paymentRepository->findOneBy(['paymentId' => $singlePayment['id']]);
            if (null === $payment) {
                $payment = new Payment();
                $payment->setPaymentId($singlePayment['id']);
                $payment->setCreatedAt(new \DateTime());
                $this->entityManager->persist($payment);
            } else {
                $payment->setUpdatedAt(new \DateTime());
            }

            $payment->setState($singlePayment['state']);
            $payment->setMethodCode($singlePayment['method']['code']);
            $subscription->setProviderType($singlePayment['method']['code']);
            $payment->setCurrencyCode($singlePayment['currency_code']);
            $payment->setAmount($singlePayment['amount']);
            $payment->setSubscription($subscription);
            $payments[] = $payment;
        }

        return $payments;
    }

    protected function sendTransactionCompletedEmail($subscription)
    {
        $email = new Email();
        $emailTemplate = $this->entityManager
            ->getRepository(EmailTemplate::class)
            ->findByName('transaction_completed_customer');
        $templateData = $this->emailRenderer
            ->compileMessage($emailTemplate, ['entity' => $subscription]);
        list($subjectRendered, $templateRendered) = $templateData;

        $email->setSubject($subjectRendered);
        $email->setBody($templateRendered);

        $this->emailProcessor->process($email);
    }
}
