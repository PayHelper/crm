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
        $schema->getTable('ph_customer')->addColumn('emailVerifiedAt', 'datetime', array('comment' => '(DC2Type:datetime)', 'notnull' => false));
    }
}
