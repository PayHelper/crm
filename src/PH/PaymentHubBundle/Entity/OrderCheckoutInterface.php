<?php

/*
 * Copyright 2016 Sourcefabric z.ú. and contributors.
 */

namespace PH\PaymentHubBundle\Entity;

interface OrderCheckoutInterface
{
    const STATE_NEW = 'new';
    const STATE_COMPLETED = 'completed';
    const STATE_PAYMENT_SELECTED = 'payment_selected';
    const STATE_PAYMENT_SKIPPED = 'payment_skipped';
}
