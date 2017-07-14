<?php

namespace PH\PaymentHubBundle\Controller;

use PH\PaymentHubBundle\Entity\Subscription;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/subscriptions/http-push")
 */
class ContentPushController extends Controller
{
    /**
     * @Route("/retrieve", name="ph_subscriptions_httppush_retrieve")
     */
    public function indexAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $manager = $this->get('doctrine')->getManager();
        $subscriptionRepository = $manager->getRepository(Subscription::class);
        $subscriptionService = $this->container->get('ph_payment_hub.service.subscription');

        /** @var SubscriptionInterface $subscription */
        $subscription = $subscriptionRepository->findOneBy(['orderId' => $data['id']]);

        if ($subscription === null) {
            $subscription = new Subscription();
            $subscription->setCreatedAt(new \DateTime());
            $manager->persist($subscription);
        } else {
            $subscription->setUpdatedAt(new \DateTime());
        }

        $subscriptionService->processIncoimingData($subscription, $data);
        $manager->flush();

        return new JsonResponse(['status' => 'OK']);
    }
}
