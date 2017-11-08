<?php

namespace PH\PaymentHubBundle\Controller;

use Oro\Bundle\ChannelBundle\Entity\Channel;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use PH\PaymentHubBundle\Entity\Customer;
use PH\PaymentHubBundle\Entity\CustomerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/customers")
 */
class CustomersController extends Controller
{
    /**
     * @Route("/list", name="subscriptions.customers_index")
     * @Template()
     * @Acl(
     *     id="subscriptions.customers_view",
     *     type="entity",
     *     class="PHPaymentHubBundle:Customer",
     *     permission="VIEW"
     * )
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/view/{id}", name="subscriptions.customer_view", requirements={"id"="\d+"})
     * @Template()
     * @AclAncestor("subscriptions.customer_view")
     */
    public function viewAction(Customer $customer)
    {
        return array(
            'customer' => $customer,
            'entity' => $customer,
        );
    }

    /**
     * @Route("/create", name="subscriptions.customer_create")
     * @Template("PHPaymentHubBundle:Customers:update.html.twig")
     * @Acl(
     *     id="subscriptions.customer_create",
     *     type="entity",
     *     class="PHPaymentHubBundle:Customer",
     *     permission="CREATE"
     * )
     */
    public function createAction(Request $request)
    {
        return $this->update(new Customer(), $request, CustomerInterface::CUSTOMER_CREATED);
    }

    /**
     * @Route("/update/{id}", name="subscriptions.customer_update", requirements={"id":"\d+"}, defaults={"id":0})
     * @Template()
     * @Acl(
     *     id="subscriptions.customer_update",
     *     type="entity",
     *     class="PHPaymentHubBundle:Customer",
     *     permission="EDIT"
     * )
     */
    public function updateAction(Customer $customer, Request $request)
    {
        return $this->update($customer, $request, CustomerInterface::CUSTOMER_UPDATED);
    }

    private function update(Customer $customer, Request $request, $action)
    {
        $form = $this->get('form.factory')->create('subscriptions_customer', $customer, ['method' => $request->getMethod()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $channelRepository = $entityManager->getRepository(Channel::class);
            $customer->setDataChannel($channelRepository->findOneBy(['name' => 'Payment Hub Channel']));
            $customer->setCreatedAt(new \DateTime());
            $customer->setUpdatedAt(new \DateTime());
            foreach ($customer->getAddresses() as $address) {
                $address->setOwner($customer);
            }

            $entityManager->persist($customer);
            $entityManager->flush();

            $this->get('event_dispatcher')->dispatch($action, new GenericEvent($customer));

            return $this->get('oro_ui.router')->redirectAfterSave(
                array(
                    'route' => 'subscriptions.customer_update',
                    'parameters' => array('id' => $customer->getId()),
                ),
                array('route' => 'subscriptions.customers_index'),
                $customer
            );
        }

        return array(
            'entity' => $customer,
            'form' => $form->createView(),
        );
    }
}
