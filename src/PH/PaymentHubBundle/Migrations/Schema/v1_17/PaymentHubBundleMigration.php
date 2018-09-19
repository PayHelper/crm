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
        $schema->getTable('ph_payment')->addColumn('bankName', 'string', array('notnull' => false));
        $schema->getTable('ph_payment')->addColumn('iban', 'string', array('notnull' => false));
        $schema->getTable('ph_payment')->addColumn('bin', 'string', array('notnull' => false));
        $schema->getTable('ph_payment')->addColumn('accountNumber', 'string', array('notnull' => false));
    }
}
