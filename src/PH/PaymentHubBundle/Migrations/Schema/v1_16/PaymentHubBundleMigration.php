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
        $queries->addQuery('ALTER TABLE ph_subscription ALTER total TYPE INT;');
        $queries->addQuery('ALTER TABLE ph_subscription ALTER total DROP DEFAULT;');
        $queries->addQuery('ALTER TABLE ph_order_item ALTER unit_price TYPE INT;');
        $queries->addQuery('ALTER TABLE ph_order_item ALTER unit_price DROP DEFAULT;');
        $queries->addQuery('ALTER TABLE ph_order_item ALTER total TYPE INT;');
        $queries->addQuery('ALTER TABLE ph_order_item ALTER total DROP DEFAULT;');
        $queries->addQuery('ALTER TABLE ph_payment ALTER amount TYPE INT;');
        $queries->addQuery('ALTER TABLE ph_payment ALTER amount DROP DEFAULT;');
    }
}
