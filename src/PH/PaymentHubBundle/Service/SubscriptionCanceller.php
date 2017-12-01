<?php

namespace PH\PaymentHubBundle\Service;

use Oro\Bundle\IntegrationBundle\Provider\Rest\Client\RestClientFactoryInterface;
use PH\PaymentHubBundle\Entity\SubscriptionInterface;

final class SubscriptionCanceller implements SubscriptionCancellerInterface
{
    private $client;
    private $username;
    private $password;

    public function __construct(RestClientFactoryInterface $restClientFactory, $host, $username, $password)
    {
        $this->client = $restClientFactory->createRestClient($host, []);
        $this->password = $password;
        $this->username = $username;
    }

    public function cancel(SubscriptionInterface $subscription)
    {
        $result = $this->client->post('/api/v1/login_check', [
            'username' => $this->username,
            'password' => $this->password,
        ]);

        $response = json_decode($result->getBodyAsString(), true);

        $this->client->delete(sprintf('/api/v1/subscriptions/%s/payments/%s/cancel',
            $subscription->getOrderId(),
            $subscription->getPayments()->first()->getPaymentId()
        ), ['Authorization' => sprintf('Bearer %s', $response['token'])]);

        $subscription->setState(SubscriptionInterface::STATE_EXPIRED);
    }
}
