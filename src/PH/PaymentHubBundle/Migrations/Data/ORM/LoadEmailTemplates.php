<?php

namespace PH\PaymentHubBundle\Migrations\Data\ORM;

use Oro\Bundle\EmailBundle\Migrations\Data\ORM\AbstractEmailFixture;

class LoadEmailTemplates extends AbstractEmailFixture
{
    /**
     * Return path to email templates.
     *
     * @return string
     */
    public function getEmailsDir()
    {
        return __DIR__.'/../data/emails';
    }
}
