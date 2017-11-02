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
 * @ORM\Entity()
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
     * Customer constructor.
     */
    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();

        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param mixed $subscriptions
     */
    public function setSubscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * @param SubscriptionInterface $subscription
     */
    public function addSubscription(SubscriptionInterface $subscription)
    {
        $this->subscriptions->add($subscription);
    }

    /**
     * @return mixed
     */
    public function getNewsletterAllowed()
    {
        return $this->newsletterAllowed;
    }

    /**
     * @param mixed $newsletterAllowed
     */
    public function setNewsletterAllowed($newsletterAllowed)
    {
        $this->newsletterAllowed = $newsletterAllowed;
    }

    /**
     * @return mixed
     */
    public function getGiftAllowed()
    {
        return $this->giftAllowed;
    }

    /**
     * @param mixed $giftAllowed
     */
    public function setGiftAllowed($giftAllowed)
    {
        $this->giftAllowed = $giftAllowed;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPublicComment()
    {
        return $this->publicComment;
    }

    /**
     * @param mixed $publicComment
     */
    public function setPublicComment($publicComment)
    {
        $this->publicComment = $publicComment;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
}
