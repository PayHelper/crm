<?php

namespace PH\PaymentHubBundle\Entity;

/**
 * Interface PaymentInterface
 */
interface PaymentInterface
{
    const STATE_CART = 'cart';
    const STATE_NEW = 'new';
    const STATE_PROCESSING = 'processing';
    const STATE_COMPLETED = 'completed';
    const STATE_FAILED = 'failed';
    const STATE_CANCELLED = 'cancelled';
    const STATE_REFUNDED = 'refunded';
    const STATE_UNKNOWN = 'unknown';
}