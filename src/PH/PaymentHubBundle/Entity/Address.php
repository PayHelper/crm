<?php

namespace PH\PaymentHubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\AddressBundle\Entity\AbstractAddress;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ph_contact_address")
 */
class Address extends AbstractAddress
{
    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(targetEntity="PH\PaymentHubBundle\Entity\Customer", inversedBy="addresses")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $owner;

    /**
     * @return CustomerInterface
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param CustomerInterface $owner
     */
    public function setOwner(CustomerInterface $owner = null)
    {
        $this->owner = $owner;
    }
}
