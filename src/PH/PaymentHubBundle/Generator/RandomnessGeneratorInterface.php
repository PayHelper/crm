<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PH\PaymentHubBundle\Generator;

interface RandomnessGeneratorInterface
{
    /**
     * @param int $length
     *
     * @return string
     */
    public function generateUriSafeString($length);

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateNumeric($length);

    /**
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    public function generateInt($min, $max);
}
