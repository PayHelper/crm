<?php

namespace PH\PaymentHubBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
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
     * @Route("/{token}/customer_data", name="ph_customer_add_to_subscription")
     * @Method("POST")
     */
    public function createCustomerAction(Request $request, $token)
    {
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

        foreach ($customer->getAddresses() as $address) {
            $entityManager->remove($address);
        }

        $form = $this->get('form.factory')->create('subscriptions_customer', $customer, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $this->updateAllowed($customer)) {
            $customer->setUpdatedAt(new \DateTime());
            foreach ($customer->getAddresses() as $address) {
                $address->setOwner($customer);
            }
            $entityManager->flush();

            return new JsonResponse(['status' => 'OK']);
        }

        return new JsonResponse(['status' => 'NOK']);
    }

    /**
     * @Route("/{token}/customer_data", name="ph_customer_get_by_subscription")
     * @Method("GET")
     */
    public function getCustomerAction(Request $request, $token)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $subscriptionRepository = $entityManager->getRepository(Subscription::class);
        $channelRepository = $entityManager->getRepository(Channel::class);
        /** @var SubscriptionInterface $subscription */
        $subscription = $subscriptionRepository->findOneBy(['token' => $token]);

        if (null === $subscription) {
            throw new NotFoundHttpException('Subscription not found', null, 404);
        }

        if (null === $customer = $subscription->getCustomer()) {
            throw new NotFoundHttpException('Customer not found');
        }

        $view = View::create();
        $view->setData($customer);
        $view->setFormat('json');

        return $this->container->get('fos_rest.view_handler')->handle($view);
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
