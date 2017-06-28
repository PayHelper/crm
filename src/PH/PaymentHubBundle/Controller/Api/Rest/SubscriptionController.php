<?php

namespace PH\PaymentHubBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;

/**
 * @RouteResource("subscription")
 * @NamePrefix("subscriptions_api_")
 */
class SubscriptionController extends RestController
{
    /**
     * @Acl(
     *      id="subscriptions.subscription_delete",
     *      type="entity",
     *      class="PHPaymentHubBundle:Subscription",
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
        return $this->get('subscriptions.subscription_manager.api');
    }
}