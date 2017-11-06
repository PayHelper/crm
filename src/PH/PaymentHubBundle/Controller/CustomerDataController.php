<?php

namespace PH\PaymentHubBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\ApiBundle\Exception\ActionNotAllowedException;
use Oro\Bundle\ChannelBundle\Entity\Channel;
use PH\PaymentHubBundle\Entity\Customer;
use PH\PaymentHubBundle\Entity\CustomerInterface;
use PH\PaymentHubBundle\Entity\Subscription;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $action = CustomerInterface::CUSTOMER_UPDATED;

        /** @var SubscriptionInterface $subscription */
        if (null === ($subscription = $subscriptionRepository->findOneBy(['token' => $token]))) {
            throw new NotFoundHttpException('Subscription not found', null, 404);
        }

        if (null === $customer = $subscription->getCustomer()) {
            $customer = $this->createNewCustomer($subscription, $entityManager);
            $action = CustomerInterface::CUSTOMER_CREATED;
        }

        $form = $this->get('form.factory')->create('subscriptions_customer', $customer, ['method' => 'POST']);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                return $this->renderForm($form, 400);
            }

            if (!$this->updateAllowed($customer)) {
                throw new ActionNotAllowedException();
            }

            if (CustomerInterface::CUSTOMER_CREATED === $action) {
                $customerRepository = $entityManager->getRepository(Customer::class);
                $existingCustomer = $customerRepository->findOneBy(['email' => $customer->getEmail()]);
                if (null !== $existingCustomer) {
                    $customer = $existingCustomer;
                } else {
                    $entityManager->persist($customer);
                }
            }

            $subscription->setCustomer($customer);
            $customer->setUpdatedAt(new \DateTime());
            foreach ($customer->getAddresses() as $address) {
                $address->setOwner($customer);
            }

            $entityManager->persist($customer);
            $entityManager->flush();

            if (CustomerInterface::CUSTOMER_CREATED === $action) {
                $this->get('event_dispatcher')->dispatch(CustomerInterface::CUSTOMER_CREATED, new GenericEvent($customer, ['action' => $action]));
            }

            return $this->renderForm($form, 201);
        }

        return $this->renderForm($form, 200);
    }

    /**
     * @param $subscription
     * @param $entityManager
     *
     * @return Customer
     */
    private function createNewCustomer($subscription, $entityManager)
    {
        $channelRepository = $entityManager->getRepository(Channel::class);
        $customer = new Customer();
        $customer->setDataChannel($channelRepository->findOneBy(['name' => 'Payment Hub Channel']));
        $customer->addSubscription($subscription);
        $customer->setCreatedAt(new \DateTime());
        $customer->setUpdatedAt(new \DateTime());

        return $customer;
    }

    /**
     * @param     $form
     * @param int $code
     *
     * @return Response
     */
    private function renderForm($form, $code = 200)
    {
        return $this->render('@PHPaymentHub/CustomerData/edit.html.twig', [
            'form' => $form->createView(),
        ], new Response('', $code));
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
