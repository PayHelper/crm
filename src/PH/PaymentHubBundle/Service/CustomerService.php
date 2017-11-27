<?php

namespace PH\PaymentHubBundle\Service;

use Oro\Bundle\ChannelBundle\Entity\Channel;
use Oro\Bundle\EmailBundle\Entity\EmailTemplate;
use Oro\Bundle\EmailBundle\Form\Model\Email;
use Oro\Bundle\EmailBundle\Mailer\Processor;
use Oro\Bundle\EmailBundle\Provider\EmailRenderer;
use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
use PH\PaymentHubBundle\Entity\CustomerInterface;
use PH\PaymentHubBundle\Generator\RandomnessGeneratorInterface;

/**
 * Class SubscriptionService.
 */
class CustomerService implements CustomerServiceInterface
{
    const MAIN_BUSINESS_UNIT = 'Main';

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var RandomnessGeneratorInterface
     */
    protected $randomnessGenerator;

    /**
     * @var Processor
     */
    protected $emailProcessor;

    /**
     * @var EmailRenderer
     */
    protected $emailRenderer;

    /**
     * @var string
     */
    protected $fromEmail;

    /**
     * CustomerService constructor.
     *
     * @param Processor                    $emailProcessor
     * @param EmailRenderer                $emailRenderer
     * @param string                       $fromEmail
     * @param EntityManagerInterface       $entityManager
     * @param RandomnessGeneratorInterface $randomnessGenerator
     */
    public function __construct(Processor $emailProcessor, EmailRenderer $emailRenderer, $fromEmail, EntityManagerInterface $entityManager, RandomnessGeneratorInterface $randomnessGenerator)
    {
        $this->emailProcessor = $emailProcessor;
        $this->emailRenderer = $emailRenderer;
        $this->fromEmail = $fromEmail;
        $this->entityManager = $entityManager;
        $this->randomnessGenerator = $randomnessGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareCustomer(CustomerInterface $customer)
    {
        $channelRepository = $this->entityManager->getRepository(Channel::class);
        $businessUnitRepository = $this->entityManager->getRepository(BusinessUnit::class);
        $businessUnit = $businessUnitRepository->findOneBy(['name' => self::MAIN_BUSINESS_UNIT]);

        $customer->setOwner($businessUnit);
        $customer->setOrganization($businessUnit->getOrganization());
        $customer->setDataChannel($channelRepository->findOneBy(['name' => 'Payment Hub Channel']));
        $customer->setCreatedAt(new \DateTime());
        $customer->setUpdatedAt(new \DateTime());
        $customer->setCustomerUpdateToken($this->randomnessGenerator->generateUriSafeString(10));
        $this->reassignAddresses($customer);

        return $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function reassignAddresses(CustomerInterface $customer)
    {
        foreach ($customer->getAddresses() as $address) {
            $address->setOwner($customer);
        }

        return $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function resetUpdateToken(CustomerInterface $customer)
    {
        $customer->setCustomerUpdateToken($this->randomnessGenerator->generateUriSafeString(10));
        $this->reassignAddresses($customer);
    }

    /**
     * {@inheritdoc}
     */
    public function sendEmailVerificationEmail(CustomerInterface $customer)
    {
        $emailTemplate = $this->entityManager->getRepository(EmailTemplate::class)->findByName(CustomerServiceInterface::EMAIL_VERIFICATION_TEMPLATE);
        list($subject, $template) = $this->emailRenderer->compileMessage($emailTemplate, ['entity' => $customer]);

        $email = new Email();
        $email
            ->setSubject($subject)
            ->setContexts([$customer])
            ->setBody($template)
            ->setTo([$customer->getEmail()])
            ->setType($emailTemplate->getType())
            ->setFrom($this->fromEmail);

        $this->emailProcessor->process($email);

        return [
            'body' => $template,
            'subject' => $subject,
        ];
    }
}
