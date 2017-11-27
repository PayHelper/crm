<?php

namespace PH\PaymentHubBundle\Controller;

use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
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

        if (isset($data['metadata']['subscriptionId'])) {
            $subscription = $subscriptionRepository->findOneBy(['orderId' => $data['metadata']['subscriptionId']]);

            if (null !== $subscription) {
                // update existing subscription data
                $subscription->setUpdatedAt(new \DateTime());
                $subscriptionService->processIncomingData($subscription, $data);
                $manager->flush();

                return new JsonResponse(['status' => 'UPDATED']);
            }

            // subscription doesnt exist
            return new JsonResponse(['status' => 'FAILED']);
        }

        /** @var SubscriptionInterface $subscription */
        $subscription = $subscriptionRepository->findOneBy(['token' => $data['token_value']]);

        if (null === $subscription) {
            $subscription = new Subscription();
            $subscription->setCreatedAt(new \DateTime());
            /** @var BusinessUnit $businessUnit */
            $businessUnit = $subscriptionService->getBusinessUnit();
            $subscription->setOwner($businessUnit);
            $subscription->setOrganization($businessUnit->getOrganization());
            $manager->persist($subscription);
        } else {
            $subscription->setUpdatedAt(new \DateTime());
        }

        $subscriptionService->processIncomingData($subscription, $data);
        $manager->flush();

        return new JsonResponse(['status' => 'OK']);
    }
}
