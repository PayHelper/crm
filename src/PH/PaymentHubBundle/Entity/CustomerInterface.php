<?php

namespace PH\PaymentHubBundle\Entity;

use Guzzle\Common\Collection;
use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
use Oro\Bundle\BusinessEntitiesBundle\Entity\BasePerson;

interface CustomerInterface
{
    /**
     * Set addresses.
     *
     * This method could not be named setAddresses because of bug CRM-253.
     *
     * @param Collection|AbstractAddress[] $addresses
     *
     * @return BasePerson
     */
    public function resetAddresses($addresses);

    /**
     * Add address.
     *
     * @param AbstractAddress $address
     *
     * @return BasePerson
     */
    public function addAddress(AbstractAddress $address);

    /**
     * Remove address.
     *
     * @param AbstractAddress $address
     *
     * @return BasePerson
     */
    public function removeAddress(AbstractAddress $address);

    /**
     * Get addresses.
     *
     * @return Collection|AbstractAddress[]
     */
    public function getAddresses();

    /**
     * @param AbstractAddress $address
     *
     * @return bool
     */
    public function hasAddress(AbstractAddress $address);
}
