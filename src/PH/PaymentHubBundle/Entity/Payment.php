<?php

namespace PH\PaymentHubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * Class Payment.
 *
 * @ORM\Entity()
 * @ORM\Table(name="ph_payment")
 *
 * @Config
 */
class Payment implements PaymentInterface
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
     * @ORM\Column(type="string", nullable=true, name="payment_id")
     *
     * @var int
     */
    protected $paymentId;

    /**
     * @ORM\Column(type="string", nullable=true, name="method_code")
     *
     * @var string
     */
    protected $methodCode;

    /**
     * @ORM\Column(type="string", name="currency_code")
     *
     * @var string
     */
    protected $currencyCode;

    /**
     * @ORM\Column(type="integer")
     *
     * @var string
     */
    protected $amount;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $state;

    /**
     * @ORM\ManyToOne(targetEntity="PH\PaymentHubBundle\Entity\Subscription", inversedBy="payments", cascade={"persist"})
     * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id")
     */
    protected $subscription;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="updated_at")
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="string", name="holder_name", nullable=true)
     *
     * @var string
     */
    protected $holderName;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     *
     * @ConfigField(
     *     defaultValues={
     *         "importexport"={
     *             "excluded"=true
     *         }
     *     }
     * )
     *
     * @var array
     */
    protected $errors = [];

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
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * {@inheritdoc}
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodCode()
    {
        return $this->methodCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setMethodCode($methodCode)
    {
        $this->methodCode = $methodCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     */
    public function setState($state)
    {
        $this->state = $state;
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

    /**
     * {@inheritdoc}
     */
    public function getHolderName()
    {
        return $this->holderName;
    }

    /**
     * {@inheritdoc}
     */
    public function setHolderName($holderName)
    {
        $this->holderName = $holderName;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        return json_encode($this->errors);
    }

    /**
     * {@inheritdoc}
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * {@inheritdoc}
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }
}
