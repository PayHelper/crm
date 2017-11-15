<?php

namespace PH\PaymentHubBundle\Migrations\Schema\v1_6;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class PaymentHubBundleMigration implements Migration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery('ALTER TABLE ph_customer ADD emailVerificationToken VARCHAR(255) DEFAULT NULL;');
        $queries->addQuery('ALTER TABLE ph_customer ADD processEmailVerification BOOLEAN DEFAULT NULL;');
    }
}
