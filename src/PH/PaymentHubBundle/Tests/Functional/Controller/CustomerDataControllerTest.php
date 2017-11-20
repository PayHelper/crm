<?php

namespace PH\PaymentHubBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use PH\PaymentHubBundle\Entity\Customer;

class CustomerDataControllerTest extends WebTestCase
{
    const TEST_SUBSCRIPTION = '{"id":27,"amount":600,"currency_code":"EUR","interval":"1 month","start_date":"2017-10-17T00:00:00+00:00","type":"recurring","items":[{"id":2,"quantity":1,"unit_price":600,"total":600}],"purchase_completed_at":null,"items_total":600,"total":600,"state":"new","created_at":"2017-10-17T08:33:21+00:00","updated_at":"2017-10-17T08:33:21+00:00","payments":[{"id":27,"method":{"id":1,"code":"mbe4","position":1,"created_at":"2017-09-21T13:08:48+00:00","updated_at":"2017-09-21T13:08:48+00:00","enabled":true,"translations":{"en":{"locale":"en","translatable":null,"id":1,"name":"Phone bill","description":"Mbe4 phone bil","instructions":"My method instructions"}},"gateway_config":{"factory_name":"mbe4","gateway_name":"phone_bill","config":{"username":"DemoClient","password":"z28jd65HgdEWMGG634gf0fmf653hjdc87os","clientId":"10063","serviceId":"10153","contentclass":"1"},"decrypted_config":null,"id":1},"supports_recurring":false,"_links":{"self":{"href":"\/api\/v1\/payment-methods\/mbe4"}}},"currency_code":"EUR","amount":600,"state":"new","details":[],"created_at":"2017-10-17T08:33:21+00:00","updated_at":"2017-10-17T08:33:21+00:00","subscription":null,"canceled_at":null}],"purchase_state":"new","payment_state":"new","token_value":"nh7e7Eu5D6"}';

    const TEST_CUSTOMER = '{"subscriptions_customer":{ "firstName":"John", "middleName":"", "lastName":"Doe", "gender":"male", "birthday":"1989-11-02", "email":"john.doe@example.com", "addresses": [{ "street":"Long Street", "city":"Berlin", "country": "DE" }] }}';
    const TEST_CUSTOMER_UPDATE = '{"subscriptions_customer":{ "firstName":"Nicolas", "middleName":"John", "lastName":"Kowalski", "gender":"male", "birthday":"1989-11-02", "email":"nicolas.kowalski@example.com", "addresses": [{ "street":"Marszałkowska", "city":"Warsaw", "country": "PL" }, { "street":"Long Street", "city":"Berlin", "country": "DE" }] }}';

    public function setUp()
    {
        $this->initClient();
    }

    public function testEditSubscriptionCustomer()
    {
        $this->client->request('GET', $this->getUrl('ph_customer_add_to_subscription').'?token=1234567890');
        self::assertEquals(404, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', $this->getUrl('ph_subscriptions_httppush_retrieve'), [], [], [], self::TEST_SUBSCRIPTION);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertEquals('{"status":"OK"}', $this->client->getResponse()->getContent());

        $this->client->request('GET', $this->getUrl('ph_customer_add_to_subscription'), ['token' => 'nh7e7Eu5D6']);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', $this->getUrl('ph_customer_add_to_subscription'), json_decode(self::TEST_CUSTOMER, true) + ['token' => 'nh7e7Eu5D6']);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', $this->getUrl('ph_customer_add_to_subscription'), ['subscriptions_customer' => ['email' => 'notanemail']] + ['token' => 'nh7e7Eu5D6']);
        self::assertEquals(400, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', $this->getUrl('ph_customer_add_to_subscription'), ['subscriptions_customer' => ['email' => 'test@email.com']] + ['token' => 'nh7e7Eu5D6']);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testEditCustomer()
    {
        $this->client->request('GET', $this->getUrl('ph_customer_add_to_subscription'));
        self::assertEquals(404, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', $this->getUrl('ph_customer_add_to_subscription', ['token' => '1234567890']));
        self::assertEquals(404, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', $this->getUrl('ph_subscriptions_httppush_retrieve'), [], [], [], self::TEST_SUBSCRIPTION);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertEquals('{"status":"OK"}', $this->client->getResponse()->getContent());

        $this->client->request('POST', $this->getUrl('ph_customer_add_to_subscription'), json_decode(self::TEST_CUSTOMER, true) + ['token' => 'nh7e7Eu5D6']);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $customerEditUrl = $this->client->getResponse()->headers->get('location');

        $this->client->request('POST', $customerEditUrl, json_decode(self::TEST_CUSTOMER_UPDATE, true));
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertContains('Nicolas', $crawler->html());
    }

    public function testActivateCustomer()
    {
        $this->client->request('POST', $this->getUrl('ph_subscriptions_httppush_retrieve'), [], [], [], self::TEST_SUBSCRIPTION);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertEquals('{"status":"OK"}', $this->client->getResponse()->getContent());

        $this->client->request('POST', $this->getUrl('ph_customer_add_to_subscription'), json_decode(self::TEST_CUSTOMER, true) + ['token' => 'nh7e7Eu5D6']);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $customer = $this->getContainer()->get('doctrine')->getManager()->getRepository(Customer::class)->findOneBy(array('email' => 'john.doe@example.com'));

        $this->client->request('GET', $this->getUrl('ph_customer_email_verify'), ['token' => $customer->getEmailVerificationToken()]);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        $customer = $this->getContainer()->get('doctrine')->getManager()->getRepository(Customer::class)->findOneBy(array('email' => 'john.doe@example.com'));
        self::assertNotNull($customer->getEmailVerifiedAt());

        $this->client->request('GET', $this->getUrl('ph_customer_email_verify'), ['token' => $customer->getEmailVerificationToken()]);
        self::assertEquals(404, $this->client->getResponse()->getStatusCode());
    }
}
