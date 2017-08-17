<?php

namespace PH\PaymentHubBundle\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use PH\PaymentHubBundle\Entity\PaymentInterface;
use PH\PaymentHubBundle\Entity\Subscription;
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
     * @Template()
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
        $originalPaymentState = $subscription->getPaymentState();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $subscription->setUpdatedAt(new \DateTime());
            $subscription->setTotal($subscription->getTotal() * 100);
            $entityManager->persist($subscription);
            if ($originalPaymentState !== $subscription->getPaymentState()) {
                $this->updatePayment($subscription);
            }
            $entityManager->flush();

            return $this->get('oro_ui.router')->redirectAfterSave(
                array(
                    'route' => 'subscriptions.subscription_update',
                    'parameters' => array('id' => $subscription->getId()),
                ),
                array('route' => 'subscriptions.subscription_index'),
                $subscription
            );
        }

        return array(
            'entity' => $subscription,
            'form' => $form->createView(),
        );
    }

    private function updatePayment(Subscription $subscription)
    {
        /** @var PaymentInterface $payment */
        foreach ($subscription->getPayments() as $payment) {
            $payment->setState($subscription->getPaymentState());
            $payment->setUpdatedAt(new \DateTime('now'));
        }
    }
}
