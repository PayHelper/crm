<?php

namespace PH\PaymentHubBundle\Migrations\Schema\v1_17;

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
        $schema->getTable('ph_customer')->addColumn('identification_token', 'string', array('notnull' => false));
        $schema->getTable('ph_customer')->addIndex(['identification_token']);

    }
}
