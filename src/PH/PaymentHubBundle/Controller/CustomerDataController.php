<?php

namespace PH\PaymentHubBundle\Controller;

use Oro\Bundle\ChannelBundle\Entity\Channel;
use PH\PaymentHubBundle\Entity\Customer;
use PH\PaymentHubBundle\Entity\Subscription;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/subscriptions")
 */
class CustomerDataController extends Controller
{
    /**
     * @Route("/{number}/customer_data", name="ph_customer_add_to_subscription")
     * @Method("POST")
     */
    public function createCustomerAction(Request $request, $number)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $subscriptionRepository = $entityManager->getRepository(Subscription::class);
        $channelRepository = $entityManager->getRepository(Channel::class);
        /** @var SubscriptionInterface $subscription */
        $subscription = $subscriptionRepository->findOneBy(['number' => $number]);

        if (null === $subscription) {
            throw new NotFoundHttpException('Subscription not found', null, 404);
        }

        if (null == $customer = $subscription->getCustomer()) {
            $customer = new Customer();
            $customer->setDataChannel($channelRepository->findOneBy(['name' => 'Payment Hub Channel']));
            $customer->addSubscription($subscription);
            $customer->setCreatedAt(new \DateTime());
            $customer->setUpdatedAt(new \DateTime());
            $entityManager->persist($customer);
            $subscription->setCustomer($customer);
        }

        $form = $this->get('form.factory')->create('subscriptions_customer', $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->updateAllowed($customer)) {
            $customer->setUpdatedAt(new \DateTime());
            $entityManager->flush();

            return new JsonResponse(['status' => 'OK']);
        }

        return new JsonResponse(['status' => 'NOK']);
    }

    private function updateAllowed(Customer $customer)
    {
        $maxValidUpdateDate = $customer->getCreatedAt();
        $maxValidUpdateDate->modify('+ 7 days');

        if ($maxValidUpdateDate > new \DateTime('now')) {
            return false;
        }

        return true;
    }
}
