<?php

namespace PH\PaymentHubBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;

/**
 * @RouteResource("customer")
 * @NamePrefix("ph_customers_api_")
 */
class CustomerController extends RestController
{
    /**
     * @Acl(
     *      id="subscriptions.customer_delete",
     *      type="entity",
     *      class="PHPaymentHubBundle:Customer",
     *      permission="DELETE"
     * )
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    public function getForm()
    {
    }

    public function getFormHandler()
    {
    }

    public function getManager()
    {
        return $this->get('subscriptions.customer_manager.api');
    }
}
