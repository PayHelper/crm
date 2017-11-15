<?php

namespace PH\PaymentHubBundle\Migrations\Schema\v1_7;

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
        $queries->addQuery('ALTER TABLE ph_customer ADD emailVerifiedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;');
        $queries->addQuery('COMMENT ON COLUMN ph_customer.emailVerifiedAt IS \'(DC2Type:datetime)\';');
    }
}
