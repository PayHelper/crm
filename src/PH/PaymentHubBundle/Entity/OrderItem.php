<?php

namespace PH\PaymentHubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * Class OrderItem.
 *
 * @ORM\Entity()
 * @ORM\Table(name="ph_order_item")
 * @Config
 */
class OrderItem implements OrderItemInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "identity"=true
     *          }
     *      }
     * )
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var int
     */
    protected $orderItemId;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $quantity;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    protected $unitPrice;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    protected $total;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $name;

    /**
     * Many Features have One Product.
     *
     * @ORM\ManyToOne(targetEntity="PH\PaymentHubBundle\Entity\Subscription", inversedBy="items", cascade={"persist"})
     * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id")
     */
    private $subscription;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * {@inheritdoc}
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderItemId()
    {
        return $this->orderItemId;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderItemId($orderItemId)
    {
        $this->orderItemId = $orderItemId;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
