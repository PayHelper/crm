<?php

namespace PH\PaymentHubBundle\Migrations\Schema\v1_4;

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
        $queries->addQuery('ALTER TABLE ph_customer ADD publicComment BOOLEAN DEFAULT NULL;');
        $queries->addQuery('ALTER TABLE ph_customer ADD phone VARCHAR(255) DEFAULT NULL;');
        $queries->addQuery('ALTER TABLE ph_customer ADD comment TEXT DEFAULT NULL;');
    }
}
