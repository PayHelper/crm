<?php

namespace PH\PaymentHubBundle\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use PH\PaymentHubBundle\Entity\Customer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
