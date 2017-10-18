<?php

namespace PH\PaymentHubBundle\Migrations\Schema\v1_2;

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
        $queries->addQuery('ALTER TABLE ph_subscription RENAME COLUMN checkoutstate TO purchaseState;');
        $queries->addQuery('ALTER TABLE ph_subscription RENAME COLUMN orderstate TO state;');
        $queries->addQuery('ALTER TABLE ph_payment ALTER methodcode DROP NOT NULL;');
    }
}
