<?php

namespace PH\PaymentHubBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\ChannelBundle\Model\ChannelAwareInterface;
use Oro\Bundle\ChannelBundle\Model\ChannelEntityTrait;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * Class Customer.
 *
 * @ORM\Entity(repositoryClass="PH\PaymentHubBundle\Repository\CustomerRepository")
 * @ORM\Table(name="ph_customer")
 * @Config(
 *      defaultValues={
 *          "activity"={
 *              "show_on_page"="\Oro\Bundle\ActivityBundle\EntityConfig\ActivityScope::VIEW_PAGE"
 *          },
 *          "entity"={
 *              "icon"="fa-user",
 *              "contact_information"={
 *                  "email"={
 *                      {"fieldName"="email"}
 *                  },
 *                  "phone"={
 *                      {"fieldName"="phone"}
 *                  }
 *              }
 *          }
 *      }
 * )
 */
class Customer extends ExtendPerson implements ChannelAwareInterface, CustomerInterface
{
    use ChannelEntityTrait;

    /**
     * @ORM\OneToMany(targetEntity="PH\PaymentHubBundle\Entity\Subscription", mappedBy="customer")
     */
    protected $subscriptions;

    /**
     * @ORM\OneToMany(targetEntity="PH\PaymentHubBundle\Entity\Address", mappedBy="owner", cascade={"all"}, orphanRemoval=true, fetch="EAGER")
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
}
