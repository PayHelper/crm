<?php

namespace PH\PaymentHubBundle\Migrations\Schema\v1_10;

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
        $schema->getTable('ph_subscription')->addColumn('intention', 'string')->setNotnull(false);
        $schema->getTable('ph_subscription')->addColumn('source', 'string')->setNotnull(false);
    }
}
