<?php

namespace PH\PaymentHubBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use PH\PaymentHubBundle\Entity\Customer;
use PH\PaymentHubBundle\Entity\CustomerInterface;
use PH\PaymentHubBundle\Entity\Subscription;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerDataController extends Controller
{
    /**
     * @Route("/subscriptions/customer/", name="ph_customer_add_to_subscription")
     * @Method("POST|GET")
     */
    public function editSubscriptionCustomerAction(Request $request)
    {
        if (null === $token = $request->get('token', null)) {
            throw new NotFoundHttpException('Subscription not found');
        }

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        $subscriptionRepository = $entityManager->getRepository(Subscription::class);
        $customerService = $this->container->get('ph_payment_hub.service.customer');
        $action = CustomerInterface::CUSTOMER_UPDATED;

        /** @var SubscriptionInterface $subscription */
        if (null === ($subscription = $subscriptionRepository->findOneBy(['token' => $token]))) {
            throw new NotFoundHttpException('Subscription not found');
        }

        if (null === $customer = $subscription->getCustomer()) {
            $customer = $customerService->prepareCustomer(new Customer());
            $action = CustomerInterface::CUSTOMER_CREATED;
        }

        $form = $this->get('form.factory')->create('subscriptions_customer', $customer, ['method' => 'POST']);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                return $this->renderForm($form, 400);
            }

            // check fo existing customer by email
            $existingCustomer = $entityManager->getRepository(Customer::class)->findOneBy(['email' => $customer->getEmail()]);
            if (null !== $existingCustomer) {
                $customer = $existingCustomer;
            }

            $subscription->setCustomer($customer);
            foreach ($customer->getAddresses() as $address) {
                $address->setOwner($customer);
            }

            $customerService->setIdentificationToken($customer);

            $entityManager->persist($customer);
            $entityManager->flush();

            $this->get('event_dispatcher')->dispatch($action, new GenericEvent($customer));

            return new RedirectResponse($this->generateUrl('ph_customer_edit', array('token' => $customer->getCustomerUpdateToken(), 'ticket'=>$customer->getIdentificationToken())));
        }

        return $this->renderForm($form, 200);
    }

    /**
     * @Route("/customer/edit/", name="ph_customer_edit")
     * @Method("POST|GET")
     */
    public function editCustomerAction(Request $request)
    {
        if (null === $token = $request->get('token', null)) {
            throw new NotFoundHttpException('Customer not found');
        }
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        $customerService = $this->container->get('ph_payment_hub.service.customer');
        /** @var CustomerInterface $customer */
        $customer = $entityManager->getRepository(Customer::class)->findOneBy(['customerUpdateToken' => $token]);
        if (null === $customer) {
            throw new NotFoundHttpException('Customer not found');
        }

        $form = $this->get('form.factory')->create('subscriptions_customer', $customer, ['method' => 'POST']);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                return $this->renderForm($form, 400);
            }

            $customerService->reassignAddresses($customer);
            $customerService->resetUpdateToken($customer);
            $entityManager->persist($customer);
            $entityManager->flush();

            $this->get('event_dispatcher')->dispatch(CustomerInterface::CUSTOMER_UPDATED, new GenericEvent($customer));

            return new RedirectResponse($this->generateUrl('ph_customer_edit', array('token' => $customer->getCustomerUpdateToken())));
        }

        return $this->renderForm($form, 200);
    }

    /**
     * @Route("/customer/email/verify.{_format}", defaults={"_format":"html"}, name="ph_customer_email_verify")
     * @Method("GET")
     * @Template()
     */
    public function verifyCustomerEmailAction(Request $request)
    {
        if (null === $token = $request->get('token', null)) {
            throw new NotFoundHttpException('Customer was not found.');
        }

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        /** @var CustomerInterface $customer */
        $customer = $entityManager->getRepository(Customer::class)->findOneBy(['emailVerificationToken' => $token]);
        if (null === $customer) {
            throw new NotFoundHttpException('Customer was not found, or email is already confirmed.');
        }

        $customer->setEmailVerificationToken(null);
        $customer->setEmailVerifiedAt(new \DateTime());
        $entityManager->flush();

        return ['customer' => $customer];
        #return $this->render('@PHPaymentHub/CustomerData/verifyCustomerEmail.html.twig', ['customer' => $customer]);
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
}
