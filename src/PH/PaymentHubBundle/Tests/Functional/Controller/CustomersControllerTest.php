<?php

namespace PH\PaymentHubBundle\Tests\Functional\Controller;

use Oro\Bundle\OrganizationBundle\Entity\Repository\OrganizationRepository;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Oro\Bundle\UserBundle\Entity;
use Oro\Bundle\UserBundle\Entity\UserManager;
use PH\PaymentHubBundle\Entity\UserInterface;

class CustomersControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->initClient(array(), $this->generateBasicAuthHeader());
        $this->createDefaultTaskUser();
    }

    public function testCreateNewCustomer()
    {
        $crawler = $this->client->request('GET', $this->getUrl('subscriptions.customers_index'));
        $link = $crawler->selectLink('Create Customer')->link();
        $crawler = $this->client->click($link);

        $form = $crawler->selectButton('Save and Close')->form();
        $form['subscriptions_customer[firstName]'] = 'Test';
        $form['subscriptions_customer[lastName]'] = 'User';
        $form['subscriptions_customer[email]'] = 'test.user@example.com';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('test.user@example.com', $crawler->html());
        $id = $this->get_string_between($crawler->html(), '"data":[{"id":"', '","firstName"');

        $crawler = $this->client->request('GET', $this->getUrl('subscriptions.customer_view', array('id' => $id)));
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('test.user@example.com', $crawler->html());

        $crawler = $this->client->request('GET', $this->getUrl('oro_task_index'));
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('User created', $crawler->html());
        $this->assertNotContains('User updated', $crawler->html());

        $crawler = $this->client->request('GET', $this->getUrl('subscriptions.customer_update', array('id' => $id)));
        $form = $crawler->selectButton('Save and Close')->form();
        $form['subscriptions_customer[firstName]'] = 'Test updated';

        $this->client->followRedirects(true);
        $this->client->submit($form);
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $crawler = $this->client->request('GET', $this->getUrl('oro_task_index'));
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('User updated', $crawler->html());
    }

    private function createDefaultTaskUser()
    {
        /** @var UserManager $userManager */
        $userManager = self::getContainer()->get('oro_user.manager');
        $manager = self::getContainer()->get('doctrine')->getManager();

        /** @var Entity\User $admin */
        $taskUser = $manager->getRepository('OroUserBundle:User')->findOneBy(['username' => 'task.user']);
        /** @var Entity\Group $group */
        $group = $manager->getRepository('OroUserBundle:Group')->findOneBy(['name' => 'Administrators']);
        if (!$taskUser) {
            /** @var Entity\Role $role */
            $role = $manager->getRepository('OroUserBundle:Role')->findOneBy(['role' => 'ROLE_ADMINISTRATOR']);
            $taskUser = $userManager->createUser();
            $taskUser
                ->setUsername(UserInterface::DEFAULT_TASKS_USER_NAME)
                ->addRole($role);
        }

        $taskUser
            ->setPlainPassword('task.user')
            ->setFirstName('Default Task')
            ->setLastName('User')
            ->setEmail('task.user@example.com')
            ->setSalt('');

        if (0 === count($taskUser->getApiKeys())) {
            /** @var OrganizationRepository $organizationRepo */
            $organizationRepo = $manager->getRepository('OroOrganizationBundle:Organization');
            $organization = $organizationRepo->getFirst();
            $api = new Entity\UserApi();
            $api->setApiKey('task_user_api_key')
                ->setUser($taskUser)
                ->setOrganization($organization);

            $taskUser->addApiKey($api);
        }

        if (!$taskUser->hasGroup($group)) {
            $taskUser->addGroup($group);
        }

        $userManager->updateUser($taskUser);
    }

    /**
     * Find checkbox.
     *
     * @param \Symfony\Component\DomCrawler\Form $form
     * @param string                             $name  Field name without trailing '[]'
     * @param string                             $value
     */
    protected function findCheckbox($form, $name, $value)
    {
        foreach ($form->offsetGet($name) as $field) {
            $available = $field->availableOptionValues();
            if (strval($value) == reset($available)) {
                return $field;
            }
        }
    }

    private function get_string_between($string, $start, $end)
    {
        $string = ' '.$string;
        $ini = strpos($string, $start);
        if (0 === $ini) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }
}
