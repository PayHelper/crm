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
    }
}
