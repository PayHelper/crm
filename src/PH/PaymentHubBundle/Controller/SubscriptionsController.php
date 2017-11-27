<?php

namespace PH\PaymentHubBundle\Controller;

use Oro\Bundle\IntegrationBundle\Provider\Rest\Client\Guzzle\GuzzleRestException;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use PH\PaymentHubBundle\Entity\OrderCheckoutInterface;
use PH\PaymentHubBundle\Entity\OrderItemInterface;
use PH\PaymentHubBundle\Entity\PaymentInterface;
use PH\PaymentHubBundle\Entity\Subscription;
use PH\PaymentHubBundle\Entity\SubscriptionBankAccount;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;
use PH\PaymentHubBundle\Form\Type\ChangeBankAccountSubscriptionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

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
     * @Route("/change/{id}", name="subscriptions.subscription_change", requirements={"id"="\d+"})
     * @Template()
     * @AclAncestor("subscriptions.subscription_change")
     */
    public function changeAction(Subscription $subscription, Request $request)
    {
        if (SubscriptionInterface::TYPE_NONRECURRING === $subscription->getType()) {
            throw new BadCredentialsException();
        }

        $form = $this->get('form.factory')->create(ChangeBankAccountSubscriptionType::class, new SubscriptionBankAccount());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            //$request->headers->remove('x-oro-hash-navigation');
            $guzzleClientFactory = $this->get('oro_integration.transport.rest.client_factory');
            //$client = $guzzleClientFactory->createRestClient('http://127.0.0.1:8001/app_dev.php', []);
            //$client->delete(sprintf('/api/v1/subscriptions/%s/payments/%s/cancel', $subscription->getOrderId(), $subscription->getPayments()->first()->getPaymentId()), ['Authorization' => 'Bearer eyJhbGciOiJSUzI1NiJ9.eyJyb2xlcyI6WyJST0xFX0FETUlOIl0sInVzZXJuYW1lIjoiYWRtaW4iLCJpYXQiOjE1MTEyNzI1MjMsImV4cCI6MTUxMTI3NjEyM30.TDs5XmUJkMFGTXv3jbPRnouhwn4GvXIdig0tKPUzNq1OEJDdrsnfyfKQhyQh7BxX6wOK70Q6VcI7XJdROsVCX0rp-nyNZ-9G5qMkQq_SVY0HVU7uw6m0chwWKZSHxG0vMM_TZ5Q29ItqiNJrgIj1uTIV7tXFIJ7LHvbw5dCcjsA7Xw5e9j8A7Fllehk2rWVo6_RwKDHGtr4il21i3kDBuMV2PbTalB4EoZQrjkkmZJjaG_aex0efL6teVO7rAIWa7_zZrUZrExin-TepW6lWupUzCPd0uKTOW0JYuP-37YvAG4jiQ_-vjE0Pl2pjYq3ZC_QUbatI3qCDDO7YIHv9RXwaZaDyap_kXbLHWuy-dQpA2aiWNRt37-3lU4gjck3uwC5AYmxKCsSnNb0K_VvHWNhn3SRIy4UD1Fy52tQZkt7pfm9eTmf6KEs5jJ4kuQzTwBlyto2y82slsc-zTgo8pRPKy19-iLI1HMzZQ1yrO7KtPqVFiLV9hsm_ZK2tZU-3Jahn7c23m-zelcFmTG-eBb4B4Nda0Sp7hCQWQoYMN3B5zcczWAyUCOqWI1ooJjT4J91lZKo7_WIq_1lrZ5RPh_5MICu2aprUU6QB8HEZDyHZI0y2dUA-p5N9QZAUigtXirOAXwHwQNs6y0Jtb7Iy8AaRGGyzVTy5xdUQVOYdpXk']);

            $date = $data->getStartDate();
//
//            $result = $client->post('/public-api/v1/subscriptions/', [
//                'amount' => $data->getAmount() * 100,
//                'interval' => $data->getInterval(),
//                'start_date' => $date->format('Y-m-d'),
//                'currency_code' => 'EUR',
//                'type' => SubscriptionInterface::TYPE_RECURRING,
//                'method' => $subscription->getProviderType(),
//                'metadata' => [
//                    'subscriptionId' => $subscription->getId(),
//                ]
//            ]);
//
//            $response = json_decode($result->getBodyAsString(), true);

            return new JsonResponse(['redirectUrl' => sprintf('http://www.hub.s-lab.sourcefabric.org/app_dev.php/public-api/v1/subscriptions/%s/pay/', /*$response['token_value']*/'PCuLIh3Ini')], Response::HTTP_OK);
            $request->attributes->set('_fullRedirect', true);
            $request->query->set('input_action', json_encode(['redirectUrl' => sprintf('http://www.hub.s-lab.sourcefabric.org/app_dev.php/public-api/v1/subscriptions/%s/pay/', /*$response['token_value']*/'PCuLIh3Ini')]));

            //return $this->redirect(sprintf('http://www.hub.s-lab.sourcefabric.org/app_dev.php/public-api/v1/subscriptions/%s/pay/', /*$response['token_value']*/'PCuLIh3Ini'));

            return $this->get('oro_ui.router')->redirect($subscription);
        }

        return array(
            'entity' => $subscription,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/change/ajax/{id}", name="subscriptions.subscription_ajax_change", requirements={"id"="\d+"})
     * @AclAncestor("subscriptions.subscription_change")
     */
    public function ajaxChangeAction(Subscription $subscription, Request $request)
    {
        if (SubscriptionInterface::TYPE_RECURRING !== $subscription->getType()) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $form = $this->get('form.factory')->create(ChangeBankAccountSubscriptionType::class, new SubscriptionBankAccount());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $guzzleClientFactory = $this->get('oro_integration.transport.rest.client_factory');
            $client = $guzzleClientFactory->createRestClient($this->container->getParameter('payments_hub.host'), []);
            $result = $client->post('/api/v1/login_check', [
                'username' => $this->container->getParameter('payments_hub.username'),
                'password' => $this->container->getParameter('payments_hub.password'),
            ]);

            $response = json_decode($result->getBodyAsString(), true);

            try {
                $client->delete(sprintf('/api/v1/subscriptions/%s/payments/%s/cancel',
                    $subscription->getOrderId(),
                    $subscription->getPayments()->first()->getPaymentId()
                ), ['Authorization' => sprintf('Bearer %s', $response['token'])]);
            } catch (GuzzleRestException $e) {
                $result = $e->getResponse()->getBodyAsString();

                return new JsonResponse(json_decode($result, true), Response::HTTP_BAD_REQUEST);
            }

            $date = $data->getStartDate();

            try {
                $result = $client->post('/public-api/v1/subscriptions/', [
                    'amount' => $data->getAmount() * 100,
                    'interval' => $data->getInterval(),
                    'start_date' => $date->format('Y-m-d'),
                    'currency_code' => 'EUR',
                    'type' => SubscriptionInterface::TYPE_RECURRING,
                    'method' => $subscription->getProviderType(),
                    'metadata' => [
                        'subscriptionId' => $subscription->getOrderId(),
                    ],
                ]);
            } catch (GuzzleRestException $e) {
                $result = $e->getResponse()->getBodyAsString();

                return new JsonResponse(json_decode($result, true), Response::HTTP_BAD_REQUEST);
            }

            $response = json_decode($result->getBodyAsString(), true);

            return new JsonResponse([
                'redirectUrl' => sprintf(
                    $this->container->getParameter('payments_hub.host').'/public-api/v1/subscriptions/%s/pay/?redirect=%s',
                    $response['token_value'],
                    $this->generateUrl('subscriptions.subscription_view', ['id' => $subscription->getId()])
                ),
            ], Response::HTTP_OK);
        }

        return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
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
