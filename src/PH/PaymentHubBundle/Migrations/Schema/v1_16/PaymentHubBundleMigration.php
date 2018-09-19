<?php

namespace PH\PaymentHubBundle\Migrations\Schema\v1_16;

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
        $queries->addQuery('ALTER TABLE ph_subscription MODIFY total INT NOT NULL;');
        $queries->addQuery('ALTER TABLE ph_order_item MODIFY unit_price INT NOT NULL;');
        $queries->addQuery('ALTER TABLE ph_order_item MODIFY total INT NOT NULL;');
        $queries->addQuery('ALTER TABLE ph_payment MODIFY amount INT NOT NULL;');
    }
}
