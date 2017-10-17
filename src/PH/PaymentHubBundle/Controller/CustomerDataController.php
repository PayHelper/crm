<?php

namespace PH\PaymentHubBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\ChannelBundle\Entity\Channel;
use PH\PaymentHubBundle\Entity\Customer;
use PH\PaymentHubBundle\Entity\Subscription;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/subscriptions")
 */
class CustomerDataController extends Controller
{
    /**
     * @Route("/customer", name="ph_customer_add_to_subscription")
     * @Method("POST|GET")
     * @Template()
     */
    public function editAction(Request $request)
    {
        if (null === $token = $request->get('token', null)) {
            throw new NotFoundHttpException('Subscription not found', null, 404);
        }

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        $subscriptionRepository = $entityManager->getRepository(Subscription::class);
        $channelRepository = $entityManager->getRepository(Channel::class);
        /** @var SubscriptionInterface $subscription */
        $subscription = $subscriptionRepository->findOneBy(['token' => $token]);

        if (null === $subscription) {
            throw new NotFoundHttpException('Subscription not found', null, 404);
        }

        if (null === $customer = $subscription->getCustomer()) {
            $customer = new Customer();
            $customer->setDataChannel($channelRepository->findOneBy(['name' => 'Payment Hub Channel']));
            $customer->addSubscription($subscription);
            $customer->setCreatedAt(new \DateTime());
            $customer->setUpdatedAt(new \DateTime());
            $entityManager->persist($customer);
            $subscription->setCustomer($customer);
        }

        $form = $this->get('form.factory')->create('subscriptions_customer', $customer, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->updateAllowed($customer)) {
            $customer->setUpdatedAt(new \DateTime());
            foreach ($customer->getAddresses() as $address) {
                $address->setOwner($customer);
            }

            $entityManager->persist($customer);
            $entityManager->flush();

            return ['form' => $form->createView()];
        }

        return ['form' => $form->createView()];
    }

    /**
     * @param Customer $customer
     *
     * @return bool
     */
    private function updateAllowed(Customer $customer)
    {
        $maxValidUpdateDate = $customer->getCreatedAt();
        $maxValidUpdateDate->modify('+ 7 days');

        if ($maxValidUpdateDate < new \DateTime('now')) {
            return false;
        }

        return true;
    }
}
