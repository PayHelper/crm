<?php

namespace PH\PaymentHubBundle\Entity;

interface OrderCheckoutInterface
{
    const STATE_CART = 'cart';
    const STATE_COMPLETED = 'completed';
    const STATE_PAYMENT_SELECTED = 'payment_selected';
    const STATE_PAYMENT_SKIPPED = 'payment_skipped';
}