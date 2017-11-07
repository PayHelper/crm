<?php

namespace PH\PaymentHubBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

class ContentPushControllerTest extends WebTestCase
{
    const TEST_SUBSCRIPTION = '{"id":27,"amount":600,"currency_code":"EUR","interval":"1 month","start_date":"2017-10-17T00:00:00+00:00","type":"recurring","items":[{"id":2,"quantity":1,"unit_price":600,"total":600}],"purchase_completed_at":null,"items_total":600,"total":600,"state":"new","created_at":"2017-10-17T08:33:21+00:00","updated_at":"2017-10-17T08:33:21+00:00","payments":[{"id":27,"method":{"id":1,"code":"mbe4","position":1,"created_at":"2017-09-21T13:08:48+00:00","updated_at":"2017-09-21T13:08:48+00:00","enabled":true,"translations":{"en":{"locale":"en","translatable":null,"id":1,"name":"Phone bill","description":"Mbe4 phone bil","instructions":"My method instructions"}},"gateway_config":{"factory_name":"mbe4","gateway_name":"phone_bill","config":{"username":"DemoClient","password":"z28jd65HgdEWMGG634gf0fmf653hjdc87os","clientId":"10063","serviceId":"10153","contentclass":"1"},"decrypted_config":null,"id":1},"supports_recurring":false,"_links":{"self":{"href":"\/api\/v1\/payment-methods\/mbe4"}}},"currency_code":"EUR","amount":600,"state":"new","details":[],"created_at":"2017-10-17T08:33:21+00:00","updated_at":"2017-10-17T08:33:21+00:00","subscription":null,"canceled_at":null}],"purchase_state":"new","payment_state":"new","token_value":"nh7e7Eu5D6"}';
    const TEST_SUBSCRIPTION_UPDATE = '{"id":27,"amount":600,"currency_code":"EUR","interval":"1 month","start_date":"2017-10-17T00:00:00+00:00","type":"recurring","items":[{"id":2,"quantity":1,"unit_price":600,"total":600}],"purchase_completed_at":null,"items_total":600,"total":600,"state":"new","created_at":"2017-10-17T08:33:21+00:00","updated_at":"2017-10-17T08:33:21+00:00","payments":[{"id":27,"method":{"id":1,"code":"mbe4","position":1,"created_at":"2017-09-21T13:08:48+00:00","updated_at":"2017-09-21T13:08:48+00:00","enabled":true,"translations":{"en":{"locale":"en","translatable":null,"id":1,"name":"Phone bill","description":"Mbe4 phone bil","instructions":"My method instructions"}},"gateway_config":{"factory_name":"mbe4","gateway_name":"phone_bill","config":{"username":"DemoClient","password":"z28jd65HgdEWMGG634gf0fmf653hjdc87os","clientId":"10063","serviceId":"10153","contentclass":"1"},"decrypted_config":null,"id":1},"supports_recurring":false,"_links":{"self":{"href":"\/api\/v1\/payment-methods\/mbe4"}}},"currency_code":"EUR","amount":600,"state":"paid","details":[],"created_at":"2017-10-17T08:33:21+00:00","updated_at":"2017-10-17T08:33:21+00:00","subscription":null,"canceled_at":null}],"purchase_state":"new","payment_state":"paid","token_value":"nh7e7Eu5D6"}';
    const TEST_SUBSCRIPTION_WITHOUT_PAYMENTS = '{"id":27,"amount":600,"currency_code":"EUR","interval":"1 month","start_date":"2017-10-17T00:00:00+00:00","type":"recurring","items":[{"id":2,"quantity":1,"unit_price":600,"total":600}],"purchase_completed_at":null,"items_total":600,"total":600,"state":"new","created_at":"2017-10-17T08:33:21+00:00","updated_at":"2017-10-17T08:33:21+00:00","payments":[],"purchase_state":"new","payment_state":"paid","token_value":"nh7e7Eu5D6"}';

    public function setUp()
    {
        $this->initClient();
    }

    public function testCreateAndUpdate()
    {
        $this->client->request('GET', $this->getUrl('ph_customer_add_to_subscription').'?token=1234567890');
        self::assertEquals(404, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', $this->getUrl('ph_subscriptions_httppush_retrieve'), [], [], [], self::TEST_SUBSCRIPTION);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertEquals('{"status":"OK"}', $this->client->getResponse()->getContent());

        $this->client->request('POST', $this->getUrl('ph_subscriptions_httppush_retrieve'), [], [], [], self::TEST_SUBSCRIPTION_UPDATE);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertEquals('{"status":"OK"}', $this->client->getResponse()->getContent());
    }

    public function testCreateWithoutPayments()
    {
        $this->client->request('POST', $this->getUrl('ph_subscriptions_httppush_retrieve'), [], [], [], self::TEST_SUBSCRIPTION_WITHOUT_PAYMENTS);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertEquals('{"status":"OK"}', $this->client->getResponse()->getContent());

        $this->client->request('POST', $this->getUrl('ph_subscriptions_httppush_retrieve'), [], [], [], self::TEST_SUBSCRIPTION_UPDATE);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertEquals('{"status":"OK"}', $this->client->getResponse()->getContent());
    }
}
