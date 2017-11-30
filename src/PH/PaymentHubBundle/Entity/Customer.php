<?php

namespace PH\PaymentHubBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\ChannelBundle\Model\ChannelAwareInterface;
use Oro\Bundle\ChannelBundle\Model\ChannelEntityTrait;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

/**
 * Class Customer.
 *
 * @ORM\Entity(repositoryClass="PH\PaymentHubBundle\Repository\CustomerRepository")
 * @ORM\Table(name="ph_customer")
 *
 * @Config(
 *   defaultValues={
 *     "ownership"={
 *       "owner_type"="BUSINESS_UNIT",
 *       "owner_field_name"="owner",
 *       "owner_column_name"="business_unit_owner_id",
 *       "organization_field_name"="organization",
 *       "organization_column_name"="organization_id"
 *     },
 *     "security"={
 *       "type"="ACL",
 *       "group_name"="",
 *       "category"="customer_management",
 *       "field_acl_supported"="true"
 *     },
 *     "activity"={
 *       "show_on_page"="\Oro\Bundle\ActivityBundle\EntityConfig\ActivityScope::VIEW_PAGE"
 *     },
 *     "entity"={
 *       "icon"="fa-user",
 *       "contact_information"={
 *         "email"={
 *           {"fieldName"="email"}
 *         },
 *         "phone"={
 *           {"fieldName"="phone"}
 *         }
 *       }
 *     }
 *   }
 * )
 */
class Customer extends ExtendPerson implements ChannelAwareInterface, CustomerInterface
{
    use ChannelEntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @ConfigField(
     *     defaultValues={
     *         "importexport"={
     *             "identity"=true
     *         }
     *     }
     * )
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="PH\PaymentHubBundle\Entity\Subscription", mappedBy="customer")
     */
    protected $subscriptions;

    /**
     * @ORM\OneToMany(targetEntity="PH\PaymentHubBundle\Entity\Address", mappedBy="owner", cascade={"all"}, orphanRemoval=true, fetch="EAGER")
     *
     * * @ConfigField(
     *     defaultValues={
     *         "importexport"={
     *             "full"=true
     *         }
     *     }
     * )
     */
    protected $addresses;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $newsletterAllowed;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $giftAllowed;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $publicComment;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $comment;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $customerUpdateToken;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $emailVerificationToken;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $processEmailVerification;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $emailVerifiedAt;

    /**
     * @var \Oro\Bundle\OrganizationBundle\Entity\BusinessUnit
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\BusinessUnit", cascade={"persist"})
     * @ORM\JoinColumn(name="business_unit_owner_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $owner;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $organization;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * {@inheritdoc}
     */
    public function addSubscription(SubscriptionInterface $subscription)
    {
        $this->subscriptions->add($subscription);
    }

    /**
     * {@inheritdoc}
     */
    public function getNewsletterAllowed()
    {
        return $this->newsletterAllowed;
    }

    /**
     * {@inheritdoc}
     */
    public function setNewsletterAllowed($newsletterAllowed)
    {
        $this->newsletterAllowed = $newsletterAllowed;
    }

    /**
     * {@inheritdoc}
     */
    public function getGiftAllowed()
    {
        return $this->giftAllowed;
    }

    /**
     * {@inheritdoc}
     */
    public function setGiftAllowed($giftAllowed)
    {
        $this->giftAllowed = $giftAllowed;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * {@inheritdoc}
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicComment()
    {
        return $this->publicComment;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublicComment($publicComment)
    {
        $this->publicComment = $publicComment;
    }

    /**
     * {@inheritdoc}
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * {@inheritdoc}
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerUpdateToken()
    {
        return $this->customerUpdateToken;
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerUpdateToken($customerUpdateToken)
    {
        $this->customerUpdateToken = $customerUpdateToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailVerificationToken()
    {
        return $this->emailVerificationToken;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmailVerificationToken($emailVerificationToken)
    {
        $this->emailVerificationToken = $emailVerificationToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessEmailVerification()
    {
        return $this->processEmailVerification;
    }

    /**
     * {@inheritdoc}
     */
    public function setProcessEmailVerification($processEmailVerification)
    {
        $this->processEmailVerification = $processEmailVerification;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailVerifiedAt()
    {
        return $this->emailVerifiedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmailVerifiedAt($emailVerifiedAt)
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }
}
