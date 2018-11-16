<?php

namespace PH\PaymentHubBundle\Migrations\Schema\v1_18;

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
        if (!$schema->getTable('ph_contact_address')->hasColumn('serialized_data')) {
            $schema->getTable('ph_contact_address')->addColumn(
                'serialized_data',
                'text',
                array('notnull' => false, 'comment' => '(DC2Type:array)')
            );
        }
        $schema->getTable('ph_payment')->addColumn('bankName', 'string', array('notnull' => false));
        $schema->getTable('ph_payment')->addColumn('iban', 'string', array('notnull' => false));
        $schema->getTable('ph_payment')->addColumn('bin', 'string', array('notnull' => false));
        $schema->getTable('ph_payment')->addColumn('accountNumber', 'string', array('notnull' => false));
    }
}
