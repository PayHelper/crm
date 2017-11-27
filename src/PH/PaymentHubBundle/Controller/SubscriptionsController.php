<?php

namespace PH\PaymentHubBundle\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use PH\PaymentHubBundle\Entity\OrderCheckoutInterface;
use PH\PaymentHubBundle\Entity\OrderItemInterface;
use PH\PaymentHubBundle\Entity\PaymentInterface;
use PH\PaymentHubBundle\Entity\Subscription;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/subscriptions/list")
 */
class SubscriptionsController extends Controller
{
    /**
     * @Route("/", name="subscriptions.subscription_index")
     *
     * @Template()
     *
     * @Acl(
     *     id="subscriptions.subscription_view",
     *     type="entity",
     *     class="PHPaymentHubBundle:Subscription",
     *     permission="VIEW"
     * )
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/{id}", name="subscriptions.subscription_view", requirements={"id"="\d+"})
     * @Template()
     * @AclAncestor("subscriptions.subscription_view")
     */
    public function viewAction(Subscription $subscription)
    {
        return array(
            'subscription' => $subscription,
            'entity' => $subscription,
        );
    }

    /**
     * @Route("/create", name="subscriptions.subscription_create")
     * @Template("PHPaymentHubBundle:Subscriptions:update.html.twig")
     * @Acl(
     *     id="subscriptions.subscription_create",
     *     type="entity",
     *     class="PHPaymentHubBundle:Subscription",
     *     permission="CREATE"
     * )
     */
    public function createAction(Request $request)
    {
        return $this->update(new Subscription(), $request);
    }

    /**
     * @Route("/update/{id}", name="subscriptions.subscription_update", requirements={"id":"\d+"}, defaults={"id":0})
     * @Template()
     * @Acl(
     *     id="subscriptions.subscription_update",
     *     type="entity",
     *     class="PHPaymentHubBundle:Subscription",
     *     permission="EDIT"
     * )
     */
    public function updateAction(Subscription $subscription, Request $request)
    {
        return $this->update($subscription, $request);
    }

    private function update(Subscription $subscription, Request $request)
    {
        $form = $this->get('form.factory')->create('subscriptions_subscription', $subscription);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if (null === $subscription->getOrderId() || false !== strpos($subscription->getOrderId(), 'internal_')) {
                if (null === $subscription->getCheckoutCompletedAt() && OrderCheckoutInterface::STATE_COMPLETED === $subscription->getPurchaseState()) {
                    $subscription->setCheckoutCompletedAt(new \DateTime());
                }
            }
            $this->handleSubscription($subscription);

            $entityManager->persist($subscription);
            $entityManager->flush();

            return $this->get('oro_ui.router')->redirectAfterSave(
                array(
                    'route' => 'subscriptions.subscription_update',
                    'parameters' => array('id' => $subscription->getId()),
                ),
                array('route' => 'subscriptions.subscription_view', 'parameters' => array('id' => $subscription->getId())),
                $subscription
            );
        }

        return array(
            'entity' => $subscription,
            'form' => $form->createView(),
        );
    }

    /**
     * @param Subscription $subscription
     */
    private function handleSubscription(Subscription $subscription)
    {
        $orderItems = $subscription->getItems();
        $payments = $subscription->getPayments();
        $subscription->setTotal(0);
        $subscription->setToken($this->getToken());
        $dateCode = date('ymdhis');
        if (null === $subscription->getOrderId()) {
            $subscription->setOrderId('internal_'.$dateCode);
        }

        if (SubscriptionInterface::TYPE_NONRECURRING === $subscription->getType()) {
            $subscription->setInterval(null);
            $subscription->setStartDate(null);
        }

        /** @var OrderItemInterface $item */
        foreach ($orderItems as $item) {
            if (null === $item->getOrderItemId()) {
                $item->setOrderItemId('internal_item'.$dateCode);
            }
            $item->setTotal(floatval($item->getUnitPrice()) * floatval($item->getQuantity()));
            $subscription->setTotal(floatval($subscription->getTotal()) + floatval($item->getTotal()));
            $item->setSubscription($subscription);
        }

        /** @var PaymentInterface $payment */
        foreach ($payments as $payment) {
            if (null === $payment->getAmount()) {
                $payments->removeElement($payment);
                continue;
            }
            if (null === $payment->getPaymentId()) {
                $payment->setPaymentId('internal_payment'.$dateCode);
            }
            $payment->setSubscription($subscription);
        }
    }

    /**
     * @return string
     */
    private function getToken()
    {
        return $this->container->get('ph_payment_hub.generator.randomness')->generateUriSafeString(10);
    }
}
