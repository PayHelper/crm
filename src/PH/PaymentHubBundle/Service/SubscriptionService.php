<?php

namespace PH\PaymentHubBundle\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\EmailBundle\Entity\EmailTemplate;
use Oro\Bundle\EmailBundle\Form\Model\Email;
use Oro\Bundle\EmailBundle\Mailer\Processor;
use Oro\Bundle\EmailBundle\Provider\EmailRenderer;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
use PH\PaymentHubBundle\Entity\OrderItem;
use PH\PaymentHubBundle\Entity\Payment;
use PH\PaymentHubBundle\Entity\PaymentInterface;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;

/**
 * Class SubscriptionService.
 */
class SubscriptionService implements SubscriptionServiceInterface
{
    const MAIN_BUSINESS_UNIT = 'Main';

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

    /**
     * @var string
     */
    protected $fromEmail;

    /**
     * SubscriptionService constructor.
     *
     * @param Processor              $emailProcessor
     * @param EmailRenderer          $emailRenderer
     * @param EntityManagerInterface $entityManager
     * @param string                 $fromEmail
     */
    public function __construct(Processor $emailProcessor, EmailRenderer $emailRenderer, EntityManagerInterface $entityManager, $fromEmail)
    {
        $this->emailProcessor = $emailProcessor;
        $this->emailRenderer = $emailRenderer;
        $this->entityManager = $entityManager;
        $this->fromEmail = $fromEmail;
    }

    /**
     * {@inheritdoc}
     */
    public function processIncomingData(SubscriptionInterface $subscription, $data)
    {
        $subscription->setState($data['state']);
        $subscription->setTotal($data['total']);
        $subscription->setOrderId($data['id']);
        $subscription->setPurchaseState($data['purchase_state']); //purchase_state

        $subscription->setPaymentState($data['payment_state']);
        $subscription->setCheckoutCompletedAt(new \DateTime($data['purchase_completed_at']));
        $subscription->setToken($data['token_value']);
        $subscription->setType($data['type']);
        $subscription->setInterval($data['interval']);
        $subscription->setStartDate(new \DateTime($data['start_date']));

        $subscription->setItems($this->handleOrderItems($subscription, $data));
        $subscription->setPayments($this->handlePayments($subscription, $data));

        if (isset($data['metadata'])) {
            if (isset($data['metadata']['intention'])) {
                $subscription->setIntention($data['metadata']['intention']);
            }

            if (isset($data['metadata']['source'])) {
                $subscription->setSource($data['metadata']['source']);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendTransactionCompletedEmail(SubscriptionInterface $subscription)
    {
        $email = new Email();
        $emailTemplate = $this->entityManager
            ->getRepository(EmailTemplate::class)
            ->findByName('transaction_completed_customer');
        $templateData = $this->emailRenderer
            ->compileMessage($emailTemplate, ['entity' => $subscription]);
        list($subjectRendered, $templateRendered) = $templateData;

        $email->setSubject($subjectRendered);
        $email->setContexts([$subscription->getCustomer(), $subscription]);
        $email->setBody($templateRendered);
        $email->setTo([$subscription->getCustomer()->getEmail()]);
        $email->setType($emailTemplate->getType());
        $email->setFrom($this->fromEmail);

        $this->emailProcessor->process($email);

        return [
            'body' => $templateRendered,
            'subject' => $subjectRendered,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getBusinessUnit()
    {
        $businessUnitRepository = $this->entityManager->getRepository(BusinessUnit::class);

        return $businessUnitRepository->findOneBy(['name' => self::MAIN_BUSINESS_UNIT]);
    }

    /**
     * @param SubscriptionInterface $subscription
     * @param array                 $data
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
            $orderItem->setName('default order item name');
            $orderItem->setSubscription($subscription);
            $orderItems[] = $orderItem;
        }

        return $orderItems;
    }

    /**
     * @param SubscriptionInterface $subscription
     * @param array                 $data
     *
     * @return array
     */
    protected function handlePayments(SubscriptionInterface $subscription, $data)
    {
        $payments = [];
        // set existing payments status to cancelled
        $existingPayments = $subscription->getPayments();

        foreach ($existingPayments as $existingPayment) {
            $existingPayment->setState(PaymentInterface::STATE_CANCELLED);
        }

        foreach ($data['payments'] as $singlePayment) {
            $paymentsCollection = $this->getPayment($existingPayments, (string) $singlePayment['id']);
            $payment = $paymentsCollection[0];

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

            if (isset($singlePayment['details']) && PaymentInterface::STATE_FAILED === $singlePayment['state']) {
                $payment->setErrors($singlePayment['details']);
            }

            if (SubscriptionInterface::TYPE_RECURRING === $subscription->getType()) {
                if (isset($singlePayment['details']) && isset($singlePayment['details']['mandate'])) {
                    $payment->setHolderName($singlePayment['details']['mandate']['details']['consumerName']);
                }
            }

            $payment->setSubscription($subscription);
            $payments[] = $payment;
        }

        return $payments;
    }

    /**
     * @param Collection $payments
     * @param            $id
     *
     * @return mixed
     */
    private function getPayment(Collection $payments, $id)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('paymentId', $id));

        return $payments->matching($criteria);
    }
}
