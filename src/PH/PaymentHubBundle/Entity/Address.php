<?php

namespace PH\PaymentHubBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\ContactBundle\Model\ExtendContactAddress;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ph_contact_address")
 *
 * @Config
 */
class Address extends ExtendContactAddress
{
    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(targetEntity="PH\PaymentHubBundle\Entity\Customer", inversedBy="addresses")
     *
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $owner;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\AddressBundle\Entity\AddressType", cascade={"persist"})
     *
     * @ORM\JoinTable(
     *     name="ph_contact_adr_to_adr_type",
     *     joinColumns={@ORM\JoinColumn(name="contact_address_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="type_name", referencedColumnName="name")}
     * )
     *
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=200,
     *              "short"=true
     *          }
     *      }
     * )
     */
    protected $types;

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
